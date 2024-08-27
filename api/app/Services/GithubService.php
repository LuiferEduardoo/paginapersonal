<?php

namespace App\Services;

use GuzzleHttp\Client;
use Dotenv\Dotenv;
use Parsedown;
use Carbon\Carbon;
use App\Models\Projects;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Repositories; 
use App\Models\Categories;
use App\Services\ClassificationService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\Relations\Relation;

class GithubService{
    protected $url;
    protected $classificationService;

    public function __construct(ClassificationService $classificationService, $url = "https://api.github.com/repos")
    {
        $this->url = $url;
        $this->classificationService = $classificationService;
    }

    protected function getClient()
    {
        $dotenv = Dotenv::createImmutable(base_path()); // Ajusta la ubicación del archivo .env según corresponda
        $dotenv->load();
        $token = $_ENV['GITHUB_TOKEN'];
        $client = new Client([
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
                'Authorization' => "Bearer $token",
            ],
        ]);
        return $client;
    }

    protected function getInformationRepository($repositoryUrl)
    {
        $client = $this->getClient();

        $path = parse_url($repositoryUrl, PHP_URL_PATH);
    
            // Elimina la barra diagonal inicial y el nombre de usuario
            $path = ltrim($path, '/');
            $path_parts = explode('/', $path);
            
            // El primer elemento del array es el nombre de usuario y el segundo es el nombre del repositorio
            $userName = $path_parts[0];
            $repositoryName = $path_parts[1];
            $fullName = "$userName/$repositoryName";
            try {
                $response = $client->get("$this->url/$fullName");
                if ($response->getStatusCode() == 200) {
                    $data = json_decode($response->getBody(), true);
                
                    // Obtiene las url para las demas solicitudes
                    $UrlRepository = $data['html_url'];
                    $nameRepository = $data['name'];

                    return [
                        'name' => $nameRepository,
                        'link' => $UrlRepository,
                    ];

                } else {
                    throw new \Exception('Could not get information from GitHub');
                }
            } catch (ClientException $e) {
                throw new \Exception($e->getMessage());
            } catch (GuzzleException $e) {
                throw new \Exception($e->getMessage());
            }
    }

    public function create(Model $model, $repositoriesUrlList, $categoriesRepositoriesList){
        foreach ($repositoriesUrlList as $key => $repositoryUrl) {
            
            try {
                $reponseCallToAPI = $this->getInformationRepository($repositoryUrl);
                $repositoryData = array_merge(
                    ['project_id' => $model->id],
                    $reponseCallToAPI
                );
                $repository = new Repositories($repositoryData);

                $model->repositories()->save($repository);
                $categories = array($categoriesRepositoriesList[$key]);
                $this->classificationService->createItems($repository, $categories, 'categories', Categories::class, 'name');
            } catch (ClientException $e) {
                throw new \Exception($e->getMessage());
            } catch (GuzzleException $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }
    public function delete(Model $object)
    {
        try{
            $relations = $object->getRelations();
    
            foreach ($relations as $relationName => $relation) {
                if ($relation instanceof Relation) {
                    $object->unsetRelation($relationName);
                }
            }

            $this->classificationService->deleteItems($object, 'categories');
        } catch (\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }

    public function update(Model $model, $repositoriesUrlList, $categoriesRepositoriesList, $idsUpdateRepositoryList, $updateCategoriesList, $idsEliminateRepositoriesList){
        if ((count($repositoriesUrlList) > 0) && (count($categoriesRepositoriesList) > 0) && (count($repositoriesUrlList) === count($categoriesRepositoriesList))) {
            $this->create($model, $repositoriesUrlList, $categoriesRepositoriesList);
        }

        if ((count($idsUpdateRepositoryList) > 0) && (count($updateCategoriesList) > 0) && (count($idsUpdateRepositoryList) === count($updateCategoriesList))) {
            foreach ($idsUpdateRepositoryList as $key => $idRepository) {
                $repository = Repositories::find($idRepository);
                if ($repository['project_id'] !== $model->id) {
                    throw new HttpException(401, 'Unauthorized access.');
                }
                if (isset($updateCategoriesList[$key])) {
                    $categoriesToCreateList = array($updateCategoriesList[$key]);

                    $this->classificationService->deleteItems($repository, 'categories');
                    $this->classificationService->createItems($repository, $categoriesToCreateList, 'categories', Categories::class, 'name');
                }
            }
        }

        if(count($idsEliminateRepositoriesList) > 0){
            foreach ($idsEliminateRepositoriesList as $key => $idEliminateRepository) {
                $repository = Repositories::find($idEliminateRepository);
                if($repository['project_id'] !== $model['id']){
                    throw new HttpException(401, 'Unauthorized access.');
                }
                $this->classificationService->deleteItems($repository, 'categories');
                $repository->delete();
            }
        }
    }
}
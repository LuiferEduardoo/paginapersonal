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
use App\Models\ProjectHistory;
use Illuminate\Database\Eloquent\Relations\Relation;

class GithubService{
    protected $url;

    public function __construct($url = "https://api.github.com/repos")
    {
        $this->url = $url;
    }

    private function getClient()
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

    public function getInformationRepository(Model $object, $repositoryUrl){
        $client = $this->getClient();
        // Obtiene la ruta de la URL
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
                $readmeUrl = $data['url'] . "/readme";
                $tagsUrl = $data['tags_url'];
                $contributorsUrl = $data['contributors_url'];

                // Obtiene el ultimo tag
                $tags_response =  $client->get($tagsUrl);
                $tags_data = json_decode($tags_response->getBody(), true);
                $version = $tags_data ? $tags_data[0]['name'] : null; 

                // Se obtiene el contenido del readme
                $readme_response = $client->get($readmeUrl);
                $readme_data = json_decode($readme_response->getBody(), true);

                $readmeContent = "";
                // Decodifica el contenido base64 del archivo README
                if (isset($readme_data['content'])) {
                    $readmeContentMarkdown = base64_decode($readme_data['content']); // Obtén el contenido del archivo README.md
                    $parsedown = new Parsedown();
                    $readmeContent = $parsedown->text($readmeContentMarkdown); // Convierte el contenido de Markdown a HTML
                }
                // Se obtienen los contribuidores 
                $contributors_response = $client->get($contributorsUrl); 
                $contributors_data = json_decode($contributors_response->getBody(), true);
                $contributors = [];
                foreach ($contributors_data as $contributor) {
                    if($contributor['login'] != "LuiferEduardoo"){
                        array_push($contributors, $contributor['login']);
                    }
                }
                // Crear un nuevo registro en la tabla project_history y guardar los datos relacionados con el proyecto
                $history = new ProjectHistory([
                    'id_project' => $object->id, // Asignar el ID del proyecto al campo 'id_project' en la tabla project_history
                    'id_repository' => $data['id'],
                    'description' => $readmeContent,
                    'date' => Carbon::parse($data['created_at'])->format('Y-m-d H:i:s'),
                    'updated' => Carbon::parse($data['updated_at'])->format('Y-m-d H:i:s'),
                    'pushed_at' => Carbon::parse($data['pushed_at'])->format('Y-m-d H:i:s'),
                    'version' => $version,
                    'url_proyect' => $data['homepage'],
                    'url_repository' => $data['html_url'],
                    'documentation' => $data['has_pages'] ? "$UrlRepository/wiki" : null,
                    'contributors' => json_encode($contributors), // Convierte el array de contribuidores a JSON para guardarlos en el campo 'contributors' que es de tipo JSON
                ]);

                // Guardar el historial en la relación history()
                $object->history()->save($history);

            } else {
                throw new \Exception('Could not get information from GitHub');
            }
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage());
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }
    }
    public function deleteAllRelations(Model $object)
    {
        try{
            $relations = $object->getRelations();
    
            foreach ($relations as $relationName => $relation) {
                if ($relation instanceof Relation) {
                    $object->unsetRelation($relationName);
                }
            }
        } catch (\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }

    public function updateInformation(Model $object, $repositoryUrl, $date){
        $client = $this->getClient();
        // Obtiene la ruta de la URL
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
                if(Carbon::parse($data['pushed_at'])->format('Y-m-d H:i:s') != $date){
                    // Obtiene las url para las demas solicitudes
                    $UrlRepository = $data['html_url'];
                    $readmeUrl = $data['url'] . "/readme";
                    $tagsUrl = $data['tags_url'];
                    $contributorsUrl = $data['contributors_url'];
    
                    // Obtiene el ultimo tag
                    $tags_response =  $client->get($tagsUrl);
                    $tags_data = json_decode($tags_response->getBody(), true);
                    $version = $tags_data ? $tags_data[0]['name'] : null; 
    
                    // Se obtiene el contenido del readme
                    $readme_response = $client->get($readmeUrl);
                    $readme_data = json_decode($readme_response->getBody(), true);
    
                    $readmeContent = "";
                    // Decodifica el contenido base64 del archivo README
                    if (isset($readme_data['content'])) {
                        $readmeContentMarkdown = base64_decode($readme_data['content']); // Obtén el contenido del archivo README.md
                        $parsedown = new Parsedown();
                        $readmeContent = $parsedown->text($readmeContentMarkdown); // Convierte el contenido de Markdown a HTML
                    }
                    // Se obtienen los contribuidores 
                    $contributors_response = $client->get($contributorsUrl); 
                    $contributors_data = json_decode($contributors_response->getBody(), true);
                    $contributors = [];
                    foreach ($contributors_data as $contributor) {
                        if($contributor['login'] != "LuiferEduardoo"){
                            array_push($contributors, $contributor['login']);
                        }
                    }
                    // Crear un nuevo registro en la tabla project_history y guardar los datos relacionados con el proyecto
                    $history = new ProjectHistory([
                        'id_project' => $object->id, // Asignar el ID del proyecto al campo 'id_project' en la tabla project_history
                        'id_repository' => $data['id'],
                        'description' => $readmeContent,
                        'date' => Carbon::parse($data['created_at'])->format('Y-m-d H:i:s'),
                        'updated' => Carbon::parse($data['updated_at'])->format('Y-m-d H:i:s'),
                        'pushed_at' => Carbon::parse($data['pushed_at'])->format('Y-m-d H:i:s'),
                        'version' => $version,
                        'url_proyect' => $data['homepage'],
                        'url_repository' => $data['html_url'],
                        'documentation' => $data['has_pages'] ? "$UrlRepository/wiki" : null,
                        'contributors' => json_encode($contributors), // Convierte el array de contribuidores a JSON para guardarlos en el campo 'contributors' que es de tipo JSON
                    ]);
    
                    // Guardar el historial en la relación history()
                    $object->history()->save($history);
    
                }
            } else {
                throw new \Exception('Could not get information from GitHub');
            }
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage());
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
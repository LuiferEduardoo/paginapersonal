<?php

namespace App\Services;

use GuzzleHttp\Client;
use Dotenv\Dotenv;
use Parsedown;
use Carbon\Carbon;
use App\Models\Projects;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

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

    public function getInformationRepository(Model $object, $user, $repository, $name){
        $client = $this->getClient();
        $fullName = "$user/$repository";
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
                $version = $tags_data[0]['name']; 

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
                // Eliminar caracteres especiales y conservar tildes
                $link = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $name));
                $link = preg_replace('/[^a-z0-9\-]/', '', str_replace(' ', '-', $link));

                $baseLink = $link;
                $suffix = 1;
                while (Projects::where('link', $link)->exists()) {
                    $link = "$baseLink-$suffix";
                    $suffix++;
                }
                // Se guarda la información en la base de datos
                $object->url_repository = $data['html_url'];
                $object->link = $link;
                $object->id_repository = $data['id'];
                $object->description = $readmeContent;
                $object->date = Carbon::parse($data['created_at'])->format('Y-m-d H:i:s');
                $object->updated = Carbon::parse($data['updated_at'])->format('Y-m-d H:i:s');
                $object->version = $version; 
                $object->url_proyect = $data['homepage'];
                $object->documentation = $data['has_pages'] ? "$UrlRepository/wiki" : null;
                $object->contributors = $contributors;
                $object->save();
            } else {
                DB::rollBack();
                throw new \Exception('Could not get information from GitHub');
            }
        } catch (ClientException $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        } catch (GuzzleException $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
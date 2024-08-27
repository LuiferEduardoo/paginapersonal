<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\GithubService;
use App\Services\ClassificationService;
use App\Models\Repositories;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Mockery;
use Mockery\MockInterface;

class GithubServiceTest extends TestCase
{
    protected $classificationServiceMock;
    protected $githubService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classificationServiceMock = Mockery::mock(ClassificationService::class);
        $this->githubService = new GithubService($this->classificationServiceMock);
    }

    public function testGetClient()
    {
        $githubService = Mockery::mock(GithubService::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $client = $githubService->shouldReceive('getClient')->andReturn(new Client())->getMock();

        $this->assertInstanceOf(Client::class, $client->getClient());
    }

    public function testGetInformationRepositoryReturnsValidData()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockResponse = new Response(200, [], json_encode([
            'html_url' => 'https://github.com/user/repo',
            'name' => 'repo',
        ]));

        $mockClient->shouldReceive('get')->andReturn($mockResponse);

        $githubService = Mockery::mock(GithubService::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getClient')
            ->andReturn($mockClient)
            ->getMock();

        $result = $githubService->getInformationRepository('https://github.com/user/repo');

        $this->assertEquals([
            'name' => 'repo',
            'link' => 'https://github.com/user/repo',
        ], $result);
    }

    public function testGetInformationRepositoryThrowsExceptionOnClientError()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockRequest = Mockery::mock(\Psr\Http\Message\RequestInterface::class);
    
        $mockClient->shouldReceive('get')
                   ->andThrow(new ClientException(
                       'Client error',
                       $mockRequest,
                       new Response(404)
                   ));
    
        $githubService = Mockery::mock(GithubService::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getClient')
            ->andReturn($mockClient)
            ->getMock();
    
        $this->expectException(\Exception::class);
        $githubService->getInformationRepository('https://github.com/user/repo');
    }

    public function testCreate()
    {
        // Crear un mock del modelo y configurar la propiedad id
        $mockModel = Mockery::mock(Model::class)->makePartial();
        $mockModel->id = 1; // Asignar un valor válido para la propiedad id
    
        // Crear un mock para la relación de repositorios
        $mockRepositories = Mockery::mock('Illuminate\Database\Eloquent\Relations\HasMany'); // Asegúrate de usar el tipo de relación correcto
        $mockRepositories->shouldReceive('save')
            ->with(Mockery::type(Repositories::class)) // Asegura que el argumento sea del tipo esperado
            ->andReturn(true);
    
        $mockModel->shouldReceive('repositories')->andReturn($mockRepositories);
    
        // Crear un mock para ClassificationService
        $this->classificationServiceMock = Mockery::mock(ClassificationService::class);
        $this->classificationServiceMock
            ->shouldReceive('createItems')
            ->once();
    
        // Crear una instancia del GithubService usando el mock de ClassificationService
        $githubService = Mockery::mock(GithubService::class, [$this->classificationServiceMock])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getInformationRepository')
            ->andReturn(['name' => 'repo', 'link' => 'https://github.com/user/repo'])
            ->getMock();
    
        // Ejecutar el método create
        $githubService->create($mockModel, ['https://github.com/user/repo'], ['category']);
        $this->assertTrue(true);
    }       
    
    public function testDelete()
    {
        // Crear un mock del modelo
        $mockModel = Mockery::mock(Model::class)->makePartial();
    
        // Crear un mock para la relación de categorías
        $mockRelation = Mockery::mock(Relation::class);
        
        // Configurar el mock para devolver la relación de categorías
        $mockModel->shouldReceive('getRelations')->andReturn([
            'categories' => $mockRelation,
        ]);
    
        // Configurar el mock de ClassificationService
        $this->classificationServiceMock
            ->shouldReceive('deleteItems')
            ->once();
    
        // Crear una instancia del GithubService usando el mock de ClassificationService
        $githubService = new GithubService($this->classificationServiceMock);
    
        // Ejecutar el método delete
        $githubService->delete($mockModel);
        
        // Verificar que se hayan realizado las llamadas esperadas
        $this->classificationServiceMock->shouldHaveReceived('deleteItems')->once()->with($mockModel, 'categories');
        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
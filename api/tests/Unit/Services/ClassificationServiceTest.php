<?php

namespace Tests\Unit\Services;

use App\Services\ClassificationService;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;
use Mockery;

class ClassificationServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateItems()
    {
        // Crear mocks para los modelos
        $objectMock = Mockery::mock(Model::class);
        $relationMock = Mockery::mock('relation');
        $itemModelMock = Mockery::mock('alias:ItemModelAlias');
    
        // Mockear el método relation()
        $objectMock->shouldReceive('relation')->andReturn($relationMock);
    
        // Permitir la simulación del método estático
        $itemModelMock->shouldAllowMockingMethod('whereIn');
    
        // Mockear el método whereIn()
        $itemModelMock->shouldReceive('whereIn')->with('name', ['item1', 'item2'])->andReturnSelf();
    
        // Mockear el método get() para devolver una colección vacía
        $itemModelMock->shouldReceive('get')->andReturn(collect([]));
    
        // Mockear el método create()
        $itemModelMock->shouldReceive('create')->andReturnUsing(function ($attributes) {
            return (object) array_merge($attributes, ['id' => rand(1, 100)]);
        });
    
        // Mockear el método sync() de la relación
        $relationMock->shouldReceive('sync')->once();
    
        // Instanciar el servicio y llamar al método createItems()
        $service = new ClassificationService();
        $service->createItems($objectMock, ['item1', 'item2'], 'relation', $itemModelMock, 'name');
        
        $this->assertTrue(true); // Si no hay excepciones, la prueba pasa
    }

    public function testDeleteItems()
    {
        // Crear mocks para los modelos
        $objectMock = Mockery::mock(Model::class);
        $relationMock = Mockery::mock('relation');
        
        // Mockear el método relation()
        $objectMock->shouldReceive('relation')->andReturn($relationMock);

        // Mockear el método detach() de la relación
        $relationMock->shouldReceive('detach')->once();

        // Instanciar el servicio y llamar al método deleteItems()
        $service = new ClassificationService();
        $service->deleteItems($objectMock, 'relation');
        
        $this->assertTrue(true); // Si no hay excepciones, la prueba pasa
    }

    public function testUpdateItems()
    {
        // Crear un mock para el modelo utilizando alias
        $itemModelMock = Mockery::mock('alias:App\\Models\\ItemModel');
        
        // Permitir la simulación del método estático whereIn
        $itemModelMock->shouldAllowMockingMethod('whereIn');

        // Mockear el método whereIn()
        $itemModelMock->shouldReceive('whereIn')
            ->with('name', ['item1', 'item2'])
            ->andReturnSelf();

        // Mockear el método get() para devolver una colección vacía
        $itemModelMock->shouldReceive('get')
            ->andReturn(collect([]));

        // Mockear el método create() para devolver un nuevo objeto con un ID simulado
        $itemModelMock->shouldReceive('create')
            ->andReturnUsing(function ($attributes) {
                return (object) array_merge($attributes, ['id' => rand(1, 100)]);
            });

        // Crear un mock para el objeto y la relación
        $objectMock = Mockery::mock(Model::class);
        $relationMock = Mockery::mock();

        // Mockear el método relation()
        $objectMock->shouldReceive('relation')
            ->andReturn($relationMock);

        // Mockear el método detach() de la relación
        $relationMock->shouldReceive('detach')
            ->once();

        // Mockear el método sync() de la relación
        $relationMock->shouldReceive('sync')
            ->once();

        // Instanciar el servicio y llamar al método updateItems()
        $service = new ClassificationService();
        $service->updateItems($objectMock, ['item1', 'item2'], 'relation', $itemModelMock, 'name');
        
        // Verificar que no se lanzan excepciones
        $this->assertTrue(true);
    }
}
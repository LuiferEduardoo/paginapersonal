<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\TechnologyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Mockery;

class TechnologyServiceTest extends TestCase
{
    protected $technologyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->technologyService = new TechnologyService();
    }

    public function testAddTechnologySuccess()
    {
        $object = Mockery::mock(Model::class);
        $object->shouldReceive('technology->attach')
            ->once()
            ->with([1, 2, 3]);

        $response = $this->technologyService->addTechnology($object, [1, 2, 3]);

        $this->assertNull($response);
    }

    public function testAddTechnologyFailure()
    {
        $object = Mockery::mock(Model::class);
        $object->shouldReceive('technology->attach')
            ->once()
            ->with([1, 2, 3])
            ->andThrow(new \Exception('Failed to attach technologies'));

        $response = $this->technologyService->addTechnology($object, [1, 2, 3]);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->status());
        $this->assertEquals('Failed to attach technologies', $response->getData()->message);
    }

    public function testDeleteTechnologySuccess()
    {
        $object = Mockery::mock(Model::class);
        $object->shouldReceive('technology->detach')
            ->once();

        $response = $this->technologyService->deleteTechnology($object);

        $this->assertNull($response);
    }

    public function testDeleteTechnologyFailure()
    {
        $object = Mockery::mock(Model::class);
        $object->shouldReceive('technology->detach')
            ->once()
            ->andThrow(new \Exception('Failed to detach technologies'));

        $response = $this->technologyService->deleteTechnology($object);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->status());
        $this->assertEquals('Failed to detach technologies', $response->getData()->message);
    }

    public function testUpdateTechnologySuccess()
    {
        $object = Mockery::mock(Model::class);
        $object->shouldReceive('technology->detach')
            ->once();
        $object->shouldReceive('technology->attach')
            ->once()
            ->with([1, 2, 3]);

        $response = $this->technologyService->updateTechnology($object, [1, 2, 3]);

        $this->assertNull($response);
    }

    public function testUpdateTechnologyFailure()
    {
        $object = Mockery::mock(Model::class);
        $object->shouldReceive('technology->detach')
            ->once()
            ->andThrow(new \Exception('Failed to detach technologies'));

        $response = $this->technologyService->updateTechnology($object, [1, 2, 3]);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->status());
        $this->assertEquals('Failed to detach technologies', $response->getData()->message);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
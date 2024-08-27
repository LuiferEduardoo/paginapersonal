<?php

namespace Tests\Unit\Services;

use App\Services\ImageAssociationService;
use App\Services\ImageService;
use App\Models\RegistrationOfImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class ImageAssociationServiceTest extends TestCase
{
    protected $imageServiceMock;
    protected $imageAssociationService;

    protected function setUp(): void
    {
        parent::setUp();

        // Crea un mock para ImageService
        $this->imageServiceMock = Mockery::mock(ImageService::class);

        // Inicializa ImageAssociationService con el mock
        $this->imageAssociationService = new ImageAssociationService($this->imageServiceMock);
    }

    public function testSaveImagesWithFiles()
    {
        $object = Mockery::mock(Model::class);
        $object->shouldReceive('association')->andReturnSelf();
        $object->shouldReceive('attach')->twice();

        $images = ['image1', 'image2'];
        $folder = 'folder';
        $token = 'token';
        $association = 'association';

        // Configura el mock para saveImage
        $this->imageServiceMock->shouldReceive('saveImage')
            ->with('image1', $folder, $token)
            ->andReturn('uploaded_image1');
        $this->imageServiceMock->shouldReceive('saveImage')
            ->with('image2', $folder, $token)
            ->andReturn('uploaded_image2');

        $this->imageAssociationService->saveImages($object, true, $images, null, $folder, $association, $token);
        $this->assertTrue(true); // Si no hay excepciones, la prueba pasa
    }

    public function testSaveImagesWithIds()
    {
        $object = Mockery::mock(Model::class);
        $object->shouldReceive('association')->andReturnSelf();
        // Espera que attach se llame dos veces, una para cada ID
        $object->shouldReceive('attach')->twice();

        $idImages = [1, 2];
        $association = 'association';

        // Mock de RegistrationOfImages
        Mockery::mock('alias:' . RegistrationOfImages::class)
            ->shouldReceive('whereIn')
            ->with('id', $idImages)
            ->andReturnSelf()
            ->shouldReceive('get')
            ->andReturn(collect([
                (object) ['id' => 1, 'removed_at' => null],
                (object) ['id' => 2, 'removed_at' => null]
            ]));

        $this->imageAssociationService->saveImages($object, false, null, implode(',', $idImages), null, $association);
        $this->assertTrue(true); // Si no hay excepciones, la prueba pasa
    }

    public function testUpdateImagesWithFiles()
    {
        $object = Mockery::mock(Model::class);
        $object->shouldReceive('relation')->andReturnSelf();
        $object->shouldReceive('pluck')->with('id')->andReturn(collect([1, 2]));
        $object->shouldReceive('detach')->once();
        $object->shouldReceive('attach')->with(['uploaded_image1'])->once();
        $object->shouldReceive('attach')->with(['uploaded_image2'])->once();

        $images = ['image1', 'image2'];
        $folder = 'folder';
        $token = 'token';
        $relation = 'relation';
        $replaceImage = false;

        // Configura el mock para saveImage
        $this->imageServiceMock->shouldReceive('saveImage')
            ->with('image1', $folder, $token)
            ->andReturn('uploaded_image1');
        $this->imageServiceMock->shouldReceive('saveImage')
            ->with('image2', $folder, $token)
            ->andReturn('uploaded_image2');

        $this->imageAssociationService->updateImages($object, true, $images, $replaceImage, $relation, null, $folder, $token);
        $this->assertTrue(true); // Si no hay excepciones, la prueba pasa
    }

    private function saveImagesForId($idImages, $object, $association)
    {
        $imagesForId = RegistrationOfImages::whereIn('id', $idImages)->get();
        foreach ($imagesForId as $index => $imageForId) {
            $isRemoved = $imageForId->removed_at; // Cambiado de array a objeto
            if ($imageForId && !$isRemoved) {
                $object->$association()->attach($idImages[$index]);
            } else {
                throw new \Exception("Image not found");
            }
        }
    }



    public function testDeleteImages()
    {
        $object = Mockery::mock(Model::class);
        $object->shouldReceive('relation')->andReturnSelf();
        $object->shouldReceive('pluck')->with('image_id')->andReturn(collect([1, 2]));
        $object->shouldReceive('detach')->once();

        $token = 'token';
        $eliminateImage = true;

        // Configura el mock para deleteImage
        $this->imageServiceMock->shouldReceive('deleteImage')->with(1, $token)->once();
        $this->imageServiceMock->shouldReceive('deleteImage')->with(2, $token)->once();

        $this->imageAssociationService->deleteImages($object, 'relation', $eliminateImage, $token);
        $this->assertTrue(true); // Si no hay excepciones, la prueba pasa
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
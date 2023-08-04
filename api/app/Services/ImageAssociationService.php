<?php

namespace App\Services;

use App\Models\RegistrationOfImages;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImageService;

class ImageAssociationService
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function saveImage (Model $object, $image, $folder, $association, $token){
        try{
            // Se guarda la imagen en la API
            $uploadImage = $this->imageService->saveImage($image, $folder, $token);
            // Asocia la imagen con la habilidad
            $object->$association()->attach($uploadImage);  
        } catch(\Exception $e){
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }

    public function saveImageForId(Model $object, $idImage, $relation){
        try{
            $imageForId = RegistrationOfImages::find($idImage);
            $isRemoved = $imageForId['removed_at'];

            if ($imageForId && !$isRemoved) {
                $object->$relation()->attach($idImage);
            } else {
                throw new \Exception("Image not found");
            }
        } catch(\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }

    public function saveImageForUrl(Model $object, $url){
        try{
            $object->url = $url;
            $object->save();
        } catch(\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }

    public function updateImage(Model $object, $image, $replaceImage, $relation, $folder, $token){
        try{
            if($object->url != Null){
                $object->url = Null;
                $object->save();
            }
            if($replaceImage){
                // Se reemplaza la imagen
                $idImageExit = $object->$relation()->first()->id;
                $this->imageService->updateImage($idImageExit, $image, $token);
            }else{
                // Se sube la imagen
                $updateImage = $this->imageService->saveImage($image, $folder, $token);
                // Actualiza la relación de imagen en la habilidad
                $object->$relation()->sync([$updateImage]);
            }
        } catch(\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }

    public function updateImageForId(Model $object, $idImage, $replaceImage, $relation, $token){
        try{
            if($object->url != Null){
                $object->url = Null;
                $object->save();
            }
            $imageForId = RegistrationOfImages::find($idImage);
            $isRemoved = $imageForId['removed_at'];
            if ($imageForId && !$isRemoved){
                if($replaceImage){
                    $idImageExit = $object->$relation()->first()->id;
                    $this->imageService->deleteImage($idImageExit, $token);
                }
                $object->$relation()->sync([$idImage]);
            } else{
                throw new \Exception("Image not found");
            }
        } catch(\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }

    public function updateImageForUrl(Model $object, $url, $replaceImage, $relation, $token){
        try{
            $object->url = $url;
            $object->save();
            if ($replaceImage) {
                $existingImage = $object->$relation()->first();
                if ($existingImage) {
                    $idImageExit = $existingImage->id;
                    $this->imageService->deleteImage($idImageExit, $token);
                }
            }
            if ($object->$relation()->exists()) {
                $object->$relation()->detach();
            }
        } catch(\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteImage(Model $object, $relation, $eliminateImage, $token){
        try{
            $imageIArray = $object->$relation()->pluck('image_id');
            $object->$relation()->detach();
            if($eliminateImage){
                $imageId = $imageIArray[0];
                $this->imageService->deleteImage($imageId, $token);
            }
        } catch(\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }
}
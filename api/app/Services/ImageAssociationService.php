<?php

namespace App\Services;

use App\Models\RegistrationOfImages;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageAssociationService
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    private function saveImagesForFile($object, $images, $folder, $token, $association){
        $imagesArray = is_array($images) ? $images : [$images];
        foreach($imagesArray as $imageArray){
            // Se guarda la imagen en la API
            $uploadImage = $this->imageService->saveImage($imageArray, $folder, $token);
            // Asocia la imagen con la habilidad
            $object->$association()->attach($uploadImage); 
        }
    }
    private function saveImagesForId($idImages, $object, $association){
        $imagesForId = RegistrationOfImages::whereIn('id', $idImages)->get();
        foreach($imagesForId as $index => $imageForId){
            $isRemoved = $imageForId['removed_at'];
            if ($imageForId && !$isRemoved) {
                $object->$association()->attach($idImages[$index]);
            } else {
                throw new \Exception("Image not found");
            }
        }
    }

    public function saveImages (Model $object, $haveImages=false, $images=null, $idImages=null, $folder=null, $association=null, $token=null){
        try{
            if($haveImages){
                $this->saveImagesForFile($object, $images, $folder, $token, $association);
            } else if($idImages){
                $ids = explode(",", $idImages);
                $this->saveImagesForId($ids, $object, $association);
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Image not entered"
                    ], 400);
            }
        } catch(\Exception $e){
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }
    private function updateImageForFile($object, $relation, $replaceImage, $images, $folder, $token){
        $imagesArray = is_array($images) ? $images : [$images];
        if($replaceImage){
            // Se reemplaza la imagen
            $idImagesExit = $object->$relation->pluck('id');
            foreach($idImagesExit as $index => $idImageExit){
                $this->imageService->updateImage($idImageExit, $imagesArray[$index], $token);
            }
        }else{
            foreach($imagesArray as $image){
                // Se sube la imagen
                $updateImage = $this->imageService->saveImage($image, $folder, $token);
                // Actualiza la relación de imagen en la habilidad
                $object->$relation()->sync([$updateImage]);
            }
        }
    }
    private function updateImageForId($object, $idImages, $replaceImage, $relation, $token){
        try{
            $imagesForId = RegistrationOfImages::whereIn('id', $idImages)->get();
            foreach($imagesForId as $index => $imageForId){
                $isRemoved = $imageForId['removed_at'];
                if ($imageForId && !$isRemoved){
                    $idImageExit = $object->$relation->pluck('id');
                    if($replaceImage && $idImageExit){
                        $this->imageService->deleteImage($idImageExit[$index], $token);
                    }
                    $object->$relation()->sync([$idImages[$index]]);
                } else{
                    throw new \Exception("Image not found");
                }
            }
        } catch(\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }
    public function updateImages(Model $object, $haveImages, $images, $replaceImage, $relation, $idImages, $folder, $token){
        try{
            if($haveImages){
                $this->updateImageForFile($object, $relation, $replaceImage, $images, $folder, $token);
            } else if($idImages){
                $ids = explode(",", $idImages);
                $this->updateImageForId($object, $ids, $replaceImage, $relation, $token);
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Image not entered"
                    ], 400);
            }
        } catch(\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteImages(Model $object, $relation, $eliminateImage, $token){
        try{
            $imagesIds = $object->$relation()->pluck('image_id');
            $object->$relation()->detach();
            if($eliminateImage){
                foreach($imagesIds as $imageId){
                    $this->imageService->deleteImage($imageId, $token);
                }
            }
        } catch(\Exception $e) {
            // Manejo de la excepción
            throw new \Exception($e->getMessage());
        }
    }
}
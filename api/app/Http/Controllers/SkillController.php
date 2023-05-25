<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skills;
use App\Models\RegistrationOfImages;
use App\Services\ImageService;
use App\Services\TagsService;
use App\Http\Requests\ValidateDate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SkillController extends Controller
{
    protected $imageService;
    protected $tagsService;

    public function __construct(ImageService $imageService, TagsService $tagsService)
    {
        $this->imageService = $imageService;
        $this->tagsService = $tagsService;
    }

    public function GetSkills(Request $request){
        if($request->input('id')){
            $id = $request->input('id'); 
            $skill = Skills::with('GetTags', 'image')
                ->where('id', $id)
                ->get();
            return response()->json($skill);
        }
        $skills = Skills::with('GetTags', 'image')
            ->where('visible', true)
            ->get();
        return response()->json($skills);
    }

    public function PostSkills(ValidateDate $request)
    {
        try{
            DB::beginTransaction();
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);

            $name = $request->input('name'); 
            $date = $request->input('date');
            $tags = $request->input('tags');
            
            // Se convierten los tags en array
            $arrayTags = explode(",", $tags); 
        
            // Crea la habilidad
            $skill = new Skills([
                'name' => $name,
                'date' => $date,
            ]);
            // Guarda la habilidad en la base de datos
            $skill->save();

            if($request->hasFile('image')){
                $file = $request->file('image');
                // Se guarda la imagen en la API
                $uploadImage = $this->imageService->saveImage($file, 'skill', $token);
                // Asocia la imagen con la habilidad
                $skill->RelationImage()->attach($uploadImage);  
            }else if($request->input('id_image')){
                $idImage =$request->input('id_image');
                $imageForId = RegistrationOfImages::find($idImage);
                $isRemoved = $imageForId['removed_at'];
                if ($imageForId && !$isRemoved){
                    $skill->RelationImage()->attach($idImage);
                } else{
                    DB::rollBack();
                    return response()->json([
                        'message' => "Image not fount"
                    ], 404);
                }
            }else if($request->input('url')){
                $url = $request->input('url');
                $skill->url = $url;
                $skill->save();
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Image not entered"
                    ], 400);
            }
            // Se obtiene los IDs de los tags
            $tagIds = $this->tagsService->createAndAttachTags($arrayTags);

            // Asocia los tags con la habilidad
            $skill->RelationTags()->attach($tagIds);

            DB::commit(); // Confirmar la transacción
            return response()->json([
                'message' => 'Skills successfully created'
            ], 200);

        } catch (QueryException $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => 'Failed to create skill'
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Skill not fount"
            ], 404);
        }
    }

    public function DeleteSkills(ValidateDate $request){
        try{
            $id = $request->input('id');

            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);

            if(Skills::findOrFail($id)){
                $skill = Skills::findOrFail($id);
                $tagIds = $skill->tags->pluck('id');
                $imageIArray = $skill->RelationImage()->pluck('image_id');
                $skill->RelationTags()->detach($tagIds);
                $skill->RelationImage()->detach();
                $eliminateImage = filter_var($request->input('eliminate_image'), FILTER_VALIDATE_BOOLEAN);
                if($eliminateImage){
                    $imageId = $imageIArray[0];
                    $deleteImage = $this->imageService->deleteImage($imageId, $token);
                }
                $skill->delete();
                return response()->json(['message' => 'Skills successfully deleted'],200);
            }
            return response()->json([
                'message' => "Skill not fount"
            ], 404);
        } catch(ModelNotFoundException $e){
            return response()->json([
                'message' => "Error removing skill"
            ], 500);
        }
    }

    public function PutSkills(ValidateDate $request, $id){
        try{
            DB::beginTransaction();
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);

            $skill = Skills::findOrFail($id);
            // Recuperar los nuevos datos de la habilidad desde el request
            $name = $request->input('name');
            $date = $request->input('date');
            $tags = $request->input('tags');
            
            // Actualizar los campos de la habilidad
            $skill->name = $name;
            $skill->date = $date;
            // Guardar los cambios en la base de datos
            $skill->save();
            // Se convierten los tags en array
            $arrayTags = explode(",", $tags); 

            $replaceImage = filter_var($request->input('replace_image'), FILTER_VALIDATE_BOOLEAN);

            if($request->hasFile('image')){
                $file = $request->file('image');
                if($skill->url != Null){
                    $skill->url = Null;
                    $skill->save();
                }
                if($replaceImage){
                    // Se reemplaza la imagen
                    $idImageExit = $skill->RelationImage()->first()->id;
                    $this->imageService->updateImage($idImageExit, $file, $token);
                }else{
                    // Se sube la imagen
                    $updateImage = $this->imageService->saveImage($file, 'skill', $token);
                    // Actualiza la relación de imagen en la habilidad
                    $skill->RelationImage()->sync([$updateImage]);
                }
            } else if($request->input('id_image')){
                $idImage =$request->input('id_image');
                if($skill->url != Null){
                    $skill->url = Null;
                    $skill->save();
                }
                $imageForId = RegistrationOfImages::find($idImage);
                $isRemoved = $imageForId['removed_at'];
                if ($imageForId && !$isRemoved){
                    if($replaceImage){
                        $idImageExit = $skill->RelationImage()->first()->id;
                        $this->imageService->deleteImage($idImageExit, $token);
                    }
                    $skill->RelationImage()->sync([$idImage]);
                } else{
                    DB::rollBack(); // Deshacer la transacción en caso de error
                    return response()->json([
                        'message' => "Image not fount"
                    ], 404);
                }
            } else if($request->input('url')){
                $url = $request->input('url');
                $skill->url = $url;
                $skill->save();
                if($replaceImage){
                    $idImageExit = $skill->RelationImage()->first()->id;
                    $this->imageService->deleteImage($idImageExit, $token);
                }
                if($skill->RelationImage()->first()->id){
                    $skill->RelationImage()->detach();
                }
            }else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Image not entered"
                    ], 400);
            }
            // Se obtiene los IDs de los tags
            $tagIds = $this->tagsService->createAndAttachTags($arrayTags);

            // Eliminar las asociaciones existentes
            $skill->RelationTags()->detach();

            // Asocia los tags con las nuevas habilidad
            $skill->RelationTags()->attach($tagIds);

            DB::commit(); // Confirmar la transacción
        
            return response()->json([
                'message' => 'Skill successfully updated'
            ], 200);
        }  catch (QueryException $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => 'Failed to updated skill'
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Skill not fount"
            ], 404);
        }
    }

    public function PatchSkills(ValidateDate $request, $id){
        $errorImage = response()->json([
            'message' => "You cannot upload more than one image"
        ], 409);
        try{
            DB::beginTransaction();

            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);

            $skill = Skills::find($id);
            $replaceImage = $request->input('replace_image');
        
            if ($request->input('name')) {
                $name = $request->input('name');
                $skill->name = $name;
            }
            if ($request->input('date')) {
                $date = $request->input('date');
                $skill->date = $date;
            }
            if ($request->input('visible') !== null) {
                $visible = filter_var($request->input('visible'), FILTER_VALIDATE_BOOLEAN);
                $skill->visible = $visible;
            }
            if($request->input('tags')){
                $tags = $request->input('tags');
                $arrayTags = explode(",", $tags); 
                // Se obtiene los IDs de los tags
                $tagIds = $this->tagsService->createAndAttachTags($arrayTags);

                // Eliminar las asociaciones existentes
                $skill->RelationTags()->detach();

                // Asocia los tags con las nuevas habilidad
                $skill->RelationTags()->attach($tagIds);
            }
            if($request->file('image')){
                $image = $request->file('image'); 
                if($skill->url != Null){
                    $skill->url = Null;
                    $skill->save();
                }
                if(!$request->input('url') && !$request->input('id_image')){

                    if($replaceImage){
                        // Se reemplaza la imagen
                        $idImageExit = $skill->RelationImage()->first()->id;
                        $this->imageService->updateImage($idImageExit, $image, $token);
                    }else{
                        // Se sube la imagen
                        $updateImage = $this->imageService->saveImage($image, 'skill', $token);
                        // Actualiza la relación de imagen en la habilidad
                        $skill->RelationImage()->sync([$updateImage]);
                    }
                }
                else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            if($request->input('url')){
                if(!$request->file('image') && !$request->input('id_image')){
                    $url = $request->input('url');
                    $skill->url = $url;
                    $skill->save();
                    if($replaceImage){
                        $idImageExit = $skill->RelationImage()->first()->id;
                        $this->imageService->deleteImage($idImageExit, $token);
                    }
                    if($skill->RelationImage()->first()->id){
                        $skill->RelationImage()->detach();
                    }
                }
                else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            if($request->input('id_image')){
                $idImage = $request->input('id_image');
                if(!$request->file('image') && !$request->input('url')){
                    if($skill->url != Null){
                        $skill->url = Null;
                        $skill->save();
                    }
                    $imageForId = RegistrationOfImages::find($idImage);
                    $isRemoved = $imageForId['removed_at'];
                    if ($imageForId && !$isRemoved){
                        if($replaceImage){
                            $idImageExit = $skill->RelationImage()->first()->id;
                            $this->imageService->deleteImage($idImageExit, $token);
                        }
                        $skill->RelationImage()->sync([$idImage]);
                    } else{
                        DB::rollBack(); // Deshacer la transacción en caso de error
                        return response()->json([
                            'message' => "Image not fount"
                        ], 404);
                    }
                }
                else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
        
            $skill->save();
            DB::commit(); // Confirmar la transacción
        
            return response()->json([
                'message' => 'Skill updated successfully',
            ], 200);
        }   catch (QueryException $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => 'Failed to updated skill'
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Skill not fount"
            ], 404);
        }
    }
}
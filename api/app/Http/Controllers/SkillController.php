<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skills;
use App\Services\ImageAssociationService;
use App\Services\TagsService;
use App\Http\Requests\ValidateDate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SkillController extends Controller
{
    protected $tagsService;
    protected $imageAssociationService;

    public function __construct(TagsService $tagsService, ImageAssociationService $imageAssociationService)
    {
        $this->tagsService = $tagsService;
        $this->imageAssociationService = $imageAssociationService;
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
                // Se guarda la imagen en la API y se hace la asociación
                $this->imageAssociationService->saveImage($skill, $file, 'skill', 'RelationImage', $token);  
            }else if($request->input('id_image')){
                $idImage =$request->input('id_image');
                $this->imageAssociationService->saveImageForId($skill, $idImage, 'RelationImage');
            }else if($request->input('url')){
                $url = $request->input('url');
                $this->imageAssociationService->saveImageForUrl($skill, $url);
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Image not entered"
                    ], 400);
            }
            // Se crean los tags y se asocian con la la habilidad
            $this->tagsService->createTags($skill, $arrayTags,'RelationTags');

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
            $eliminateImage = filter_var($request->input('eliminate_image'), FILTER_VALIDATE_BOOLEAN);

            if(Skills::findOrFail($id)){
                $skill = Skills::findOrFail($id);
                $this->tagsService->deleteTags($skill, 'RelationTags');
                $this->imageAssociationService->deleteImage($skill, 'RelationImage', $eliminateImage, $token);
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
            // Se actualizan los tags
            $this->tagsService->updateTags($skill, $arrayTags, 'RelationTags');

            $replaceImage = filter_var($request->input('replace_image'), FILTER_VALIDATE_BOOLEAN);

            if($request->hasFile('image')){
                $file = $request->file('image');
                $this->imageAssociationService->updateImage($skill, $file, $replaceImage, 'RelationImage', 'skill', $token);
            } else if($request->input('id_image')){
                $idImage =$request->input('id_image');
                $this->imageAssociationService->updateImageForId($skill, $idImage, $replaceImage, 'RelationImage', $token);
            } else if($request->input('url')){
                $url = $request->input('url');
                $this->imageAssociationService->updateImageForUrl($skill, $url, $replaceImage, 'RelationImage', $token);
            }else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Image not entered"
                    ], 400);
            }

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
                $this->tagsService->updateTags($skill, $arrayTags, 'RelationTags');
            }
            if($request->file('image')){
                $image = $request->file('image'); 
                if(!$request->input('url') && !$request->input('id_image')){
                    $this->imageAssociationService->updateImage($skill, $image, $replaceImage, 'RelationImage', 'skill', $token);
                } else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            if($request->input('url')){
                if(!$request->file('image') && !$request->input('id_image')){
                    $url = $request->input('url');
                    $this->imageAssociationService->updateImageForUrl($skill, $url, $replaceImage, 'RelationImage', $token);
                }
                else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            if($request->input('id_image')){
                $idImage = $request->input('id_image');
                if(!$request->file('image') && !$request->input('url')){
                    $this->imageAssociationService->updateImageForId($skill, $idImage, $replaceImage, 'RelationImage', $token);
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
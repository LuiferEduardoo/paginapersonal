<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skills;
use App\Models\Tags;
use App\Models\Categories;
use App\Models\Subcategories;
use App\Services\ImageAssociationService;
use App\Services\ClassificationService;
use App\Http\Requests\ValidateDate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    protected $classificationService;
    protected $imageAssociationService;

    public function __construct(ClassificationService $classificationService, ImageAssociationService $imageAssociationService)
    {
        $this->classificationService = $classificationService;
        $this->imageAssociationService = $imageAssociationService;
    }

    public function GetSkills(Request $request){
        $query = Skills::with('image', 'categories', 'subcategories', 'tags');
        if ($request->input('id')) {
            $id = $request->input('id');
            $query->where('id', $id);
        }
        $query->where('visible', true);
        $skills = $query->get();
        return response()->json($skills);
    }

    public function PostSkills(ValidateDate $request)
    {
        try{
            DB::beginTransaction();
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);

            $categories = $request->input('categories');
            $subcategories = $request->input('subcategories');
            $tags = $request->input('tags');

            // Crea la habilidad
            $skill = Skills::create([
                'name' => $request->input('name'),
                'date' => $request->input('date'),
            ]);

            if($request->hasFile('image')){
                $file = $request->file('image');
                // Se guarda la imagen en la API y se hace la asociación
                $this->imageAssociationService->saveImage($skill, $file, 'skill', 'image', $token);  
            }else if($request->input('id_image')){
                $idImage =$request->input('id_image');
                $this->imageAssociationService->saveImageForId($skill, $idImage, 'image');
            }else if($request->input('url')){
                $url = $request->input('url');
                $this->imageAssociationService->saveImageForUrl($skill, $url);
            } else{
                DB::rollBack();
                    return response()->json([
                        'message' => "Image not entered"
                    ], 400);
            }
            // Se crean las categorias y las subcategorias y los tags y se asocian con la habilidad
            $this->classificationService->createItems($skill, explode(",", $categories), 'categories', Categories::class, 'name');
            $this->classificationService->createItems($skill, explode(",", $subcategories), 'subcategories', Subcategories::class, 'name');
            $this->classificationService->createItems($skill, explode(",", $tags), 'tags', Tags::class, 'name');

            DB::commit(); // Confirmar la transacción
            return response()->json([
                'message' => 'Skills successfully created'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
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
                $items = array('tags', 'categories', 'subcategories');
                foreach ($items as $item){
                    $this->classificationService->deleteItems($skill, $item);
                }
                $this->imageAssociationService->deleteImage($skill, 'image', $eliminateImage, $token);
                $skill->delete();
                return response()->json(['message' => 'Skills successfully deleted'],200);
            }
            return response()->json([
                'message' => "Skill not fount"
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
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
            $categories = $request->input('categories');
            $subcategories = $request->input('subcategories');
            $tags = $request->input('tags');
            
            // Actualizar los campos de la habilidad
            $skill->name = $request->input('name');
            $skill->date = $request->input('date');
            // Guardar los cambios en la base de datos
            $skill->save();
            // Se actualizan las categorias, las subcategorias y los tags
            $this->classificationService->updateItems($skill, explode(",", $categories), 'categories', Categories::class, 'name');
            $this->classificationService->updateItems($skill, explode(",", $subcategories), 'subcategories', Subcategories::class, 'name');
            $this->classificationService->updateItems($skill, explode(",", $tags), 'tags', Tags::class, 'name');

            $replaceImage = filter_var($request->input('replace_image'), FILTER_VALIDATE_BOOLEAN);

            if($request->hasFile('image')){
                $file = $request->file('image');
                $this->imageAssociationService->updateImage($skill, $file, $replaceImage, 'image', 'skill', $token);
            } else if($request->input('id_image')){
                $idImage =$request->input('id_image');
                $this->imageAssociationService->updateImageForId($skill, $idImage, $replaceImage, 'image', $token);
            } else if($request->input('url')){
                $url = $request->input('url');
                $this->imageAssociationService->updateImageForUrl($skill, $url, $replaceImage, 'image', $token);
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
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
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
            if($request->input('categories')){
                $categories = $request->input('categories');
                $this->classificationService->updateItems($skill, explode(",", $categories), 'categories', Categories::class, 'name');
            }
            if($request->input('subcategories')){
                $subcategories = $request->input('subcategories');
                $this->classificationService->updateItems($skill, explode(",", $subcategories), 'subcategories', Subcategories::class, 'name');
            }
            if($request->input('tags')){
                $tags = $request->input('tags');
                $this->classificationService->updateItems($skill, explode(",", $tags), 'tags', Tags::class, 'name');
            }
            if($request->file('image')){
                $image = $request->file('image'); 
                if(!$request->input('url') && !$request->input('id_image')){
                    $this->imageAssociationService->updateImage($skill, $image, $replaceImage, 'image', 'skill', $token);
                } else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            if($request->input('url')){
                if(!$request->file('image') && !$request->input('id_image')){
                    $url = $request->input('url');
                    $this->imageAssociationService->updateImageForUrl($skill, $url, $replaceImage, 'image', $token);
                }
                else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            if($request->input('id_image')){
                $idImage = $request->input('id_image');
                if(!$request->file('image') && !$request->input('url')){
                    $this->imageAssociationService->updateImageForId($skill, $idImage, $replaceImage, 'image', $token);
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
        }catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Skill not fount"
            ], 404);
        }
    }
}
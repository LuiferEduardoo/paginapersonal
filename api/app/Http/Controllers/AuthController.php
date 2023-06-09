<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ValidateDate;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Services\ImageAssociationService;

class AuthController extends Controller
{
    protected $imageAssociationService;

    public function __construct(ImageAssociationService $imageAssociationService)
    {
        $this->imageAssociationService = $imageAssociationService;
    }
    public function login(ValidateDate $request){
        $data = json_decode($request->getContent());
        $user = User::where('email', $data->email)->first();
        if($user && Hash::check($data->password, $user->password)){
            $token = $user->createToken($user->name);
            return response()->json(["access_token" => "$token->plainTextToken",
            "token_type"=> "bearer"
        ], 200);
        }
        return response()->json([  
            "error"=> "Unauthorized",
            "message"=> "Invalid credentials"], 401);
    }

    public function getInformationUser(Request $request){
        $user = $request->user();

        // Obtener la imagen de perfil del usuario
        $profileImage = $user->profile->first();

        // En caso de que no se encuentre una imagen de perfil
        return response()->json([
            $user
        ]);
    }

    public function logout(Request $request)
    {
        try{
            $user = Auth::user();
            $user->currentAccessToken()->update(['expires_at' => now()]);
        
            return response()->json([
                'message' => 'Logout successful'
            ], 200);
        }
        catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Invalid token"
            ], 403);
        }
    }

    function updateInformationUser(ValidateDate $request){
        $errorImage = response()->json([
            'message' => "You cannot upload more than one image"
        ], 409);
        try{
            DB::beginTransaction();
            $token = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $token);
            $user = $request->user();
            if($request->input('name')){
                $user->name = $request->input('name');
            }
            if($request->input('email')){
                $user->email = $request->input('email');
            }
            if($request->input('password')){
                $user->password = bcrypt($request->input('password'));
            }
            $replaceImage = filter_var($request->input('replace_image'), FILTER_VALIDATE_BOOLEAN);
            if($request->hasFile('image')){
                $image = $request->file('image'); 
                if(!$request->input('id_image')){
                    $this->imageAssociationService->updateImage($user, $image, $replaceImage, 'profile', 'about/profile', $token);
                } else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            if($request->input('id_image')){
                $idImage = $request->input('id_image');
                if(!$request->hasFile('image')){
                    $this->imageAssociationService->updateImageForId($user, $idImage, $replaceImage, 'profile', $token);
                }
                else{
                    DB::rollBack();
                    return $errorImage;
                }
            }
            $user->save();
            DB::commit(); // Confirmar la transacción
            return response()->json([
                'message' => 'Information successfully updated'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } catch(ModelNotFoundException $e){
            DB::rollBack(); // Deshacer la transacción en caso de error
            return response()->json([
                'message' => "Information could not be updated"
            ], 404);
        }
    }
}
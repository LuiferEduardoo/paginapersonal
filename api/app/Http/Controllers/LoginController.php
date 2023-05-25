<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ValidateDate;
use App\Models\User;

class LoginController extends Controller
{
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
}
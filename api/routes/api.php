<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmailController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SkillController;
use App\Services\ApiKeyGenerator;


Route::middleware(['api_key'])->group(function () {
    Route::post('/email', [EmailController::class, 'SendEmail']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/skills', [SkillController::class, 'GetSkills']);
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/user', function(Request $request) {
            return $request->user();
        });
        Route::post('/skills/create', [SkillController::class, 'PostSkills']);
        Route::delete('/skills', [SkillController::class, 'DeleteSkills']);
        Route::put('/skills/{id}', [SkillController::class, 'PutSkills']);
        Route::patch('/skills/{id}', [SkillController::class, 'PatchSkills']);
    });
});

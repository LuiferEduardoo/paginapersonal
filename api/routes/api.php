<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BlogController;


Route::post('/email', [EmailController::class, 'SendEmail']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/skills', [SkillController::class, 'GetSkills']);
Route::get('/projects', [ProjectController::class, 'getProject']);
Route::get('/blogposts', [BlogController::class, 'getBlogPost']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', [AuthController::class, 'getInformationUser']);
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::patch('/user', [AuthController::class, 'updateInformationUser']);

    Route::post('/skills/create', [SkillController::class, 'createSkills']);
    Route::delete('/skills/{id}', [SkillController::class, 'deleteSkills']);
    Route::put('/skills/{id}', [SkillController::class, 'updateSkills']);

    Route::post('/project/create', [ProjectController::class, 'createProject']);
    Route::delete('/project/{id}', [ProjectController::class, 'deleteProject']);
    Route::put('/project/{id}', [ProjectController::class, 'updateProject']);

    Route::post('/blogpost/create', [BlogController::class, 'createBlogPost']);
    Route::delete('/blogpost/{id}', [BlogController::class, 'deleteBlogPost']);
    Route::put('/blogpost/{id}', [BlogController::class, 'updateBlogPost']);
});

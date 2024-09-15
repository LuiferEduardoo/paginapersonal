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
Route::get('/skills/{id?}', [SkillController::class, 'GetSkills']);
Route::get('/projects/{id?}', [ProjectController::class, 'getProject']);
Route::get('/blogposts/{id?}', [BlogController::class, 'getBlogPost']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', [AuthController::class, 'getInformationUser']);
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::patch('/user', [AuthController::class, 'updateInformationUser']);

    Route::post('/skills/create', [SkillController::class, 'createSkills']);
    Route::delete('/skills/{id}', [SkillController::class, 'deleteSkills']);
    Route::put('/skills/{id}', [SkillController::class, 'updateSkills']);

    Route::post('/projects/create', [ProjectController::class, 'createProject']);
    Route::delete('/projects/{id}', [ProjectController::class, 'deleteProject']);
    Route::put('/projects/{id}', [ProjectController::class, 'updateProject']);

    Route::post('/blogposts/create', [BlogController::class, 'createBlogPost']);
    Route::delete('/blogposts/{id}', [BlogController::class, 'deleteBlogPost']);
    Route::put('/blogposts/{id}', [BlogController::class, 'updateBlogPost']);
});

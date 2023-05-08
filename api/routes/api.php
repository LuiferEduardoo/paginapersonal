<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmailController;
use App\Services\ApiKeyGenerator;


Route::middleware(['api_key'])->group(function () {
    Route::post('/email', [EmailController::class, 'SendEmail']);
    Route::get('/key', [EmailController::class, 'GetInformation']);
});

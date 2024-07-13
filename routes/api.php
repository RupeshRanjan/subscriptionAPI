<?php

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\WebsiteController;
use App\Http\Controllers\API\PostController;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group( function () {
    Route::apiResource('websites', WebsiteController::class);
    Route::post('websites/{website}/subscribe', [WebsiteController::class, 'subscribe']);
    Route::apiResource('websites/{website}/posts', PostController::class);
});

Route::get('send/mail', [WebsiteController::class, 'sendMailWithAttachment']);


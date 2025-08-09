<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('blogs', [BlogController::class, 'store']);
    
    Route::match(['get', 'post'], 'blogs', [BlogController::class, 'index']);
    Route::put('blogs/{id}', [BlogController::class, 'update']);
    Route::delete('blogs/{id}', [BlogController::class, 'destroy']);
   Route::get('blogs/{id}', [BlogController::class, 'show']);
    Route::post('blogs/{id}/toggle-like', [LikeController::class, 'toggle']);
});


Route::get('/test-api', function () {
    return response()->json(['status' => 'API working!']);
});
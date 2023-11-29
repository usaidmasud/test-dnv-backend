<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\UmkmController;
use App\Http\Controllers\PublicUmkmController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/profile', [AuthController::class, 'profile']);
    Route::apiResource('/umkm', UmkmController::class);
    Route::apiResource('/product', ProductController::class);
    Route::post('/file/upload', [FileController::class, 'upload']);
});

Route::apiResource('/province', ProvinceController::class)->only(['index', 'show']);
Route::apiResource('/city', CityController::class)->only(['show']);

Route::apiResource('public/umkm', UmkmController::class)->only(['index', 'show']);
Route::get('public/umkm/product/{pid}', [UmkmController::class, 'showProduct']);

Route::apiResource('public/product', ProductController::class)->only(['index', 'show']);

<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register',[AuthController::class,'register']);
Route::post('auth/login',[AuthController::class,'login']);

// protegiendo la rutas con credenciales
Route::group(['middleware'=> ['jwt.verify']], function() {
    Route::apiResource('auth/users',UserController::class);
    Route::get('auth/me',[AuthController::class,'me']);
    Route::get('auth/logout',[AuthController::class,'logout']);
    Route::post('auth/refreshToken',[AuthController::class,'refreshToken']);
    Route::apiResource('task',TaskController::class);
});


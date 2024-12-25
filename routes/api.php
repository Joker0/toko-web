<?php

use App\Http\Middleware\APIAuthMiddleware;
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

Route::post('/users', [App\Http\Controllers\UserController::class, 'register']);
Route::post('/users/login', [App\Http\Controllers\UserController::class, 'login']);

Route::middleware([APIAuthMiddleware::class])->group(function () {
    Route::get('/users/me', [App\Http\Controllers\UserController::class, 'get']);
    Route::patch('/users/me', [App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/me', [App\Http\Controllers\UserController::class, 'logout']);
});
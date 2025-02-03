<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SPKController;
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

Route::post('/auth/login', [UserController::class, 'loginUser']);

Route::get('spks', [SPKController::class, 'index']);
Route::get('spks/{id}', [SPKController::class, 'show']);
Route::post('spks', [SPKController::class, 'store']);
Route::put('spks/{id}', [SPKController::class, 'update']);
Route::delete('spks/{id}', [SPKController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout',[UserController::class, 'logout']);
});
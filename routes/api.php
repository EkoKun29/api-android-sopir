<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SPKController;
use App\Http\Controllers\AbsenBerangkatController;
use App\Http\Controllers\AbsenPulangController;
use App\Http\Controllers\Api\ExportDataController;
use App\Http\Controllers\SPKJemberController;

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

Route::post('/auth/login', [UserController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [UserController::class, 'logout']);

    // SPK
    Route::get('/spk', [SPKController::class, 'index']);
    Route::get('/spk/{id}', [SPKController::class, 'show']);
    Route::post('/spk', [SPKController::class, 'store']);
    Route::put('/spk/{id}', [SPKController::class, 'update']);
    Route::delete('/spk/{id}', [SPKController::class, 'destroy']);

    // SPK Jember
    Route::get('/spk-jember', [SPKJemberController::class, 'index']);
    Route::get('/spk-jember/{id}', [SPKJemberController::class, 'show']);
    Route::post('/spk-jember', [SPKJemberController::class, 'store']);
    Route::put('/spk-jember/{id}', [SPKJemberController::class, 'update']);
    Route::delete('/spk-jember/{id}', [SPKJemberController::class, 'destroy']);

    // Absen Berangkat
    Route::get('/absen-berangkat', [AbsenBerangkatController::class, 'index']);
    Route::get('/absen-berangkat/{id}', [AbsenBerangkatController::class, 'show']);
    Route::post('/absen-berangkat', [AbsenBerangkatController::class, 'store']);
    Route::put('/absen-berangkat/{id}', [AbsenBerangkatController::class, 'update']);
    Route::delete('/absen-berangkat/{id}', [AbsenBerangkatController::class, 'destroy']);

    // Absen Pulang
    Route::get('/absen-pulang', [AbsenPulangController::class, 'index']);
    Route::get('/absen-pulang/{id}', [AbsenPulangController::class, 'show']);
    Route::post('/absen-pulang', [AbsenPulangController::class, 'store']);
    Route::put('/absen-pulang/{id}', [AbsenPulangController::class, 'update']);
    Route::delete('/absen-pulang/{id}', [AbsenPulangController::class, 'destroy']);

    // User
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::post('/user', [UserController::class, 'store']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
});

Route::get('/absen-berangkat/{startDate}/{endDate}',[ExportDataController::class, 'absen']);

Route::get('/absen-pulang/{startDate}/{endDate}',[ExportDataController::class, 'absenpulang']);

Route::get('/spk/{startDate}/{endDate}',[ExportDataController::class, 'spk']);

Route::get('/spk-jember/{startDate}/{endDate}',[ExportDataController::class, 'spkjember']);

Route::get('/all-spk/{startDate}/{endDate}', [ExportDataController::class, 'allSpk']);




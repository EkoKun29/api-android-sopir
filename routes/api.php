<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SPKController;
use App\Http\Controllers\AbsenBerangkatController;
use App\Http\Controllers\AbsenPulangController;
use App\Http\Controllers\API\ExportDataController;

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
});

Route::get('/absen-berangkat/{startDate}/{endDate}',[ExportDataController::class, 'absen']);

Route::get('/absen-pulang/{startDate}/{endDate}',[ExportDataController::class, 'absenpulang']);


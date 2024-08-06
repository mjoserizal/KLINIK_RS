<?php

use App\Http\Controllers\Api\PasienController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RekamController;

Route::post('/rekam-luka-bakar/{no_rm}', [RekamController::class, 'store']);
Route::get('/pasien', [PasienController::class, 'index']);
Route::get('/pasien/{no_rm}', [PasienController::class, 'showByNoRm']);

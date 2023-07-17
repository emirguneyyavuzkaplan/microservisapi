<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GateController;

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

Route::post('register', [UserController::class, 'create']);

Route::middleware('auth:api')->group(function () {
    Route::post('entry', [EntryController::class, 'create']);
});

Route::post('/open-gate', [GateController::class, 'openGate']);

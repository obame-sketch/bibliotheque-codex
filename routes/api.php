<?php

use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\LivreController;
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

Route::apiResource('livres', LivreController::class)->only(['index', 'show']);

Route::post('emprunts/emprunter', [EmpruntController::class, 'emprunter']);
Route::post('emprunts/{id}/retourner', [EmpruntController::class, 'retourner']);
Route::apiResource('emprunts', EmpruntController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

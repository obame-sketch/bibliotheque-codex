<?php

use App\Http\Controllers\BibliothecaireController;
use Illuminate\Support\Facades\Route;

Route::get('/livres', [BibliothecaireController::class, 'listerLivres']);
Route::get('/livres/{id}', [BibliothecaireController::class, 'afficherLivre']);
Route::post('/livres', [BibliothecaireController::class, 'ajouterLivre']);
Route::post('/exemplaires', [BibliothecaireController::class, 'ajouterExemplaire']);

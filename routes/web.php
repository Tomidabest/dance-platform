<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadPageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StudioController;

Route::get('/', [LeadPageController::class, 'index']);


Route::get('/register', [RegisterController::class, 'index']);


Route::get('/search', [SearchController::class, 'index'])->name('search');


Route::get('/studios/{studio}', [StudioController::class, 'single'])->name('studios.single');

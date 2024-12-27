<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadPageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StudioController;

Route::get('/', [LeadPageController::class, 'index'])->name('landing');


Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');


Route::get('/search', [SearchController::class, 'index'])->name('search');


Route::get('/studios', [StudioController::class, 'index'])->name('studios.all');


Route::get('/studios/{studio}', [StudioController::class, 'single'])->name('studios.single');


Route::post('/classes/{class}/book', [StudioController::class, 'book'])->name('class.book');

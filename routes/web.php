<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadPageController;
use App\Http\Controllers\RegisterController;

Route::get('/', [LeadPageController::class, 'index']);


Route::get('/register', [RegisterController::class, 'index']);

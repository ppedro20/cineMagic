<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::view('/', 'home')->name('home');

Route::resource('movies', MovieController::class);

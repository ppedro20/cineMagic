<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TheaterController;

Route::view('/', 'welcome')->name('home');

Route::resource('movies', MovieController::class);
Route::resource('theaters', TheaterController::class);
Route::resource('customers', CustomerController::class);

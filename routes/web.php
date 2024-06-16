<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\AdministrativeController;
use App\Http\Controllers\ProfileController;

/* ----- PUBLIC ROUTES ----- */
Route::view('/', 'home')->name('home');

Route::get('/generate-pdf', [PDFController::class, 'generatePDF']);

// Movies
Route::get('movies/showmovies', [MovieController::class, 'showMovies'])
    ->name('movies.showmovies');
Route::resource('movies', MovieController::class)->only(['show']);

// Screenings
Route::get('screenings/showscreenings', [ScreeningController::class, 'showScreenings'])
    ->name('screenings.showscreenings');

Route::get('screenings/show/{screening}',[ScreeningController::class, 'show'])
    ->name('screenings.show');


// Cart
Route::post('cart/{screening}/{seat}', [CartController::class, 'addToCart'])
    ->name('cart.add');
Route::delete('cart/{screening}/{seat}', [CartController::class, 'removeFromCart'])
    ->name('cart.remove');
Route::get('cart', [CartController::class, 'show'])->name('cart.show');
Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('cart', [CartController::class, 'confirm'])
        ->name('cart.confirm');

/* ----- ------ ------ ----- */


/* ----- Non-Verified users ----- */
Route::middleware('auth')->group(function () {
    Route::get('/password', [ProfileController::class, 'editPassword'])->name('profile.edit.password');
});



/* ----- Verified users ----- */
Route::middleware('auth', 'verified')->group(function () {


    Route::get('/dashboard', function () {
        return view('dashboard');
       })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::delete('administratives/{administrative}/photo', [AdministrativeController::class, 'destroyPhoto'])
    ->name('administratives.photo.destroy')
    ->can('update', 'administrative');

    //Admnistrative resource routes are protected by AdministrativePolicy on the controller
    Route::resource('administratives', AdministrativeController::class);

    Route::resource('genres', GenreController::class);


    Route::delete('movies/{movie}/poster', [MovieController::class, 'destroyPoster'])
        ->name('movies.poster.destroy')
        ->can('update', 'movie');

    Route::resource('movies', MovieController::class)->except(['show']);

    Route::resource('screenings', ScreeningController::class)->except(['show']);

    Route::resource('theaters', TheaterController::class);

    Route::get('/seats/create/{theaterId}', [SeatController::class, 'create'])
        ->name('seats.create');

    Route::resource('seats', SeatController::class)->except(['create']);

    Route::get('tickets',[TicketController::class, 'index'])
        ->name('tickets.index');

    Route::get('tickets/{ticket}',[TicketController::class, 'show'])
        ->name('tickets.show');

    Route::resource('purchases',PurchaseController::class)->only(['index', 'show']);
    Route::get('purchases/{purchase}/receipt',[PurchaseController::class, 'showReciept'])
        ->name('purchases.receipt');
    Route::get('/pdf/{filename}', [PDFController::class, 'showPDF'])
        ->name('pdf.show');


    Route::get('configurations',[ConfigurationController::class, 'edit'])
        ->name('configurations.edit');


    Route::put('configurations',[ConfigurationController::class, 'update'])
        ->name('configurations.update');
});



require __DIR__ . '/auth.php';


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TheaterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\AdministrativeController;
use App\Http\Controllers\StatisticsController;


Route::view('/', 'home')->name('home');



// Movie
Route::delete('movies/{movie}/poster', [MovieController::class, 'destroyPoster'])
    ->name('movies.poster.destroy')
    ->can('update', 'movie');

Route::get('movies/showmovies', [MovieController::class, 'showMovies'])
    ->name('movies.showmovies');
Route::resource('movies', MovieController::class);


// Screening
Route::get('screenings/showscreenings', [ScreeningController::class, 'showScreenings'])
    ->name('screenings.showscreenings');

Route::resource('screenings', ScreeningController::class);


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

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
       })->middleware(['auth', 'verified'])->name('dashboard');

    // Statistics
    Route::get('/statistics', [StatisticsController::class, 'index'])
        ->name('statistics.index');

    // Profile
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Admin
    Route::delete('administratives/{administrative}/photo', [AdministrativeController::class, 'destroyPhoto'])
    ->name('administratives.photo.destroy');

    Route::resource('administratives', AdministrativeController::class);

    // Employee
    Route::delete('employees/{employee}/photo', [EmployeeController::class, 'destroyPhoto'])
    ->name('employees.photo.destroy');
    Route::resource('employees', EmployeeController::class);

    // Customer
    Route::delete('customers/{customer}/photo', [CustomerController::class, 'destroyPhoto'])
    ->name('customers.photo.destroy');

    Route::resource('customers', CustomerController::class)->except(['create', 'store']);


    // Genre
    Route::resource('genres', GenreController::class);







    // Screening
    Route::resource('screenings', ScreeningController::class)->except(['show']);


    // Theater
    Route::resource('theaters', TheaterController::class);


    // Seats
    Route::get('/seats/create/{theaterId}', [SeatController::class, 'create'])
        ->name('seats.create');

    Route::resource('seats', SeatController::class)->except(['create']);


    // Tickets
    Route::get('tickets',[TicketController::class, 'index'])
        ->name('tickets.index');

    Route::get('tickets/{ticket}',[TicketController::class, 'show'])
        ->name('tickets.show');

    Route::get('tickets/validate/{screening}',[TicketController::class, 'validate'])
        ->name('tickets.validate');

    Route::patch('tickets/updatestatus/{ticket}',[TicketController::class, 'updateStatus'])
        ->name('tickets.updateStatus');


    // Purchase
    Route::resource('purchases',PurchaseController::class)->only(['index', 'show']);
    Route::get('purchases/{purchase}/receipt',[PurchaseController::class, 'showReciept'])
        ->name('purchases.receipt');
    // Pdf
    Route::get('/pdf/{filename}', [PDFController::class, 'showPDF'])
        ->name('pdf.show');

    // Configuration
    Route::get('configurations',[ConfigurationController::class, 'edit'])
        ->name('configurations.edit');

    Route::put('configurations',[ConfigurationController::class, 'update'])
        ->name('configurations.update');
});


require __DIR__ . '/auth.php';

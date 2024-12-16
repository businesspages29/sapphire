<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// route controller group of home controller

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/create', 'create')->name('home.create');
    Route::post('/store', 'store')->name('home.store');
    Route::get('/match/{id}/round/{round}', 'matchRound')->name('home.matchRound');
});

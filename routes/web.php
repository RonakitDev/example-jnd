<?php

use App\Http\Controllers\ShotlinkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('shotlink', MainController::class);
Route::get('/data', [MainController::class, 'getData'])->name('data.get');
Route::get('/jdntest/{id}', [MainController::class, 'openlink']);






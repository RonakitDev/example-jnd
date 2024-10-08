<?php

use App\Http\Controllers\ShotlinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/shotlink', [ShotlinkController::class, 'index'])->name('shotlink');
Route::post('/shotlink/store', [ShotlinkController::class, 'add'])->name('data.save');
Route::post('/shotlink/update/{id}', [ShotlinkController::class, 'update']);
Route::post('/shotlink/delete/{id}', [ShotlinkController::class, 'delete']);
Route::get('/data', [ShotlinkController::class, 'getData'])->name('data.get'); // Replace YourController with your actual controller
Route::get('/jdntest/{id}', [ShotlinkController::class, 'openlink']);






<?php

use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/telkomsel', [SurveyController::class, 'telkomsel']);
Route::post('/telkomsel', [SurveyController::class, 'storeTelkomsel']);

Route::get('/indihome', [SurveyController::class, 'indihome']);
Route::post('/indihome', [SurveyController::class, 'storeIndihome']);

Route::get('/template', [SurveyController::class, 'template']);
Route::post('/template', [SurveyController::class, 'storeTemplate']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

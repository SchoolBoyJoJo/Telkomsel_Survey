<?php

use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // ini nanti untuk halaman login admin
});

Route::get('/telkomsel', [SurveyController::class, 'telkomsel']);
Route::post('/telkomsel', [SurveyController::class, 'storeTelkomsel']);

Route::get('/indihome', [SurveyController::class, 'indihome']);
Route::post('/indihome', [SurveyController::class, 'storeIndihome']);

Route::get('/template', [SurveyController::class, 'template']);
Route::post('/template', [SurveyController::class, 'storeTemplate']);
<?php

use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('survey');
});

Route::post('/submit-survey', [SurveyController::class, 'store']);

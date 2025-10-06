<?php

use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaranController;
use App\Http\Controllers\MakeSurveyController;
use Illuminate\Support\Facades\Route;
use Vinkla\Hashids\Facades\Hashids;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/telkomsel', [SurveyController::class, 'telkomsel']);
Route::post('/telkomsel', [SurveyController::class, 'storeTelkomsel']);

Route::get('/indihome', [SurveyController::class, 'indihome']);
Route::post('/indihome', [SurveyController::class, 'storeIndihome']);

Route::get('/template', [SurveyController::class, 'template']);
Route::post('/template', [SurveyController::class, 'storeTemplate']);

Route::resource('surveys', SurveyController::class);

// 🧩 Ganti route publik agar pakai hash
Route::get('/survey/{hash}', function ($hash) {
    $decoded = Hashids::decode($hash);
    $id = $decoded[0] ?? null;

    abort_unless($id, 404);

    return app(SurveyController::class)->publicShow($id);
})->name('survey.public.show');

Route::post('/survey/{hash}/dynamic-submit', function ($hash, \Illuminate\Http\Request $request) {
    $decoded = Hashids::decode($hash);
    $id = $decoded[0] ?? null;

    abort_unless($id, 404);

    return app(SurveyController::class)->storeDynamicAnswer($request, $id);
})->name('survey.dynamic.store');

Route::post('/surveys/store-template', [SurveyController::class, 'storeTemplate'])->name('surveys.storeTemplate');

Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');

Route::post('/saran/summary-ajax', [SaranController::class, 'summaryAjax'])->name('saran.summary.ajax');

Route::get('/surveys/create', [MakeSurveyController::class, 'create'])->name('surveys.create');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/download', [DashboardController::class, 'download'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.download');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

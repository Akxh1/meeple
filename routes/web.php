<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestExamController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsTeacher;
use App\Http\Controllers\Teacher\QuestionController;
use App\Http\Controllers\HintController;
use App\Http\Controllers\RiskPredictorController;

Route::post('/generate-hint', [HintController::class, 'generate']);

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', IsTeacher::class])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('questions/upload', [QuestionController::class, 'showUploadForm'])->name('questions.upload');
        Route::post('questions/preview', [QuestionController::class, 'previewUpload'])->name('questions.preview');
        Route::post('questions/store', [QuestionController::class, 'storeUploaded'])->name('questions.store');
    });

// web.php
Route::get('/risk-predictor', [RiskPredictorController::class, 'index'])->name('risk.predictor');
Route::post('/risk-predictor/upload', [RiskPredictorController::class, 'upload'])->name('risk.predictor.upload');


Route::get('/Test-Exam', [TestExamController::class,'index'])->name('test-exam.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsTeacher;
use App\Http\Controllers\Teacher\QuestionController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestExamController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsTeacher;
use App\Http\Controllers\Teacher\QuestionController;
use App\Http\Controllers\HintController;
use App\Http\Controllers\RiskPredictorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LevelIndicatorExamController;



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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/student', [DashboardController::class, 'studentDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('student.dashboard');

// Module Detail Page (for students)
Route::get('/module/{module}', [DashboardController::class, 'showModule'])
    ->middleware(['auth', 'verified'])
    ->name('student.module.show');

// ================================================================
// Level Indicator Exam Routes
// ================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/module/{module}/level-indicator', [LevelIndicatorExamController::class, 'show'])
        ->name('level-indicator.show');
    Route::get('/module/{module}/level-indicator/start', [LevelIndicatorExamController::class, 'start'])
        ->name('level-indicator.start');
    Route::post('/module/{module}/level-indicator/submit', [LevelIndicatorExamController::class, 'submit'])
        ->name('level-indicator.submit');
    Route::get('/module/{module}/level-indicator/results/{attempt}', [LevelIndicatorExamController::class, 'results'])
        ->name('level-indicator.results');
});

// Student Detail Page (for instructors)
Route::get('/dashboard/student/{student}', [DashboardController::class, 'showStudent'])
    ->middleware(['auth', 'verified'])
    ->name('instructor.student.show');

// Send Warning Notification
Route::post('/dashboard/student/{student}/warn', [DashboardController::class, 'sendWarning'])
    ->middleware(['auth', 'verified'])
    ->name('instructor.student.warn');

// Generate AI Insights
Route::post('/dashboard/student/{student}/generate-insights', [DashboardController::class, 'generateAIInsights'])
    ->middleware(['auth', 'verified'])
    ->name('instructor.student.insights');

// Module Settings (for instructors)
Route::get('/dashboard/module/{module}/settings', [DashboardController::class, 'showModuleSettings'])
    ->middleware(['auth', 'verified'])
    ->name('instructor.module.settings');

Route::post('/dashboard/module/{module}/settings', [DashboardController::class, 'updateModuleSettings'])
    ->middleware(['auth', 'verified'])
    ->name('instructor.module.settings.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/student/warnings', [StudentController::class, 'getWarnings'])
    ->middleware('auth');

// Notification API Routes
use App\Http\Controllers\NotificationController;

Route::middleware('auth')->group(function () {
    Route::get('/api/notifications', [NotificationController::class, 'getMyNotifications']);
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);
    Route::post('/api/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/api/notifications/mark-all-read', [NotificationController::class, 'markAllRead']);
    Route::post('/api/notifications/send-warning', [NotificationController::class, 'sendWarning']);
    Route::get('/api/students/dropdown', [NotificationController::class, 'getStudentsForDropdown']);
});


require __DIR__ . '/auth.php';

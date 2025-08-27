<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\StudySessionController;
use App\Http\Controllers\ChatController;

// Public API routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/login/face', [AuthController::class, 'recognizeFace']);
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);


// Protected API routes (require authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // User routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/user/update', [AuthController::class, 'updateUser']);
    
    // Dashboard API Routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [DashboardController::class, 'getStats']);
        Route::get('/recent-activity', [DashboardController::class, 'getRecentActivity']);
        Route::get('/upcoming-sessions', [DashboardController::class, 'getUpcomingSessions']);
    });
    
    // Flashcard API Routes
    Route::prefix('flashcards')->group(function () {
        Route::get('/', [FlashcardController::class, 'index']);
        Route::post('/', [FlashcardController::class, 'store']);
        Route::get('/{id}', [FlashcardController::class, 'show']);
        Route::put('/{id}', [FlashcardController::class, 'update']);
        Route::delete('/{id}', [FlashcardController::class, 'destroy']);
        Route::post('/generate-ai', [FlashcardController::class, 'generateAI']);
        Route::post('/{id}/share', [FlashcardController::class, 'share']);
        Route::post('/{id}/study', [FlashcardController::class, 'recordStudySession']);
    });
    
    // Study Session API Routes
    Route::prefix('study-sessions')->group(function () {
        Route::get('/', [StudySessionController::class, 'index']);
        Route::post('/', [StudySessionController::class, 'store']);
        Route::get('/{id}', [StudySessionController::class, 'show']);
        Route::put('/{id}', [StudySessionController::class, 'update']);
        Route::delete('/{id}', [StudySessionController::class, 'destroy']);
        Route::post('/{id}/join', [StudySessionController::class, 'join']);
    });
    
    // Chat API Routes
    Route::prefix('chat')->group(function () {
        Route::get('/messages', [ChatController::class, 'getMessages']);
        Route::post('/messages', [ChatController::class, 'sendMessage']);
        Route::post('/messages/{id}/delete', [ChatController::class, 'deleteMessage']);
        Route::get('/groups', [ChatController::class, 'getGroups']);
        Route::post('/groups', [ChatController::class, 'createGroup']);
    });

//     // Quiz API Routes

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('quizzes')->group(function () {
        Route::post('/create/{setId}', [QuizController::class, 'store'])->name('quizzes.store.api');
        Route::post('/{quizId}/start', [QuizController::class, 'startAttempt']);
        Route::post('/attempts/{attemptId}/answer', [QuizController::class, 'submitAnswer']);
        Route::post('/attempts/{attemptId}/complete', [QuizController::class, 'completeAttempt']);
        Route::get('/attempts/{attemptId}/results', [QuizController::class, 'getResults']);
        Route::get('/history', [QuizController::class, 'getHistory']);
    });
});
    
    // Real-time updates
    Route::get('/updates', [DashboardController::class, 'streamUpdates']);
});
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('index');
});

Route::get('/auth', function () {
    return view('auth');
})->name('auth');

// ======== ADD THESE AUTHENTICATION ROUTES ========
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// ================================================

// Password reset routes
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

// Facial recognition routes
Route::post('/facial/register', [AuthController::class, 'registerFace'])->name('facial.register');
Route::post('/facial/login', [AuthController::class, 'loginFace'])->name('facial.login');

Route::get('login/google', [AuthController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Dashboard route (protected by auth middleware)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');// ================================================

// ======== ADD THESE RESOURCE ROUTES ========
Route::resource('flashcards', FlashcardController::class)->middleware('auth');
Route::resource('groups', GroupController::class)->middleware('auth');
Route::resource('quizzes', QuizController::class)->middleware('auth');
Route::resource('chats', ChatController::class)->middleware('auth');
// ===========================================



// Flashcard Routes
Route::get('/flashcards', [FlashcardController::class, 'index'])->name('flashcards.index');
Route::get('/flashcards/create', [FlashcardController::class, 'create'])->name('flashcards.create');
Route::post('/flashcards', [FlashcardController::class, 'store'])->name('flashcards.store');
Route::get('/flashcards/{id}', [FlashcardController::class, 'show'])->name('flashcards.show');
Route::get('/flashcards/{id}/edit', [FlashcardController::class, 'edit'])->name('flashcards.edit');
Route::put('/flashcards/{id}', [FlashcardController::class, 'update'])->name('flashcards.update');
Route::delete('/flashcards/{id}', [FlashcardController::class, 'destroy'])->name('flashcards.destroy');

// Quiz Routes
Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
Route::get('/quizzes/{id}', [QuizController::class, 'show'])->name('quizzes.show');
Route::get('/quizzes/{id}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
Route::put('/quizzes/{id}', [QuizController::class, 'update'])->name('quizzes.update');
Route::delete('/quizzes/{id}', [QuizController::class, 'destroy'])->name('quizzes.destroy');

// Video Call Routes
Route::get('/video-calls', [VideoCallController::class, 'index'])->name('video-calls');
Route::get('/video-calls/{roomId}', [VideoCallController::class, 'join'])->name('video-calls.join');

// Chat Routes
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::get('/chat/{groupId}', [ChatController::class, 'show'])->name('chat.show');

// Study Group Routes
Route::get('/study-groups', [StudyGroupController::class, 'index'])->name('study-groups.index');
Route::get('/study-groups/create', [StudyGroupController::class, 'create'])->name('study-groups.create');
Route::post('/study-groups', [StudyGroupController::class, 'store'])->name('study-groups.store');
Route::get('/study-groups/{id}', [StudyGroupController::class, 'show'])->name('study-groups.show');
Route::get('/study-groups/{id}/edit', [StudyGroupController::class, 'edit'])->name('study-groups.edit');
Route::put('/study-groups/{id}', [StudyGroupController::class, 'update'])->name('study-groups.update');
Route::delete('/study-groups/{id}', [StudyGroupController::class, 'destroy'])->name('study-groups.destroy');

// Additional routes for schedule and settings
Route::get('/schedule', function () {
    return view('schedule');
})->name('schedule.index');

Route::get('/settings', function () {
    return view('settings');
})->name('settings.index');



require __DIR__.'/auth.php';
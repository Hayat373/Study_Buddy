<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\StudyGroupController;
use App\Http\Controllers\VideoCallController;
use App\Http\Controllers\StudySessionController;

// Add these new import statements
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\DiscussionReplyController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\StudyGroupInvitationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingsController;

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('index');
});

Route::get('/auth', function () {
    return view('auth');
})->name('auth');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password reset routes
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

// Facial recognition routes
Route::post('/facial/register', [AuthController::class, 'registerFace'])->name('facial.register');
Route::post('/facial/login', [AuthController::class, 'loginFace'])->name('facial.login');

// Google authentication
Route::get('login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Public routes (no auth required)
Route::get('/schedule', function () {
    return view('schedule');
})->name('schedule.index');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Flashcard Routes
    Route::prefix('flashcards')->group(function () {
        Route::get('/', [FlashcardController::class, 'index'])->name('flashcards.index');
        Route::get('/create', [FlashcardController::class, 'create'])->name('flashcards.create');
        Route::post('/', [FlashcardController::class, 'store'])->name('flashcards.store');
        Route::get('/{id}', [FlashcardController::class, 'show'])->name('flashcards.show');
        Route::get('/{id}/edit', [FlashcardController::class, 'edit'])->name('flashcards.edit');
        Route::put('/{id}', [FlashcardController::class, 'update'])->name('flashcards.update');
        Route::delete('/{id}', [FlashcardController::class, 'destroy'])->name('flashcards.destroy');
        Route::post('/generate-ai', [FlashcardController::class, 'generateAI'])->name('flashcards.generate.ai');
        Route::post('/{id}/share', [FlashcardController::class, 'share'])->name('flashcards.share');
        Route::post('/generate-from-file', [FlashcardController::class, 'generateFromFile'])->name('flashcards.generate.file');
    });
    
    // Study Session Routes
    Route::prefix('study-sessions')->group(function () {
        Route::get('/', [StudySessionController::class, 'index'])->name('study-sessions.index');
        Route::get('/create', [StudySessionController::class, 'create'])->name('study-sessions.create');
        Route::post('/', [StudySessionController::class, 'store'])->name('study-sessions.store');
        Route::get('/{id}', [StudySessionController::class, 'show'])->name('study-sessions.show');
        Route::get('/{id}/edit', [StudySessionController::class, 'edit'])->name('study-sessions.edit');
        Route::put('/{id}', [StudySessionController::class, 'update'])->name('study-sessions.update');
        Route::delete('/{id}', [StudySessionController::class, 'destroy'])->name('study-sessions.destroy');
        Route::post('/{id}/join', [StudySessionController::class, 'join'])->name('study-sessions.join');
    });

   // Quiz Routes
Route::resource('quizzes', QuizController::class);
Route::get('quizzes/create/{setId}', [QuizController::class, 'create'])->name('quizzes.create');
Route::post('quizzes/store/{setId}', [QuizController::class, 'store'])->name('quizzes.store');
Route::get('quizzes/take/{id}', [QuizController::class, 'takeQuiz'])->name('quizzes.take');
Route::post('quizzes/start/{quizId}', [QuizController::class, 'startAttempt'])->name('quizzes.start');
Route::post('quizzes/answer/{attemptId}', [QuizController::class, 'submitAnswer'])->name('quizzes.answer');
Route::post('quizzes/complete/{attemptId}', [QuizController::class, 'completeAttempt'])->name('quizzes.complete');
Route::get('quizzes/results/{attemptId}', [QuizController::class, 'getResults'])->name('quizzes.results');
Route::get('quiz/history', [QuizController::class, 'history'])->name('quiz.history');
Route::get('quizzes/take/{id}', [QuizController::class, 'takeQuiz'])->name('quizzes.take');

    // // Video Call Routes
    // Route::prefix('video-calls')->group(function () {
    //     Route::get('/', [VideoCallController::class, 'index'])->name('video-calls.index');
    //     Route::get('/{roomId}', [VideoCallController::class, 'join'])->name('video-calls.join');
    // });

    // Chat Routes
    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/{chat}', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/{chat}/message', [ChatController::class, 'storeMessage'])->name('chat.message.store');
        Route::get('/user/{user}', [ChatController::class, 'findOrCreate'])->name('chat.with.user');
        Route::get('/users/search', [ChatController::class, 'userSearch'])->name('chat.users.search');
        Route::post('/start', [ChatController::class, 'startChat'])->name('chat.start');
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('chat.unread.count');
        Route::post('/mark-all-read', [ChatController::class, 'markAllAsRead'])->name('chat.mark.all.read');
    });

    // Study Groups Routes
    Route::prefix('study-groups')->group(function () {
        Route::get('/', [StudyGroupController::class, 'index'])->name('study-groups.index');
        Route::post('/', [StudyGroupController::class, 'store'])->name('study-groups.store');
        Route::get('/create', [StudyGroupController::class, 'create'])->name('study-groups.create');
        Route::get('/{id}', [StudyGroupController::class, 'show'])->name('study-groups.show');
        Route::get('/{id}/edit', [StudyGroupController::class, 'edit'])->name('study-groups.edit');
        Route::put('/{id}', [StudyGroupController::class, 'update'])->name('study-groups.update');
        Route::delete('/{id}', [StudyGroupController::class, 'destroy'])->name('study-groups.destroy');
        Route::post('/{id}/join', [StudyGroupController::class, 'join'])->name('study-groups.join');
        Route::post('/{id}/leave', [StudyGroupController::class, 'leave'])->name('study-groups.leave');
        
        // Study Group Invitations
        Route::post('/{groupId}/invite', [StudyGroupInvitationController::class, 'invite'])->name('study-groups.invite');
        Route::get('/invitations/{token}/accept', [StudyGroupInvitationController::class, 'accept'])->name('study-groups.invitation.accept');
        Route::get('/invitations/{token}/decline', [StudyGroupInvitationController::class, 'decline'])->name('study-groups.invitation.decline');
        Route::get('/{groupId}/invitations', [StudyGroupInvitationController::class, 'pendingInvitations'])->name('study-groups.invitations.pending');

        // Discussions Routes
        Route::prefix('{groupId}')->group(function () {
            Route::get('/discussions', [DiscussionController::class, 'index'])->name('study-groups.discussions.index');
            Route::get('/discussions/create', [DiscussionController::class, 'create'])->name('study-groups.discussions.create');
            Route::post('/discussions', [DiscussionController::class, 'store'])->name('study-groups.discussions.store');
            Route::get('/discussions/{discussionId}', [DiscussionController::class, 'show'])->name('study-groups.discussions.show');
            Route::post('/discussions/{discussionId}/pin', [DiscussionController::class, 'pin'])->name('study-groups.discussions.pin');
            Route::delete('/discussions/{discussionId}', [DiscussionController::class, 'destroy'])->name('study-groups.discussions.destroy');
            
            // Discussion Replies
            Route::post('/discussions/{discussionId}/replies', [DiscussionReplyController::class, 'store'])->name('study-groups.discussions.replies.store');
            Route::delete('/discussions/{discussionId}/replies/{replyId}', [DiscussionReplyController::class, 'destroy'])->name('study-groups.discussions.replies.destroy');
            
            // Resources Routes
            Route::get('/resources', [ResourceController::class, 'index'])->name('study-groups.resources.index');
            Route::get('/resources/create', [ResourceController::class, 'create'])->name('study-groups.resources.create');
            Route::post('/resources', [ResourceController::class, 'store'])->name('study-groups.resources.store');
            Route::get('/resources/{resourceId}/download', [ResourceController::class, 'download'])->name('study-groups.resources.download');
            Route::delete('/resources/{resourceId}', [ResourceController::class, 'destroy'])->name('study-groups.resources.destroy');
        });

        // Add this route inside your study-groups prefix group
Route::delete('/invitations/{invitationId}', [StudyGroupInvitationController::class, 'cancel'])->name('study-groups.invitation.cancel');
    });

    // User Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', function () {
            return view('profile.index');
        })->name('profile.index');
        
        Route::get('/edit', function () {
            return view('profile.edit');
        })->name('profile.edit');
        
        Route::put('/update', function (Request $request) {
            // Profile update logic - you should create a controller for this
            return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
        })->name('profile.update');
    });
    
    // Settings Routes
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings.index');
});

// Fallback route for undefined routes
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::resource('schedule', ScheduleController::class);
Route::get('/schedule/calendar', [ScheduleController::class, 'calendar'])->name('schedule.calendar');
Route::get('/schedule/api', [ScheduleController::class, 'apiIndex'])->name('schedule.api');

// Settings routes
Route::prefix('settings')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    Route::post('/privacy', [SettingsController::class, 'updatePrivacy'])->name('settings.privacy.update');
    Route::post('/logout', [SettingsController::class, 'logout'])->name('logout');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/study-session/start', [StudySessionController::class, 'start'])->name('study-session.start');

// Add to web.php
Route::get('/test-file-processing', function () {
    $aiService = new App\Services\AIService();
    
    // Test with a simple text file
    $testContent = "This is a test content about programming. PHP is a server-side scripting language. JavaScript is used for client-side scripting.";
    
    Storage::disk('public')->put('test.txt', $testContent);
    
    try {
        $flashcards = $aiService->generateFlashcardsFromFile('test.txt', 'txt', 3);
        dd($flashcards);
    } catch (\Exception $e) {
        dd('Error: ' . $e->getMessage());
    }
});

// Add to web.php for testing
Route::post('/test-upload', function(Request $request) {
    \Log::info('Test upload received');
    \Log::info('Files:', $request->file() ?: ['no files']);
    
    try {
        $request->validate([
            'file' => 'required|file|mimes:txt,pdf,docx,md|max:10240'
        ]);
        
        $file = $request->file('file');
        $path = $file->store('test_uploads', 'public');
        
        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize()
        ]);
    } catch (\Exception $e) {
        \Log::error('Test upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

// routes/web.php
Route::get('/phpinfo', function() {
    phpinfo();
});

// Add to web.php
Route::get('/upload-test', function() {
    return response()->json([
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'max_file_uploads' => ini_get('max_file_uploads'),
        'memory_limit' => ini_get('memory_limit')
    ]);
});


require __DIR__.'/auth.php';
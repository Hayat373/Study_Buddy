<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FlashcardSet;
use App\Models\StudySession;
use App\Models\UserProgress;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Debug: check if user is authenticated
        if (!Auth::check()) {
            abort(403, 'User not authenticated');
        }
        
        $user = Auth::user();
        
        // Debug: check what user data we have
        \Log::info('Dashboard accessed by user: ' . $user->id . ' - ' . $user->email);
        
        // Get real data for dashboard
        $flashcardSetsCount = FlashcardSet::where('user_id', $user->id)->count();
        
        $studySessions = StudySession::where('user_id', $user->id)
            ->where('scheduled_at', '>=', Carbon::today())
            ->orderBy('scheduled_at', 'asc')
            ->take(3)
            ->get();
            
        $recentActivities = UserProgress::with('flashcardSet')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $totalStudyTime = UserProgress::where('user_id', $user->id)
            ->sum('study_time');
            
        $masteryLevel = UserProgress::where('user_id', $user->id)
            ->avg('mastery_level') ?? 0;

        return view('dashboard', compact(
            'user', 
            'flashcardSetsCount', 
            'studySessions', 
            'recentActivities',
            'totalStudyTime',
            'masteryLevel'
        ));
    }
    
    public function getStats(Request $request)
    {
        $user = Auth::user();
        
        // Real-time stats for AJAX requests
        $stats = [
            'flashcardSets' => FlashcardSet::where('user_id', $user->id)->count(),
            'studyTimeToday' => UserProgress::where('user_id', $user->id)
                ->whereDate('created_at', Carbon::today())
                ->sum('study_time'),
            'quizzesCompleted' => UserProgress::where('user_id', $user->id)
                ->where('type', 'quiz')
                ->count(),
            'masteryLevel' => UserProgress::where('user_id', $user->id)
                ->avg('mastery_level') ?? 0,
        ];
        
        return response()->json($stats);
    }
}
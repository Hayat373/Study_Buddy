<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudySessionController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id'
        ]);
        
        $schedule = Schedule::where('id', $request->schedule_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        // Check if it's a study session
        if ($schedule->type !== 'study_session') {
            return response()->json([
                'success' => false,
                'message' => 'This schedule is not a study session'
            ]);
        }
        
        // Here you would typically:
        // 1. Create a new study session record
        // 2. Redirect to the study interface
        // 3. Start tracking time, etc.
        
        // For now, we'll just redirect to flashcards
        return response()->json([
            'success' => true,
            'redirect_url' => route('flashcards.index')
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::where('user_id', Auth::id())
            ->orderBy('start_time', 'asc')
            ->get();
            
        return view('schedule.index', compact('schedules'));
    }

    public function create()
    {
        return view('schedule.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'type' => 'required|in:study_session,exam,reminder,group_study',
            'color' => 'nullable|string',
            'recurring' => 'boolean',
            'recurring_pattern' => 'nullable|required_if:recurring,true|in:daily,weekly,monthly',
            'recurring_until' => 'nullable|required_if:recurring,true|date|after:start_time'
        ]);

        Schedule::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'type' => $request->type,
            'color' => $request->color ?? $this->getDefaultColor($request->type),
            'recurring' => $request->recurring ?? false,
            'recurring_pattern' => $request->recurring_pattern,
            'recurring_until' => $request->recurring_until
        ]);

        return redirect()->route('schedule.index')
            ->with('success', 'Schedule created successfully.');
    }

    public function show(Schedule $schedule)
    {
        $this->authorize('view', $schedule);
        return view('schedule.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $this->authorize('update', $schedule);
        return view('schedule.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'type' => 'required|in:study_session,exam,reminder,group_study',
            'color' => 'nullable|string',
            'recurring' => 'boolean',
            'recurring_pattern' => 'nullable|required_if:recurring,true|in:daily,weekly,monthly',
            'recurring_until' => 'nullable|required_if:recurring,true|date|after:start_time'
        ]);

        $schedule->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'type' => $request->type,
            'color' => $request->color ?? $this->getDefaultColor($request->type),
            'recurring' => $request->recurring ?? false,
            'recurring_pattern' => $request->recurring_pattern,
            'recurring_until' => $request->recurring_until
        ]);

        return redirect()->route('schedule.index')
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);
        $schedule->delete();

        return redirect()->route('schedule.index')
            ->with('success', 'Schedule deleted successfully.');
    }
    
    public function calendar()
    {
        $schedules = Schedule::where('user_id', Auth::id())->get();
        return view('schedule.calendar', compact('schedules'));
    }
    
    public function apiIndex()
    {
        $schedules = Schedule::where('user_id', Auth::id())->get();
        
        return response()->json($schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'title' => $schedule->title,
                'start' => $schedule->start_time,
                'end' => $schedule->end_time,
                'color' => $schedule->color,
                'description' => $schedule->description,
                'type' => $schedule->type
            ];
        }));
    }

    private function getDefaultColor($type)
    {
        $colors = [
            'study_session' => '#2dc2ff',
            'exam' => '#ff6b6b',
            'reminder' => '#78f7d1',
            'group_study' => '#ffa726'
        ];
        
        return $colors[$type] ?? '#2dc2ff';
    }
}
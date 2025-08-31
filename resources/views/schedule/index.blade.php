@extends('layouts.app')

@section('title', 'Schedule - Study Buddy')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<style>
    .calendar-container {
        background: rgba(20, 40, 60, 0.5);
        border-radius: 20px;
        padding: 25px;
        border: 1px solid rgba(57, 183, 255, 0.1);
        backdrop-filter: blur(10px);
        margin-bottom: 30px;
    }
    
    .fc {
        color: #dffbff;
    }
    
    .fc-toolbar-title {
        color: #dffbff;
        font-weight: 600;
    }
    
    .fc-button {
        background: rgba(57, 183, 255, 0.2) !important;
        border: 1px solid rgba(57, 183, 255, 0.3) !important;
        color: #dffbff !important;
    }
    
    .fc-button:hover {
        background: rgba(57, 183, 255, 0.3) !important;
    }
    
    .fc-button-active {
        background: rgba(57, 183, 255, 0.4) !important;
    }
    
    .fc-daygrid-day-number, .fc-col-header-cell-cushion {
        color: #dffbff;
    }
    
    .fc-event {
        border: none;
        border-radius: 8px;
        padding: 3px 6px;
        font-weight: 500;
    }
    
    .schedule-actions {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .schedule-list {
        background: rgba(20, 40, 60, 0.5);
        border-radius: 20px;
        padding: 25px;
        border: 1px solid rgba(57, 183, 255, 0.1);
        backdrop-filter: blur(10px);
    }
    
    .schedule-item {
        display: flex;
        align-items: center;
        padding: 15px;
        margin-bottom: 15px;
        background: rgba(15, 30, 45, 0.3);
        border-radius: 12px;
        border-left: 4px solid;
    }
    
    .schedule-item:last-child {
        margin-bottom: 0;
    }
    
    .schedule-color {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        margin-right: 15px;
    }
    
    .schedule-content {
        flex: 1;
    }
    
    .schedule-title {
        font-weight: 600;
        color: #dffbff;
        margin-bottom: 5px;
    }
    
    .schedule-time {
        color: #a4d8e8;
        font-size: 14px;
        margin-bottom: 5px;
    }
    
    .schedule-type {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        background: rgba(57, 183, 255, 0.2);
        color: #2dc2ff;
    }
    
    .schedule-actions-btn {
        display: flex;
        gap: 10px;
    }
    
    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(57, 183, 255, 0.1);
        border: 1px solid rgba(57, 183, 255, 0.2);
        color: #2dc2ff;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-icon:hover {
        background: rgba(57, 183, 255, 0.2);
        transform: translateY(-2px);
    }
    
    .btn-icon.delete {
        background: rgba(255, 107, 107, 0.1);
        border: 1px solid rgba(255, 107, 107, 0.2);
        color: #ff6b6b;
    }
    
    .btn-icon.delete:hover {
        background: rgba(255, 107, 107, 0.2);
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #a4d8e8;
    }
    
    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.7;
    }
    
    .empty-state p {
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>My Schedule</h1>
        <p>Plan your study sessions, exams, and reminders</p>
    </div>
    
    <div class="schedule-actions">
        <a href="{{ route('schedule.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Schedule
        </a>
        <a href="{{ route('schedule.calendar') }}" class="btn btn-outline">
            <i class="fas fa-calendar-alt"></i> Calendar View
        </a>
    </div>
    
    <div class="calendar-container">
        <div id="calendar"></div>
    </div>
    
    <div class="schedule-list">
        <h2 style="color: #dffbff; margin-bottom: 20px;">Upcoming Events</h2>
        
        @if($schedules->count() > 0)
            @foreach($schedules as $schedule)
                <div class="schedule-item" style="border-left-color: {{ $schedule->color }};">
                    <div class="schedule-color" style="background: {{ $schedule->color }};"></div>
                    <div class="schedule-content">
                        <h3 class="schedule-title">{{ $schedule->title }}</h3>
                        <div class="schedule-time">
                            <i class="fas fa-clock"></i> 
                            {{ $schedule->start_time->format('M j, Y g:i A') }} - 
                            {{ $schedule->end_time->format('g:i A') }}
                        </div>
                        <span class="schedule-type">{{ str_replace('_', ' ', $schedule->type) }}</span>
                    </div>
                    <div class="schedule-actions-btn">
                        <a href="{{ route('schedule.edit', $schedule->id) }}" class="btn-icon">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('schedule.destroy', $schedule->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon delete" onclick="return confirm('Are you sure you want to delete this schedule?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-plus"></i>
                <p>You don't have any schedules yet. Create your first schedule to get started!</p>
                <a href="{{ route('schedule.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Schedule
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: '{{ route("schedule.api") }}',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            eventClick: function(info) {
                // You can implement event click functionality here
                console.log('Event: ' + info.event.title);
            }
        });
        calendar.render();
    });
</script>
@endsection
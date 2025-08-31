@extends('layouts.app')

@section('title', 'Calendar View - Study Buddy')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<style>
    .calendar-full {
        background: rgba(20, 40, 60, 0.5);
        border-radius: 20px;
        padding: 25px;
        border: 1px solid rgba(57, 183, 255, 0.1);
        backdrop-filter: blur(10px);
        margin-bottom: 30px;
        height: 70vh;
    }
    
    .fc {
        color: #dffbff;
        height: 100%;
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
        cursor: pointer;
    }
    
    .calendar-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .view-options {
        display: flex;
        gap: 10px;
    }
    
    .view-option {
        padding: 8px 16px;
        background: rgba(57, 183, 255, 0.1);
        border: 1px solid rgba(57, 183, 255, 0.2);
        border-radius: 8px;
        color: #dffbff;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .view-option.active {
        background: rgba(57, 183, 255, 0.3);
    }
    
    .view-option:hover {
        background: rgba(57, 183, 255, 0.2);
    }
    
    .event-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(10, 25, 41, 0.8);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .event-modal.show {
        opacity: 1;
        visibility: visible;
    }
    
    .modal-content {
        background: rgba(20, 40, 60, 0.95);
        border-radius: 20px;
        padding: 30px;
        border: 1px solid rgba(57, 183, 255, 0.2);
        width: 90%;
        max-width: 500px;
        backdrop-filter: blur(10px);
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    
    .event-modal.show .modal-content {
        transform: translateY(0);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    }
    
    .modal-title {
        color: #dffbff;
        font-weight: 600;
        font-size: 1.5rem;
        margin: 0;
    }
    
    .close-modal {
        background: none;
        border: none;
        color: #a4d8e8;
        font-size: 1.5rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    
    .close-modal:hover {
        color: #ff6b6b;
    }
    
    .event-details {
        margin-bottom: 25px;
    }
    
    .event-detail {
        display: flex;
        margin-bottom: 12px;
    }
    
    .event-icon {
        width: 24px;
        color: #2dc2ff;
        margin-right: 12px;
    }
    
    .event-text {
        color: #dffbff;
        flex: 1;
    }
    
    .event-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="calendar-actions">
        <h1>Calendar View</h1>
        <div class="view-options">
            <a href="{{ route('schedule.index') }}" class="btn btn-outline">
                <i class="fas fa-list"></i> List View
            </a>
            <a href="{{ route('schedule.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Event
            </a>
        </div>
    </div>
    
    <div class="calendar-full">
        <div id="calendar"></div>
    </div>
</div>

<div class="event-modal" id="eventModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="eventTitle"></h2>
            <button class="close-modal" id="closeModal">&times;</button>
        </div>
        <div class="event-details">
            <div class="event-detail">
                <div class="event-icon"><i class="fas fa-align-left"></i></div>
                <div class="event-text" id="eventDescription"></div>
            </div>
            <div class="event-detail">
                <div class="event-icon"><i class="fas fa-clock"></i></div>
                <div class="event-text" id="eventTime"></div>
            </div>
            <div class="event-detail">
                <div class="event-icon"><i class="fas fa-tag"></i></div>
                <div class="event-text" id="eventType"></div>
            </div>
        </div>
        <div class="event-actions">
            <button class="btn btn-outline" id="closeBtn">Close</button>
            <a href="#" class="btn btn-primary" id="editBtn">Edit</a>
        </div>
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
                const event = info.event;
                const modal = document.getElementById('eventModal');
                const start = event.start.toLocaleString();
                const end = event.end ? event.end.toLocaleString() : 'No end time';
                
                document.getElementById('eventTitle').textContent = event.title;
                document.getElementById('eventDescription').textContent = event.extendedProps.description || 'No description';
                document.getElementById('eventTime').textContent = `${start} to ${end}`;
                document.getElementById('eventType').textContent = event.extendedProps.type.replace('_', ' ');
                document.getElementById('editBtn').href = `/schedule/${event.id}/edit`;
                
                modal.classList.add('show');
            }
        });
        calendar.render();
        
        // Close modal
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('eventModal').classList.remove('show');
        });
        
        document.getElementById('closeBtn').addEventListener('click', function() {
            document.getElementById('eventModal').classList.remove('show');
        });
    });
</script>
@endsection
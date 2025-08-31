@extends('layouts.app')

@section('title', 'Create Schedule - Study Buddy')

@section('styles')
<style>
    .form-container {
        background: rgba(20, 40, 60, 0.5);
        border-radius: 20px;
        padding: 30px;
        border: 1px solid rgba(57, 183, 255, 0.1);
        backdrop-filter: blur(10px);
        margin-bottom: 30px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #dffbff;
        font-weight: 500;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        background: rgba(15, 30, 45, 0.3);
        border: 1px solid rgba(57, 183, 255, 0.2);
        border-radius: 12px;
        color: #dffbff;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #2dc2ff;
        box-shadow: 0 0 0 3px rgba(45, 194, 255, 0.2);
    }
    
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23a4d8e8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    
    .color-options {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    
    .color-option {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .color-option.active {
        border-color: #fff;
        transform: scale(1.1);
    }
    
    .recurring-options {
        margin-top: 15px;
        padding: 15px;
        background: rgba(15, 30, 45, 0.2);
        border-radius: 12px;
        border: 1px solid rgba(57, 183, 255, 0.1);
        display: none;
    }
    
    .recurring-options.show {
        display: block;
    }
    
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Create New Schedule</h1>
        <p>Add a new study session, exam, or reminder to your calendar</p>
    </div>
    
    <div class="form-container">
        <form action="{{ route('schedule.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="type">Type</label>
                <select class="form-control form-select" id="type" name="type" required>
                    <option value="study_session">Study Session</option>
                    <option value="exam">Exam</option>
                    <option value="reminder">Reminder</option>
                    <option value="group_study">Group Study</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Color</label>
                <input type="hidden" id="color" name="color" value="#2dc2ff">
                <div class="color-options">
                    <div class="color-option active" style="background-color: #2dc2ff;" data-color="#2dc2ff"></div>
                    <div class="color-option" style="background-color: #78f7d1;" data-color="#78f7d1"></div>
                    <div class="color-option" style="background-color: #ff6b6b;" data-color="#ff6b6b"></div>
                    <div class="color-option" style="background-color: #ffa726;" data-color="#ffa726"></div>
                    <div class="color-option" style="background-color: #ab47bc;" data-color="#ab47bc"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="start_time">Start Time</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="end_time">End Time</label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" id="recurring" name="recurring" value="1"> Recurring Event
                </label>
                
                <div class="recurring-options" id="recurringOptions">
                    <div class="form-group">
                        <label class="form-label" for="recurring_pattern">Recurring Pattern</label>
                        <select class="form-control form-select" id="recurring_pattern" name="recurring_pattern">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="recurring_until">Repeat Until</label>
                        <input type="date" class="form-control" id="recurring_until" name="recurring_until">
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Schedule</button>
                <a href="{{ route('schedule.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Color selection
        const colorOptions = document.querySelectorAll('.color-option');
        const colorInput = document.getElementById('color');
        
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                colorOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                colorInput.value = this.getAttribute('data-color');
            });
        });
        
        // Recurring event toggle
        const recurringCheckbox = document.getElementById('recurring');
        const recurringOptions = document.getElementById('recurringOptions');
        const recurringPattern = document.getElementById('recurring_pattern');
        const recurringUntil = document.getElementById('recurring_until');
        
        recurringCheckbox.addEventListener('change', function() {
            if (this.checked) {
                recurringOptions.classList.add('show');
                recurringPattern.setAttribute('required', 'required');
                recurringUntil.setAttribute('required', 'required');
            } else {
                recurringOptions.classList.remove('show');
                recurringPattern.removeAttribute('required');
                recurringUntil.removeAttribute('required');
            }
        });
        
        // Set default datetime values
        const now = new Date();
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');
        
        // Format to YYYY-MM-DDTHH:MM
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        };
        
        // Set start time to next hour
        const nextHour = new Date(now);
        nextHour.setHours(nextHour.getHours() + 1, 0, 0, 0);
        startTime.value = formatDate(nextHour);
        
        // Set end time to 1 hour after start
        const endHour = new Date(nextHour);
        endHour.setHours(endHour.getHours() + 1);
        endTime.value = formatDate(endHour);
        
        // Update end time when start time changes
        startTime.addEventListener('change', function() {
            const startDate = new Date(this.value);
            const newEndDate = new Date(startDate);
            newEndDate.setHours(newEndDate.getHours() + 1);
            
            endTime.value = formatDate(newEndDate);
        });
    });
</script>
@endsection
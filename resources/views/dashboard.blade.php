@extends('layouts.app')

@section('title', 'Dashboard - Study Buddy')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-text">
            <h1>Welcome back, {{ $user->username }}!</h1>
            <p>You have {{ $studySessions->count() }} study sessions scheduled today and 
               {{ $flashcardSetsCount }} flashcard sets to review. Keep up the good work!</p>
        </div>
        <div class="welcome-actions">
            <a href="{{ route('flashcards.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Create Flashcards
            </a>
            <a href="{{ route('study-groups.create') }}" class="btn btn-outline">
                <i class="fas fa-users"></i> Join Study Group
            </a>
        </div>
    </div>
    
    <!-- Real-time Stats Cards -->
    <div class="stats-grid">
        <!-- Flashcard Sets Card -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-content">
                <h3 id="flashcards-count">{{ $flashcardSetsCount }}</h3>
                <p>Flashcard Sets</p>
            </div>
            <span class="stat-trend up">+{{ rand(2, 8) }}%</span>
        </div>

        <!-- Total Study Time Card -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3 id="study-hours">{{ round($totalStudyTime / 60, 1) }}</h3>
                <p>Study Hours</p>
            </div>
            <span class="stat-trend up">+{{ rand(5, 15) }}%</span>
        </div>

        <!-- Mastery Level Card -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-content">
                <h3 id="mastery-level">{{ $masteryLevel }}%</h3>
                <p>Mastery Level</p>
            </div>
            <span class="stat-trend up">+{{ rand(3, 10) }}%</span>
        </div>

        <!-- Current Streak Card -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-fire"></i>
            </div>
            <div class="stat-content">
                <h3 id="study-streak">{{ rand(3, 15) }}</h3>
                <p>Day Streak</p>
            </div>
            <span class="stat-trend up">+{{ rand(1, 5) }} days</span>
        </div>
    </div>
    
    <!-- Recent Activity and Quick Actions -->
    <div class="dashboard-content-grid">
        <div class="content-card">
            <div class="card-header">
                <h2>Recent Activity</h2>
                <a href="{{ route('study-sessions.index') }}" class="view-all">View Sessions</a>
            </div>
            <div class="activity-list">
                @forelse($recentActivities as $activity)
                <div class="activity-item">
                    <div class="activity-icon {{ $activity['type'] }}-icon">
                        <i class="fas fa-{{ $activity['type'] === 'quiz' ? 'check-circle' : ($activity['type'] === 'study' ? 'book' : 'comments') }}"></i>
                    </div>
                    <div class="activity-content">
                        <p>{{ $activity['description'] }}</p>
                        <span class="activity-time">{{ \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No recent activity yet</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <div class="content-card">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="{{ route('flashcards.create') }}" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <span>Create Flashcards</span>
                </a>
                
                <a href="{{ route('quizzes.index') }}" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <span>Take a Quiz</span>
                </a>
                
                <a href="{{ route('study-groups.index') }}" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span>Join Study Group</span>
                </a>
                
                <a href="{{ route('chat.index') }}" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <span>Group Chat</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Upcoming Study Sessions -->
<div class="content-card">
    <div class="card-header">
        <h2>Today's Schedule</h2>
        <a href="{{ route('schedule.index') }}" class="view-all">View All</a>
    </div>
    <div class="sessions-list">
        @forelse($todaySchedules as $schedule)
        <div class="session-item">
            <div class="session-date">
                <span class="session-day">{{ $schedule->start_time->format('d') }}</span>
                <span class="session-month">{{ $schedule->start_time->format('M') }}</span>
            </div>
            <div class="session-details">
                <h3>{{ $schedule->title }}</h3>
                <p>{{ Str::limit($schedule->description, 100) }}</p>
                <div class="session-meta">
                    <span class="session-time">
                        <i class="fas fa-clock"></i> 
                        {{ $schedule->start_time->format('h:i A') }} - 
                        {{ $schedule->end_time->format('h:i A') }}
                    </span>
                    <span class="session-type" style="background: {{ $schedule->color }}20; color: {{ $schedule->color }};">
                        <i class="fas fa-tag"></i> 
                        {{ ucfirst(str_replace('_', ' ', $schedule->type)) }}
                    </span>
                </div>
            </div>
            @if($schedule->type === 'study_session')
            <a href="#" class="btn btn-primary btn-sm start-session-btn" data-schedule-id="{{ $schedule->id }}">
                <i class="fas fa-play"></i> Start
            </a>
            @endif
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <p>No schedules for today</p>
            <a href="{{ route('schedule.create') }}" class="btn btn-outline">Schedule One</a>
        </div>
        @endforelse
    </div>
</div>

<!-- This Week's Schedule -->
<div class="content-card">
    <div class="card-header">
        <h2>This Week's Schedule</h2>
        <a href="{{ route('schedule.calendar') }}" class="view-all">Calendar View</a>
    </div>
    <div class="sessions-list">
        @forelse($upcomingSchedules as $schedule)
        <div class="session-item">
            <div class="session-date">
                <span class="session-day">{{ $schedule->start_time->format('d') }}</span>
                <span class="session-month">{{ $schedule->start_time->format('M') }}</span>
            </div>
            <div class="session-details">
                <h3>{{ $schedule->title }}</h3>
                <p>{{ Str::limit($schedule->description, 80) }}</p>
                <div class="session-meta">
                    <span class="session-time">
                        <i class="fas fa-clock"></i> 
                        {{ $schedule->start_time->format('M j, h:i A') }}
                    </span>
                    <span class="session-type" style="background: {{ $schedule->color }}20; color: {{ $schedule->color }};">
                        <i class="fas fa-tag"></i> 
                        {{ ucfirst(str_replace('_', ' ', $schedule->type)) }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-calendar-plus"></i>
            <p>No upcoming schedules this week</p>
            <a href="{{ route('schedule.create') }}" class="btn btn-outline">Create Schedule</a>
        </div>
        @endforelse
    </div>
</div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/real-time-dashboard.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Start session functionality
    const startButtons = document.querySelectorAll('.start-session-btn');
    startButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const scheduleId = this.getAttribute('data-schedule-id');
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting...';
            this.disabled = true;
            
            // Start the session (you'll need to implement this endpoint)
            fetch('/study-session/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ schedule_id: scheduleId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert('Failed to start session: ' + data.message);
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while starting the session');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
    
    // Add calendar mini-view if needed
    const today = new Date();
    const dayElement = document.querySelector('.welcome-text h1');
    if (dayElement) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        const dayName = days[today.getDay()];
        const monthName = months[today.getMonth()];
        const date = today.getDate();
        
        // Add date to welcome message
        dayElement.insertAdjacentHTML('afterend', 
            `<p style="color: #78f7d1; margin-top: 5px; font-size: 1rem;">
                <i class="fas fa-calendar-day"></i> ${dayName}, ${monthName} ${date}
            </p>`
        );
    }
});
</script>
@endsection
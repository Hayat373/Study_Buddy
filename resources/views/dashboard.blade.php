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
    <div class="stats-grid" id="statsGrid">
        @include('partials.real-time-stats', [
            'flashcardSetsCount' => $flashcardSetsCount,
            'totalStudyTime' => $totalStudyTime,
            'masteryLevel' => $masteryLevel
        ])
    </div>
    
    <!-- Recent Activity and Quick Actions -->
    <div class="dashboard-content-grid">
        <div class="content-card">
            <div class="card-header">
                <h2>Recent Activity</h2>
                <a href="#" class="view-all">View All</a>
            </div>
            <div class="activity-list">
                @forelse($recentActivities as $activity)
                <div class="activity-item">
                    <div class="activity-icon {{ $activity->type }}-icon">
                        <i class="fas fa-{{ $activity->type === 'quiz' ? 'check-circle' : 'book' }}"></i>
                    </div>
                    <div class="activity-content">
                        <p>{{ $activity->description }}</p>
                        <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
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
            <h2>Upcoming Study Sessions</h2>
            <a href="#" class="view-all">View All</a>
        </div>
        <div class="sessions-list">
            @forelse($studySessions as $session)
            <div class="session-item">
                <div class="session-date">
                    <span class="session-day">{{ $session->scheduled_at->format('d') }}</span>
                    <span class="session-month">{{ $session->scheduled_at->format('M') }}</span>
                </div>
                <div class="session-details">
                    <h3>{{ $session->title }}</h3>
                    <p>{{ $session->description }}</p>
                    <div class="session-meta">
                        <span class="session-time">
                            <i class="fas fa-clock"></i> 
                            {{ $session->scheduled_at->format('h:i A') }}
                        </span>
                        <span class="session-members">
                            <i class="fas fa-users"></i> 
                            {{ $session->participants_count }} participants
                        </span>
                    </div>
                </div>
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-calendar-check"></i> Join
                </button>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>No upcoming study sessions</p>
                <a href="{{ route('study-groups.create') }}" class="btn btn-outline">Schedule One</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/real-time-dashboard.js') }}"></script>
@endsection
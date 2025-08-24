@extends('layout')

@section('title', 'Dashboard - Study Buddy')

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome back, {{ $user->username }}!</h1>
            <p>Ready to continue your learning journey?</p>
        </div>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <h3>5</h3>
                    <p>Flashcard Sets</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>12</h3>
                    <p>Quizzes Completed</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>8h 42m</h3>
                    <p>Study Time</p>
                </div>
            </div>
        </div>
        
        <div class="dashboard-sections">
            <div class="dashboard-section">
                <h2>Recent Activity</h2>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="activity-content">
                            <p>Studied <strong>Spanish Vocabulary</strong> flashcards</p>
                            <span class="activity-time">2 hours ago</span>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="activity-content">
                            <p>Completed <strong>JavaScript Basics</strong> quiz</p>
                            <span class="activity-time">1 day ago</span>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="activity-content">
                            <p>Joined <strong>Web Development</strong> study group</p>
                            <span class="activity-time">3 days ago</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-section">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="{{ route('flashcards.index') }}" class="action-btn">
                        <i class="fas fa-layer-group"></i>
                        <span>Study Flashcards</span>
                    </a>
                    
                    <a href="{{ route('quizzes.index') }}" class="action-btn">
                        <i class="fas fa-question-circle"></i>
                        <span>Take a Quiz</span>
                    </a>
                    
                    <a href="{{ route('groups.index') }}" class="action-btn">
                        <i class="fas fa-users"></i>
                        <span>Join a Study Group</span>
                    </a>
                    
                    <a href="{{ route('chats.index') }}" class="action-btn">
                        <i class="fas fa-comments"></i>
                        <span>Group Chat</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
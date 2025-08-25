@extends('layouts.app')

@section('title', 'Dashboard - Study Buddy')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-text">
            <h1>Welcome back, {{ $user->username }}!</h1>
            <p>You have 3 study sessions scheduled today and 12 flashcards to review. Keep up the good work!</p>
        </div>
        <div class="welcome-actions">
            <button class="btn btn-primary">
                <i class="fas fa-play-circle"></i>
                Start Studying
            </button>
            <button class="btn btn-outline">
                <i class="fas fa-plus"></i>
                Create New
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-content">
                <h3>5</h3>
                <p>Flashcard Sets</p>
            </div>
            <div class="stat-trend up">
                <i class="fas fa-arrow-up"></i> 12%
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
            <div class="stat-trend up">
                <i class="fas fa-arrow-up"></i> 8%
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
            <div class="stat-trend up">
                <i class="fas fa-arrow-up"></i> 24%
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-content">
                <h3>85%</h3>
                <p>Mastery Level</p>
            </div>
            <div class="stat-trend down">
                <i class="fas fa-arrow-down"></i> 3%
            </div>
        </div>
    </div>
    
    <!-- Main Content Grid -->
    <div class="dashboard-content-grid">
        <!-- Recent Activity -->
        <div class="content-card">
            <div class="card-header">
                <h2>Recent Activity</h2>
                <a href="#" class="view-all">View All</a>
            </div>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon completed">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="activity-content">
                        <p>Studied <strong>Spanish Vocabulary</strong> flashcards</p>
                        <span class="activity-time">2 hours ago</span>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon completed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="activity-content">
                        <p>Completed <strong>JavaScript Basics</strong> quiz</p>
                        <span class="activity-time">1 day ago</span>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon joined">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="activity-content">
                        <p>Joined <strong>Web Development</strong> study group</p>
                        <span class="activity-time">3 days ago</span>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon created">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="activity-content">
                        <p>Created <strong>Chemistry Elements</strong> flashcard set</p>
                        <span class="activity-time">4 days ago</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="content-card">
            <div class="card-header">
                <h2>Quick Actions</h2>
            </div>
            <div class="action-buttons">
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <span>Study Flashcards</span>
                </a>
                
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <span>Take a Quiz</span>
                </a>
                
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span>Join a Study Group</span>
                </a>
                
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <span>Group Chat</span>
                </a>
                
               
                
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <span>Schedule Session</span>
                </a>
            </div>
        </div>
        
        <!-- Upcoming Study Sessions -->
        <div class="content-card">
            <div class="card-header">
                <h2>Upcoming Study Sessions</h2>
                <a href="#" class="view-all">View All</a>
            </div>
            <div class="sessions-list">
                <div class="session-item">
                    <div class="session-date">
                        <span class="session-day">24</span>
                        <span class="session-month">Aug</span>
                    </div>
                    <div class="session-details">
                        <h3>Physics Study Group</h3>
                        <p>Quantum Mechanics Review</p>
                        <div class="session-meta">
                            <span class="session-time">
                                <i class="fas fa-clock"></i> 3:00 PM - 4:30 PM
                            </span>
                            <span class="session-members">
                                <i class="fas fa-users"></i> 4 participants
                            </span>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-video"></i> Join
                    </button>
                </div>
                
                <div class="session-item">
                    <div class="session-date">
                        <span class="session-day">25</span>
                        <span class="session-month">Aug</span>
                    </div>
                    <div class="session-details">
                        <h3>Spanish Practice</h3>
                        <p>Conversation Practice</p>
                        <div class="session-meta">
                            <span class="session-time">
                                <i class="fas fa-clock"></i> 10:00 AM - 11:00 AM
                            </span>
                            <span class="session-members">
                                <i class="fas fa-users"></i> 2 participants
                            </span>
                        </div>
                    </div>
                    <button class="btn btn-outline btn-sm">
                        <i class="fas fa-video"></i> Join
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Study Progress -->
        <div class="content-card">
            <div class="card-header">
                <h2>Study Progress</h2>
            </div>
            <div class="progress-chart">
                <div class="progress-item">
                    <div class="progress-info">
                        <span class="progress-subject">Mathematics</span>
                        <span class="progress-percent">78%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 78%"></div>
                    </div>
                </div>
                
                <div class="progress-item">
                    <div class="progress-info">
                        <span class="progress-subject">Physics</span>
                        <span class="progress-percent">65%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 65%"></div>
                    </div>
                </div>
                
                <div class="progress-item">
                    <div class="progress-info">
                        <span class="progress-subject">Chemistry</span>
                        <span class="progress-percent">92%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 92%"></div>
                    </div>
                </div>
                
                <div class="progress-item">
                    <div class="progress-info">
                        <span class="progress-subject">Biology</span>
                        <span class="progress-percent">55%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 55%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
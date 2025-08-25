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
            <button class="btn btn-primary">Start Studying</button>
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
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-content">
                <h3>85%</h3>
                <p>Mastery Level</p>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity and Quick Actions -->
    <div class="dashboard-content-grid">
        <div class="content-card">
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
        
        <div class="content-card">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="#" class="action-btn">
                    <i class="fas fa-layer-group"></i>
                    <span>Study Flashcards</span>
                </a>
                
                <a href="#" class="action-btn">
                    <i class="fas fa-question-circle"></i>
                    <span>Take a Quiz</span>
                </a>
                
                <a href="#" class="action-btn">
                    <i class="fas fa-users"></i>
                    <span>Join a Study Group</span>
                </a>
                
                <a href="#" class="action-btn">
                    <i class="fas fa-comments"></i>
                    <span>Group Chat</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Upcoming Study Sessions -->
    <div class="content-card">
        <h2>Upcoming Study Sessions</h2>
        <div class="sessions-list">
            <div class="session-item">
                <div class="session-date">
                    <span class="session-day">24</span>
                    <span class="session-month">Aug</span>
                </div>
                <div class="session-details">
                    <h3>Physics Study Group</h3>
                    <p>Quantum Mechanics Review</p>
                    <span class="session-time">3:00 PM - 4:30 PM</span>
                </div>
                <button class="btn btn-outline">Join Session</button>
            </div>
            
            <div class="session-item">
                <div class="session-date">
                    <span class="session-day">25</span>
                    <span class="session-month">Aug</span>
                </div>
                <div class="session-details">
                    <h3>Spanish Practice</h3>
                    <p>Conversation Practice</p>
                    <span class="session-time">10:00 AM - 11:00 AM</span>
                </div>
                <button class="btn btn-outline">Join Session</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Dashboard Styles */
    .dashboard-container {
        max-width: 100%;
        padding: 0;
    }
    
    .welcome-banner {
        background: linear-gradient(105deg, rgba(45, 194, 255, 0.15), rgba(120, 247, 209, 0.1));
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid rgba(57, 183, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .welcome-text h1 {
        font-size: 2.2rem;
        margin-bottom: 10px;
        background: linear-gradient(135deg, #dffbff 0%, #78f7d1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .welcome-text p {
        color: #a4d8e8;
        max-width: 600px;
        margin: 0;
    }
    
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-primary {
        background: linear-gradient(90deg, #2dc2ff 0%, #78f7d1 100%);
        color: #0a1929;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(45, 194, 255, 0.3);
    }
    
    .btn-outline {
        background: transparent;
        border: 1px solid rgba(57, 183, 255, 0.3);
        color: #2dc2ff;
    }
    
    .btn-outline:hover {
        background: rgba(57, 183, 255, 0.1);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: rgba(20, 40, 60, 0.5);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid rgba(57, 183, 255, 0.1);
        display: flex;
        align-items: center;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2dc2ff 0%, #78f7d1 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.2rem;
    }
    
    .stat-content h3 {
        font-size: 1.8rem;
        margin-bottom: 5px;
        color: #dffbff;
    }
    
    .stat-content p {
        color: #a4d8e8;
        margin: 0;
    }
    
    .dashboard-content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .content-card {
        background: rgba(20, 40, 60, 0.5);
        border-radius: 16px;
        padding: 25px;
        border: 1px solid rgba(57, 183, 255, 0.1);
    }
    
    .content-card h2 {
        margin-bottom: 20px;
        color: #dffbff;
        font-size: 1.5rem;
    }
    
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(57, 183, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #2dc2ff;
    }
    
    .activity-content p {
        margin: 0;
        color: #dffbff;
    }
    
    .activity-time {
        font-size: 0.8rem;
        color: #a4d8e8;
    }
    
    .action-buttons {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        padding: 15px;
        background: rgba(57, 183, 255, 0.1);
        border: 1px solid rgba(57, 183, 255, 0.2);
        border-radius: 8px;
        color: #dffbff;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        background: rgba(57, 183, 255, 0.2);
        transform: translateY(-2px);
    }
    
    .action-btn i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    
    .sessions-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .session-item {
        display: flex;
        align-items: center;
        padding: 15px;
        background: rgba(15, 30, 45, 0.3);
        border-radius: 12px;
        border: 1px solid rgba(57, 183, 255, 0.1);
    }
    
    .session-date {
        text-align: center;
        margin-right: 15px;
        min-width: 60px;
    }
    
    .session-day {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: #2dc2ff;
    }
    
    .session-month {
        display: block;
        font-size: 0.8rem;
        color: #a4d8e8;
        text-transform: uppercase;
    }
    
    .session-details {
        flex: 1;
    }
    
    .session-details h3 {
        margin: 0 0 5px 0;
        color: #dffbff;
        font-size: 1.1rem;
    }
    
    .session-details p {
        margin: 0 0 5px 0;
        color: #a4d8e8;
    }
    
    .session-time {
        font-size: 0.8rem;
        color: #78f7d1;
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .dashboard-content-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .welcome-banner {
            flex-direction: column;
            text-align: center;
            gap: 20px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .session-item {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        
        .session-date {
            margin-right: 0;
        }
    }
</style>
@endsection
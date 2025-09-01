@extends('layouts.app')

@section('title', 'Quiz History')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Quiz History</h1>
        <div class="dashboard-actions">
            <a href="{{ route('quizzes.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Quizzes
            </a>
        </div>
    </div>

    <div class="history-container">
        @if($attempts->count() > 0)
        <div class="history-list">
            @foreach($attempts as $attempt)
            <div class="history-item">
                <div class="history-main">
                    <h3>{{ $attempt->quiz->title }}</h3>
                    <div class="history-meta">
                        <span class="score {{ $attempt->score / $attempt->total_questions >= 0.7 ? 'high-score' : 'low-score' }}">
                            {{ $attempt->score }}/{{ $attempt->total_questions }}
                        </span>
                        <span class="time">{{ gmdate('H:i:s', $attempt->time_taken) }}</span>
                        <span class="date">{{ $attempt->completed_at->format('M j, Y') }}</span>
                    </div>
                </div>
                <div class="history-actions">
                    <a href="{{ route('quizzes.results', $attempt->id) }}" class="btn btn-sm btn-outline">
                        <i class="fas fa-chart-bar"></i> View Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-container">
            {{ $attempts->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <h3>No Quiz History Yet</h3>
            <p>You haven't completed any quizzes yet. Start studying and take a quiz to see your results here.</p>
            <a href="{{ route('quizzes.index') }}" class="btn btn-primary">
                <i class="fas fa-play"></i> Take a Quiz
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.history-container {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 25px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
}

.history-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: rgba(15, 30, 45, 0.3);
    border-radius: 12px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    transition: transform 0.3s ease;
}

.history-item:hover {
    transform: translateY(-2px);
}

.history-main h3 {
    color: #dffbff;
    margin: 0 0 10px 0;
    font-size: 1.1rem;
}

.history-meta {
    display: flex;
    gap: 15px;
    align-items: center;
}

.history-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    color: #a4d8e8;
}

.score {
    padding: 3px 8px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 12px;
}

.high-score {
    background: rgba(120, 247, 209, 0.2);
    color: #78f7d1;
}

.low-score {
    background: rgba(255, 107, 107, 0.2);
    color: #ff6b6b;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #a4d8e8;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.7;
}

.empty-state h3 {
    color: #dffbff;
    margin-bottom: 10px;
    font-size: 1.5rem;
}

.empty-state p {
    margin-bottom: 25px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.pagination-container {
    margin-top: 25px;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .history-item {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .history-meta {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
@endsection
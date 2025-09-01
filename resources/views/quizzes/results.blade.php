@extends('layouts.app')

@section('title', 'Quiz Results')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Quiz Results</h1>
        <div class="dashboard-actions">
            <a href="{{ route('quizzes.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Quizzes
            </a>
        </div>
    </div>

    <div class="results-summary">
        <div class="score-card">
            <div class="score-circle">
                <svg viewBox="0 0 36 36" class="circular-chart">
                    <path class="circle-bg"
                        d="M18 2.0845
                          a 15.9155 15.9155 0 0 1 0 31.831
                          a 15.9155 15.9155 0 0 1 0 -31.831"
                    />
                    <path class="circle"
                        stroke-dasharray="{{ ($attempt->score / $attempt->total_questions) * 100 }}, 100"
                        d="M18 2.0845
                          a 15.9155 15.9155 0 0 1 0 31.831
                          a 15.9155 15.9155 0 0 1 0 -31.831"
                    />
                    <text x="18" y="20.35" class="percentage">{{ round(($attempt->score / $attempt->total_questions) * 100) }}%</text>
                </svg>
            </div>
            <div class="score-details">
                <h2>{{ $attempt->score }} / {{ $attempt->total_questions }} Correct</h2>
                <div class="score-meta">
                    <p><i class="fas fa-clock"></i> Time taken: {{ gmdate('H:i:s', $attempt->time_taken) }}</p>
                    <p><i class="fas fa-calendar"></i> Completed: {{ $attempt->completed_at->format('M j, Y g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="results-details">
        <h2>Question Review</h2>
        <div class="questions-review">
            @foreach($attempt->answers as $index => $answer)
            <div class="review-item {{ $answer->is_correct ? 'correct' : 'incorrect' }}">
                <div class="review-header">
                    <span class="question-number">Question #{{ $index + 1 }}</span>
                    <span class="result-badge">
                        {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
                    </span>
                </div>
                <div class="review-content">
                    <p class="question-text">{{ $answer->question->flashcard->question }}</p>
                    <div class="answer-comparison">
                        <div class="answer-row">
                            <span class="answer-label">Your answer:</span>
                            <span class="user-answer">{{ $answer->user_answer }}</span>
                        </div>
                        @if(!$answer->is_correct)
                        <div class="answer-row">
                            <span class="answer-label">Correct answer:</span>
                            <span class="correct-answer">{{ $answer->question->flashcard->answer }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.results-summary {
    margin-bottom: 30px;
}

.score-card {
    display: flex;
    align-items: center;
    gap: 30px;
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 30px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
}

.score-circle {
    width: 120px;
    height: 120px;
}

.circular-chart {
    display: block;
    width: 100%;
    height: 100%;
}

.circle-bg {
    fill: none;
    stroke: rgba(57, 183, 255, 0.1);
    stroke-width: 3.8;
}

.circle {
    fill: none;
    stroke-width: 2.8;
    stroke-linecap: round;
    animation: progress 1s ease-out forwards;
}

@keyframes progress {
    0% {
        stroke-dasharray: 0 100;
    }
}

.circle.correct {
    stroke: #78f7d1;
}

.circle.incorrect {
    stroke: #ff6b6b;
}

.percentage {
    fill: #dffbff;
    font-size: 0.5em;
    text-anchor: middle;
    font-weight: 600;
}

.score-details h2 {
    color: #dffbff;
    margin-bottom: 15px;
    font-size: 1.8rem;
}

.score-meta p {
    color: #a4d8e8;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.results-details h2 {
    color: #dffbff;
    margin-bottom: 20px;
    font-size: 1.5rem;
}

.questions-review {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.review-item {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid;
    backdrop-filter: blur(10px);
}

.review-item.correct {
    border-color: rgba(120, 247, 209, 0.2);
}

.review-item.incorrect {
    border-color: rgba(255, 107, 107, 0.2);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
}

.question-number {
    color: #dffbff;
    font-weight: 500;
}

.result-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.review-item.correct .result-badge {
    background: rgba(120, 247, 209, 0.2);
    color: #78f7d1;
}

.review-item.incorrect .result-badge {
    background: rgba(255, 107, 107, 0.2);
    color: #ff6b6b;
}

.question-text {
    color: #dffbff;
    margin-bottom: 15px;
    font-weight: 500;
}

.answer-comparison {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.answer-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.answer-label {
    color: #a4d8e8;
    font-size: 14px;
    min-width: 100px;
}

.user-answer, .correct-answer {
    color: #dffbff;
    font-size: 14px;
    flex: 1;
}

.review-item.incorrect .user-answer {
    color: #ff6b6b;
    text-decoration: line-through;
}

.correct-answer {
    color: #78f7d1;
    font-weight: 500;
}

@media (max-width: 768px) {
    .score-card {
        flex-direction: column;
        text-align: center;
    }
    
    .answer-row {
        flex-direction: column;
        gap: 5px;
    }
    
    .answer-label {
        min-width: auto;
    }
}
</style>
@endsection
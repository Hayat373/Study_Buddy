@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>{{ $quiz->title }}</h1>
        <div class="dashboard-actions">
            <a href="{{ route('quizzes.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Quizzes
            </a>
            <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-outline">
                <i class="fas fa-edit"></i> Edit
            </a>
            <!-- Replace the button with a form -->
<form action="{{ route('quizzes.start', $quiz->id) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-play"></i> Start Quiz
    </button>
</form>

        </div>
    </div>

    <div class="quiz-details">
        <div class="detail-card">
            <h3>Quiz Details</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">{{ $quiz->description }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Number of Questions</span>
                    <span class="detail-value">{{ $quiz->question_count }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Time Limit</span>
                    <span class="detail-value">{{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'No time limit' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Questions Shuffled</span>
                    <span class="detail-value">{{ $quiz->shuffle_questions ? 'Yes' : 'No' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Show Correct Answers</span>
                    <span class="detail-value">{{ $quiz->show_correct_answers ? 'Yes' : 'No' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Created</span>
                    <span class="detail-value">{{ $quiz->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <div class="questions-card">
            <h3>Questions</h3>
            <div class="questions-list">
                @foreach($quiz->questions as $index => $question)
                <div class="question-item">
                    <span class="question-number">#{{ $index + 1 }}</span>
                    <div class="question-content">
                        <p class="question-text">{{ $question->flashcard->question }}</p>
                        <p class="answer-text">{{ $question->flashcard->answer }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Quiz Modal -->
<div id="quizModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Ready to Start?</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>You're about to start: <strong>{{ $quiz->title }}</strong></p>
            <ul>
                <li>{{ $quiz->question_count }} questions</li>
                @if($quiz->time_limit)
                <li>{{ $quiz->time_limit }} minute time limit</li>
                @endif
                <li>Questions will {{ $quiz->shuffle_questions ? '' : 'not ' }}be shuffled</li>
            </ul>
        </div>
        <div class="modal-footer">
            <button id="confirmStart" class="btn btn-primary">Start Quiz</button>
            <button class="btn btn-outline close-btn">Cancel</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('quizModal');
    const startBtn = document.getElementById('startQuiz');
    const confirmBtn = document.getElementById('confirmStart');
    const closeBtns = document.querySelectorAll('.close, .close-btn');

    startBtn.addEventListener('click', function() {
        modal.style.display = 'block';
    });

    closeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    });

    confirmBtn.addEventListener('click', function() {
        window.location.href = "{{ route('quizzes.start', $quiz->id) }}";
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>

<style>
.quiz-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.detail-card, .questions-card {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 25px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
}

.detail-card h3, .questions-card h3 {
    color: #dffbff;
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 1.3rem;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    padding-bottom: 10px;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-label {
    color: #a4d8e8;
    font-size: 13px;
    margin-bottom: 5px;
}

.detail-value {
    color: #dffbff;
    font-weight: 500;
}

.questions-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.question-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    background: rgba(15, 30, 45, 0.3);
    border-radius: 12px;
    border: 1px solid rgba(57, 183, 255, 0.1);
}

.question-number {
    background: linear-gradient(135deg, #2dc2ff 0%, #78f7d1 100%);
    color: #0a1929;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
}

.question-content {
    flex: 1;
}

.question-text {
    color: #dffbff;
    margin: 0 0 8px 0;
    font-weight: 500;
}

.answer-text {
    color: #78f7d1;
    margin: 0;
    font-size: 14px;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

.modal-content {
    background: rgba(20, 40, 60, 0.95);
    margin: 10% auto;
    padding: 0;
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    border: 1px solid rgba(57, 183, 255, 0.2);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    color: #dffbff;
    margin: 0;
    font-size: 1.5rem;
}

.close {
    color: #a4d8e8;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #dffbff;
}

.modal-body {
    padding: 20px;
    color: #a4d8e8;
}

.modal-body ul {
    margin: 15px 0;
    padding-left: 20px;
}

.modal-body li {
    margin-bottom: 8px;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid rgba(57, 183, 255, 0.1);
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

@media (max-width: 768px) {
    .quiz-details {
        grid-template-columns: 1fr;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
@extends('layouts.app')

@section('title', 'Create Quiz')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Create Quiz from Flashcard Set</h1>
        <div class="dashboard-actions">
            <a href="{{ route('quizzes.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Quizzes
            </a>
        </div>
    </div>

    <div class="create-quiz-form">
        <form action="{{ route('quizzes.store', $setId) }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="question_count">Number of Questions</label>
                <input type="number" id="question_count" name="question_count" 
                       min="1" max="{{ $maxQuestions }}" value="{{ min(10, $maxQuestions) }}" 
                       class="form-control" required>
                <small>Maximum available: {{ $maxQuestions }} flashcards</small>
            </div>

            <div class="form-group">
                <label for="time_limit">Time Limit (minutes)</label>
                <input type="number" id="time_limit" name="time_limit" 
                       min="1" class="form-control" placeholder="Optional">
                <small>Leave empty for no time limit</small>
            </div>

            <div class="form-check">
                <input type="checkbox" id="shuffle_questions" name="shuffle_questions" 
                       value="1" class="form-check-input" checked>
                <label for="shuffle_questions" class="form-check-label">Shuffle Questions</label>
            </div>

            <div class="form-check">
                <input type="checkbox" id="show_correct_answers" name="show_correct_answers" 
                       value="1" class="form-check-input" checked>
                <label for="show_correct_answers" class="form-check-label">Show Correct Answers After Submission</label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Quiz
            </button>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
.create-quiz-form {
    max-width: 600px;
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 25px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    color: #dffbff;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    background: rgba(15, 30, 45, 0.3);
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    color: #dffbff;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #2dc2ff;
    box-shadow: 0 0 0 2px rgba(45, 194, 255, 0.2);
}

.form-check {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.form-check-input {
    margin-right: 10px;
}

.form-check-label {
    color: #dffbff;
    cursor: pointer;
}

small {
    color: #a4d8e8;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}
</style>
@endsection
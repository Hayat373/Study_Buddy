@extends('layouts.app')

@section('title', 'Quiz Results')

@section('content')
<div class="container">
    <div class="quiz-results">
        <div class="results-header">
            <h2>Quiz Results: {{ $attempt->quiz->title }}</h2>
            <p>Score: {{ $attempt->score }} / {{ $attempt->total_questions }}</p>
            <p>Time Taken: {{ $attempt->time_taken }} seconds</p>
        </div>
        <div class="answers-review">
            <h3>Your Answers</h3>
            @foreach ($attempt->answers as $answer)
                <div class="answer-item {{ $answer->is_correct ? 'correct' : 'incorrect' }}">
                    <div class="question">{{ $answer->question->flashcard->question }}</div>
                    <div class="user-answer">Your Answer: {{ $answer->user_answer }}</div>
                    <div class="correct-answer">Correct Answer: {{ $answer->question->flashcard->answer }}</div>
                </div>
            @endforeach
        </div>
        <div class="quiz-actions">
            <a href="{{ route('quizzes.index') }}" class="btn btn-primary">Back to Quizzes</a>
            <a href="{{ route('quizzes.create', $attempt->quiz->flashcard_set_id) }}" class="btn btn-secondary">Retake Quiz</a>
        </div>
    </div>
</div>
@endsection
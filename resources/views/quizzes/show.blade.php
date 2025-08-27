@extends('layouts.app')

@section('title', 'Quiz: {{ $quiz->title }}')

@section('content')
<div class="container">
    <div class="header">
        <h1>{{ $quiz->title }}</h1>
        <p>{{ $quiz->description }}</p>
        <p>Number of Questions: {{ $quiz->question_count }}</p>
        <p>Time Limit: {{ $quiz->time_limit ?? 'None' }} minutes</p>
        <p>Shuffle Questions: {{ $quiz->shuffle_questions ? 'Yes' : 'No' }}</p>
        <p>Show Correct Answers: {{ $quiz->show_correct_answers ? 'Yes' : 'No' }}</p>
    </div>

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if ($quiz->questions->isEmpty())
        <div class="error-messages">
            <p>No questions available for this quiz. Please try creating the quiz again or contact support.</p>
        </div>
        <a href="{{ route('quizzes.create', $quiz->flashcard_set_id) }}" class="btn btn-primary">Recreate Quiz</a>
    @else
        <div class="quiz-content">
            <div class="question-card" id="questionCard">
                <h3 id="questionText">{{ $quiz->questions->first()->flashcard->question }}</h3>
                <div class="answer-section">
                    <textarea id="userAnswer" rows="4" placeholder="Type your answer here..." autofocus></textarea>
                    <button id="submitAnswerBtn" class="btn btn-primary" data-quiz-id="{{ $quiz->id }}">Submit Answer</button>
                </div>
            </div>
            <div class="quiz-feedback" id="quizFeedback" style="display: none;">
                <h4 id="feedbackTitle"></h4>
                <p id="feedbackText"></p>
                <p><strong>Correct Answer:</strong> <span id="correctAnswer"></span></p>
                <button id="nextQuestionBtn" class="btn btn-primary" style="display: none;">Next Question</button>
            </div>
        </div>
    @endif
</div>

@section('scripts')
    <script src="{{ asset('js/quiz-show.js') }}"></script>
@endsection
@endsection
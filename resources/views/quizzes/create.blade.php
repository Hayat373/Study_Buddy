@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
@section('title', 'Create Quiz')

@section('content')

<div class="container">
    <div class="header">
        <h1>Create Quiz from Flashcard Set</h1>
        <a href="{{ url()->previous() }}" class="back-btn">← Back</a>
    </div>

    <div class="quiz-create-form">
        <form id="quizCreateForm" method="POST" action="{{ route('quizzes.store', $setId) }}">
            @csrf
            <input type="hidden" id="flashcardSetId" name="flashcard_set_id" value="{{ $setId }}">

            <div class="form-group">
                <label for="questionCount">Number of Questions:</label>
                <input type="number" id="questionCount" name="question_count" min="1" max="{{ $maxQuestions }}" value="{{ min(10, $maxQuestions) }}" required>
                <span class="max-questions">(Max: {{ $maxQuestions }})</span>
            </div>

            <div class="form-group">
                <label for="timeLimit">Time Limit (minutes, optional):</label>
                <input type="number" id="timeLimit" name="time_limit" min="1" placeholder="No time limit">
            </div>

            <div class="form-group checkbox-group">
                <label class="checkbox-container">
                    <input type="checkbox" id="shuffleQuestions" name="shuffle_questions" checked>
                    <span class="checkmark"></span>
                    Shuffle Questions
                </label>
            </div>

            <div class="form-group checkbox-group">
                <label class="checkbox-container">
                    <input type="checkbox" id="showCorrectAnswers" name="show_correct_answers" checked>
                    <span class="checkmark"></span>
                    Show Correct Answers After Submission
                </label>
            </div>

            <button type="submit" class="btn btn-primary" id="createQuizBtn">Create Quiz</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/quiz-create.js') }}"></script>
@endsection
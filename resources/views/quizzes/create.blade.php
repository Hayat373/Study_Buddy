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
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($maxQuestions < 1)
            <div class="error-messages">
                <p>No flashcards available in this set. Please add flashcards before creating a quiz.</p>
            </div>
        @else
            <form id="quizCreateForm" method="POST" action="{{ route('quizzes.store', $setId) }}">
                @csrf
                <input type="hidden" id="flashcardSetId" name="flashcard_set_id" value="{{ $setId }}">

                <div class="form-group">
                    <label for="questionCount">Number of Questions:</label>
                    <input type="number" id="questionCount" name="question_count" min="1" max="{{ $maxQuestions }}" value="{{ old('question_count', min(10, $maxQuestions)) }}" required>
                    <span class="max-questions">(Max: {{ $maxQuestions }})</span>
                    @error('question_count')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="timeLimit">Time Limit (minutes, optional):</label>
                    <input type="number" id="timeLimit" name="time_limit" min="1" placeholder="No time limit" value="{{ old('time_limit') }}">
                    @error('time_limit')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group checkbox-group">
                    <label class="checkbox-container">
                        <input type="checkbox" id="shuffleQuestions" name="shuffle_questions" {{ old('shuffle_questions', true) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        Shuffle Questions
                    </label>
                    @error('shuffle_questions')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group checkbox-group">
                    <label class="checkbox-container">
                        <input type="checkbox" id="showCorrectAnswers" name="show_correct_answers" {{ old('show_correct_answers', true) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        Show Correct Answers After Submission
                    </label>
                    @error('show_correct_answers')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" id="createQuizBtn">Create Quiz</button>
            </form>
        @endif
    </div>
</div>

@section('scripts')
    <script src="{{ asset('js/quiz-create.js') }}"></script>
@endsection
@endsection
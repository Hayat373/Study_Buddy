@extends('layouts.app')

@section('title', 'Edit Quiz')

@section('content')
<div class="container">
    <div class="header">
        <h1>Edit Quiz: {{ $quiz->title }}</h1>
        <a href="{{ route('quizzes.index') }}" class="back-btn">← Back to Quizzes</a>
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
                <p>No flashcards available in this set. Please add flashcards before editing the quiz.</p>
            </div>
        @else
            <form id="quizEditForm" method="POST" action="{{ route('quizzes.update', $quiz->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="flashcard_set_id" value="{{ $quiz->flashcard_set_id }}">

                <div class="form-group">
                    <label for="questionCount">Number of Questions:</label>
                    <input type="number" id="questionCount" name="question_count" min="1" max="{{ $maxQuestions }}" value="{{ old('question_count', $quiz->question_count) }}" required>
                    <span class="max-questions">(Max: {{ $maxQuestions }})</span>
                    @error('question_count')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="timeLimit">Time Limit (minutes, optional):</label>
                    <input type="number" id="timeLimit" name="time_limit" min="1" placeholder="No time limit" value="{{ old('time_limit', $quiz->time_limit) }}">
                    @error('time_limit')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group checkbox-group">
                    <label class="checkbox-container">
                        <input type="checkbox" id="shuffleQuestions" name="shuffle_questions" {{ old('shuffle_questions', $quiz->shuffle_questions) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        Shuffle Questions
                    </label>
                    @error('shuffle_questions')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group checkbox-group">
                    <label class="checkbox-container">
                        <input type="checkbox" id="showCorrectAnswers" name="show_correct_answers" {{ old('show_correct_answers', $quiz->show_correct_answers) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        Show Correct Answers After Submission
                    </label>
                    @error('show_correct_answers')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" id="updateQuizBtn">Update Quiz</button>
            </form>
        @endif
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionCountInput = document.getElementById('questionCount');
            if (questionCountInput) {
                const maxQuestions = parseInt(questionCountInput.getAttribute('max'));
                const updateQuizBtn = document.getElementById('updateQuizBtn');

                if (maxQuestions < 1 && updateQuizBtn) {
                    updateQuizBtn.disabled = true;
                    updateQuizBtn.textContent = 'Cannot Update Quiz';
                }

                questionCountInput.addEventListener('change', function() {
                    if (this.value > maxQuestions) {
                        this.value = maxQuestions;
                    } else if (this.value < 1) {
                        this.value = 1;
                    }
                });
            }
        });
    </script>
@endsection
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Quiz - Study Buddy</title>
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Create Quiz from Flashcard Set</h1>
            <a href="{{ url()->previous() }}" class="back-btn">← Back</a>
        </div>

        <div class="quiz-create-form">
            <form id="quizCreateForm">
                <input type="hidden" id="flashcardSetId" value="{{ $setId }}">

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
</body>
</html>
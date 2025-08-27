<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quiz - Study Buddy</title>
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
</head>
<body>
    <div class="quiz-container">
        <div class="quiz-header">
            <div class="quiz-info">
                <h1 id="quizTitle">Quiz: {{ $quiz->title }}</h1>
                <p>From: {{ $quiz->flashcardSet->title }}</p>
            </div>
            <div class="quiz-controls">
                <div class="quiz-timer" id="quizTimer">00:00</div>
                <div class="quiz-progress" id="quizProgress">Question 1 of {{ $quiz->question_count }}</div>
                <button id="endQuizBtn" class="btn btn-danger">End Quiz</button>
            </div>
        </div>
        
        <div class="quiz-content">
            <div class="question-card" id="questionCard">
                <h3 id="questionText">{{ $questions[0]->flashcard->question }}</h3>
                <div class="answer-section">
                    <textarea id="userAnswer" rows="4" placeholder="Type your answer here..." autofocus></textarea>
                    <button id="submitAnswerBtn" class="btn btn-primary">Submit Answer</button>
                </div>
            </div>
            
            <div class="quiz-feedback" id="quizFeedback" style="display: none;">
                <h4 id="feedbackTitle"></h4>
                <p id="feedbackText"></p>
                <p><strong>Correct Answer:</strong> <span id="correctAnswer"></span></p>
                <button id="nextQuestionBtn" class="btn btn-primary">Next Question</button>
            </div>
        </div>
    </div>

    <script>
        window.quizData = {
            quiz: @json($quiz),
            questions: @json($questions),
            attempt: @json($attempt)
        };
    </script>
    <script src="{{ asset('js/quiz-interface.js') }}"></script>
</body>
</html>
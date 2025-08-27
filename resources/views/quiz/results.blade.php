<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results - Study Buddy</title>
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Quiz Results</h1>
            <a href="{{ route('dashboard') }}" class="back-btn">← Dashboard</a>
        </div>

        <div class="quiz-results">
            <div class="results-header">
                <h2>{{ $attempt->quiz->title }}</h2>
                <p>Completed on {{ $attempt->completed_at->format('F j, Y g:i A') }}</p>
            </div>

            <div class="quiz-stats">
                <div class="stat">
                    <h3>{{ $attempt->score }}/{{ $attempt->total_questions }}</h3>
                    <p>Score</p>
                </div>
                <div class="stat">
                    <h3>{{ floor($attempt->time_taken / 60) }}:{{ sprintf('%02d', $attempt->time_taken % 60) }}</h3>
                    <p>Time Taken</p>
                </div>
                <div class="stat">
                    <h3>{{ round(($attempt->score / $attempt->total_questions) * 100) }}%</h3>
                    <p>Percentage</p>
                </div>
            </div>

            <div class="answers-review">
                <h3>Question Review</h3>
                @foreach($attempt->answers as $index => $answer)
                <div class="answer-item {{ $answer->is_correct ? 'correct' : 'incorrect' }}">
                    <div class="question">{{ $index + 1 }}. {{ $answer->question->flashcard->question }}</div>
                    <div class="user-answer">Your answer: {{ $answer->user_answer }}</div>
                    @if(!$answer->is_correct)
                    <div class="correct-answer">Correct answer: {{ $answer->question->flashcard->answer }}</div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="quiz-actions">
                <a href="{{ route('quiz.create', ['setId' => $attempt->quiz->flashcard_set_id]) }}" class="btn btn-primary">Take Another Quiz</a>
                <a href="{{ route('flashcards.show', ['id' => $attempt->quiz->flashcard_set_id]) }}" class="btn btn-secondary">Back to Flashcards</a>
            </div>
        </div>
    </div>
</body>
</html>
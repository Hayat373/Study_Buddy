@extends('layouts.app')

@section('title', 'Taking Quiz: ' . $quiz->title)

@section('content')
<div class="dashboard-container">
    <div class="quiz-header">
        <h1>{{ $quiz->title }}</h1>
        <div class="quiz-timer" id="quizTimer">
            <i class="fas fa-clock"></i>
            <span id="timeRemaining">--:--</span>
        </div>
    </div>

    <div class="quiz-progress">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width: 0%"></div>
        </div>
        <div class="progress-text">
            Question <span id="currentQuestion">1</span> of <span id="totalQuestions">{{ count($questions) }}</span>
        </div>
    </div>

    <form id="quizForm" action="{{ route('quizzes.answer', $attempt->id) }}" method="POST">
        @csrf
        <input type="hidden" name="question_id" id="questionId">
        
        <div class="question-container">
            <div class="question-card" id="questionCard">
                <h2 id="questionText">Loading question...</h2>
                <div class="answer-input">
                    <input type="text" id="userAnswer" name="user_answer" 
                           placeholder="Type your answer here..." autocomplete="off" required>
                </div>
                <div class="question-actions">
                    <button type="button" id="prevQuestion" class="btn btn-outline" disabled>
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Submit Answer <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="button" id="nextQuestion" class="btn btn-outline" style="display: none;">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <div class="feedback-card" id="feedbackCard" style="display: none;">
                <h3 id="feedbackTitle"></h3>
                <div class="feedback-content">
                    <p id="feedbackText"></p>
                    <div class="correct-answer" id="correctAnswerContainer">
                        <strong>Correct answer:</strong> <span id="correctAnswer"></span>
                    </div>
                </div>
                <div class="feedback-actions">
                    <button type="button" id="continueBtn" class="btn btn-primary">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="quiz-complete" id="quizComplete" style="display: none;">
        <div class="complete-card">
            <i class="fas fa-check-circle"></i>
            <h2>Quiz Complete!</h2>
            <p id="scoreText">Your score: </p>
            <div class="complete-actions">
                <a href="{{ route('quizzes.results', $attempt->id) }}" class="btn btn-primary">
                    View Detailed Results
                </a>
                <a href="{{ route('quizzes.index') }}" class="btn btn-outline">
                    Back to Quizzes
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questions = @json($questions);
    const timeLimit = {{ $quiz->time_limit ? $quiz->time_limit * 60 : 0 }};
    const showCorrectAnswers = {{ $quiz->show_correct_answers ? 'true' : 'false' }};
    
    let currentQuestionIndex = 0;
    let userAnswers = {};
    let timer;
    let timeRemaining = timeLimit;
    let quizCompleted = false;

    // DOM Elements
    const questionText = document.getElementById('questionText');
    const questionId = document.getElementById('questionId');
    const userAnswer = document.getElementById('userAnswer');
    const prevButton = document.getElementById('prevQuestion');
    const nextButton = document.getElementById('nextQuestion');
    const submitButton = document.querySelector('button[type="submit"]');
    const progressFill = document.getElementById('progressFill');
    const currentQuestionSpan = document.getElementById('currentQuestion');
    const timeRemainingSpan = document.getElementById('timeRemaining');
    const questionCard = document.getElementById('questionCard');
    const feedbackCard = document.getElementById('feedbackCard');
    const feedbackTitle = document.getElementById('feedbackTitle');
    const feedbackText = document.getElementById('feedbackText');
    const correctAnswer = document.getElementById('correctAnswer');
    const correctAnswerContainer = document.getElementById('correctAnswerContainer');
    const continueBtn = document.getElementById('continueBtn');
    const quizComplete = document.getElementById('quizComplete');
    const scoreText = document.getElementById('scoreText');
    const quizForm = document.getElementById('quizForm');

    // Initialize quiz
    function initQuiz() {
        loadQuestion(0);
        updateProgress();
        
        if (timeLimit > 0) {
            startTimer();
        }
    }

    // Load question
    function loadQuestion(index) {
        if (index < 0 || index >= questions.length) return;
        
        currentQuestionIndex = index;
        const question = questions[index];
        
        questionText.textContent = question.flashcard.question;
        questionId.value = question.id;
        userAnswer.value = userAnswers[question.id] || '';
        
        // Update navigation buttons
        prevButton.disabled = index === 0;
        nextButton.style.display = index === questions.length - 1 ? 'none' : 'inline-flex';
        submitButton.style.display = index === questions.length - 1 ? 'inline-flex' : 'none';
        
        currentQuestionSpan.textContent = index + 1;
        updateProgress();
    }

    // Update progress bar
    function updateProgress() {
        const progress = ((currentQuestionIndex + 1) / questions.length) * 100;
        progressFill.style.width = `${progress}%`;
    }

    // Start timer
    function startTimer() {
        updateTimerDisplay();
        
        timer = setInterval(function() {
            timeRemaining--;
            updateTimerDisplay();
            
            if (timeRemaining <= 0) {
                clearInterval(timer);
                completeQuiz();
            }
        }, 1000);
    }

    // Update timer display
    function updateTimerDisplay() {
        if (timeLimit > 0) {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            timeRemainingSpan.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Change color when time is running out
            if (timeRemaining < 60) {
                timeRemainingSpan.style.color = '#ff6b6b';
            }
        }
    }

    // Handle form submission
    quizForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(quizForm);
        const questionId = formData.get('question_id');
        const userAnswer = formData.get('user_answer');
        
        // Store answer locally
        userAnswers[questionId] = userAnswer;
        
        try {
            const response = await fetch(quizForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    question_id: questionId,
                    user_answer: userAnswer
                })
            });
            
            const data = await response.json();
            
            // Show feedback
            showFeedback(data);
            
        } catch (error) {
            console.error('Error submitting answer:', error);
            alert('Error submitting answer. Please try again.');
        }
    });

    // Show feedback
    function showFeedback(data) {
        feedbackTitle.textContent = data.is_correct ? 'Correct!' : 'Incorrect';
        feedbackTitle.style.color = data.is_correct ? '#78f7d1' : '#ff6b6b';
        
        feedbackText.textContent = data.is_correct ? 
            'Great job! Your answer is correct.' : 
            'Your answer was not correct.';
        
        if (showCorrectAnswers) {
            correctAnswer.textContent = data.correct_answer;
            correctAnswerContainer.style.display = 'block';
        } else {
            correctAnswerContainer.style.display = 'none';
        }
        
        questionCard.style.display = 'none';
        feedbackCard.style.display = 'block';
    }

    // Continue to next question or complete quiz
    continueBtn.addEventListener('click', function() {
        feedbackCard.style.display = 'none';
        
        if (currentQuestionIndex < questions.length - 1) {
            questionCard.style.display = 'block';
            loadQuestion(currentQuestionIndex + 1);
        } else {
            completeQuiz();
        }
    });

    // Navigation buttons
    prevButton.addEventListener('click', function() {
        loadQuestion(currentQuestionIndex - 1);
    });

    nextButton.addEventListener('click', function() {
        loadQuestion(currentQuestionIndex + 1);
    });

    // Complete quiz
    async function completeQuiz() {
        if (quizCompleted) return;
        quizCompleted = true;
        
        clearInterval(timer);
        
        try {
            const response = await fetch('{{ route("quizzes.complete", $attempt->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            // Show completion screen
            scoreText.textContent = `Your score: ${data.score} out of ${data.total_questions}`;
            quizComplete.style.display = 'block';
            questionCard.style.display = 'none';
            
        } catch (error) {
            console.error('Error completing quiz:', error);
            alert('Error completing quiz. Please try again.');
        }
    }

    // Initialize the quiz
    initQuiz();
});
</script>

<style>
.quiz-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.quiz-timer {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(20, 40, 60, 0.5);
    padding: 12px 20px;
    border-radius: 12px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    color: #dffbff;
    font-weight: 600;
    font-size: 1.1rem;
}

.quiz-timer i {
    color: #2dc2ff;
}

.quiz-progress {
    margin-bottom: 30px;
}

.progress-bar {
    height: 8px;
    background: rgba(57, 183, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 10px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #2dc2ff 0%, #78f7d1 100%);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.progress-text {
    color: #a4d8e8;
    font-size: 14px;
    text-align: center;
}

.question-container {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 30px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
    margin-bottom: 30px;
}

.question-card h2 {
    color: #dffbff;
    margin-bottom: 25px;
    font-size: 1.5rem;
    line-height: 1.4;
}

.answer-input {
    margin-bottom: 25px;
}

.answer-input input {
    width: 100%;
    padding: 15px 20px;
    background: rgba(15, 30, 45, 0.3);
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    color: #dffbff;
    font-size: 16px;
}

.answer-input input:focus {
    outline: none;
    border-color: #2dc2ff;
    box-shadow: 0 0 0 2px rgba(45, 194, 255, 0.2);
}

.question-actions {
    display: flex;
    gap: 15px;
    justify-content: space-between;
}

.feedback-card h3 {
    font-size: 1.8rem;
    margin-bottom: 15px;
}

.feedback-content {
    margin-bottom: 25px;
}

.feedback-content p {
    color: #dffbff;
    font-size: 16px;
    margin-bottom: 15px;
}

.correct-answer {
    background: rgba(120, 247, 209, 0.1);
    border: 1px solid rgba(120, 247, 209, 0.2);
    border-radius: 8px;
    padding: 15px;
    color: #78f7d1;
}

.feedback-actions {
    display: flex;
    justify-content: center;
}

.quiz-complete {
    text-align: center;
}

.complete-card {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 40px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
}

.complete-card i {
    font-size: 4rem;
    color: #78f7d1;
    margin-bottom: 20px;
}

.complete-card h2 {
    color: #dffbff;
    margin-bottom: 15px;
    font-size: 2rem;
}

.complete-card p {
    color: #a4d8e8;
    font-size: 1.2rem;
    margin-bottom: 25px;
}

.complete-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .quiz-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .question-actions {
        flex-direction: column;
    }
    
    .complete-actions {
        flex-direction: column;
    }
}
</style>
@endsection
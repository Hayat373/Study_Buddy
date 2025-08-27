class QuizManager {
    constructor() {
        this.quiz = window.quizData.quiz;
        this.questions = window.quizData.questions;
        this.attempt = window.quizData.attempt;
        this.currentQuestionIndex = 0;
        this.timerInterval = null;
        this.timeElapsed = 0;
        
        this.initializeEventListeners();
        this.startTimer();
        this.displayQuestion(0);
    }
    
    initializeEventListeners() {
        document.getElementById('submitAnswerBtn').addEventListener('click', () => {
            this.submitAnswer();
        });
        
        document.getElementById('nextQuestionBtn').addEventListener('click', () => {
            this.nextQuestion();
        });
        
        document.getElementById('endQuizBtn').addEventListener('click', () => {
            this.endQuiz();
        });
        
        // Allow pressing Enter to submit answer
        document.getElementById('userAnswer').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.submitAnswer();
            }
        });
    }
    
    startTimer() {
        this.timeElapsed = 0;
        this.timerInterval = setInterval(() => {
            this.timeElapsed++;
            this.updateTimerDisplay();
        }, 1000);
    }
    
    updateTimerDisplay() {
        const minutes = Math.floor(this.timeElapsed / 60);
        const seconds = this.timeElapsed % 60;
        document.getElementById('quizTimer').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    displayQuestion(index) {
        if (index >= this.questions.length) {
            this.completeQuiz();
            return;
        }
        
        this.currentQuestionIndex = index;
        const question = this.questions[index].flashcard;
        
        document.getElementById('questionText').textContent = question.question;
        document.getElementById('userAnswer').value = '';
        document.getElementById('quizProgress').textContent = 
            `Question ${index + 1} of ${this.questions.length}`;
        
        // Show question, hide feedback
        document.getElementById('questionCard').style.display = 'block';
        document.getElementById('quizFeedback').style.display = 'none';
        
        // Focus on answer textarea
        document.getElementById('userAnswer').focus();
    }
    
    async submitAnswer() {
        const userAnswer = document.getElementById('userAnswer').value.trim();
        
        if (!userAnswer) {
            alert('Please enter an answer');
            return;
        }
        
        try {
            const response = await fetch(`/api/quizzes/attempts/${this.attempt.id}/answer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                },
                body: JSON.stringify({
                    question_id: this.questions[this.currentQuestionIndex].id,
                    user_answer: userAnswer
                })
            });
            
            if (response.ok) {
                const result = await response.json();
                this.showFeedback(result);
            } else {
                throw new Error('Failed to submit answer');
            }
        } catch (error) {
            console.error('Answer submission error:', error);
            alert('Failed to submit answer');
        }
    }
    
    showFeedback(result) {
        document.getElementById('questionCard').style.display = 'none';
        document.getElementById('quizFeedback').style.display = 'block';
        
        if (result.is_correct) {
            document.getElementById('feedbackTitle').textContent = 'Correct!';
            document.getElementById('feedbackTitle').className = 'correct';
            document.getElementById('feedbackText').textContent = 'Well done! Your answer is correct.';
            document.getElementById('quizFeedback').className = 'quiz-feedback correct';
        } else {
            document.getElementById('feedbackTitle').textContent = 'Incorrect';
            document.getElementById('feedbackTitle').className = 'incorrect';
            document.getElementById('feedbackText').textContent = 'Your answer was not correct.';
            document.getElementById('quizFeedback').className = 'quiz-feedback incorrect';
        }
        
        document.getElementById('correctAnswer').textContent = result.correct_answer;
    }
    
    nextQuestion() {
        this.displayQuestion(this.currentQuestionIndex + 1);
    }
    
    async endQuiz() {
        if (confirm('Are you sure you want to end the quiz? Your progress will be saved.')) {
            await this.completeQuiz();
        }
    }
    
    async completeQuiz() {
        clearInterval(this.timerInterval);
        
        try {
            const response = await fetch(`/api/quizzes/attempts/${this.attempt.id}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                }
            });
            
            if (response.ok) {
                const attempt = await response.json();
                window.location.href = `/quiz/attempt/${attempt.id}/results`;
            } else {
                throw new Error('Failed to complete quiz');
            }
        } catch (error) {
            console.error('Quiz completion error:', error);
            alert('Failed to complete quiz');
        }
    }
}

// Initialize the quiz when the page loads
document.addEventListener('DOMContentLoaded', () => {
    window.quizManager = new QuizManager();
});
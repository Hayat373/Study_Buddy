document.addEventListener('DOMContentLoaded', function() {
    const quizId = document.getElementById('submitAnswerBtn').dataset.quizId;
    let attemptId = null;
    let questions = [];
    let currentQuestionIndex = 0;

    // Start quiz attempt
    async function startQuiz() {
        try {
            const response = await fetch(`/api/quizzes/${quizId}/start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                throw new Error('Failed to start quiz');
            }

            const data = await response.json();
            attemptId = data.attempt.id;
            questions = data.questions;
            displayQuestion();
        } catch (error) {
            console.error('Quiz start error:', error);
            alert('Failed to start quiz. Please try again.');
        }
    }

    // Display current question
    function displayQuestion() {
        const questionCard = document.getElementById('questionCard');
        const questionText = document.getElementById('questionText');
        const userAnswer = document.getElementById('userAnswer');
        const quizFeedback = document.getElementById('quizFeedback');
        const nextQuestionBtn = document.getElementById('nextQuestionBtn');

        if (currentQuestionIndex < questions.length) {
            questionText.textContent = questions[currentQuestionIndex].flashcard.question;
            userAnswer.value = '';
            quizFeedback.style.display = 'none';
            nextQuestionBtn.style.display = 'none';
            questionCard.style.display = 'block';
        } else {
            // Quiz completed
            questionCard.style.display = 'none';
            completeQuiz();
        }
    }

    // Submit answer
    document.getElementById('submitAnswerBtn').addEventListener('click', async function() {
        const userAnswer = document.getElementById('userAnswer').value.trim();
        if (!userAnswer) {
            alert('Please enter an answer.');
            return;
        }

        try {
            const response = await fetch(`/api/quizzes/attempts/${attemptId}/answer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    question_id: questions[currentQuestionIndex].id,
                    user_answer: userAnswer
                })
            });

            if (!response.ok) {
                throw new Error('Failed to submit answer');
            }

            const data = await response.json();
            const quizFeedback = document.getElementById('quizFeedback');
            const feedbackTitle = document.getElementById('feedbackTitle');
            const feedbackText = document.getElementById('feedbackText');
            const correctAnswer = document.getElementById('correctAnswer');
            const nextQuestionBtn = document.getElementById('nextQuestionBtn');

            quizFeedback.style.display = 'block';
            quizFeedback.className = `quiz-feedback ${data.is_correct ? 'correct' : 'incorrect'}`;
            feedbackTitle.textContent = data.is_correct ? 'Correct!' : 'Incorrect';
            feedbackText.textContent = data.is_correct ? 'Great job!' : 'Better luck next time.';
            correctAnswer.textContent = data.correct_answer;
            nextQuestionBtn.style.display = 'block';
        } catch (error) {
            console.error('Answer submission error:', error);
            alert('Failed to submit answer. Please try again.');
        }
    });

    // Next question
    document.getElementById('nextQuestionBtn').addEventListener('click', function() {
        currentQuestionIndex++;
        displayQuestion();
    });

    // Complete quiz
    async function completeQuiz() {
        try {
            const response = await fetch(`/api/quizzes/attempts/${attemptId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                throw new Error('Failed to complete quiz');
            }

            const data = await response.json();
            window.location.href = `/quizzes/attempts/${data.id}/results`;
        } catch (error) {
            console.error('Quiz completion error:', error);
            alert('Failed to complete quiz. Please try again.');
        }
    }

    // Start the quiz on page load
    startQuiz();
});
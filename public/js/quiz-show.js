document.addEventListener('DOMContentLoaded', function() {
    const submitAnswerBtn = document.getElementById('submitAnswerBtn');
    const quizId = document.getElementById('submitAnswerBtn').dataset.quizId;
    let attemptId = null;
    let questions = [];
    let currentQuestionIndex = 0;

    // Disable submit button until quiz starts
    if (submitAnswerBtn) {
        submitAnswerBtn.disabled = true;
    }

    // Start quiz attempt
    async function startQuiz() {

        if (!quizId) {
            alert('Quiz ID not found. Please try again.');
            return;
        }

        try {
            const response = await fetch(`/api/quizzes/${quizId}/start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

           if (!response.ok) {
                const errorData = await response.json();
                console.error('Error response:', errorData);
                throw new Error(errorData.error || 'Failed to start quiz');
            }

            const data = await response.json();
            attemptId = data.attempt.id;
            questions = data.questions;
            if (questions.length === 0) {
                alert('No questions available for this quiz.');
                return;
            }
            submitAnswerBtn.disabled = false;
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
    if (submitAnswerBtn) {
        submitAnswerBtn.addEventListener('click', async function() {
            if (!attemptId || !questions[currentQuestionIndex]) {
                alert('Quiz not started or no questions available.');
                return;
            }

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
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Failed to submit answer');
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
                alert(`Failed to submit answer: ${error.message}. Please try again.`);
            }
        });
    }

    // Next question
    const nextQuestionBtn = document.getElementById('nextQuestionBtn');
    if (nextQuestionBtn) {
        nextQuestionBtn.addEventListener('click', function() {
            currentQuestionIndex++;
            displayQuestion();
        });
    }

    // Complete quiz
    async function completeQuiz() {
        if (!attemptId) {
            alert('Quiz not started.');
            return;
        }

        try {
            const response = await fetch(`/api/quizzes/attempts/${attemptId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Failed to complete quiz');
            }

            const data = await response.json();
            window.location.href = `/quizzes/attempts/${data.id}/results`;
        } catch (error) {
            console.error('Quiz completion error:', error);
            alert(`Failed to complete quiz: ${error.message}. Please try again.`);
        }
    }

    // Start the quiz on page load
    startQuiz();
});
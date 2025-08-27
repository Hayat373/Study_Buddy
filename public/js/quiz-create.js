document.addEventListener('DOMContentLoaded', function() {
    const quizCreateForm = document.getElementById('quizCreateForm');
    const createQuizBtn = document.getElementById('createQuizBtn');
    const flashcardSetId = document.getElementById('flashcardSetId').value;
    const questionCountInput = document.getElementById('questionCount');
    const maxQuestions = parseInt(questionCountInput.getAttribute('max'));

    // Update question count if exceeds max
    questionCountInput.addEventListener('change', function() {
        if (this.value > maxQuestions) {
            this.value = maxQuestions;
        }
    });

    // Handle form submission
    quizCreateForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = {
            question_count: parseInt(questionCountInput.value),
            time_limit: document.getElementById('timeLimit').value ? 
                       parseInt(document.getElementById('timeLimit').value) : null,
            shuffle_questions: document.getElementById('shuffleQuestions').checked,
            show_correct_answers: document.getElementById('showCorrectAnswers').checked
        };

        createQuizBtn.disabled = true;
        createQuizBtn.textContent = 'Creating Quiz...';

        try {
            const response = await fetch(`/api/quizzes/create/${flashcardSetId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                // Redirect to quiz page
                window.location.href = `/quiz/${data.id}`;
            } else {
                alert(data.error || 'Failed to create quiz');
                createQuizBtn.disabled = false;
                createQuizBtn.textContent = 'Create Quiz';
            }
        } catch (error) {
            console.error('Quiz creation error:', error);
            alert('Failed to create quiz. Please try again.');
            createQuizBtn.disabled = false;
            createQuizBtn.textContent = 'Create Quiz';
        }
    });
});
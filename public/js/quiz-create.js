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


    // Disable button if maxQuestions < 1
        if (maxQuestions < 1 && createQuizBtn) {
            createQuizBtn.disabled = true;
            createQuizBtn.textContent = 'Cannot Create Quiz';
        }


        // Prevent invalid values
        questionCountInput.addEventListener('change', function() {
            if (this.value > maxQuestions) {
                this.value = maxQuestions;
            } else if (this.value < 1) {
                this.value = 1;
            }
        });
       
    // // Handle form submission
    // quizCreateForm.addEventListener('submit', async function(e) {
    //     e.preventDefault();
        
    //     const formData = {
    //         question_count: parseInt(questionCountInput.value),
    //         time_limit: document.getElementById('timeLimit').value ? 
    //                    parseInt(document.getElementById('timeLimit').value) : null,
    //         shuffle_questions: document.getElementById('shuffleQuestions').checked,
    //         show_correct_answers: document.getElementById('showCorrectAnswers').checked
    //     };

    //     console.log('Form data being sent:', formData); // Debugging log
    //     createQuizBtn.disabled = true;
    //     createQuizBtn.textContent = 'Creating Quiz...';

    //     try {
    //         const response = await fetch(`/api/quizzes/create/${flashcardSetId}`, {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    //                 'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
    //             },
    //             body: JSON.stringify(formData)
    //         });

    //         // Check for response
    //         if (!response.ok) {
    //             const errorData = await response.text(); // Get raw response text
    //             console.error('Error response:', errorData); // Log the error response
    //             throw new Error('Failed to create quiz');
    //         }

    //         const data = await response.json();

    //         // Redirect to quiz page
    //         window.location.href = `/quiz/${data.id}`;
    //     } catch (error) {
    //         console.error('Quiz creation error:', error);
    //         alert('Failed to create quiz. Please try again.');
    //         createQuizBtn.disabled = false;
    //         createQuizBtn.textContent = 'Create Quiz';
    //     }
    // });
});
@extends('layouts.app')

@section('title', 'Create Flashcard Set - Study Buddy')

@section('styles')
<style>
    .flashcard-create-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .create-header {
        margin-bottom: 30px;
    }

    .create-header h1 {
        font-size: 2rem;
        color: #dffbff;
        margin-bottom: 10px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #dffbff;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        background: rgba(20, 40, 60, 0.5);
        border: 1px solid rgba(57, 183, 255, 0.2);
        border-radius: 8px;
        color: #dffbff;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #2dc2ff;
        box-shadow: 0 0 0 2px rgba(45, 194, 255, 0.2);
    }

    .flashcards-container {
        margin-top: 30px;
    }

    .flashcard-item {
        background: rgba(20, 40, 60, 0.3);
        border: 1px solid rgba(57, 183, 255, 0.1);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .flashcard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .flashcard-number {
        color: #2dc2ff;
        font-weight: 600;
    }

    .remove-flashcard {
        background: rgba(255, 107, 107, 0.2);
        border: 1px solid rgba(255, 107, 107, 0.3);
        color: #ff6b6b;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
    }

    .remove-flashcard:hover {
        background: rgba(255, 107, 107, 0.3);
    }

    .add-flashcard-btn {
        background: linear-gradient(90deg, #2dc2ff 0%, #78f7d1 100%);
        color: #0a1929;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 20px;
    }

    .add-flashcard-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(45, 194, 255, 0.3);
    }

    .generate-ai-btn {
        background: rgba(156, 39, 176, 0.2);
        border: 1px solid rgba(156, 39, 176, 0.3);
        color: #9c27b0;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        margin-left: 15px;
    }

    .generate-ai-btn:hover {
        background: rgba(156, 39, 176, 0.3);
    }

    .ai-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(10, 25, 41, 0.9);
        z-index: 1000;
    }

    .ai-modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(20, 40, 60, 0.95);
        padding: 30px;
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid rgba(57, 183, 255, 0.1);
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }
</style>
@endsection

@section('content')
<div class="flashcard-create-container">
    <div class="create-header">
        <h1>Create New Flashcard Set</h1>
        <p>Create a new set of flashcards to study with</p>
    </div>

    <form action="{{ route('flashcards.store') }}" method="POST" id="flashcardForm">
        @csrf
        
        <div class="form-group">
            <label for="title">Set Title *</label>
            <input type="text" id="title" name="title" class="form-control" required placeholder="Enter a title for your flashcard set">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Describe what this flashcard set is about"></textarea>
        </div>

        <div class="flashcards-container" id="flashcardsContainer">
            <h3>Flashcards</h3>
            <div class="flashcard-item" data-index="0">
                <div class="flashcard-header">
                    <span class="flashcard-number">Card #1</span>
                    <button type="button" class="remove-flashcard" onclick="removeFlashcard(0)" style="display: none;">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
                <div class="form-group">
                    <label>Question *</label>
                    <input type="text" name="flashcards[0][question]" class="form-control" required placeholder="Enter question">
                </div>
                <div class="form-group">
                    <label>Answer *</label>
                    <textarea name="flashcards[0][answer]" class="form-control" rows="2" required placeholder="Enter answer"></textarea>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="button" class="add-flashcard-btn" onclick="addFlashcard()">
                <i class="fas fa-plus"></i> Add Another Card
            </button>
            <button type="button" class="generate-ai-btn" onclick="openAIModal()">
                <i class="fas fa-robot"></i> Generate with AI
            </button>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="is_public" name="is_public" value="1">
            <label for="is_public">Make this set public (visible to other users)</label>
        </div>

        <div class="form-actions">
            <a href="{{ route('flashcards.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Flashcard Set
            </button>
        </div>
    </form>
</div>

<!-- AI Generation Modal -->
<div class="ai-modal" id="aiModal">
    <div class="ai-modal-content">
        <h3>Generate Flashcards with AI</h3>
        <div class="form-group">
            <label for="aiTopic">Topic *</label>
            <input type="text" id="aiTopic" class="form-control" placeholder="Enter topic (e.g., Spanish Vocabulary, Math Formulas)">
        </div>
        <div class="form-group">
            <label for="aiCount">Number of Cards (1-20)</label>
            <input type="number" id="aiCount" class="form-control" min="1" max="20" value="5">
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeAIModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="generateAIFlashcards()">Generate</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let flashcardCount = 1;

    function addFlashcard() {
        const container = document.getElementById('flashcardsContainer');
        const newCard = document.createElement('div');
        newCard.className = 'flashcard-item';
        newCard.innerHTML = `
            <div class="flashcard-header">
                <span class="flashcard-number">Card #${flashcardCount + 1}</span>
                <button type="button" class="remove-flashcard" onclick="removeFlashcard(${flashcardCount})">
                    <i class="fas fa-times"></i> Remove
                </button>
            </div>
            <div class="form-group">
                <label>Question *</label>
                <input type="text" name="flashcards[${flashcardCount}][question]" class="form-control" required placeholder="Enter question">
            </div>
            <div class="form-group">
                <label>Answer *</label>
                <textarea name="flashcards[${flashcardCount}][answer]" class="form-control" rows="2" required placeholder="Enter answer"></textarea>
            </div>
        `;
        container.appendChild(newCard);
        flashcardCount++;
    }

    function removeFlashcard(index) {
        const card = document.querySelector(`.flashcard-item[data-index="${index}"]`);
        if (card) {
            card.remove();
            // Reindex remaining cards
            const cards = document.querySelectorAll('.flashcard-item');
            cards.forEach((card, i) => {
                card.setAttribute('data-index', i);
                card.querySelector('.flashcard-number').textContent = `Card #${i + 1}`;
            });
            flashcardCount = cards.length;
        }
    }

    function openAIModal() {
        document.getElementById('aiModal').style.display = 'block';
    }

    function closeAIModal() {
        document.getElementById('aiModal').style.display = 'none';
    }

    function generateAIFlashcards() {
        const topic = document.getElementById('aiTopic').value;
        const count = document.getElementById('aiCount').value;

        if (!topic) {
            alert('Please enter a topic');
            return;
        }

        fetch('{{ route("flashcards.generate.ai") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ topic, count })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear existing cards except first one
                const container = document.getElementById('flashcardsContainer');
                const cards = container.querySelectorAll('.flashcard-item');
                for (let i = 1; i < cards.length; i++) {
                    cards[i].remove();
                }

                // Add AI generated cards
                data.flashcards.forEach((flashcard, index) => {
                    if (index === 0) {
                        // Update first card
                        document.querySelector('input[name="flashcards[0][question]"]').value = flashcard.question;
                        document.querySelector('textarea[name="flashcards[0][answer]"]').value = flashcard.answer;
                    } else {
                        addFlashcard();
                        const lastIndex = flashcardCount - 1;
                        document.querySelector(`input[name="flashcards[${lastIndex}][question]"]`).value = flashcard.question;
                        document.querySelector(`textarea[name="flashcards[${lastIndex}][answer]"]`).value = flashcard.answer;
                    }
                });

                closeAIModal();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to generate flashcards');
        });
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('aiModal');
        if (event.target === modal) {
            closeAIModal();
        }
    });
</script>
@endsection
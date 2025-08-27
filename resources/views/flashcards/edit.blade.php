@extends('layouts.app')

@section('title', 'Edit Flashcard Set - Study Buddy')

@section('styles')
<style>
    .flashcard-edit-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .edit-header {
        margin-bottom: 30px;
    }

    .edit-header h1 {
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
</style>
@endsection

@section('content')
<div class="flashcard-edit-container">
    <div class="edit-header">
        <h1>Edit Flashcard Set</h1>
        <p>Update your flashcard set information</p>
    </div>

    <form action="{{ route('flashcards.update', $flashcardSet->id) }}" method="POST" id="flashcardForm">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="title">Set Title *</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $flashcardSet->title) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $flashcardSet->description) }}</textarea>
        </div>

        <div class="flashcards-container" id="flashcardsContainer">
            <h3>Flashcards</h3>
            @foreach($flashcardSet->flashcards as $index => $flashcard)
            <div class="flashcard-item" data-index="{{ $index }}">
                <div class="flashcard-header">
                    <span class="flashcard-number">Card #{{ $index + 1 }}</span>
                    <button type="button" class="remove-flashcard" onclick="removeFlashcard({{ $index }})" {{ $index === 0 ? 'style="display: none;"' : '' }}>
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
                <input type="hidden" name="flashcards[{{ $index }}][id]" value="{{ $flashcard->id }}">
                <div class="form-group">
                    <label>Question *</label>
                    <input type="text" name="flashcards[{{ $index }}][question]" class="form-control" value="{{ old('flashcards.'.$index.'.question', $flashcard->question) }}" required>
                </div>
                <div class="form-group">
                    <label>Answer *</label>
                    <textarea name="flashcards[{{ $index }}][answer]" class="form-control" rows="2" required>{{ old('flashcards.'.$index.'.answer', $flashcard->answer) }}</textarea>
                </div>
            </div>
            @endforeach
        </div>

        <div class="form-group">
            <button type="button" class="add-flashcard-btn" onclick="addFlashcard()">
                <i class="fas fa-plus"></i> Add Another Card
            </button>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public', $flashcardSet->is_public) ? 'checked' : '' }}>
            <label for="is_public">Make this set public (visible to other users)</label>
        </div>

        <div class="form-actions">
            <a href="{{ route('flashcards.show', $flashcardSet->id) }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Flashcard Set
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let flashcardCount = {{ $flashcardSet->flashcards->count() }};

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
</script>
@endsection
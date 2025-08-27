@extends('layouts.app')

@section('title', $flashcardSet->title . ' - Study Buddy')

@section('styles')
<style>
    .flashcard-show-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .show-header {
        margin-bottom: 30px;
    }

    .show-header h1 {
        font-size: 2.2rem;
        color: #dffbff;
        margin-bottom: 10px;
    }

    .set-description {
        color: #a4d8e8;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    .set-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #78f7d1;
        font-size: 0.9rem;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .study-mode-container {
        background: rgba(20, 40, 60, 0.5);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .study-mode-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .study-mode-header h2 {
        color: #dffbff;
        margin-bottom: 10px;
    }

    .flashcard-display {
        perspective: 1000px;
        margin: 0 auto;
        max-width: 500px;
    }

    .flashcard {
        width: 100%;
        height: 300px;
        position: relative;
        transform-style: preserve-3d;
        transition: transform 0.6s;
        cursor: pointer;
    }

    .flashcard.flipped {
        transform: rotateY(180deg);
    }

    .flashcard-front,
    .flashcard-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 16px;
        padding: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .flashcard-front {
        background: linear-gradient(135deg, #2dc2ff 0%, #78f7d1 100%);
        color: #0a1929;
    }

    .flashcard-back {
        background: linear-gradient(135deg, #78f7d1 0%, #2dc2ff 100%);
        color: #0a1929;
        transform: rotateY(180deg);
    }

    .flashcard-content {
        font-size: 1.2rem;
        line-height: 1.6;
    }

    .study-controls {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 30px;
    }

    .navigation-buttons {
        display: flex;
        gap: 10px;
    }

    .progress-indicator {
        text-align: center;
        color: #a4d8e8;
        margin-top: 15px;
    }

    .flashcards-list {
        margin-top: 40px;
    }

    .flashcards-list h3 {
        color: #dffbff;
        margin-bottom: 20px;
        font-size: 1.5rem;
    }

    .flashcard-item {
        background: rgba(20, 40, 60, 0.3);
        border: 1px solid rgba(57, 183, 255, 0.1);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
    }

    .flashcard-question {
        color: #dffbff;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .flashcard-answer {
        color: #a4d8e8;
        border-left: 3px solid #2dc2ff;
        padding-left: 15px;
        margin-left: 5px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #a4d8e8;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 20px;
        color: rgba(57, 183, 255, 0.3);
    }
</style>
@endsection

@section('content')
<div class="flashcard-show-container">
    <div class="show-header">
        <h1>{{ $flashcardSet->title }}</h1>
        <p class="set-description">{{ $flashcardSet->description }}</p>
        
        <div class="set-meta">
            <div class="meta-item">
                <i class="fas fa-layer-group"></i>
                <span>{{ $flashcardSet->flashcards->count() }} cards</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-user"></i>
                <span>Created by {{ $flashcardSet->user->username }}</span>
            </div>
            @if($flashcardSet->is_public)
            <div class="meta-item">
                <i class="fas fa-globe"></i>
                <span>Public</span>
            </div>
            @endif
        </div>

        @if($flashcardSet->user_id == auth()->id())
        <div class="action-buttons">
            <a href="{{ route('flashcards.edit', $flashcardSet->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Set
            </a>
            <form action="{{ route('flashcards.destroy', $flashcardSet->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline" onclick="return confirm('Are you sure you want to delete this flashcard set?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
            <button class="btn btn-outline" onclick="shareSet()">
                <i class="fas fa-share"></i> Share
            </button>
        </div>
        @endif
    </div>

    @if($flashcardSet->flashcards->count() > 0)
    <div class="study-mode-container">
        <div class="study-mode-header">
            <h2>Study Mode</h2>
            <p>Click the card to flip it and reveal the answer</p>
        </div>

        <div class="flashcard-display">
            <div class="flashcard" id="studyFlashcard" onclick="flipCard()">
                <div class="flashcard-front">
                    <div class="flashcard-content" id="cardFront">
                        {{ $flashcardSet->flashcards[0]->question }}
                    </div>
                </div>
                <div class="flashcard-back">
                    <div class="flashcard-content" id="cardBack">
                        {{ $flashcardSet->flashcards[0]->answer }}
                    </div>
                </div>
            </div>
        </div>

        <div class="study-controls">
            <div class="navigation-buttons">
                <button class="btn btn-outline" onclick="prevCard()" id="prevBtn" disabled>
                    <i class="fas fa-arrow-left"></i> Previous
                </button>
                <button class="btn btn-outline" onclick="nextCard()" id="nextBtn">
                    Next <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <div class="progress-indicator" id="progressIndicator">
            Card 1 of {{ $flashcardSet->flashcards->count() }}
        </div>
    </div>

    <div class="flashcards-list">
        <h3>All Flashcards in This Set</h3>
        @foreach($flashcardSet->flashcards as $index => $flashcard)
        <div class="flashcard-item">
            <div class="flashcard-question">
                <strong>Q:</strong> {{ $flashcard->question }}
            </div>
            <div class="flashcard-answer">
                <strong>A:</strong> {{ $flashcard->answer }}
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3>No flashcards in this set yet</h3>
        <p>Add some flashcards to start studying</p>
        @if($flashcardSet->user_id == auth()->id())
        <a href="{{ route('flashcards.edit', $flashcardSet->id) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Flashcards
        </a>
        @endif
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    let currentCardIndex = 0;
    const flashcards = @json($flashcardSet->flashcards);
    const totalCards = flashcards.length;

    function flipCard() {
        const card = document.getElementById('studyFlashcard');
        card.classList.toggle('flipped');
    }

    function showCard(index) {
        const card = document.getElementById('studyFlashcard');
        const frontContent = document.getElementById('cardFront');
        const backContent = document.getElementById('cardBack');
        const progressIndicator = document.getElementById('progressIndicator');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        // Reset card to front position
        card.classList.remove('flipped');

        // Update card content
        frontContent.textContent = flashcards[index].question;
        backContent.textContent = flashcards[index].answer;

        // Update progress indicator
        progressIndicator.textContent = `Card ${index + 1} of ${totalCards}`;

        // Update button states
        prevBtn.disabled = index === 0;
        nextBtn.disabled = index === totalCards - 1;
    }

    function nextCard() {
        if (currentCardIndex < totalCards - 1) {
            currentCardIndex++;
            showCard(currentCardIndex);
        }
    }

    function prevCard() {
        if (currentCardIndex > 0) {
            currentCardIndex--;
            showCard(currentCardIndex);
        }
    }

    function shareSet() {
        // Simple implementation - you can enhance this with actual sharing functionality
        const shareUrl = window.location.href;
        navigator.clipboard.writeText(shareUrl).then(() => {
            alert('Link copied to clipboard!');
        }).catch(() => {
            prompt('Copy this link to share:', shareUrl);
        });
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'ArrowRight') {
            nextCard();
        } else if (event.key === 'ArrowLeft') {
            prevCard();
        } else if (event.key === ' ') {
            event.preventDefault();
            flipCard();
        }
    });
</script>
@endsection
@extends('layouts.app')

@section('title', 'My Flashcards - Study Buddy')

@section('content')
<div class="container">
    <div class="header">
        <h1>My Flashcards</h1>
        <a href="{{ route('flashcards.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Set
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="flashcards-grid">
        @forelse($flashcardSets as $set)
            <div class="flashcard-set">
                <div class="set-header">
                    <h3>{{ $set->title }}</h3>
                    <span class="card-count">{{ $set->flashcards->count() }} cards</span>
                </div>
                <p class="set-description">{{ Str::limit($set->description, 100) }}</p>
                
                <div class="set-actions">
                    <a href="{{ route('flashcards.show', $set->id) }}" class="btn btn-sm">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('flashcards.edit', $set->id) }}" class="btn btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('flashcards.destroy', $set->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-layer-group"></i>
                <h3>No flashcard sets yet</h3>
                <p>Create your first flashcard set to get started!</p>
                <a href="{{ route('flashcards.create') }}" class="btn btn-primary">
                    Create Flashcard Set
                </a>
            </div>
        @endforelse
    </div>
    
    @if($flashcardSets->count())
        <div class="pagination">
            {{ $flashcardSets->links() }}
        </div>
    @endif
</div>

<style>
.flashcards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.flashcard-set {
    background: rgba(20, 40, 60, 0.5);
    border: 1px solid rgba(57, 183, 255, 0.1);
    border-radius: 12px;
    padding: 20px;
    transition: transform 0.3s ease;
}

.flashcard-set:hover {
    transform: translateY(-5px);
}

.set-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
}

.set-header h3 {
    margin: 0;
    color: #dffbff;
    font-size: 1.2rem;
}

.card-count {
    background: rgba(57, 183, 255, 0.2);
    color: #2dc2ff;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.set-description {
    color: #a4d8e8;
    margin-bottom: 20px;
    font-size: 0.9rem;
}

.set-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    grid-column: 1 / -1;
}

.empty-state i {
    font-size: 4rem;
    color: rgba(57, 183, 255, 0.3);
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #dffbff;
    margin-bottom: 10px;
}

.empty-state p {
    color: #a4d8e8;
    margin-bottom: 30px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}
</style>
@endsection
@extends('layouts.app')

@section('title', 'My Quizzes')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>My Quizzes</h1>
        <div class="dashboard-actions">
            <a href="{{ route('flashcards.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Flashcards
            </a>
        </div>
    </div>

    <div class="quizzes-grid">
        @foreach($quizzes as $quiz)
        <div class="quiz-card">
            <div class="quiz-header">
                <h3>{{ $quiz->title }}</h3>
                <span class="quiz-badge">{{ $quiz->questions->count() }} questions</span>
            </div>
            <div class="quiz-content">
                <p>{{ $quiz->description }}</p>
                <div class="quiz-meta">
                    <span><i class="fas fa-clock"></i> {{ $quiz->time_limit ?? 'No' }} time limit</span>
                    <span><i class="fas fa-shuffle"></i> {{ $quiz->shuffle_questions ? 'Shuffled' : 'In order' }}</span>
                </div>
            </div>
            <div class="quiz-actions">
                <a href="{{ route('quizzes.show', $quiz->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye"></i> View
                </a>
                <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-outline">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{ $quizzes->links() }}
</div>
@endsection

@section('styles')
<style>
.quizzes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.dashboard-header{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.quiz-card {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease;
}

.quiz-card:hover {
    transform: translateY(-5px);
}

.quiz-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.quiz-header h3 {
    color: #dffbff;
    margin: 0;
    font-size: 1.2rem;
}

.quiz-badge {
    background: linear-gradient(135deg, #2dc2ff 0%, #78f7d1 100%);
    color: #0a1929;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.quiz-content p {
    color: #a4d8e8;
    margin-bottom: 15px;
}

.quiz-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
}

.quiz-meta span {
    color: #78f7d1;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.quiz-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
</style>
@endsection
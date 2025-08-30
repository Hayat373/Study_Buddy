@extends('layouts.app')

@section('title', 'Create Discussion - ' . $studyGroup->name)

@section('styles')
<style>
.discussion-create-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.create-header {
    margin-bottom: 30px;
}

.create-header h1 {
    color: #dffbff;
    margin-bottom: 10px;
}

.create-header p {
    color: #a4d8e8;
}

.discussion-form {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 25px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #dffbff;
    font-weight: 500;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    background: rgba(15, 30, 45, 0.5);
    color: #dffbff;
    font-size: 14px;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2dc2ff;
}

.form-group textarea {
    min-height: 200px;
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endsection

@section('content')
<div class="discussion-create-container">
    <div class="create-header">
        <h1>Create New Discussion</h1>
        <p>Share your thoughts, questions, or ideas with the study group.</p>
    </div>

    <div class="discussion-form">
        <form action="{{ route('study-groups.discussions.store', $studyGroup->id) }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="title">Discussion Title *</label>
                <input type="text" id="title" name="title" required 
                       placeholder="Enter a clear and descriptive title"
                       value="{{ old('title') }}">
                @error('title')
                    <span style="color: #ff6b6b; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="content">Discussion Content *</label>
                <textarea id="content" name="content" required 
                          placeholder="Share your thoughts, questions, or ideas...">{{ old('content') }}</textarea>
                @error('content')
                    <span style="color: #ff6b6b; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Discussion</button>
                <a href="{{ route('study-groups.discussions.index', $studyGroup->id) }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
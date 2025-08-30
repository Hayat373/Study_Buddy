@extends('layouts.app')

@section('title', 'Create Study Group - Study Buddy')

@section('styles')
<style>
.create-group-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.create-header {
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #dffbff;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    font-size: 14px;
    background: rgba(15, 30, 45, 0.5);
    color: #dffbff;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.form-group small {
    color: #a4d8e8;
    font-size: 12px;
    display: block;
    margin-top: 4px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    font-weight: normal;
    color: #dffbff;
}

.checkbox-label input {
    width: auto;
    margin-right: 8px;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endsection

@section('content')
<div class="create-group-container">
    <div class="create-header">
        <h1>Create New Study Group</h1>
        <p>Bring students together to collaborate and study more effectively.</p>
    </div>
    
    <form action="{{ route('study-groups.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">Group Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter a descriptive name for your group">
            @error('name')
                <small style="color: #ff6b6b;">{{ $message }}</small>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="What will this group focus on? What are the goals?">{{ old('description') }}</textarea>
            @error('description')
                <small style="color: #ff6b6b;">{{ $message }}</small>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="e.g., Mathematics, Biology, History, etc.">
            @error('subject')
                <small style="color: #ff6b6b;">{{ $message }}</small>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="max_members">Maximum Members</label>
            <input type="number" id="max_members" name="max_members" value="{{ old('max_members', 10) }}" min="2" max="50" required>
            @error('max_members')
                <small style="color: #ff6b6b;">{{ $message }}</small>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}> 
                Make this group public
            </label>
            <small>Public groups can be discovered by all users. Private groups require an invitation or join code.</small>
            @error('is_public')
                <small style="color: #ff6b6b;">{{ $message }}</small>
            @enderror
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Group</button>
            <a href="{{ route('study-groups.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
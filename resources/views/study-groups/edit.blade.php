@extends('layouts.app')

@section('title', 'Edit ' . $studyGroup->name . ' - Study Buddy')

@section('styles')
<style>
.edit-group-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.edit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
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
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
}

.delete-form {
    display: inline;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
}
</style>
@endsection

@section('content')
<div class="edit-group-container">
    <div class="edit-header">
        <h1>Edit Study Group</h1>
        <a href="{{ route('study-groups.show', $studyGroup->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Group
        </a>
    </div>
    
    <form action="{{ route('study-groups.update', $studyGroup->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Group Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $studyGroup->name) }}" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description', $studyGroup->description) }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" value="{{ old('subject', $studyGroup->subject) }}" placeholder="e.g., Mathematics, Biology, etc.">
        </div>
        
        <div class="form-group">
            <label for="max_members">Maximum Members</label>
            <input type="number" id="max_members" name="max_members" value="{{ old('max_members', $studyGroup->max_members) }}" min="2" max="50" required>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public', $studyGroup->is_public) ? 'checked' : '' }}> 
                Make this group public
            </label>
            <small>Public groups can be discovered by all users. Private groups require an invitation or join code.</small>
        </div>
        
        <div class="form-actions">
            <div>
                <button type="submit" class="btn btn-primary">Update Group</button>
                <a href="{{ route('study-groups.show', $studyGroup->id) }}" class="btn btn-secondary">Cancel</a>
            </div>
            
            <form action="{{ route('study-groups.destroy', $studyGroup->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete()" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Group
                </button>
            </form>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this study group? This action cannot be undone.')) {
        event.target.closest('form').submit();
    }
}
</script>
@endsection
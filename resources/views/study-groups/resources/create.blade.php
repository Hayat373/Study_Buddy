@extends('layouts.app')

@section('title', 'Upload Resource - ' . $studyGroup->name)

@section('styles')
<style>
.resource-create-container {
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

.resource-form {
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
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    background: rgba(15, 30, 45, 0.5);
    color: #dffbff;
    font-size: 14px;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #2dc2ff;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.file-input-container {
    position: relative;
}

.file-input {
    padding: 40px 20px;
    border: 2px dashed rgba(57, 183, 255, 0.3);
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.file-input:hover {
    border-color: #2dc2ff;
}

.file-input i {
    font-size: 48px;
    color: #2dc2ff;
    margin-bottom: 10px;
}

.file-input-text {
    color: #a4d8e8;
}

.file-input input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
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
<div class="resource-create-container">
    <div class="create-header">
        <h1>Upload Resource to {{ $studyGroup->name }}</h1>
        <p>Share study materials, documents, or other resources with the group.</p>
    </div>

    <div class="resource-form">
        <form action="{{ route('study-groups.resources.store', $studyGroup->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="title">Resource Title *</label>
                <input type="text" id="title" name="title" required 
                       placeholder="Enter a descriptive title for your resource"
                       value="{{ old('title') }}">
                @error('title')
                    <span style="color: #ff6b6b; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" 
                          placeholder="Describe what this resource is about...">{{ old('description') }}</textarea>
                @error('description')
                    <span style="color: #ff6b6b; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="file">File *</label>
                <div class="file-input-container">
                    <div class="file-input">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <div class="file-input-text">
                            <p>Click to upload or drag and drop</p>
                            <p>Maximum file size: 10MB</p>
                        </div>
                        <input type="file" id="file" name="file" required accept="*/*">
                    </div>
                </div>
                @error('file')
                    <span style="color: #ff6b6b; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Upload Resource</button>
                <a href="{{ route('study-groups.resources.index', $studyGroup->id) }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('file').addEventListener('change', function(e) {
    const fileInput = e.target;
    const fileName = fileInput.files[0]?.name;
    if (fileName) {
        document.querySelector('.file-input-text p:first-child').textContent = `Selected: ${fileName}`;
    }
});
</script>
@endsection
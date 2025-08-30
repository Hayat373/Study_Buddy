@extends('layouts.app')

@section('title', 'Resources - ' . $studyGroup->name)

@section('styles')
<style>
.resources-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    min-height: 80vh;
}

.resources-header {
    text-align: center;
    margin-bottom: 40px;
}

.resources-header h1 {
    color: #dffbff;
    margin-bottom: 10px;
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #2dc2ff 0%, #78f7d1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.resources-header p {
    color: #a4d8e8;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

.resources-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
    margin: 0 auto;
    justify-content: center;
}

.resource-card {
    background: linear-gradient(135deg, rgba(20, 40, 60, 0.8), rgba(15, 30, 45, 0.9));
    border-radius: 20px;
    padding: 25px;
    border: 1px solid rgba(57, 183, 255, 0.15);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.resource-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2dc2ff, #78f7d1);
    border-radius: 20px 20px 0 0;
}

.resource-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    border-color: rgba(57, 183, 255, 0.3);
}

.resource-icon {
    text-align: center;
    margin-bottom: 20px;
}

.resource-icon i {
    font-size: 3.5rem;
    color: #2dc2ff;
    background: linear-gradient(135deg, rgba(45, 194, 255, 0.1), rgba(120, 247, 209, 0.1));
    padding: 20px;
    border-radius: 50%;
}

.resource-content {
    flex: 1;
    text-align: center;
}

.resource-title {
    color: #dffbff;
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 15px;
    line-height: 1.4;
}

.resource-description {
    color: #a4d8e8;
    margin-bottom: 20px;
    line-height: 1.5;
}

.resource-meta {
    background: rgba(15, 30, 45, 0.6);
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
}

.meta-item:last-child {
    border-bottom: none;
}

.meta-label {
    color: #a4d8e8;
    font-size: 0.9rem;
}

.meta-value {
    color: #dffbff;
    font-weight: 500;
    font-size: 0.9rem;
}

.resource-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.btn-download {
    background: linear-gradient(135deg, #2dc2ff, #78f7d1);
    color: #0a1929;
    border: none;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-download:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(45, 194, 255, 0.4);
}

.btn-danger {
    background: rgba(244, 67, 54, 0.9);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 12px;
    transition: all 0.3s ease;
    width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-danger:hover {
    background: rgba(244, 67, 54, 1);
    transform: translateY(-2px);
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #a4d8e8;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.7;
    color: #2dc2ff;
}

.empty-state h3 {
    color: #dffbff;
    margin-bottom: 15px;
    font-size: 1.5rem;
}

.empty-state p {
    margin-bottom: 25px;
    font-size: 1.1rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 15px 30px;
    background: linear-gradient(135deg, #2dc2ff, #78f7d1);
    color: #0a1929;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.upload-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(45, 194, 255, 0.4);
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

/* File type specific colors */
.file-pdf { color: #ff5252 !important; }
.file-word { color: #448aff !important; }
.file-excel { color: #4caf50 !important; }
.file-powerpoint { color: #ff9100 !important; }
.file-image { color: #ff4081 !important; }
.file-zip { color: #ffc107 !important; }
.file-audio { color: #7c4dff !important; }
.file-video { color: #ff1744 !important; }

@media (max-width: 768px) {
    .resources-grid {
        grid-template-columns: 1fr;
        max-width: 500px;
    }
    
    .resource-card {
        padding: 20px;
    }
    
    .resource-actions {
        flex-direction: column;
    }
    
    .resources-header h1 {
        font-size: 2rem;
    }
}

@media (max-width: 480px) {
    .resources-container {
        padding: 15px;
    }
    
    .resource-card {
        padding: 15px;
    }
    
    .resource-icon i {
        font-size: 2.5rem;
        padding: 15px;
    }
}
</style>
@endsection

@section('content')
<div class="resources-container">
    <div class="resources-header">
        <h1>Study Resources</h1>
        <p>Shared materials and documents for {{ $studyGroup->name }}</p>
    </div>

    <div class="resources-grid">
        @forelse($resources as $resource)
        <div class="resource-card">
            <div class="resource-icon">
                @if(str_contains($resource->file_type, 'pdf'))
                <i class="fas fa-file-pdf file-pdf"></i>
                @elseif(str_contains($resource->file_type, 'word') || str_contains($resource->file_type, 'document'))
                <i class="fas fa-file-word file-word"></i>
                @elseif(str_contains($resource->file_type, 'excel') || str_contains($resource->file_type, 'sheet'))
                <i class="fas fa-file-excel file-excel"></i>
                @elseif(str_contains($resource->file_type, 'powerpoint') || str_contains($resource->file_type, 'presentation'))
                <i class="fas fa-file-powerpoint file-powerpoint"></i>
                @elseif(str_contains($resource->file_type, 'image'))
                <i class="fas fa-file-image file-image"></i>
                @elseif(str_contains($resource->file_type, 'zip') || str_contains($resource->file_type, 'compressed'))
                <i class="fas fa-file-archive file-zip"></i>
                @elseif(str_contains($resource->file_type, 'audio'))
                <i class="fas fa-file-audio file-audio"></i>
                @elseif(str_contains($resource->file_type, 'video'))
                <i class="fas fa-file-video file-video"></i>
                @else
                <i class="fas fa-file" style="color: #2dc2ff;"></i>
                @endif
            </div>

            <div class="resource-content">
                <h3 class="resource-title">{{ $resource->title }}</h3>
                
                @if($resource->description)
                <p class="resource-description">{{ $resource->description }}</p>
                @endif

                <div class="resource-meta">
                    <div class="meta-item">
                        <span class="meta-label">File Name:</span>
                        <span class="meta-value">{{ $resource->file_name }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Type:</span>
                        <span class="meta-value">{{ $resource->file_type }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Size:</span>
                        <span class="meta-value">{{ $resource->formatted_size }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Downloads:</span>
                        <span class="meta-value">{{ $resource->download_count }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Uploaded by:</span>
                        <span class="meta-value">{{ $resource->user->username }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Uploaded:</span>
                        <span class="meta-value">{{ $resource->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <div class="resource-actions">
                <a href="{{ route('study-groups.resources.download', [$studyGroup->id, $resource->id]) }}" 
                   class="btn-download">
                    <i class="fas fa-download"></i>
                    Download
                </a>
                
                @if($resource->user_id === Auth::id() || $studyGroup->isAdmin(Auth::id()))
                <form action="{{ route('study-groups.resources.destroy', [$studyGroup->id, $resource->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" 
                            onclick="return confirm('Are you sure you want to delete this resource?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <h3>No Resources Yet</h3>
            <p>This study group doesn't have any shared resources yet. Be the first to upload study materials!</p>
            <a href="{{ route('study-groups.resources.create', $studyGroup->id) }}" class="upload-btn">
                <i class="fas fa-upload"></i>
                Upload Resource
            </a>
        </div>
        @endforelse
    </div>

    @if($resources->hasPages())
    <div class="pagination">
        {{ $resources->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
// Add some interactive effects
document.querySelectorAll('.resource-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-8px)';
        card.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.3)';
    });
    
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0)';
        card.style.boxShadow = 'none';
    });
});

// Add loading animation for download buttons
document.querySelectorAll('.btn-download').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
        this.style.opacity = '0.8';
        
        setTimeout(() => {
            this.innerHTML = originalText;
            this.style.opacity = '1';
        }, 2000);
    });
});
</script>
@endsection
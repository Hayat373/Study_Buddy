@extends('layouts.app')

@section('title', 'Resources - ' . $studyGroup->name)

@section('content')
<div class="resources-container">
    <div class="resources-header">
        <h1>Resources - {{ $studyGroup->name }}</h1>
        <a href="{{ route('study-groups.resources.create', $studyGroup->id) }}" class="btn btn-primary">
            <i class="fas fa-upload"></i> Upload Resource
        </a>
    </div>

    <div class="resources-grid">
        @foreach($resources as $resource)
        <div class="resource-card">
            <div class="resource-icon">
                @if(str_contains($resource->file_type, 'image'))
                <i class="fas fa-file-image fa-3x"></i>
                @elseif(str_contains($resource->file_type, 'pdf'))
                <i class="fas fa-file-pdf fa-3x"></i>
                @elseif(str_contains($resource->file_type, 'word'))
                <i class="fas fa-file-word fa-3x"></i>
                @else
                <i class="fas fa-file fa-3x"></i>
                @endif
            </div>
            
            <div class="resource-details">
                <h3>{{ $resource->title }}</h3>
                <p class="resource-description">{{ Str::limit($resource->description, 100) }}</p>
                
                <div class="resource-meta">
                    <span class="file-name">{{ $resource->file_name }}</span>
                    <span class="file-size">{{ $resource->formatted_size }}</span>
                    <span class="downloads">{{ $resource->download_count }} downloads</span>
                </div>
                
                <div class="resource-actions">
                    <a href="{{ route('study-groups.resources.download', [$studyGroup->id, $resource->id]) }}" 
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-download"></i> Download
                    </a>
                    
                    @if($resource->user_id === Auth::id() || $studyGroup->isAdmin(Auth::id()))
                    <form action="{{ route('study-groups.resources.destroy', [$studyGroup->id, $resource->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Delete this resource?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination">
        {{ $resources->links() }}
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Discussions - ' . $studyGroup->name)

@section('styles')
<style>
.discussions-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.discussions-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.discussions-header h1 {
    color: #dffbff;
    margin: 0;
}

.discussions-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.discussion-card {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
    position: relative;
}

.discussion-card.pinned {
    border-left: 4px solid #ffc107;
}

.pinned-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.discussion-content h3 {
    margin: 0 0 10px 0;
}

.discussion-content h3 a {
    color: #dffbff;
    text-decoration: none;
}

.discussion-content h3 a:hover {
    color: #2dc2ff;
}

.discussion-excerpt {
    color: #a4d8e8;
    margin-bottom: 15px;
    line-height: 1.5;
}

.discussion-meta {
    display: flex;
    gap: 15px;
    color: #a4d8e8;
    font-size: 14px;
    flex-wrap: wrap;
}

.discussion-actions {
    margin-top: 15px;
    display: flex;
    gap: 10px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #a4d8e8;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.7;
}

.pagination {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .discussions-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .discussion-meta {
        flex-direction: column;
        gap: 5px;
    }
    
    .discussion-actions {
        flex-direction: column;
    }
}
</style>
@endsection

@section('content')
<div class="discussions-container">
    <div class="discussions-header">
        <h1>Discussions - {{ $studyGroup->name }}</h1>
        <a href="{{ route('study-groups.discussions.create', $studyGroup->id) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Discussion
        </a>
    </div>

    <div class="discussions-list">
        @forelse($discussions as $discussion)
        <div class="discussion-card {{ $discussion->is_pinned ? 'pinned' : '' }}">
            @if($discussion->is_pinned)
            <div class="pinned-badge">
                <i class="fas fa-thumbtack"></i> Pinned
            </div>
            @endif
            
            <div class="discussion-content">
                <h3>
                    <a href="{{ route('study-groups.discussions.show', [$studyGroup->id, $discussion->id]) }}">
                        {{ $discussion->title }}
                    </a>
                </h3>
                <p class="discussion-excerpt">{{ Str::limit(strip_tags($discussion->content), 200) }}</p>
                
                <div class="discussion-meta">
                    <span class="author">By {{ $discussion->user->username }}</span>
                    <span class="date">{{ $discussion->created_at->diffForHumans() }}</span>
                    <span class="replies">{{ $discussion->replies->count() }} replies</span>
                </div>
            </div>
            
            <div class="discussion-actions">
                <a href="{{ route('study-groups.discussions.show', [$studyGroup->id, $discussion->id]) }}" 
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-comments"></i> View Discussion
                </a>
                
                @if($studyGroup->isAdmin(Auth::id()))
                <form action="{{ route('study-groups.discussions.pin', [$studyGroup->id, $discussion->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $discussion->is_pinned ? 'btn-warning' : 'btn-outline' }}">
                        <i class="fas fa-thumbtack"></i> {{ $discussion->is_pinned ? 'Unpin' : 'Pin' }}
                    </button>
                </form>
                @endif
                
                @if($discussion->user_id === Auth::id() || $studyGroup->isAdmin(Auth::id()))
                <form action="{{ route('study-groups.discussions.destroy', [$studyGroup->id, $discussion->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Delete this discussion?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-comments"></i>
            <h3>No discussions yet</h3>
            <p>Be the first to start a discussion in this study group!</p>
            <a href="{{ route('study-groups.discussions.create', $studyGroup->id) }}" class="btn btn-primary">
                Start Discussion
            </a>
        </div>
        @endforelse
    </div>

    @if($discussions->hasPages())
    <div class="pagination">
        {{ $discussions->links() }}
    </div>
    @endif
</div>
@endsection
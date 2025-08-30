@extends('layouts.app')

@section('title', $discussion->title . ' - ' . $studyGroup->name)

@section('styles')
<style>
.discussion-show-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.discussion-header {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 25px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    margin-bottom: 25px;
    backdrop-filter: blur(10px);
    position: relative;
}

.discussion-title {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 15px;
}

.discussion-title h1 {
    color: #dffbff;
    margin: 0;
    flex: 1;
}

.discussion-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    color: #a4d8e8;
    font-size: 14px;
}

.discussion-content {
    color: #dffbff;
    line-height: 1.6;
    margin-bottom: 20px;
}

.discussion-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.pinned-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.replies-section {
    margin-top: 30px;
}

.replies-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.reply-form {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    margin-bottom: 25px;
    backdrop-filter: blur(10px);
}

.reply-form textarea {
    width: 100%;
    min-height: 100px;
    padding: 12px 15px;
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    background: rgba(15, 30, 45, 0.5);
    color: #dffbff;
    resize: vertical;
}

.replies-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.reply-item {
    background: rgba(20, 40, 60, 0.3);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(57, 183, 255, 0.1);
}

.reply-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap;
    gap: 10px;
}

.reply-author {
    color: #dffbff;
    font-weight: 500;
}

.reply-time {
    color: #a4d8e8;
    font-size: 12px;
}

.reply-content {
    color: #dffbff;
    line-height: 1.5;
    margin-bottom: 15px;
}

.reply-actions {
    display: flex;
    gap: 15px;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #a4d8e8;
}

@media (max-width: 768px) {
    .discussion-title {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .discussion-actions,
    .reply-actions {
        flex-direction: column;
    }
    
    .replies-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endsection

@section('content')
<div class="discussion-show-container">
    <div class="discussion-header">
        @if($discussion->is_pinned)
        <div class="pinned-badge">
            <i class="fas fa-thumbtack"></i> Pinned
        </div>
        @endif
        
        <div class="discussion-title">
            <h1>{{ $discussion->title }}</h1>
            <div class="discussion-actions">
                @if($studyGroup->isAdmin(Auth::id()))
                <form action="{{ route('study-groups.discussions.pin', [$studyGroup->id, $discussion->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $discussion->is_pinned ? 'btn-warning' : 'btn-secondary' }}">
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
        
        <div class="discussion-meta">
            <span>By {{ $discussion->user->username }}</span>
            <span>{{ $discussion->created_at->format('M d, Y \a\t H:i') }}</span>
            <span>{{ $discussion->allReplies->count() }} replies</span>
        </div>
        
        <div class="discussion-content">
            {!! nl2br(e($discussion->content)) !!}
        </div>
    </div>

    <div class="replies-section">
        <div class="replies-header">
            <h2>Replies ({{ $discussion->allReplies->count() }})</h2>
        </div>

        <div class="reply-form">
            <form action="{{ route('study-groups.discussions.replies.store', [$studyGroup->id, $discussion->id]) }}" method="POST">
                @csrf
                <textarea name="content" placeholder="Write your reply..." required></textarea>
                <div class="form-actions" style="margin-top: 15px;">
                    <button type="submit" class="btn btn-primary">Post Reply</button>
                </div>
            </form>
        </div>

        <div class="replies-list">
            @forelse($discussion->replies as $reply)
            <div class="reply-item">
                <div class="reply-header">
                    <span class="reply-author">{{ $reply->user->username }}</span>
                    <span class="reply-time">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="reply-content">
                    {!! nl2br(e($reply->content)) !!}
                </div>
                
                @if($reply->user_id === Auth::id() || $studyGroup->isAdmin(Auth::id()))
                <div class="reply-actions">
                    <form action="{{ route('study-groups.discussions.replies.destroy', [$studyGroup->id, $discussion->id, $reply->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Delete this reply?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div class="empty-state">
                <p>No replies yet. Be the first to reply!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
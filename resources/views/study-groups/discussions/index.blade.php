@extends('layouts.app')

@section('title', 'Discussions - ' . $studyGroup->name)

@section('content')
<div class="discussions-container">
    <div class="discussions-header">
        <h1>Discussions - {{ $studyGroup->name }}</h1>
        <a href="{{ route('study-groups.discussions.create', $studyGroup->id) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Discussion
        </a>
    </div>

    <div class="discussions-list">
        @foreach($discussions as $discussion)
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
                @if($studyGroup->isAdmin(Auth::id()))
                <form action="{{ route('study-groups.discussions.pin', [$studyGroup->id, $discussion->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $discussion->is_pinned ? 'btn-warning' : 'btn-secondary' }}">
                        <i class="fas fa-thumbtack"></i> {{ $discussion->is_pinned ? 'Unpin' : 'Pin' }}
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination">
        {{ $discussions->links() }}
    </div>
</div>
@endsection
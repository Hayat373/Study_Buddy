@extends('layouts.app')

@section('title', 'Chat - Study Buddy')

@section('styles')
<style>
/* Chat Styles */
.chat-container {
    display: flex;
    height: calc(100vh - 180px);
    background: rgba(15, 30, 45, 0.8);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(57, 183, 255, 0.1);
}

.chat-sidebar {
    width: 320px;
    border-right: 1px solid rgba(57, 183, 255, 0.1);
    background: rgba(10, 25, 41, 0.7);
    display: flex;
    flex-direction: column;
}

.chat-header {
    padding: 20px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    background: rgba(15, 30, 45, 0.8);
}

.chat-search {
    position: relative;
    margin-bottom: 15px;
}

.chat-search input {
    width: 100%;
    padding: 12px 15px 12px 45px;
    background: rgba(15, 30, 45, 0.6);
    border: 1px solid rgba(57, 183, 255, 0.1);
    border-radius: 12px;
    color: #dffbff;
    font-size: 14px;
    transition: all 0.3s ease;
}

.chat-search input:focus {
    outline: none;
    border-color: rgba(57, 183, 255, 0.4);
    box-shadow: 0 0 0 3px rgba(57, 183, 255, 0.1);
}

.chat-search i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(57, 183, 255, 0.6);
}

.chat-list {
    flex: 1;
    overflow-y: auto;
}

.chat-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    cursor: pointer;
    transition: background 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.chat-item:hover {
    background: rgba(57, 183, 255, 0.1);
}

.chat-item.active {
    background: rgba(57, 183, 255, 0.15);
}

.chat-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #39b7ff 0%, #2dc2ff 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 18px;
    margin-right: 15px;
    flex-shrink: 0;
}

.chat-info {
    flex: 1;
    min-width: 0;
}

.chat-name {
    font-weight: 600;
    color: #dffbff;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-preview {
    font-size: 14px;
    color: rgba(223, 251, 255, 0.7);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-meta {
    text-align: right;
    margin-left: 10px;
}

.chat-time {
    font-size: 12px;
    color: rgba(57, 183, 255, 0.7);
    margin-bottom: 5px;
}

.chat-badge {
    display: inline-block;
    padding: 4px 8px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
    color: white;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
}

.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.chat-conversation {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: rgba(10, 25, 41, 0.5);
}

.chat-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: rgba(57, 183, 255, 0.6);
}

.chat-empty i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.7;
}

.chat-empty p {
    font-size: 16px;
    opacity: 0.8;
}

.message {
    margin-bottom: 20px;
    display: flex;
}

.message.sent {
    justify-content: flex-end;
}

.message.received {
    justify-content: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 18px;
    position: relative;
}

.message.sent .message-content {
    background: linear-gradient(135deg, #39b7ff 0%, #2dc2ff 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.message.received .message-content {
    background: rgba(15, 30, 45, 0.6);
    color: #dffbff;
    border-bottom-left-radius: 4px;
    border: 1px solid rgba(57, 183, 255, 0.1);
}

.message-time {
    font-size: 11px;
    margin-top: 5px;
    opacity: 0.8;
}

.chat-input-container {
    padding: 20px;
    border-top: 1px solid rgba(57, 183, 255, 0.1);
    background: rgba(15, 30, 45, 0.8);
}

.chat-input-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.chat-input {
    flex: 1;
    padding: 12px 16px;
    background: rgba(10, 25, 41, 0.6);
    border: 1px solid rgba(57, 183, 255, 0.1);
    border-radius: 24px;
    resize: none;
    font-family: inherit;
    font-size: 14px;
    line-height: 1.4;
    color: #dffbff;
    max-height: 120px;
    transition: border-color 0.3s ease;
}

.chat-input:focus {
    outline: none;
    border-color: rgba(57, 183, 255, 0.4);
}

.send-button {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #39b7ff 0%, #2dc2ff 100%);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.send-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(57, 183, 255, 0.3);
}

.send-button:disabled {
    background: rgba(57, 183, 255, 0.3);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Responsive */
@media (max-width: 768px) {
    .chat-container {
        flex-direction: column;
        height: calc(100vh - 140px);
    }
    
    .chat-sidebar {
        width: 100%;
        height: 40%;
        border-right: none;
        border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    }
    
    .chat-main {
        height: 60%;
    }
    
    .message-content {
        max-width: 85%;
    }
}
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">Chat</h1>
    <p class="dashboard-subtitle">Connect with other students</p>
</div>

<div class="chat-container">
    <div class="chat-sidebar">
        <div class="chat-header">
            <div class="chat-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search conversations...">
            </div>
        </div>
        <div class="chat-list">
            @forelse($chats as $chat)
            <a href="{{ route('chat.show', $chat) }}" class="chat-item">
                <div class="chat-avatar">
                    {{ substr($chat->other_user->username, 0, 1) }}
                </div>
                <div class="chat-info">
                    <div class="chat-name">{{ $chat->other_user->username }}</div>
                    <div class="chat-preview">
                        @if($chat->messages->count() > 0)
                            {{ Str::limit($chat->messages->first()->message, 30) }}
                        @else
                            Start a conversation
                        @endif
                    </div>
                </div>
                <div class="chat-meta">
                    @if($chat->messages->count() > 0)
                    <div class="chat-time">
                        {{ $chat->messages->first()->created_at->diffForHumans() }}
                    </div>
                    @endif
                    @if($chat->unread_count > 0)
                    <span class="chat-badge">{{ $chat->unread_count }}</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="chat-empty">
                <i class="fas fa-comments"></i>
                <p>No conversations yet</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <div class="chat-main">
        <div class="chat-empty">
            <i class="fas fa-comment-dots"></i>
            <p>Select a conversation to start chatting</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatItems = document.querySelectorAll('.chat-item');
    
    chatItems.forEach(item => {
        item.addEventListener('click', function() {
            chatItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
@endsection
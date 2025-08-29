@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->username . ' - Study Buddy')

@section('styles')
<style>
/* Same styles as in index.blade.php */
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

.chat-conversation-header {
    padding: 15px 20px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    background: rgba(15, 30, 45, 0.8);
    display: flex;
    align-items: center;
}

.conversation-user {
    display: flex;
    align-items: center;
}

.conversation-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #39b7ff 0%, #2dc2ff 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-right: 12px;
}

.conversation-name {
    font-weight: 600;
    color: #dffbff;
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
    <div class="header-left">
        <a href="{{ route('chat.index') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="dashboard-title">Chat</h1>
    </div>
    <p class="dashboard-subtitle">Chat with {{ $otherUser->username }}</p>
</div>

<div class="chat-container">
    <div class="chat-sidebar">
        {{-- Replace the chat-header section with this code --}}
<div class="chat-header">
    <div class="user-search-container">
        <i class="fas fa-search user-search-icon"></i>
        <input type="text" class="user-search-input" placeholder="Search users to chat with...">
        <div class="user-search-results" id="userSearchResults"></div>
    </div>
</div>

        <div class="chat-list">
            @forelse($chats as $c)
            <a href="{{ route('chat.show', $c) }}" class="chat-item {{ $c->id == $chat->id ? 'active' : '' }}">
                <div class="chat-avatar">
                    {{ substr($c->other_user->username, 0, 1) }}
                </div>
                <div class="chat-info">
                    <div class="chat-name">{{ $c->other_user->username }}</div>
                    <div class="chat-preview">
                        @if($c->messages->count() > 0)
                            {{ Str::limit($c->messages->first()->message, 30) }}
                        @else
                            Start a conversation
                        @endif
                    </div>
                </div>
                <div class="chat-meta">
                    @if($c->messages->count() > 0)
                    <div class="chat-time">
                        {{ $c->messages->first()->created_at->diffForHumans() }}
                    </div>
                    @endif
                    @if($c->unread_count > 0)
                    <span class="chat-badge">{{ $c->unread_count }}</span>
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
        <div class="chat-conversation-header">
            <div class="conversation-user">
                <div class="conversation-avatar">
                    {{ substr($otherUser->username, 0, 1) }}
                </div>
                <div class="conversation-name">{{ $otherUser->username }}</div>
            </div>
        </div>
        
        <div class="chat-conversation" id="messageContainer">
            @foreach($messages as $message)
            <div class="message {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}">
                <div class="message-content">
                    <div class="message-text">{{ $message->message }}</div>
                    <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="chat-input-container">
            <form id="messageForm">
                @csrf
                <div class="chat-input-wrapper">
                    <textarea 
                        id="messageInput" 
                        class="chat-input" 
                        placeholder="Type your message..." 
                        rows="1"
                    ></textarea>
                    <button type="submit" id="sendButton" class="send-button">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="chatContainer" data-chat-id="{{ $chat->id }}" data-user-id="{{ auth()->id() }}"></div>

<form id="startChatForm" action="{{ route('chat.start') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="user_id" id="startChatUserId">
</form>
@endsection

@section('scripts')
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('chatContainer');
    const messageContainer = document.getElementById('messageContainer');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    
    // User search functionality
    const userSearchInput = document.querySelector('.user-search-input');
    const userSearchResults = document.getElementById('userSearchResults');
    const startChatForm = document.getElementById('startChatForm');
    const startChatUserId = document.getElementById('startChatUserId');
    
    let searchTimeout;
    
    if (!chatContainer) return;
    
    const chatId = chatContainer.dataset.chatId;
    const userId = chatContainer.dataset.userId;
    
    // User search functionality
    if (userSearchInput) {
        userSearchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                userSearchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('chat.users.search') }}?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(users => {
                        if (users.length === 0) {
                            userSearchResults.innerHTML = '<div class="user-result-item">No users found</div>';
                        } else {
                            userSearchResults.innerHTML = users.map(user => `
                                <div class="user-result-item" data-user-id="${user.id}">
                                    <div class="user-result-avatar">${user.username.charAt(0).toUpperCase()}</div>
                                    <div class="user-result-info">
                                        <div class="user-result-name">${user.username}</div>
                                        <div class="user-result-username">${user.email}</div>
                                    </div>
                                </div>
                            `).join('');
                        }
                        userSearchResults.style.display = 'block';
                        
                        // Add click event to user results
                        document.querySelectorAll('.user-result-item[data-user-id]').forEach(item => {
                            item.addEventListener('click', function() {
                                const userId = this.getAttribute('data-user-id');
                                startChatUserId.value = userId;
                                startChatForm.submit();
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Error searching users:', error);
                    });
            }, 300);
        });
        
        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!userSearchInput.contains(e.target) && !userSearchResults.contains(e.target)) {
                userSearchResults.style.display = 'none';
            }
        });
        
        // Keep results visible when clicking on them
        userSearchResults.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Scroll to bottom of chat
    function scrollToBottom() {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
    
    // Format message time
    function formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
    
    // Add message to chat
    function addMessage(message, isOwnMessage = false) {
        const messageElement = document.createElement('div');
        messageElement.className = `message ${isOwnMessage ? 'sent' : 'received'}`;
        
        messageElement.innerHTML = `
            <div class="message-content">
                <div class="message-text">${message.message}</div>
                <div class="message-time">${formatTime(message.created_at)}</div>
            </div>
        `;
        
        messageContainer.appendChild(messageElement);
        scrollToBottom();
    }
    
    // Send message
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        sendButton.disabled = true;
        
        fetch(`/chat/${chatId}/message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            addMessage(data, true);
            messageInput.value = '';
            sendButton.disabled = false;
            messageInput.style.height = 'auto';
        })
        .catch(error => {
            console.error('Error sending message:', error);
            sendButton.disabled = false;
        });
    });
    
    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Initial scroll to bottom
    scrollToBottom();
    
    // Set up polling for new messages (simple implementation)
    setInterval(() => {
        fetch(`/chat/${chatId}`)
            .then(response => response.text())
            .then(html => {
                // This is a simple implementation - in a real app you'd use WebSockets
                // and only update new messages, not reload the whole page
                // For simplicity, we're just reloading the page every 10 seconds
                // window.location.reload();
            });
    }, 10000);
});
</script>
@endsection
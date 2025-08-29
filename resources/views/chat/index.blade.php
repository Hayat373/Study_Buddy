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
    width: 40%; /* Changed from 320px to 40% */
    border-right: 1px solid rgba(57, 183, 255, 0.1);
    background: rgba(10, 25, 41, 0.7);
    display: flex;
    flex-direction: column;
}

.chat-main {
    width: 60%; /* Added width for the chat area */
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

.user-search-container {
    position: relative;
    margin-bottom: 15px;
}

.user-search-input {
    width: 100%;
    padding: 12px 15px 12px 45px;
    background: rgba(15, 30, 45, 0.6);
    border: 1px solid rgba(57, 183, 255, 0.1);
    border-radius: 12px;
    color: #dffbff;
    font-size: 14px;
    transition: all 0.3s ease;
}

.user-search-input:focus {
    outline: none;
    border-color: rgba(57, 183, 255, 0.4);
    box-shadow: 0 0 0 3px rgba(57, 183, 255, 0.1);
}

.user-search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(57, 183, 255, 0.6);
}

.user-search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: rgba(15, 30, 45, 0.95);
    border-radius: 8px;
    border: 1px solid rgba(57, 183, 255, 0.2);
    margin-top: 5px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.user-result-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.user-result-item:hover {
    background: rgba(57, 183, 255, 0.1);
}

.user-result-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #39b7ff 0%, #2dc2ff 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-right: 12px;
    flex-shrink: 0;
}

.user-result-info {
    flex: 1;
}

.user-result-name {
    font-weight: 600;
    color: #dffbff;
    margin-bottom: 2px;
}

.user-result-username {
    font-size: 12px;
    color: rgba(223, 251, 255, 0.7);
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

.start-chat-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    padding: 20px;
    text-align: center;
}

.start-chat-icon {
    font-size: 64px;
    margin-bottom: 20px;
    color: rgba(57, 183, 255, 0.7);
}

.start-chat-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #dffbff;
}

.start-chat-description {
    font-size: 16px;
    margin-bottom: 30px;
    color: rgba(223, 251, 255, 0.8);
    max-width: 400px;
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
/* Notification Styles for Chat */
.notification-dropdown {
    position: absolute;
    top: 70px;
    right: 20px;
    width: 360px;
    background: rgba(15, 30, 45, 0.95);
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(57, 183, 255, 0.2);
    z-index: 1000;
    display: none;
    overflow: hidden;
}

.notification-dropdown.show {
    display: block;
}

.notification-header {
    padding: 20px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h3 {
    color: #dffbff;
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.notification-clear {
    background: transparent;
    border: none;
    color: rgba(57, 183, 255, 0.8);
    font-size: 12px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.notification-clear:hover {
    color: #39b7ff;
}

.notification-list {
    max-height: 400px;
    overflow-y: auto;
}

.notification-item {
    padding: 15px 20px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    cursor: pointer;
    transition: background 0.3s ease;
}

.notification-item:hover {
    background: rgba(57, 183, 255, 0.1);
}

.notification-item.unread {
    background: rgba(57, 183, 255, 0.08);
}

.notification-content {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.notification-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #39b7ff 0%, #2dc2ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
}

.notification-details {
    flex: 1;
}

.notification-message {
    color: #dffbff;
    font-size: 14px;
    margin-bottom: 4px;
    line-height: 1.4;
}

.notification-time {
    color: rgba(57, 183, 255, 0.7);
    font-size: 12px;
}

.notification-empty {
    padding: 40px 20px;
    text-align: center;
    color: rgba(223, 251, 255, 0.6);
}

.notification-empty i {
    font-size: 32px;
    margin-bottom: 10px;
    opacity: 0.7;
}

.notification-empty p {
    margin: 0;
    font-size: 14px;
}

/* Light theme adjustments */
.light-theme .notification-dropdown {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(57, 183, 255, 0.3);
}

.light-theme .notification-header {
    border-bottom: 1px solid rgba(57, 183, 255, 0.2);
}

.light-theme .notification-header h3 {
    color: #2a4d69;
}

.light-theme .notification-item {
    border-bottom: 1px solid rgba(57, 183, 255, 0.2);
}

.light-theme .notification-item:hover {
    background: rgba(57, 183, 255, 0.1);
}

.light-theme .notification-item.unread {
    background: rgba(57, 183, 255, 0.08);
}

.light-theme .notification-message {
    color: #2a4d69;
}

.light-theme .notification-time {
    color: rgba(57, 183, 255, 0.8);
}

/* Responsive */
@media (max-width: 768px) {
    .notification-dropdown {
        position: fixed;
        top: 70px;
        right: 10px;
        left: 10px;
        width: auto;
        max-width: 400px;
        margin: 0 auto;
    }
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
        width: 100%;
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
            <div class="user-search-container">
                <i class="fas fa-search user-search-icon"></i>
                <input type="text" class="user-search-input" placeholder="Search users to chat with...">
                <div class="user-search-results" id="userSearchResults"></div>
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
        <div class="start-chat-container">
            <i class="fas fa-comment-dots start-chat-icon"></i>
            <h2 class="start-chat-title">Start a Conversation</h2>
            <p class="start-chat-description">Search for users above to start chatting with other Study Buddy users.</p>
        </div>
    </div>
</div>
<!-- Notification Dropdown (Chat specific) -->
<div class="notification-dropdown" id="notificationDropdown">
    <div class="notification-header">
        <h3>Chat Notifications</h3>
        <button class="notification-clear" id="markAllAsRead">Mark all as read</button>
    </div>
    <div class="notification-list" id="notificationList">
        <div class="notification-empty">
            <i class="fas fa-bell-slash"></i>
            <p>No new notifications</p>
        </div>
    </div>
</div>

<form id="startChatForm" action="{{ route('chat.start') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="user_id" id="startChatUserId">
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSearchInput = document.querySelector('.user-search-input');
    const userSearchResults = document.getElementById('userSearchResults');
    const startChatForm = document.getElementById('startChatForm');
    const startChatUserId = document.getElementById('startChatUserId');

    // Chat-specific notification functionality
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationBadge = document.getElementById('notificationBadge');
    const markAllAsReadBtn = document.getElementById('markAllAsRead');
    
    let searchTimeout;
    
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
    
    // Toggle notifications dropdown
    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('show');
            loadChatNotifications();
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                notificationDropdown.classList.remove('show');
            }
        });
    }
    
    // Mark all notifications as read
    if (markAllAsReadBtn) {
        markAllAsReadBtn.addEventListener('click', function() {
            markAllChatNotificationsAsRead();
        });
    }
    
    // Load chat notifications
    function loadChatNotifications() {
        fetch('{{ route("chat.unread.count") }}')
            .then(response => response.json())
            .then(data => {
                const notificationList = document.getElementById('notificationList');
                
                if (data.unread_count > 0) {
                    notificationList.innerHTML = `
                        <div class="notification-item unread">
                            <div class="notification-content">
                                <div class="notification-icon">
                                    <i class="fas fa-comment"></i>
                                </div>
                                <div class="notification-details">
                                    <div class="notification-message">
                                        You have ${data.unread_count} unread message${data.unread_count !== 1 ? 's' : ''} in chats
                                    </div>
                                    <div class="notification-time">
                                        Just now
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Add specific chat notifications
                    @foreach($chats as $chat)
                        @if($chat->unread_count > 0)
                            notificationList.innerHTML += `
                                <div class="notification-item unread">
                                    <div class="notification-content">
                                        <div class="notification-icon">
                                            {{ substr($chat->other_user->username, 0, 1) }}
                                        </div>
                                        <div class="notification-details">
                                            <div class="notification-message">
                                                {{ $chat->unread_count }} new message${ {{ $chat->unread_count }} !== 1 ? 's' : '' } from {{ $chat->other_user->username }}
                                            </div>
                                            <div class="notification-time">
                                                {{ $chat->messages->first() ? $chat->messages->first()->created_at->diffForHumans() : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        @endif
                    @endforeach
                } else {
                    notificationList.innerHTML = `
                        <div class="notification-empty">
                            <i class="fas fa-bell-slash"></i>
                            <p>No new chat notifications</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            });
    }
    
    // Mark all chat notifications as read
    function markAllChatNotificationsAsRead() {
        fetch('{{ route("chat.mark.all.read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to reflect changes
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error marking notifications as read:', error);
        });
    }
    
    // Load initial unread count for badge
    function loadUnreadCount() {
        fetch('{{ route("chat.unread.count") }}')
            .then(response => response.json())
            .then(data => {
                if (notificationBadge) {
                    notificationBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    notificationBadge.style.display = data.unread_count > 0 ? 'flex' : 'none';
                }
            })
            .catch(error => {
                console.error('Error loading unread count:', error);
            });
    }
    
    // Load initial unread count
    loadUnreadCount();
});
</script>
@endsection
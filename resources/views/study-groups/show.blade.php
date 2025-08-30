@extends('layouts.app')

@section('title', $studyGroup->name . ' - Study Buddy')

@section('styles')
<style>
.study-group-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.group-header {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 25px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    margin-bottom: 25px;
    backdrop-filter: blur(10px);
}

.group-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.group-title h1 {
    color: #dffbff;
    margin: 0;
}

.group-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #a4d8e8;
    font-size: 14px;
}

.group-description {
    color: #a4d8e8;
    line-height: 1.6;
    margin-bottom: 20px;
}

.group-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.tabs {
    display: flex;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.2);
}

.tab {
    padding: 10px 20px;
    background: none;
    border: none;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    color: #a4d8e8;
    transition: all 0.3s ease;
}

.tab.active {
    border-bottom-color: #2dc2ff;
    color: #dffbff;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.members-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.member-card {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 12px;
    padding: 15px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    text-align: center;
}

.member-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2dc2ff 0%, #78f7d1 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    color: #0a1929;
    font-weight: bold;
    font-size: 20px;
}

.member-name {
    color: #dffbff;
    margin-bottom: 5px;
    font-weight: 500;
}

.member-role {
    color: #a4d8e8;
    font-size: 12px;
    text-transform: uppercase;
}

.member-role.admin {
    color: #78f7d1;
}

.invite-section {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    margin-bottom: 25px;
    backdrop-filter: blur(10px);
}

.invite-form {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.invite-form input {
    flex: 1;
    padding: 10px 12px;
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    background: rgba(15, 30, 45, 0.5);
    color: #dffbff;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #a4d8e8;
}

@media (max-width: 768px) {
    .group-title {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .group-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .members-grid {
        grid-template-columns: 1fr;
    }
    
    .invite-form {
        flex-direction: column;
    }
}
</style>
@endsection

@section('content')
<div class="study-group-container">
    <div class="group-header">
        <div class="group-title">
            <h1>{{ $studyGroup->name }}</h1>
            <div>
                <span class="badge {{ $studyGroup->is_public ? 'public' : 'private' }}">
                    {{ $studyGroup->is_public ? 'Public' : 'Private' }}
                </span>
            </div>
        </div>
        
        <div class="group-meta">
            <div class="meta-item">
                <i class="fas fa-book"></i>
                <span>{{ $studyGroup->subject ?? 'General' }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-users"></i>
                <span>{{ $studyGroup->members_count }}/{{ $studyGroup->max_members }} members</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-user"></i>
                <span>Created by: {{ $studyGroup->creator->username }}</span>
            </div>
            @if(!$studyGroup->is_public)
            <div class="meta-item">
                <i class="fas fa-key"></i>
                <span>Join code: {{ $studyGroup->join_code }}</span>
            </div>
            @endif
        </div>
        
        <p class="group-description">{{ $studyGroup->description }}</p>
        
        <div class="group-actions">
            @if($isMember)
                <form action="{{ route('study-groups.leave', $studyGroup->id) }}" method="POST">
                    @csrf
                    <button type="button" onclick="confirmLeave()" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Leave Group
                    </button>
                </form>
            @else
                <form action="{{ route('study-groups.join', $studyGroup->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Join Group
                    </button>
                </form>
            @endif
            
            @if($isAdmin)
                <a href="{{ route('study-groups.edit', $studyGroup->id) }}" class="btn btn-secondary">
                    <i class="fas fa-edit"></i> Edit Group
                </a>
            @endif
        </div>
    </div>
    
    @if($isMember || $studyGroup->is_public)
    <div class="tabs">
        <button class="tab active" onclick="switchTab('members')">Members</button>
        <button class="tab" onclick="switchTab('discussions')">Discussions</button>
        <button class="tab" onclick="switchTab('resources')">Resources</button>
        @if($isAdmin)
        <button class="tab" onclick="switchTab('invitations')">Invitations</button>
        @endif
    </div>
    
    <div id="membersTab" class="tab-content active">
        <h2>Group Members</h2>
        <div class="members-grid">
            @foreach($members as $member)
            <div class="member-card">
                <div class="member-avatar">
                    {{ strtoupper(substr($member->user->username, 0, 1)) }}
                </div>
                <div class="member-name">{{ $member->user->username }}</div>
                <div class="member-role {{ $member->role }}">{{ $member->role }}</div>
            </div>
            @endforeach
        </div>
    </div>
    
    <div id="discussionsTab" class="tab-content">
        <div class="empty-state">
            <i class="fas fa-comments fa-3x" style="margin-bottom: 15px;"></i>
            <h3>Discussions Coming Soon</h3>
            <p>Group discussions feature will be available in the next update.</p>
        </div>
    </div>
    
    <div id="resourcesTab" class="tab-content">
        <div class="empty-state">
            <i class="fas fa-file-alt fa-3x" style="margin-bottom: 15px;"></i>
            <h3>Resources Coming Soon</h3>
            <p>Group resources feature will be available in the next update.</p>
        </div>
    </div>
    
    @if($isAdmin)
    <div id="invitationsTab" class="tab-content">
        <div class="invite-section">
            <h2>Invite Members</h2>
            <form action="{{ route('study-groups.invite', $studyGroup->id) }}" method="POST" class="invite-form">
                @csrf
                <input type="email" name="emails[]" placeholder="Enter email address" required>
                <button type="submit" class="btn btn-primary">Send Invitation</button>
            </form>
        </div>
        
        <h2>Pending Invitations</h2>
        @if($pendingInvitations->count() > 0)
        <div class="members-grid">
            @foreach($pendingInvitations as $invitation)
            <div class="member-card">
                <div class="member-avatar">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="member-name">{{ $invitation->email }}</div>
                <div class="member-role">Invited</div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <p>No pending invitations</p>
        </div>
        @endif
    </div>
    @endif
    @else
    <div class="empty-state">
        <i class="fas fa-lock fa-3x" style="margin-bottom: 15px;"></i>
        <h3>Private Group</h3>
        <p>This is a private study group. You need to join to see the content.</p>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function switchTab(tabName) {
    // Update tabs
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');
    
    // Show/hide content
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.getElementById(tabName + 'Tab').classList.add('active');
}

function confirmLeave() {
    if (confirm('Are you sure you want to leave this study group?')) {
        event.target.closest('form').submit();
    }
}

// Handle join form submission for private groups
document.querySelector('form[action*="join"]')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Joining...';
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === 'This group requires a join code') {
            const joinCode = prompt('This is a private group. Please enter the join code:');
            if (joinCode) {
                // Add join code to form and resubmit
                const joinCodeInput = document.createElement('input');
                joinCodeInput.type = 'hidden';
                joinCodeInput.name = 'join_code';
                joinCodeInput.value = joinCode;
                form.appendChild(joinCodeInput);
                form.submit();
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        } else {
            form.submit();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

// Handle invite form submission with AJAX
document.querySelector('.invite-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';
    
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            if (data.message === 'Invitations sent successfully') {
                form.reset();
                // Reload the page to see new invitations
                setTimeout(() => window.location.reload(), 1000);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to send invitations');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});
</script>
@endsection
@extends('layouts.app')

@section('title', 'Study Groups - Study Buddy')

@section('styles')
<style>
/* Study Groups Specific Styles */
.study-groups-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
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

.groups-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.group-card {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease;
}

.group-card:hover {
    transform: translateY(-5px);
}

.group-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.group-header h3 {
    margin: 0;
    color: #dffbff;
}

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}

.badge.public {
    background-color: rgba(46, 125, 50, 0.2);
    color: #4caf50;
}

.badge.private {
    background-color: rgba(198, 40, 40, 0.2);
    color: #f44336;
}

.group-description {
    color: #a4d8e8;
    margin-bottom: 15px;
    line-height: 1.4;
}

.group-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-bottom: 15px;
    font-size: 14px;
    color: rgba(164, 216, 232, 0.8);
}

.group-actions {
    display: flex;
    gap: 10px;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: #a4d8e8;
}

.loading {
    text-align: center;
    padding: 40px;
    color: #a4d8e8;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    backdrop-filter: blur(5px);
}

.modal {
    background: rgba(15, 30, 45, 0.95);
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    border: 1px solid rgba(57, 183, 255, 0.2);
    backdrop-filter: blur(20px);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
}

.modal-header h3 {
    margin: 0;
    color: #dffbff;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #a4d8e8;
}

.modal-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #dffbff;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    font-size: 14px;
    background: rgba(15, 30, 45, 0.5);
    color: #dffbff;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.form-group small {
    color: #a4d8e8;
    font-size: 12px;
    display: block;
    margin-top: 4px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    font-weight: normal;
    color: #dffbff;
}

.checkbox-label input {
    width: auto;
    margin-right: 8px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

/* Toast notifications */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.toast {
    padding: 12px 20px;
    margin-bottom: 10px;
    border-radius: 8px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: 300px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideIn 0.3s ease;
}

.toast.success {
    background: rgba(46, 125, 50, 0.9);
    border-left: 4px solid #4caf50;
}

.toast.error {
    background: rgba(198, 40, 40, 0.9);
    border-left: 4px solid #f44336;
}

.toast.info {
    background: rgba(33, 150, 243, 0.9);
    border-left: 4px solid #2196f3;
}

.toast-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 16px;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .groups-grid {
        grid-template-columns: 1fr;
    }
    
    .group-actions {
        flex-direction: column;
    }
}
</style>
@endsection

@section('content')
<div class="study-groups-container">
    <div class="header">
        <h1>Study Groups</h1>
        <button onclick="showCreateModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Group
        </button>
    </div>

    <div class="tabs">
        <button class="tab active" onclick="switchTab('myGroups')">My Groups</button>
        <button class="tab" onclick="switchTab('publicGroups')">Discover Groups</button>
    </div>

    <!-- My Groups -->
    <div id="myGroupsTab" class="tab-content">
        <div class="groups-grid">
            @forelse($userGroups as $group)
            <div class="group-card">
                <div class="group-header">
                    <h3>{{ $group->name }}</h3>
                    <span class="badge {{ $group->is_public ? 'public' : 'private' }}">
                        {{ $group->is_public ? 'Public' : 'Private' }}
                    </span>
                </div>
                <p class="group-description">{{ $group->description }}</p>
                <div class="group-details">
                    <span><i class="fas fa-book"></i> {{ $group->subject ?? 'General' }}</span>
                    <span><i class="fas fa-users"></i> {{ $group->members_count }}/{{ $group->max_members }} members</span>
                    <span><i class="fas fa-user"></i> Created by: {{ $group->creator->username }}</span>
                </div>
                <div class="group-actions">
                    <a href="{{ route('study-groups.show', $group->id) }}" class="btn btn-secondary">
                        <i class="fas fa-eye"></i> View
                    </a>
                    @if(!$group->is_admin)
                    <form action="{{ route('study-groups.leave', $group->id) }}" method="POST" class="leave-form">
                        @csrf
                        <button type="button" onclick="confirmLeave(this)" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Leave
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-users fa-3x" style="margin-bottom: 15px;"></i>
                <h3>No study groups yet</h3>
                <p>You haven't joined any study groups. Join a public group or create your own!</p>
                <button onclick="switchTab('publicGroups')" class="btn btn-primary">
                    Discover Public Groups
                </button>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Public Groups -->
    <div id="publicGroupsTab" class="tab-content" style="display: none;">
        <div class="groups-grid">
            @forelse($publicGroups as $group)
            <div class="group-card">
                <div class="group-header">
                    <h3>{{ $group->name }}</h3>
                    <span class="badge public">Public</span>
                </div>
                <p class="group-description">{{ $group->description }}</p>
                <div class="group-details">
                    <span><i class="fas fa-book"></i> {{ $group->subject ?? 'General' }}</span>
                    <span><i class="fas fa-users"></i> {{ $group->members_count }}/{{ $group->max_members }} members</span>
                    <span><i class="fas fa-user"></i> Created by: {{ $group->creator->username }}</span>
                </div>
                <div class="group-actions">
                    <form action="{{ route('study-groups.join', $group->id) }}" method="POST" class="join-form">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Join Group
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-search fa-3x" style="margin-bottom: 15px;"></i>
                <h3>No public groups available</h3>
                <p>There are no public study groups at the moment. Why not create one?</p>
                <button onclick="showCreateModal()" class="btn btn-primary">
                    Create a Group
                </button>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Create Group Modal -->
<div id="createModal" class="modal-overlay" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3>Create New Study Group</h3>
            <button onclick="hideCreateModal()" class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <form id="createGroupForm" action="{{ route('study-groups.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Group Name</label>
                    <input type="text" id="name" name="name" required placeholder="Enter group name">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" placeholder="Describe the purpose of this study group"></textarea>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="e.g., Mathematics, Biology, etc.">
                </div>
                <div class="form-group">
                    <label for="max_members">Maximum Members</label>
                    <input type="number" id="max_members" name="max_members" min="2" max="50" value="10" required>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="is_public" name="is_public" value="1" checked> 
                        Make this group public
                    </label>
                    <small>Public groups can be discovered by all users. Private groups require an invitation or join code.</small>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="hideCreateModal()" class="btn btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Create Group
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container"></div>
@endsection

@section('scripts')
<script>
// Tab switching
function switchTab(tabName) {
    // Update tabs
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');
    
    // Show/hide content
    document.getElementById('myGroupsTab').style.display = tabName === 'myGroups' ? 'block' : 'none';
    document.getElementById('publicGroupsTab').style.display = tabName === 'publicGroups' ? 'block' : 'none';
}

// Modal functions
function showCreateModal() {
    document.getElementById('createModal').style.display = 'flex';
}

function hideCreateModal() {
    document.getElementById('createModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideCreateModal();
    }
});

// Form submission with AJAX
document.getElementById('createGroupForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Creating...';
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok) {
            showToast('Group created successfully!', 'success');
            hideCreateModal();
            // Reload the page after a short delay
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Failed to create group');
        }
    } catch (error) {
        showToast(error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});

// Join group forms
document.querySelectorAll('.join-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Joining...';
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                showToast('Successfully joined the group!', 'success');
                // Reload the page after a short delay
                setTimeout(() => window.location.reload(), 1500);
            } else {
                if (response.status === 403 && data.message === 'This group requires a join code') {
                    const joinCode = prompt('This is a private group. Please enter the join code:');
                    if (joinCode) {
                        // Add join code to form and resubmit
                        const joinCodeInput = document.createElement('input');
                        joinCodeInput.type = 'hidden';
                        joinCodeInput.name = 'join_code';
                        joinCodeInput.value = joinCode;
                        this.appendChild(joinCodeInput);
                        this.submit();
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                } else {
                    throw new Error(data.message || 'Failed to join group');
                }
            }
        } catch (error) {
            showToast(error.message, 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
});

// Leave group confirmation
function confirmLeave(button) {
    if (confirm('Are you sure you want to leave this study group?')) {
        button.closest('form').submit();
    }
}

// Toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <span>${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
    `;
    
    document.querySelector('.toast-container').appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}

// Display any flash messages
@if(session('success'))
showToast('{{ session('success') }}', 'success');
@endif

@if(session('error'))
showToast('{{ session('error') }}', 'error');
@endif

@if($errors->any())
@foreach($errors->all() as $error)
showToast('{{ $error }}', 'error');
@endforeach
@endif
</script>
@endsection
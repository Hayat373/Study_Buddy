@extends('layouts.app')

@section('title', 'Settings - Study Buddy')

@section('styles')
<style>
.settings-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.settings-card {
    background: rgba(20, 40, 60, 0.5);
    border-radius: 16px;
    padding: 25px;
    border: 1px solid rgba(57, 183, 255, 0.1);
    backdrop-filter: blur(10px);
    margin-bottom: 25px;
}

.settings-header {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
}

.settings-header i {
    font-size: 1.5rem;
    margin-right: 12px;
    color: #2dc2ff;
}

.settings-header h2 {
    color: #dffbff;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    color: #a4d8e8;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    background: rgba(15, 30, 45, 0.3);
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    color: #dffbff;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #2dc2ff;
    box-shadow: 0 0 0 3px rgba(45, 194, 255, 0.1);
}

.btn-save {
    background: linear-gradient(90deg, #2dc2ff 0%, #78f7d1 100%);
    color: #0a1929;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(45, 194, 255, 0.3);
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(57, 183, 255, 0.2);
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: #dffbff;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #2dc2ff;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

.select-control {
    width: 100%;
    padding: 12px 16px;
    background: rgba(15, 30, 45, 0.3);
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    color: #dffbff;
    font-size: 14px;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%232dc2ff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
}

.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success {
    background: rgba(76, 175, 80, 0.2);
    border: 1px solid rgba(76, 175, 80, 0.3);
    color: #4caf50;
}

.alert-error {
    background: rgba(244, 67, 54, 0.2);
    border: 1px solid rgba(244, 67, 54, 0.3);
    color: #f44336;
}

.logout-section {
    text-align: center;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid rgba(57, 183, 255, 0.1);
}

.btn-logout {
    background: rgba(255, 107, 107, 0.1);
    border: 1px solid rgba(255, 107, 107, 0.2);
    color: #ff6b6b;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-logout:hover {
    background: rgba(255, 107, 107, 0.2);
    transform: translateY(-2px);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.settings-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    border-bottom: 1px solid rgba(57, 183, 255, 0.1);
    padding-bottom: 15px;
}

.tab-btn {
    padding: 10px 20px;
    background: transparent;
    border: 1px solid rgba(57, 183, 255, 0.2);
    border-radius: 8px;
    color: #a4d8e8;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.tab-btn.active {
    background: rgba(57, 183, 255, 0.1);
    color: #dffbff;
    border-color: #2dc2ff;
}

.tab-btn:hover:not(.active) {
    background: rgba(57, 183, 255, 0.05);
}

@media (max-width: 768px) {
    .settings-container {
        padding: 15px;
    }
    
    .settings-tabs {
        flex-wrap: wrap;
    }
    
    .tab-btn {
        flex: 1;
        text-align: center;
        min-width: 120px;
    }
}
</style>
@endsection

@section('content')
<div class="settings-container">
    <div class="settings-card">
        <div class="settings-header">
            <i class="fas fa-cog"></i>
            <h2>Account Settings</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="settings-tabs">
            <button class="tab-btn active" data-tab="profile">Profile</button>
            <button class="tab-btn" data-tab="security">Security</button>
            <button class="tab-btn" data-tab="notifications">Notifications</button>
            <button class="tab-btn" data-tab="privacy">Privacy</button>
        </div>

        <!-- Profile Tab -->
        <div id="profile-tab" class="tab-content active">
            <form action="{{ route('settings.profile.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <span style="color: #ff6b6b; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span style="color: #ff6b6b; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-save">Save Changes</button>
            </form>
        </div>

        <!-- Security Tab -->
        <div id="security-tab" class="tab-content">
            <form action="{{ route('settings.password.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                    @error('current_password')
                        <span style="color: #ff6b6b; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                    @error('new_password')
                        <span style="color: #ff6b6b; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn-save">Update Password</button>
            </form>
        </div>

        <!-- Notifications Tab -->
        <div id="notifications-tab" class="tab-content">
            <form action="{{ route('settings.notifications.update') }}" method="POST">
                @csrf
                <div class="form-group" style="display: flex; align-items: center; justify-content: space-between;">
                    <label for="email_notifications" style="margin-bottom: 0;">Email Notifications</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="email_notifications" name="email_notifications" {{ $user->email_notifications ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="form-group" style="display: flex; align-items: center; justify-content: space-between;">
                    <label for="push_notifications" style="margin-bottom: 0;">Push Notifications</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="push_notifications" name="push_notifications" {{ $user->push_notifications ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <button type="submit" class="btn-save">Save Preferences</button>
            </form>
        </div>

        <!-- Privacy Tab -->
        <div id="privacy-tab" class="tab-content">
            <form action="{{ route('settings.privacy.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="profile_visibility">Profile Visibility</label>
                    <select id="profile_visibility" name="profile_visibility" class="select-control">
                        <option value="public" {{ $user->profile_visibility === 'public' ? 'selected' : '' }}>Public</option>
                        <option value="friends" {{ $user->profile_visibility === 'friends' ? 'selected' : '' }}>Friends Only</option>
                        <option value="private" {{ $user->profile_visibility === 'private' ? 'selected' : '' }}>Private</option>
                    </select>
                </div>

                <button type="submit" class="btn-save">Update Privacy Settings</button>
            </form>
        </div>

        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked button
            button.classList.add('active');
            
            // Show corresponding content
            const tabId = button.getAttribute('data-tab');
            document.getElementById(`${tabId}-tab`).classList.add('active');
        });
    });
});
</script>
@endsection
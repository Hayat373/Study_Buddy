@extends('layout')

@section('title', 'Reset Password - Study Buddy')

@section('auth-content')
    <div class="hero-content">
        <div class="logo">
            <span>Study Buddy</span>
        </div>
        
        <h1>Set New Password</h1>
        
        <p class="subtitle">Enter your new password below.</p>
    </div>
    
    <div class="auth-container">
        <div class="auth-header">
            <h2>Reset Password</h2>
        </div>
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required autofocus placeholder="Enter your email">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">New Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" required placeholder="Enter new password">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password-confirm" name="password_confirmation" required placeholder="Confirm new password">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Reset Password</button>
            
            <div class="auth-links">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </form>
    </div>
@endsection
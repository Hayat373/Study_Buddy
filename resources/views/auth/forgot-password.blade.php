@extends('layout')

@section('title', 'Forgot Password - Study Buddy')

@section('auth-content')
    <div class="hero-content">
        <div class="logo">
            <span>Study Buddy</span>
        </div>
        
        <h1>Reset Your Password</h1>
        
        <p class="subtitle">Enter your email address and we'll send you a password reset link.</p>
    </div>
    
    <div class="auth-container">
        <div class="auth-header">
            <h2>Forgot Password</h2>
        </div>
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
            
            <div class="auth-links">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </form>
    </div>
@endsection
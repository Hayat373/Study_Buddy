@extends('layout')

@section('title', 'Study Buddy - Login/Signup')

@section('auth-content')
    <div class="hero-content">
        <div class="logo">
            <span>Study Buddy</span>
        </div>
        
        <h1>Unlock Your Potential:<br>The Future of Learning is Here</h1>
        
        <p class="subtitle">Immersive volumetric content, quizzes, group study -- and an AI-powered study buddy to help you level up.</p>
        
        
    </div>
    
    <div class="auth-container">
        <div class="auth-tabs">
            <div class="tab-slider" id="tabSlider"></div>
            <div class="auth-tab active" data-tab="login">Login</div>
            <div class="auth-tab" data-tab="signup">Sign Up</div>
        </div>
        
        <!-- Login Form -->
        <div class="auth-form active" id="loginForm">
            <form id="loginForm" method="POST" action="{{ route('login.post') }}"   >
                @csrf
                <div class="form-group">
                    <label for="loginEmail">Email or Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="loginEmail" name="username" placeholder="Enter your email if you forget or username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
                
                <button type="submit" class="btn btn-primary">Login to Study Buddy</button>
                
                <div class="divider"><span>Or continue with</span></div>
                
                <div class="social-auth">
                  <button type="button" class="btn btn-social" id="googleLoginBtn" onclick="window.location.href='/login/google'" style="width: 100%;">
                 <i class="fab fa-google"></i>
                    Google
                 </button>
              </div>
                
                
                <p class="terms">By continuing, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
            </form>
        </div>
        
        <!-- Signup Form -->
        <div class="auth-form" id="signupForm">
            <form id="signupForm" method="POST" action="/api/register" enctype="multipart/form-data">
                @csrf
                <div class="profile-picture-upload">
                    <div class="profile-preview" id="profilePreview">
                        <i class="fas fa-user-plus"></i>
                        <img id="profileImage" src="" alt="Profile Preview">
                    </div>
                    <div class="upload-text">
                        <p>Profile Picture</p>
                        <span>Click to upload a photo (optional)</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="signupUsername">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="signupUsername" name="username" placeholder="Choose a username" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="signupEmail">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="signupEmail" name="email" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="signupPassword">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="signupPassword" name="password" placeholder="Create a password" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="signupConfirmPassword">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="signupConfirmPassword" name="password_confirmation" placeholder="Confirm your password" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="userType">I am a</label>
                    <div class="input-wrapper">
                        <i class="fas fa-graduation-cap input-icon"></i>
                        <select id="userType" name="role" required>
                            <option value="">Select your role</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="parent">Parent</option>
                            <option value="lifelong_learner">Lifelong Learner</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Create Account</button>
                
                <div class="divider"><span>Or sign up with</span></div>
                
              <div class="social-auth">
                 <button type="button" class="btn btn-social" id="googleLoginBtn" onclick="window.location.href='/login/google'" style="width: 100%;">
                 <i class="fab fa-google"></i>
                 Google
                 </button>
            </div>  
                
                
                
                <p class="terms">By creating an account, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
            </form>
        </div>
    </div>
@endsection
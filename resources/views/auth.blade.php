<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Buddy - Login/Signup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
</head>
<body>
    <div class="particles" id="particles"></div>
    
    <div class="container">
        <div class="hero-content">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <span>Study Buddy</span>
            </div>
            
            <h1>Unlock Your Potential:<br>The Future of Learning is Here</h1>
            
            <p class="subtitle">Immersive volumetric content, quizzes, group study -- and an AI-powered study buddy to help you level up.</p>
            
            <div class="features">
                <div class="feature">
                    <i class="fas fa-cube"></i>
                    <span>3D Flashcards & immersive models</span>
                </div>
                <div class="feature">
                    <i class="fas fa-microphone"></i>
                    <span>Speak answers -- instant scoring and feedback</span>
                </div>
                <div class="feature">
                    <i class="fas fa-users"></i>
                    <span>Live rooms with synchronized whiteboards and avatars</span>
                </div>
            </div>
        </div>
        
        <div class="auth-container">
            <div class="auth-tabs">
                <div class="tab-slider" id="tabSlider"></div>
                <div class="auth-tab active" data-tab="login">Login</div>
                <div class="auth-tab" data-tab="signup">Sign Up</div>
            </div>
            
            <div class="auth-form active" id="loginForm">
                <div class="form-group">
                    <label for="loginEmail">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="loginEmail" placeholder="Enter your email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="loginPassword" placeholder="Enter your password">
                    </div>
                </div>
                
                <a href="#" class="forgot-password">Forgot password?</a>
                
                <button class="btn btn-primary">Login to Study Buddy</button>
                
                <div class="divider"><span>Or continue with</span></div>
                
                <button class="btn btn-face-auth">
                    <i class="fas fa-face-recognition"></i>
                    Facial Recognition
                </button>
                
                <p class="terms">By continuing, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
            </div>
            
            <div class="auth-form" id="signupForm">
                <div class="form-group">
                    <label for="signupName">Full Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="signupName" placeholder="Enter your full name">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="signupEmail">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="signupEmail" placeholder="Enter your email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="signupPassword">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="signupPassword" placeholder="Create a password">
                    </div>
                </div>
                
                <button class="btn btn-primary">Create Account</button>
                
                <div class="divider"><span>Or sign up with</span></div>
                
                <button class="btn btn-face-auth">
                    <i class="fas fa-face-recognition"></i>
                    Facial Recognition
                </button>
                
                <p class="terms">By creating an account, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
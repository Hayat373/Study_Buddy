<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Buddy - Login/Signup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="particles" id="particles"></div>
    
    <!-- Facial Recognition Modal -->
    <div class="facial-recognition-modal" id="faceModal">
        <div class="facial-modal-content">
            <div class="close-modal" id="closeModal">
                <i class="fas fa-times"></i>
            </div>
            <h2>Facial Recognition</h2>
            <p>Look directly into the camera to authenticate</p>
            
            <div class="face-scan-area">
                <div class="scanning-animation"></div>
                <video id="videoElement" autoplay playsinline></video>
            </div>
            
            <p class="scan-status">Initializing camera...</p>
            
            <button class="btn btn-primary" style="margin-top: 20px;">
                Complete Verification
            </button>
        </div>
    </div>
    
    <div class="container">
        <div class="hero-content">
            <div class="logo">
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
            
            <!-- Login Form -->
            <div class="auth-form active" id="loginForm">
                <form id="loginForm" method="POST" action="/api/login">
                    @csrf
                    <div class="form-group">
                        <label for="loginEmail">Email or Username</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="loginEmail" name="username" placeholder="Enter your email or username" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>
                    
                    <a href="#" class="forgot-password">Forgot password?</a>
                    
                    <button type="submit" class="btn btn-primary">Login to Study Buddy</button>
                    
                    <div class="divider"><span>Or continue with</span></div>
                    
                    <div class="social-auth">
                        <button type="button" class="btn btn-social">
                            <i class="fab fa-google"></i>
                            Google
                        </button>
                        <button type="button" class="btn btn-social">
                            <i class="fab fa-apple"></i>
                            Apple
                        </button>
                    </div>
                    
                    <button type="button" class="btn btn-face-auth" id="faceLoginBtn">
                        <i class="fas fa-face-recognition"></i>
                        Facial Recognition
                    </button>
                    
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
                        <button type="button" class="btn btn-social">
                            <i class="fab fa-google"></i>
                            Google
                        </button>
                        <button type="button" class="btn btn-social">
                            <i class="fab fa-apple"></i>
                            Apple
                        </button>
                    </div>
                    
                    <button type="button" class="btn btn-face-auth" id="faceSignupBtn">
                        <i class="fas fa-face-recognition"></i>
                        Facial Recognition
                    </button>
                    
                    <p class="terms">By creating an account, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
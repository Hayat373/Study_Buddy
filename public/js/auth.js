document.addEventListener('DOMContentLoaded', function () {
    console.log('Auth.js loaded successfully');

    // Tab switching functionality
    const tabs = document.querySelectorAll('.auth-tab');
    const tabSlider = document.getElementById('tabSlider');
    const loginForm = document.getElementById('loginForm');
    const signupFormContainer = document.getElementById('signupForm');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tabType = tab.getAttribute('data-tab');

            // Update active tab
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // Move slider
            if (tabType === 'login') {
                tabSlider.style.transform = 'translateX(0)';
                loginForm.classList.add('active');
                signupFormContainer.classList.remove('active');
            } else {
                tabSlider.style.transform = 'translateX(100%)';
                signupFormContainer.classList.add('active');
                loginForm.classList.remove('active');
            }
        });
    });

    // Create floating particles
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        const particleCount = 20;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');

            // Random size between 5 and 20px
            const size = Math.random() * 15 + 5;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;

            // Random position
            particle.style.left = `${Math.random() * 100}vw`;
            particle.style.top = `${Math.random() * 100}vh`;

            // Random animation delay
            particle.style.animationDelay = `${Math.random() * 15}s`;

            particlesContainer.appendChild(particle);
        }
    }

    // Profile picture upload functionality
    const profilePreview = document.getElementById('profilePreview');
    const profileImage = document.getElementById('profileImage');
    const profilePictureInput = document.getElementById('profilePictureInput');

    if (profilePreview && profilePictureInput) {
        profilePreview.addEventListener('click', () => {
            profilePictureInput.click();
        });

        profilePictureInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];

                // Validate file type and size
                const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

                if (!validImageTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, JPG, GIF)');
                    return;
                }

                if (file.size > 2 * 1024 * 1024) { // 2MB limit
                    alert('Please select an image smaller than 2MB');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    profileImage.src = event.target.result;
                    profileImage.style.display = 'block';
                    profilePreview.querySelector('i').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Form submission handling - FIXED VERSION
    const signupForm = document.querySelector('form#signupForm');
    
    if (signupForm) {
        console.log('Signup form found:', signupForm);
        
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submission intercepted');
            
            // Get the form element properly
            const form = this;
            const formData = new FormData(form);
            
            // Debug: Log all form data
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            console.log('FormData created successfully');
            
            // Show loading state
            const submitBtn = form.querySelector('.btn-primary');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Creating Account...';
            submitBtn.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                console.log('Response received:', response.status);
               
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.log('Non-JSON response:', text);
                        throw new Error('Server returned non-JSON response: ' + text.substring(0, 100));
                    });
                }

                return response.json().then(data => {
                    if (!response.ok) {
                        // Attach the data to the error so we can access it
                        const error = new Error('Network response was not ok: ' + response.status);
                        error.responseData = data;
                        throw error;
                    }
                    return data;
                });
            })
            .then(data => {
                console.log('Registration successful:', data);
                
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (data.message) {
                    alert(data.message);
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                if (error.responseData) {
                    // Handle validation errors from server
                    if (error.responseData.errors) {
                        const errorMessages = Object.values(error.responseData.errors).flat().join('\n');
                        alert('Please fix the following errors:\n\n' + errorMessages);
                    } else if (error.responseData.message) {
                        alert('Error: ' + error.responseData.message);
                    }
                } else {
                    // Try to get more detailed error info
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.text())
                    .then(text => {
                        console.log('Raw server response:', text);
                        alert('Registration failed. Please check the console for details.');
                    })
                    .catch(err => {
                        alert('Registration failed. Please try again. Error: ' + error.message);
                    });
                }
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    } else {
        console.error('Signup form not found!');
    }

    // Login form submission
    const loginFormElement = document.querySelector('form#loginForm');
    if (loginFormElement) {
        loginFormElement.addEventListener('submit', function(e) {
            // Let the form submit normally for traditional login
            console.log('Login form submitted');
        });
    }

    // Facial recognition modal
    const faceModal = document.getElementById('faceModal');
    const faceLoginBtn = document.getElementById('faceLoginBtn');
    const faceSignupBtn = document.getElementById('faceSignupBtn');
    const closeModalBtn = document.getElementById('closeModal');
    const videoElement = document.getElementById('videoElement');

    function openFaceModal() {
        if (faceModal) {
            faceModal.style.display = 'flex';
            // In a real app, you would access the camera here
        }
    }

    function closeFaceModal() {
        if (faceModal) {
            faceModal.style.display = 'none';
            // In a real app, you would stop the camera here
        }
    }

    if (faceLoginBtn) faceLoginBtn.addEventListener('click', openFaceModal);
    if (faceSignupBtn) faceSignupBtn.addEventListener('click', openFaceModal);
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeFaceModal);

    // Simulate camera access
    function simulateCameraAccess() {
        const scanStatus = document.querySelector('.scan-status');
        if (scanStatus) {
            scanStatus.textContent = "Camera accessed. Please look straight into the camera.";

            // Simulate face detection
            setTimeout(() => {
                scanStatus.textContent = "Face detected. Scanning...";
            }, 1500);

            setTimeout(() => {
                scanStatus.textContent = "Verification complete!";
                scanStatus.style.color = "#78f7d1";
            }, 3000);
        }
    }

    createParticles();

    // Facial recognition with camera access
    if (document.getElementById('faceLoginBtn')) {
        document.getElementById('faceLoginBtn').addEventListener('click', function() {
            const faceModal = document.getElementById('faceModal');
            if (faceModal) faceModal.style.display = 'flex';

            const video = document.getElementById('videoElement');
            const constraints = {
                video: { facingMode: 'user' } // Use the front camera
            };

            navigator.mediaDevices.getUserMedia(constraints)
                .then(function(stream) {
                    if (video) video.srcObject = stream;
                })
                .catch(function(error) {
                    console.error("Error accessing the camera: ", error);
                });
        });
    }

    // Close the modal and stop the video stream
    if (document.getElementById('closeModal')) {
        document.getElementById('closeModal').addEventListener('click', function() {
            const faceModal = document.getElementById('faceModal');
            const video = document.getElementById('videoElement');
            
            if (faceModal) faceModal.style.display = 'none';
            if (video && video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
            }
        });
    }

    // Forgot password functionality
    const forgotPasswordLink = document.querySelector('.forgot-password');
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', function(event) {
            event.preventDefault();
            
            const email = document.getElementById('loginEmail')?.value;
            if (!email) {
                alert('Please enter your email address in the login form first');
                return;
            }

            if (confirm(`Send password reset instructions to ${email}?`)) {
                fetch('/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || 'Password reset link sent to your email');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    }

    // Facial recognition variables
    let faceDetectionInterval;
    let isProcessing = false;
    let faceModelsLoaded = false;
    
    // Load face-api.js models
    async function loadModels() {
        try {
            if (typeof faceapi !== 'undefined') {
                await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
                await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
                await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
                await faceapi.nets.faceExpressionNet.loadFromUri('/models');
                
                faceModelsLoaded = true;
                console.log('Face API models loaded successfully');
            }
        } catch (error) {
            console.error('Error loading face models:', error);
        }
    }
    
    // Get face descriptor for registration or login
    async function getFaceDescriptor() {
        const video = document.getElementById('videoElement');
        const scanStatus = document.querySelector('.scan-status');
        
        try {
            if (scanStatus) {
                scanStatus.textContent = 'Processing your face...';
            }
            
            if (typeof faceapi === 'undefined') {
                throw new Error('Face API not loaded');
            }
            
            const result = await faceapi.detectSingleFace(
                video, 
                new faceapi.TinyFaceDetectorOptions()
            ).withFaceLandmarks().withFaceDescriptor();
            
            if (result) {
                return Array.from(result.descriptor);
            } else {
                throw new Error('No face detected');
            }
        } catch (error) {
            console.error('Error getting face descriptor:', error);
            if (scanStatus) {
                scanStatus.textContent = 'Error: ' + error.message;
                scanStatus.style.color = '#ff6b6b';
            }
            return null;
        }
    }
    
    // Facial registration
    async function registerWithFace() {
        const faceDescriptor = await getFaceDescriptor();
        
        if (!faceDescriptor) return;
        
        // Get form data
        const username = document.getElementById('signupUsername')?.value;
        const email = document.getElementById('signupEmail')?.value;
        const role = document.getElementById('userType')?.value;
        
        // Send to server
        try {
            const response = await fetch('/facial/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    faceDescriptor,
                    username,
                    email,
                    role
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert('Registration failed: ' + data.message);
            }
        } catch (error) {
            console.error('Registration error:', error);
            alert('Registration failed. Please try again.');
        }
    }
    
    // Facial login
    async function loginWithFace() {
        const faceDescriptor = await getFaceDescriptor();
        
        if (!faceDescriptor) return;
        
        // Send to server
        try {
            const response = await fetch('/facial/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ faceDescriptor })
            });
            
            const data = await response.json();
            
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert('Login failed: ' + data.message);
            }
        } catch (error) {
            console.error('Login error:', error);
            alert('Login failed. Please try again.');
        }
    }
    
    // Open facial recognition modal
    function openFaceModal(mode) {
        const faceModal = document.getElementById('faceModal');
        const verifyBtn = document.querySelector('.facial-modal-content .btn');
        const scanStatus = document.querySelector('.scan-status');
        
        if (!faceModal || !verifyBtn || !scanStatus) return;
        
        // Set up the verification button based on mode (login or register)
        verifyBtn.onclick = mode === 'register' ? registerWithFace : loginWithFace;
        verifyBtn.disabled = true;
        
        scanStatus.textContent = 'Initializing camera...';
        scanStatus.style.color = '#dffbff';
        
        faceModal.style.display = 'flex';
        
        // Initialize camera
        const video = document.getElementById('videoElement');
        const constraints = {
            video: { facingMode: 'user' }
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                if (video) video.srcObject = stream;
            })
            .catch(function(error) {
                console.error("Error accessing the camera: ", error);
                if (scanStatus) {
                    scanStatus.textContent = 'Camera access denied';
                    scanStatus.style.color = '#ff6b6b';
                }
            });
    }
    
    // Close facial recognition modal
    function closeFaceModal() {
        const faceModal = document.getElementById('faceModal');
        const video = document.getElementById('videoElement');
        
        clearInterval(faceDetectionInterval);
        
        if (video && video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }
        
        if (faceModal) faceModal.style.display = 'none';
    }
    
    // Set up event listeners for facial recognition
    if (document.getElementById('faceLoginBtn')) {
        document.getElementById('faceLoginBtn').addEventListener('click', () => openFaceModal('login'));
    }
    
    if (document.getElementById('faceSignupBtn')) {
        document.getElementById('faceSignupBtn').addEventListener('click', () => openFaceModal('register'));
    }
    
    if (document.getElementById('closeModal')) {
        document.getElementById('closeModal').addEventListener('click', closeFaceModal);
    }
    
    // Load models when page loads if faceapi is available
    if (typeof faceapi !== 'undefined') {
        loadModels();
    }
});
document.addEventListener('DOMContentLoaded', function () {
    // Tab switching functionality
    const tabs = document.querySelectorAll('.auth-tab');
    const tabSlider = document.getElementById('tabSlider');
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');

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
                signupForm.classList.remove('active');
            } else {
                tabSlider.style.transform = 'translateX(100%)';
                signupForm.classList.add('active');
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
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.style.display = 'none';

    profilePreview.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', (e) => {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();

            reader.onload = function(event) {
                profileImage.src = event.target.result;
                profileImage.style.display = 'block';
                profilePreview.querySelector('i').style.display = 'none';
            }

            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Facial recognition modal
    const faceModal = document.getElementById('faceModal');
    const faceLoginBtn = document.getElementById('faceLoginBtn');
    const faceSignupBtn = document.getElementById('faceSignupBtn');
    const closeModalBtn = document.getElementById('closeModal');
    const videoElement = document.getElementById('videoElement');

    function openFaceModal() {
        faceModal.style.display = 'flex';
        // In a real app, you would access the camera here
        // simulateCameraAccess();
    }

    function closeFaceModal() {
        faceModal.style.display = 'none';
        // In a real app, you would stop the camera here
    }

    faceLoginBtn.addEventListener('click', openFaceModal);
    faceSignupBtn.addEventListener('click', openFaceModal);
    closeModalBtn.addEventListener('click', closeFaceModal);

    // Simulate camera access (in a real app, you would use navigator.mediaDevices.getUserMedia)
    function simulateCameraAccess() {
        const scanStatus = document.querySelector('.scan-status');
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

    createParticles();

    // auth.js
document.getElementById('faceLoginBtn').addEventListener('click', function() {
    const faceModal = document.getElementById('faceModal');
    faceModal.style.display = 'flex'; // Show modal

    const video = document.getElementById('videoElement');
    const constraints = {
        video: { facingMode: 'user' } // Use the front camera
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(function(stream) {
            video.srcObject = stream;
        })
        .catch(function(error) {
            console.error("Error accessing the camera: ", error);
        });
});

// Close the modal and stop the video stream
document.getElementById('closeModal').addEventListener('click', function() {
    const faceModal = document.getElementById('faceModal');
    const video = document.getElementById('videoElement');
    
    faceModal.style.display = 'none'; // Hide modal
    video.srcObject.getTracks().forEach(track => track.stop()); // Stop the video stream
});


async function loadModels() {
    await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
    await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
}

document.addEventListener('DOMContentLoaded', loadModels);

document.querySelector('.btn.btn-primary').addEventListener('click', async function() {
    const video = document.getElementById('videoElement');
    const result = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks()
        .withFaceDescriptor();

    if (result) {
        const faceDescriptor = result.descriptor;
        // Send the faceDescriptor to your server for verification or registration
        // For example, use fetch to send it via POST
    } else {
        alert("No face detected!");
    }
});


// Load models when the document is ready
document.addEventListener('DOMContentLoaded', async function () {
    await loadModels(); // Load face-api.js models
});

// Handle facial recognition during signup
document.getElementById('faceSignupBtn').addEventListener('click', function() {
    openFaceModal();
});

document.querySelector('.btn.btn-primary').addEventListener('click', async function() {
    const video = document.getElementById('videoElement');
    const result = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks()
        .withFaceDescriptor();

    if (result) {
        const faceDescriptor = result.descriptor;

        // Send the face descriptor to your server for registration
        fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ faceDescriptor, /* other user data */ })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Handle success (e.g., redirect or show message)
        })
        .catch((error) => {
            console.error("Error:", error);
        });
    } else {
        alert("No face detected!");
    }
});

// Handle facial recognition during login
document.getElementById('faceLoginBtn').addEventListener('click', function() {
    openFaceModal();
});

document.querySelector('.btn.btn-primary').addEventListener('click', async function() {
    const video = document.getElementById('videoElement');
    const result = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks()
        .withFaceDescriptor();

    if (result) {
        const faceDescriptor = result.descriptor;

        // Send the face descriptor to your server for verification
        fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ faceDescriptor })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Handle success (e.g., redirect to dashboard)
        })
        .catch((error) => {
            console.error("Error:", error);
        });
    } else {
        alert("No face detected!");
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Handle forgot password click
    document.querySelector('.forgot-password').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior

        const email = prompt('Please enter your registered email:'); // Prompt for email

        if (email) {
            // Send the email to your API endpoint
            fetch('/password/email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message); // Show success or error message
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.'); // Show error message
            });
        }
    });
});

// Facial recognition variables
    let faceDetectionInterval;
    let isProcessing = false;
    let modelsLoaded = false;
    
    // Load face-api.js models
    async function loadModels() {
        try {
            // Load the models from the correct path
            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
            await faceapi.nets.faceExpressionNet.loadFromUri('/models');
            
            modelsLoaded = true;
            console.log('Face API models loaded successfully');
        } catch (error) {
            console.error('Error loading face models:', error);
        }
    }
    
    // Initialize facial recognition
    async function initFaceRecognition() {
        if (!modelsLoaded) {
            await loadModels();
        }
        
        const video = document.getElementById('videoElement');
        const scanStatus = document.querySelector('.scan-status');
        
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'user',
                    width: 640,
                    height: 480 
                } 
            });
            video.srcObject = stream;
            
            // Start face detection
            startFaceDetection(video, scanStatus);
        } catch (error) {
            console.error('Error accessing camera:', error);
            scanStatus.textContent = 'Camera access denied. Please allow camera access to use facial recognition.';
            scanStatus.style.color = '#ff6b6b';
        }
    }
    
    // Start face detection
    function startFaceDetection(video, scanStatus) {
        clearInterval(faceDetectionInterval);
        
        faceDetectionInterval = setInterval(async () => {
            if (isProcessing) return;
            
            isProcessing = true;
            try {
                const detections = await faceapi.detectSingleFace(
                    video, 
                    new faceapi.TinyFaceDetectorOptions()
                ).withFaceLandmarks().withFaceDescriptor();
                
                if (detections) {
                    scanStatus.textContent = 'Face detected. Please hold still...';
                    scanStatus.style.color = '#78f7d1';
                    
                    // Enable the verification button
                    document.querySelector('.facial-modal-content .btn').disabled = false;
                } else {
                    scanStatus.textContent = 'Please position your face in the frame';
                    scanStatus.style.color = '#ffc107';
                    
                    // Disable the verification button
                    document.querySelector('.facial-modal-content .btn').disabled = true;
                }
            } catch (error) {
                console.error('Face detection error:', error);
            }
            isProcessing = false;
        }, 1000);
    }
    
    // Get face descriptor for registration or login
    async function getFaceDescriptor() {
        const video = document.getElementById('videoElement');
        const scanStatus = document.querySelector('.scan-status');
        
        try {
            scanStatus.textContent = 'Processing your face...';
            
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
            scanStatus.textContent = 'Error: ' + error.message;
            scanStatus.style.color = '#ff6b6b';
            return null;
        }
    }
    
    // Facial registration
    async function registerWithFace() {
        const faceDescriptor = await getFaceDescriptor();
        
        if (!faceDescriptor) return;
        
        // Get form data
        const username = document.getElementById('signupUsername').value;
        const email = document.getElementById('signupEmail').value;
        const role = document.getElementById('userType').value;
        
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
        
        // Set up the verification button based on mode (login or register)
        verifyBtn.onclick = mode === 'register' ? registerWithFace : loginWithFace;
        verifyBtn.disabled = true;
        
        scanStatus.textContent = 'Initializing camera...';
        scanStatus.style.color = '#dffbff';
        
        faceModal.style.display = 'flex';
        initFaceRecognition();
    }
    
    // Close facial recognition modal
    function closeFaceModal() {
        const faceModal = document.getElementById('faceModal');
        const video = document.getElementById('videoElement');
        
        clearInterval(faceDetectionInterval);
        
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }
        
        faceModal.style.display = 'none';
    }
    
    // Set up event listeners
    document.getElementById('faceLoginBtn').addEventListener('click', () => openFaceModal('login'));
    document.getElementById('faceSignupBtn').addEventListener('click', () => openFaceModal('register'));
    document.getElementById('closeModal').addEventListener('click', closeFaceModal);
    
    // Password reset functionality
    document.querySelector('.forgot-password').addEventListener('click', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('loginEmail').value;
        
        if (!email) {
            alert('Please enter your email address first');
            return;
        }
        
        // Show a prompt for confirmation
        if (confirm(`Send password reset instructions to ${email}?`)) {
            fetch('/forgot-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email })
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
    
    // Load models when page loads
    loadModels();

});
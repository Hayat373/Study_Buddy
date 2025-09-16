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

    // Form submission handling
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
            console.log('Form data contents:');
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
                        throw new Error('Server returned non-JSON response. Please check your server validation rules.');
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
                        let errorMessages = '';
                        for (const field in error.responseData.errors) {
                            errorMessages += `${field}: ${error.responseData.errors[field].join(', ')}\n`;
                        }
                        alert('Please fix the following errors:\n\n' + errorMessages);
                    } else if (error.responseData.message) {
                        alert('Error: ' + error.responseData.message);
                    }
                } else {
                    alert('Registration failed. The server is not saving the username field. Please check your AuthController to ensure the username is included in the User::create() method.');
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

    createParticles();
});

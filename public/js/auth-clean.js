document.addEventListener('DOMContentLoaded', function () {
    // Tab switching functionality
    const tabs = document.querySelectorAll('.auth-tab');
    const tabSlider = document.getElementById('tabSlider');
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tabType = tab.getAttribute('data-tab');
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

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
            const size = Math.random() * 15 + 5;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${Math.random() * 100}vw`;
            particle.style.top = `${Math.random() * 100}vh`;
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

    function openFaceModal() {
        faceModal.style.display = 'flex';
    }

    function closeFaceModal() {
        faceModal.style.display = 'none';
    }

    faceLoginBtn.addEventListener('click', openFaceModal);
    faceSignupBtn.addEventListener('click', openFaceModal);
    closeModalBtn.addEventListener('click', closeFaceModal);

    createParticles();
});
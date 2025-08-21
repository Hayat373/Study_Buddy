
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
        
        createParticles();
  
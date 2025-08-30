// Toggle navigation on mobile
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('navToggle');
    const sideNav = document.querySelector('.side-nav');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationBadge = document.getElementById('notificationBadge');
    const navChatBadge = document.getElementById('navChatBadge');
    
    // Toggle navigation
    if (navToggle && sideNav) {
        navToggle.addEventListener('click', function() {
            sideNav.classList.toggle('active');
        });
    }
    
    // Load unread message count
    function loadUnreadCount() {
        fetch('{{ route("chat.unread.count") }}')
            .then(response => response.json())
            .then(data => {
                if (notificationBadge) {
                    notificationBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    notificationBadge.style.display = data.unread_count > 0 ? 'flex' : 'none';
                }
                
                if (navChatBadge) {
                    navChatBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    navChatBadge.style.display = data.unread_count > 0 ? 'flex' : 'none';
                }
            })
            .catch(error => {
                console.error('Error loading unread count:', error);
            });
    }
    
    // Load initial unread count
    loadUnreadCount();
    
    // Refresh unread count every 30 seconds
    setInterval(loadUnreadCount, 30000);
    
    // Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('light-theme');
            const icon = themeToggle.querySelector('i');
            
            if (document.body.classList.contains('light-theme')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                localStorage.setItem('theme', 'light');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
                localStorage.setItem('theme', 'dark');
            }
        });
        
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') {
            document.body.classList.add('light-theme');
            const icon = themeToggle.querySelector('i');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    }
    
    // Close navigation when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 1024 && sideNav && sideNav.classList.contains('active')) {
            if (!event.target.closest('.side-nav') && !event.target.closest('.nav-toggle')) {
                sideNav.classList.remove('active');
            }
        }
    });
    
    // Initialize progress bar animations
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
    
    // Toast notification styles
    const style = document.createElement('style');
    style.textContent = `
        .notification-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(15, 30, 45, 0.95);
            color: #dffbff;
            padding: 12px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 10000;
        }
        
        .notification-toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .notification-toast i {
            color: #4caf50;
        }
        
        .light-theme .notification-toast {
            background: rgba(255, 255, 255, 0.95);
            color: #2a4d69;
            border: 1px solid rgba(57, 183, 255, 0.2);
        }
    `;
    document.head.appendChild(style);
});
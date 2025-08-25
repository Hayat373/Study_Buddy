import './bootstrap';
 // Basic JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Study Buddy app loaded');
    
    // Make logout form work when clicked
    const logoutForm = document.querySelector('form.nav-item');
    if (logoutForm) {
        logoutForm.addEventListener('click', function(e) {
            if (e.target.tagName !== 'BUTTON') {
                this.querySelector('button').click();
            }
        });
    }
    
    // Responsive sidebar toggle (for mobile)
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-open');
        });
    }
});
class RealTimeDashboard {
    constructor() {
        this.statsElement = document.getElementById('statsGrid');
        this.updateInterval = 30000; // 30 seconds
        this.init();
    }

    init() {
        this.loadRealTimeStats();
        this.setupAutoRefresh();
        this.setupProgressAnimations();
    }

    loadRealTimeStats() {
        fetch('/api/dashboard/stats')
            .then(response => response.json())
            .then(data => this.updateStats(data))
            .catch(error => console.error('Error loading stats:', error));
    }

    updateStats(data) {
        // Update flashcard count
        const flashcardElement = document.getElementById('flashcards-count');
        if (flashcardElement) {
            this.animateValue(flashcardElement, parseInt(flashcardElement.textContent), data.flashcardSetsCount, 1000);
        }

        // Update study hours
        const studyHoursElement = document.getElementById('study-hours');
        if (studyHoursElement) {
            this.animateValue(studyHoursElement, parseFloat(studyHoursElement.textContent), data.totalStudyHours, 1000);
        }

        // Update mastery level
        const masteryElement = document.getElementById('mastery-level');
        if (masteryElement) {
            this.animateValue(masteryElement, parseInt(masteryElement.textContent), data.masteryLevel, 1000);
        }

        // Update streak
        const streakElement = document.getElementById('study-streak');
        if (streakElement) {
            this.animateValue(streakElement, parseInt(streakElement.textContent), data.currentStreak, 1000);
        }
    }

    animateValue(element, start, end, duration) {
        const range = end - start;
        const startTime = performance.now();
        
        function updateValue(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const value = start + (range * easeOutQuart);
            
            if (element.id === 'study-hours') {
                element.textContent = value.toFixed(1);
            } else {
                element.textContent = Math.round(value);
            }
            
            if (progress < 1) {
                requestAnimationFrame(updateValue);
            }
        }
        
        requestAnimationFrame(updateValue);
    }

    setupAutoRefresh() {
        setInterval(() => this.loadRealTimeStats(), this.updateInterval);
    }

    setupProgressAnimations() {
        // Animate progress bars on page load
        const progressBars = document.querySelectorAll('.progress-fill');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new RealTimeDashboard();
    
    // Add hover effects to cards
    const cards = document.querySelectorAll('.stat-card, .content-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.2)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = 'none';
        });
    });
});
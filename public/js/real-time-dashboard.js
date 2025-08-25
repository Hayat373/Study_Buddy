class RealTimeDashboard {
    constructor() {
        this.previousStats = null;
        this.init();
    }
    
    init() {
        // Load initial stats
        this.updateStats();
        
        // Set up periodic updates
        setInterval(() => {
            this.updateStats();
        }, 30000); // Update every 30 seconds
        
        // Set up event listeners for real-time events
        this.setupEventListeners();
    }
    
    async updateStats() {
        try {
            const response = await fetch('/dashboard/stats');
            const stats = await response.json();
            
            if (this.previousStats) {
                this.calculateTrends(stats, this.previousStats);
            }
            
            this.updateUI(stats);
            this.previousStats = stats;
            
        } catch (error) {
            console.error('Failed to update dashboard stats:', error);
        }
    }
    
    calculateTrends(currentStats, previousStats) {
        const trends = {};
        
        // Calculate percentage changes
        trends.flashcards = this.calculatePercentageChange(
            previousStats.flashcardSets, 
            currentStats.flashcardSets
        );
        
        trends.quizzes = this.calculatePercentageChange(
            previousStats.quizzesCompleted, 
            currentStats.quizzesCompleted
        );
        
        trends.studyTime = this.calculatePercentageChange(
            previousStats.studyTimeToday, 
            currentStats.studyTimeToday
        );
        
        trends.mastery = this.calculatePercentageChange(
            previousStats.masteryLevel, 
            currentStats.masteryLevel
        );
        
        this.updateTrendIndicators(trends);
    }
    
    calculatePercentageChange(oldValue, newValue) {
        if (oldValue === 0) return 0;
        return ((newValue - oldValue) / oldValue) * 100;
    }
    
    updateTrendIndicators(trends) {
        this.updateTrendElement('flashcardsTrend', trends.flashcards);
        this.updateTrendElement('quizzesTrend', trends.quizzes);
        this.updateTrendElement('studyTimeTrend', trends.studyTime);
        this.updateTrendElement('masteryTrend', trends.mastery);
    }
    
    updateTrendElement(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) {
            const trendValue = Math.abs(value).toFixed(1);
            element.textContent = `${trendValue}%`;
            
            // Update parent class for coloring
            const parent = element.closest('.stat-trend');
            if (parent) {
                parent.classList.remove('up', 'down');
                parent.classList.add(value >= 0 ? 'up' : 'down');
                
                // Update icon
                const icon = parent.querySelector('i');
                if (icon) {
                    icon.className = value >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
                }
            }
        }
    }
    
    updateUI(stats) {
        // Update flashcards count
        this.updateCounter('flashcardsCount', stats.flashcardSets);
        
        // Update quizzes count
        this.updateCounter('quizzesCount', stats.quizzesCompleted);
        
        // Update study time
        const studyTimeHours = (stats.studyTimeToday / 60).toFixed(1);
        document.getElementById('studyTime').textContent = `${studyTimeHours}h`;
        
        // Update mastery level
        document.getElementById('masteryLevel').textContent = `${Math.round(stats.masteryLevel)}%`;
    }
    
    updateCounter(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) {
            // Animate counter if value changed
            const currentValue = parseInt(element.textContent);
            if (currentValue !== value) {
                this.animateCounter(element, currentValue, value, 1000);
            }
        }
    }
    
    animateCounter(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = value;
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }
    
    setupEventListeners() {
        // Listen for custom events from other parts of the app
        document.addEventListener('flashcardCreated', () => {
            this.updateStats();
        });
        
        document.addEventListener('quizCompleted', () => {
            this.updateStats();
        });
        
        document.addEventListener('studyTimeUpdated', () => {
            this.updateStats();
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new RealTimeDashboard();
});

// Export for use in other modules
window.RealTimeDashboard = RealTimeDashboard;
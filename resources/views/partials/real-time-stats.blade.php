<div class="stat-card">
    <div class="stat-icon">
        <i class="fas fa-layer-group"></i>
    </div>
    <div class="stat-content">
        <h3 id="flashcardsCount">{{ $flashcardSetsCount }}</h3>
        <p>Flashcard Sets</p>
    </div>
    <div class="stat-trend up">
        <i class="fas fa-arrow-up"></i> <span id="flashcardsTrend">0%</span>
    </div>
</div>

<div class="stat-card">
    <div class="stat-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="stat-content">
        <h3 id="quizzesCount">0</h3>
        <p>Quizzes Completed</p>
    </div>
    <div class="stat-trend up">
        <i class="fas fa-arrow-up"></i> <span id="quizzesTrend">0%</span>
    </div>
</div>

<div class="stat-card">
    <div class="stat-icon">
        <i class="fas fa-clock"></i>
    </div>
    <div class="stat-content">
        <h3 id="studyTime">{{ round($totalStudyTime / 60, 1) }}h</h3>
        <p>Study Time</p>
    </div>
    <div class="stat-trend up">
        <i class="fas fa-arrow-up"></i> <span id="studyTimeTrend">0%</span>
    </div>
</div>

<div class="stat-card">
    <div class="stat-icon">
        <i class="fas fa-trophy"></i>
    </div>
    <div class="stat-content">
        <h3 id="masteryLevel">{{ round($masteryLevel) }}%</h3>
        <p>Mastery Level</p>
    </div>
    <div class="stat-trend {{ $masteryLevel > 0 ? 'up' : 'down' }}">
        <i class="fas fa-arrow-{{ $masteryLevel > 0 ? 'up' : 'down' }}"></i> 
        <span id="masteryTrend">0%</span>
    </div>
</div>
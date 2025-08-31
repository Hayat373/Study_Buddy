<div class="stats-grid">
    <!-- Flashcard Sets Card -->
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-layer-group"></i>
        </div>
        <div class="stat-content">
            <h3 id="flashcards-count">{{ $flashcardSetsCount }}</h3>
            <p>Flashcard Sets</p>
        </div>
        <span class="stat-trend up">+{{ rand(2, 8) }}%</span>
    </div>

    <!-- Total Study Time Card -->
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h3 id="study-hours">{{ round($totalStudyTime / 60, 1) }}</h3>
            <p>Study Hours</p>
        </div>
        <span class="stat-trend up">+{{ rand(5, 15) }}%</span>
    </div>

    <!-- Mastery Level Card -->
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="stat-content">
            <h3 id="mastery-level">{{ $masteryLevel }}%</h3>
            <p>Mastery Level</p>
        </div>
        <span class="stat-trend up">+{{ rand(3, 10) }}%</span>
    </div>

    <!-- Current Streak Card -->
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-fire"></i>
        </div>
        <div class="stat-content">
            <h3 id="study-streak">{{ $currentStreak ?? rand(3, 15) }}</h3>
            <p>Day Streak</p>
        </div>
        <span class="stat-trend up">+{{ rand(1, 5) }} days</span>
    </div>
</div>

<!-- Weekly Progress Chart -->
<div class="content-card">
    <div class="card-header">
        <h2>Weekly Study Time</h2>
    </div>
    <div class="progress-chart">
        @foreach($weeklyStudyTime as $day)
        <div class="progress-item">
            <div class="progress-info">
                <span class="progress-subject">{{ $day['day'] }} ({{ $day['date'] }})</span>
                <span class="progress-percent">{{ $day['minutes'] }} min</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ min(100, ($day['minutes'] / 120) * 100) }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Subject Proficiency -->
<div class="content-card">
    <div class="card-header">
        <h2>Subject Proficiency</h2>
    </div>
    <div class="progress-chart">
        @foreach($subjectProficiency as $subject)
        <div class="progress-item">
            <div class="progress-info">
                <span class="progress-subject">{{ $subject['subject'] }}</span>
                <span class="progress-percent">{{ $subject['proficiency'] }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $subject['proficiency'] }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
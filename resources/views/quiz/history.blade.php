<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz History - Study Buddy</title>
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    <style>
        .quiz-history {
            margin-top: 20px;
        }
        .history-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .history-info {
            flex: 1;
        }
        .history-info h3 {
            margin: 0 0 5px 0;
            color: #333;
        }
        .history-info p {
            margin: 2px 0;
            color: #666;
            font-size: 0.9rem;
        }
        .history-score {
            text-align: right;
            margin-left: 15px;
        }
        .score-value {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .score-percentage {
            font-size: 0.9rem;
            color: #666;
        }
        .score-excellent {
            color: #28a745;
        }
        .score-good {
            color: #ffc107;
        }
        .score-poor {
            color: #dc3545;
        }
        .no-history {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination button {
            margin: 0 5px;
            padding: 8px 15px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .pagination button.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Quiz History</h1>
            <a href="{{ route('dashboard') }}" class="back-btn">← Dashboard</a>
        </div>

        <div class="quiz-history">
            @if($attempts->count() > 0)
                @foreach($attempts as $attempt)
                <div class="history-item">
                    <div class="history-info">
                        <h3>{{ $attempt->quiz->title }}</h3>
                        <p>From: {{ $attempt->quiz->flashcardSet->title }}</p>
                        <p>Completed: {{ $attempt->completed_at->format('M j, Y g:i A') }}</p>
                        <p>Time taken: {{ floor($attempt->time_taken / 60) }}:{{ sprintf('%02d', $attempt->time_taken % 60) }}</p>
                    </div>
                    <div class="history-score">
                        @php
                            $percentage = round(($attempt->score / $attempt->total_questions) * 100);
                            $scoreClass = 'score-good';
                            if ($percentage >= 80) $scoreClass = 'score-excellent';
                            if ($percentage < 60) $scoreClass = 'score-poor';
                        @endphp
                        <div class="score-value {{ $scoreClass }}">{{ $attempt->score }}/{{ $attempt->total_questions }}</div>
                        <div class="score-percentage {{ $scoreClass }}">{{ $percentage }}%</div>
                        <a href="{{ route('quiz.results', ['id' => $attempt->id]) }}" class="btn btn-sm btn-outline">View Details</a>
                    </div>
                </div>
                @endforeach

                <div class="pagination">
                    @if($attempts->currentPage() > 1)
                        <a href="{{ $attempts->previousPageUrl() }}" class="btn btn-sm">Previous</a>
                    @endif
                    
                    @for($i = 1; $i <= $attempts->lastPage(); $i++)
                        <a href="{{ $attempts->url($i) }}" class="btn btn-sm {{ $attempts->currentPage() == $i ? 'active' : '' }}">{{ $i }}</a>
                    @endfor
                    
                    @if($attempts->hasMorePages())
                        <a href="{{ $attempts->nextPageUrl() }}" class="btn btn-sm">Next</a>
                    @endif
                </div>
            @else
                <div class="no-history">
                    <h3>No quiz history yet</h3>
                    <p>Take your first quiz to see your results here!</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Browse Flashcard Sets</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
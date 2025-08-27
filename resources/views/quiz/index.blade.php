@extends('layout')
@section('title', 'Study Buddy - Quizzes')
@section('content')
    <h1>Your Quizzes</h1>
    @if($quizzes->isEmpty())
        <p>No quizzes available.</p>
    @else
        <ul>
            @foreach($quizzes as $quiz)
                <li>
                    {{ $quiz->title }} ({{ $quiz->flashcardSet->title }})
                    <a href="{{ route('quizzes.show', $quiz->id) }}">View</a>
                </li>
            @endforeach
        </ul>
        {{ $quizzes->links() }}
    @endif
@endsection
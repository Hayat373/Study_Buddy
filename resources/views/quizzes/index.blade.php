@extends('layouts.app')

@section('title', 'Quizzes')

@section('content')
<div class="container">
    <h1 class="mb-4">Quizzes</h1>

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-3">
        @foreach ($flashcardSets as $flashcardSet)
            <a href="{{ route('quizzes.create', ['setId' => $flashcardSet->id]) }}" class="btn btn-primary mb-2">Create Quiz from {{ $flashcardSet->title }}</a>
        @endforeach
    </div>

    @if ($quizzes->isEmpty())
        <div class="alert alert-info">
            No quizzes available. Create your first quiz!
        </div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quizzes as $quiz)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('quizzes.show', $quiz->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $quizzes->links() }}
    @endif
</div>
@endsection
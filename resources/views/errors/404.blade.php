@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="dashboard-container">
    <div class="error-container">
        <div class="error-content">
            <h1>404</h1>
            <h2>Page Not Found</h2>
            <p>The page you're looking for doesn't exist or has been moved.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Go to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.error-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
    text-align: center;
}

.error-content h1 {
    font-size: 8rem;
    font-weight: 700;
    background: linear-gradient(135deg, #2dc2ff 0%, #78f7d1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0;
    line-height: 1;
}

.error-content h2 {
    color: #dffbff;
    font-size: 2rem;
    margin-bottom: 20px;
}

.error-content p {
    color: #a4d8e8;
    font-size: 1.1rem;
    margin-bottom: 30px;
    max-width: 400px;
}

@media (max-width: 768px) {
    .error-content h1 {
        font-size: 6rem;
    }
    
    .error-content h2 {
        font-size: 1.5rem;
    }
}
</style>
@endsection
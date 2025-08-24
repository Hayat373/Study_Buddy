<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Study Buddy')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <div class="header-left">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Study Buddy</span>
                </div>
            </div>
            
            <div class="header-center">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search flashcards, quizzes, groups...">
                </div>
            </div>
            
            <div class="header-right">
                <button class="header-btn">
                    <i class="fas fa-bell"></i>
                </button>
                
                <div class="user-menu">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                    </div>
                    <span class="username">{{ Auth::user()->username }}</span>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>
        
        <!-- Main Content -->
        <main class="app-main">
            @yield('content')
        </main>
    </div>
    
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
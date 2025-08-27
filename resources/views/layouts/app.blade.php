<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Study Buddy')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    @yield('styles')
</head>
<body class="app-body">
    <!-- App Container -->
    <div class="app-container">
        <!-- Side Navigation -->
        <aside class="side-nav">
            <div class="nav-header">
                <div class="logo">
                    
                    <span class="nav-text">Study Buddy</span>
                </div>
                <button class="nav-toggle" id="navToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('flashcards.index') }}" class="nav-link {{ request()->routeIs('flashcards.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i>
                        <span class="nav-text">Flashcards</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('quizzes.index') }}" class="nav-link {{ request()->routeIs('quizzes.*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span class="nav-text">Quizzes</span>
                    </a>
                </li>
                <li class="nav-item">
                  
                </li>
                <li class="nav-item">
                    <a href="{{ route('chat.index') }}" class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i>
                        <span class="nav-text">Chat</span>
                        <span class="nav-badge">3</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('study-groups.index') }}" class="nav-link {{ request()->routeIs('study-groups.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Study Groups</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('schedule.index') }}" class="nav-link {{ request()->routeIs('schedule.index') ? 'active' : '' }}">
                        <i class="fas fa-calendar"></i>
                        <span class="nav-text">Schedule</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}"  class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                </li>
            </ul>
            
            <div class="nav-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ substr($user->username, 0, 1) }}
                    </div>
                    <div class="user-info">
                        <span class="username">{{ $user->username }}</span>
                        <span class="user-role">Student</span>
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search flashcards, quizzes, groups...">
                </div>
                
                <div class="header-actions">
                    <button class="header-btn notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <button class="header-btn theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @yield('scripts')
</body>
</html>
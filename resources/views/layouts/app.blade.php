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
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span>Study Buddy</span>
                </div>
            </div>
            
            <a href="{{ route('dashboard') }}" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="#" class="nav-item">
                <i class="fas fa-layer-group"></i>
                <span>Flashcards</span>
            </a>
            
            <a href="#" class="nav-item">
                <i class="fas fa-comments"></i>
                <span>Group Chat</span>
            </a>
            
            <a href="#" class="nav-item">
                <i class="fas fa-question-circle"></i>
                <span>Quizzes</span>
            </a>
            
            <a href="#" class="nav-item">
                <i class="fas fa-video"></i>
                <span>Video Calls</span>
            </a>
            
            <div class="nav-divider"></div>
            
            <a href="#" class="nav-item">
                <i class="fas fa-user-friends"></i>
                <span>Study Groups</span>
            </a>
            
            <a href="#" class="nav-item">
                <i class="fas fa-chart-line"></i>
                <span>Progress</span>
            </a>
            
            <a href="#" class="nav-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            
            <div class="nav-divider"></div>
            
            <form method="POST" action="{{ route('logout') }}" class="nav-item">
                @csrf
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
                <button type="submit" style="display: none;"></button>
            </form>
        </aside>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="app-header">
                <div class="header-left">
                    <button class="menu-toggle" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search flashcards, quizzes, groups...">
                </div>
                
                <div class="user-menu">
                    <div class="notification-btn">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="user-profile">
                        <span>{{ strtoupper(substr(Auth::user()->username, 0, 2)) }}</span>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main class="content-area">
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Make logout form work when clicked
        document.querySelector('form.nav-item').addEventListener('click', function(e) {
            if (e.target.tagName !== 'BUTTON') {
                this.querySelector('button').click();
            }
        });
    </script>
</body>
</html>
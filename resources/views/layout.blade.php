<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Study Buddy')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
</head>
<body>
    <div class="particles" id="particles"></div>
    
    <!-- Facial Recognition Modal -->
    <div class="facial-recognition-modal" id="faceModal">
        <div class="facial-modal-content">
            <div class="close-modal" id="closeModal">
                <i class="fas fa-times"></i>
            </div>
            <h2>Facial Recognition</h2>
            <p>Look directly into the camera to authenticate</p>
            
            <div class="face-scan-area">
                <div class="scanning-animation"></div>
                <video id="videoElement" autoplay playsinline></video>
            </div>
            
            <p class="scan-status">Initializing camera...</p>
            
            <button class="btn btn-primary" style="margin-top: 20px;">
                Complete Verification
            </button>
        </div>
    </div>
    
    <div class="container">
        @yield('auth-content')
    </div>
    
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
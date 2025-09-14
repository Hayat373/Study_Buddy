<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Study Buddy — Neon Landing</title>

<!-- GSAP CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
<link rel="stylesheet" href="{{ asset('css/style.css') }}" /></head>

<body>
  <div class="container" id="page">
    <header>
      <div class="brand">Study Buddy</div>
      <div class="signup" id="signupButton">🔒 SIGNUP / LOGIN</div>
    </header>

    <main class="hero">
      <section class="left">
        <h1>Unlock Your Potential: <br/> The Future of Learning is Here</h1>
        <div class="sub">Immersive volumetric content, quizzes, group study — and an AI-powered study buddy to help you level up.</div>

        <div class="screen-preview">
          <!-- Mock brain graphic area -->
          <div style="display:flex;gap:14px;align-items:center">
            <div style="width:160px;height:100px;border-radius:8px;background:linear-gradient(180deg, rgba(0,0,0,0.6), rgba(20,30,50,0.4));border:1px solid rgba(57,183,255,0.04);display:flex;align-items:center;justify-content:center;">
              <svg width="120" height="80" viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <!-- Glow effects -->
    <filter id="glow" x="-30%" y="-30%" width="160%" height="160%">
      <feGaussianBlur stdDeviation="2" result="blur"/>
      <feComposite in="SourceGraphic" in2="blur" operator="over"/>
    </filter>
    
    <linearGradient id="blueGradient" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#39b7ff"/>
      <stop offset="100%" stop-color="#78f7d1"/>
    </linearGradient>
    
    <!-- Card shadow -->
    <filter id="shadow" x="0" y="0" width="200%" height="200%">
      <feOffset result="offOut" in="SourceAlpha" dx="2" dy="2"/>
      <feGaussianBlur result="blurOut" in="offOut" stdDeviation="2"/>
      <feBlend in="SourceGraphic" in2="blurOut" mode="normal"/>
    </filter>
  </defs>
  
  <!-- Card with 3D perspective -->
  <rect x="10" y="5" width="100" height="70" rx="4" fill="rgba(20,40,60,0.4)" stroke="rgba(57,183,255,0.2)" filter="url(#shadow)"/>
  <rect x="4" y="2" width="100" height="70" rx="4" fill="rgba(30,50,70,0.6)" stroke="rgba(57,183,255,0.3)" stroke-width="0.8"/>
  
  <!-- Glowing flashcard content -->
  <rect x="14" y="14" width="80" height="50" rx="2" fill="rgba(255,255,255,0.08)" stroke="rgba(57,183,255,0.15)"/>
  
  <!-- Question mark (front side) -->
  <path d="M50 35a10 10 0 1 1 20 0 10 10 0 0 1-20 0zm10-5v15" 
        stroke="url(#blueGradient)" 
        stroke-width="1.5" 
        stroke-linecap="round"
        filter="url(#glow)"/>
  
  <!-- Glowing edge effect -->
  <path d="M14 14h80v2l-4 4-76-4v-2z" fill="rgba(57,183,255,0.2)" filter="url(#glow)"/>
  
  <!-- Subtle lines representing text (back side) -->
  <path d="M30 30h40" stroke="rgba(57,183,255,0.3)" stroke-width="1" stroke-linecap="round"/>
  <path d="M30 36h32" stroke="rgba(57,183,255,0.3)" stroke-width="1" stroke-linecap="round"/>
  <path d="M30 42h36" stroke="rgba(57,183,255,0.3)" stroke-width="1" stroke-linecap="round"/>
  <path d="M30 48h28" stroke="rgba(57,183,255,0.3)" stroke-width="1" stroke-linecap="round"/>
  
  <!-- Flip indicator corner -->
  <path d="M94 66l20-20" stroke="rgba(57,183,255,0.4)" stroke-width="0.8" stroke-dasharray="2 2" stroke-linecap="round"/>
</svg>
            </div>
            <div style="flex:1">
              <h3 style="margin:0 0 6px 0;color:#dffbff">3D Flashcards</h3>
            </div>
          </div>

          <div class="features" style="margin-top:16px">
            <div class="card">
              <h4>Volumetric</h4><p>3D flashcards and immersive models.</p>
            </div>
            <div class="card">
              <h4>Quiz</h4><p>Speak answers — instant scoring and feedback.</p>
            </div>
            <div class="card">
              <h4>Group Study</h4><p>Live rooms with synchronized whiteboards and avatars.</p>
            </div>
          </div>

        </div>

      </section>

      <aside class="right">
        <div class="owl-wrap" id="owlWrap" aria-hidden="false">
          <div class="owl-glow" aria-hidden="true"></div>

          <!-- SVG Owl -->
          <svg class="owl" id="owlSVG" viewBox="0 0 360 360" width="360" height="360">
            <defs>
              <linearGradient id="owlGradient" x1="0" x2="1">
                <stop offset="0" stop-color="#2a90b9ff" stop-opacity="0.16" />
                <stop offset="1" stop-color="#78f7d1" stop-opacity="0.06" />
              </linearGradient>
            </defs>

            <!-- wings group (animated separately) -->
            <g id="leftWing" transform="translate(40,160) rotate(-8)">
              <path class="feather" d="M20 10 C 6 30, 10 78, 30 92 C46 104, 68 108, 92 88 C112 72, 90 40, 68 34 C48 30,32 18,20 10 Z"/>
              <path class="neon-line" d="M20 10 C 6 30, 10 78, 30 92 C46 104, 68 108, 92 88 C112 72, 90 40, 68 34 C48 30,32 18,20 10 Z"/>
            </g>

            <g id="rightWing" transform="translate(320,160) scale(-1,1) rotate(-8)">
              <path class="feather" d="M20 10 C 6 30, 10 78, 30 92 C46 104, 68 108, 92 88 C112 72, 90 40, 68 34 C48 30,32 18,20 10 Z"/>
              <path class="neon-line" d="M20 10 C 6 30, 10 78, 30 92 C46 104, 68 108, 92 88 C112 72, 90 40, 68 34 C48 30,32 18,20 10 Z"/>
            </g>

            <!-- main body -->
            <g id="owlBody" transform="translate(60,40)">
              <ellipse cx="120" cy="160" rx="92" ry="110" class="glow-fill" style="opacity:0.06"></ellipse>
              <path d="M36 160 C36 100, 204 92, 204 160 C204 216, 144 268, 120 268 C96 268,36 216,36 160 Z" class="feather"/>
              <path d="M36 160 C36 100, 204 92, 204 160 C204 216, 144 268, 120 268 C96 268,36 216,36 160 Z" class="neon-line" style="stroke-opacity:0.28"/>
              <!-- feather details -->
              <g id="featherRows" transform="translate(68,138)">
                <path d="M0 0 q12 18 34 0" stroke="rgba(35, 159, 231, 0.14)" fill="none" stroke-width="1.2" />
                <path d="M0 18 q14 20 34 2" stroke="rgba(57,183,255,0.12)" fill="none" stroke-width="1.0" transform="translate(0,12)"/>
                <path d="M0 36 q12 18 34 0" stroke="rgba(57,183,255,0.10)" fill="none" stroke-width="0.9" transform="translate(0,24)"/>
              </g>

              <!-- head with eyes -->
              <g id="head" transform="translate(40,18)">
                <ellipse cx="120" cy="80" rx="82" ry="64" class="feather" />
                <path d="M38 74 C38 30, 200 28, 200 74" class="neon-line" style="stroke-opacity:0.18"/>

                <!-- left eye group -->
                <g id="leftEyeGroup" transform="translate(72,60)">
                  <circle class="eye-iris" cx="0" cy="0" r="22"/>
                  <circle class="eye-pupil" cx="2" cy="0" r="8"/>
                  <!-- eyelid overlay (for blinking) -->
                  <path class="eyelid" id="leftEyelid" d="M-26 -22 C -10 -28, 10 -28, 26 -22 C 12 -6, -12 -6, -26 -22 Z" transform="translate(0,0) scale(1,1)"/>
                </g>

                <!-- right eye group -->
                <g id="rightEyeGroup" transform="translate(168,60)">
                  <circle class="eye-iris" cx="0" cy="0" r="22"/>
                  <circle class="eye-pupil" cx="2" cy="0" r="8"/>
                  <path class="eyelid" id="rightEyelid" d="M-26 -22 C -10 -28, 10 -28, 26 -22 C 12 -6, -12 -6, -26 -22 Z"/>
                </g>

                <!-- beak (opens) -->
                <g id="beakGroup" transform="translate(120,96)">
                  <path id="beakTop" d="M -14 0 q14 10 28 0 q-10 -10 -28 0" fill="#a6eef9" opacity="0.95" stroke="rgba(50,150,200,0.16)" stroke-width="0.6" transform="translate(-14,-6)"/>
                  <path id="beakBottom" d="M -14 0 q14 12 28 0 q-10 -12 -28 0" fill="#78f7d1" opacity="0.9" stroke="rgba(40,120,150,0.12)" stroke-width="0.6" transform="translate(-14,4)"/>
                </g>

                <!-- cute ear tufts -->
                <path d="M50 40 q-6 -20 12 -28" stroke="rgba(57,183,255,0.16)" fill="none" />
                <path d="M210 40 q6 -20 -12 -28" stroke="rgba(57,183,255,0.16)" fill="none" />
              </g>

            </g>

            <!-- subtle outline around owl -->
            <circle cx="180" cy="160" r="136" fill="none" stroke="rgba(57,183,255,0.02)" />

          </svg>

          <!-- facial login floating button -->
          <div class="face-login" id="faceBtn" title="Zero click Facial Signup">
            <div class="face-icon" aria-hidden="true">
              <!-- face icon -->
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect x="1" y="1" width="22" height="22" rx="5" stroke="rgba(57,183,255,0.12)"/>
                <circle cx="8.5" cy="10.5" r="1.8" fill="#bfefff"/>
                <circle cx="15.5" cy="10.5" r="1.8" fill="#bfefff"/>
                <path d="M8 15c2 1.5 6 1.5 8 0" stroke="#9fe9ff" stroke-width="1.2" fill="none" stroke-linecap="round"/>
              </svg>
            </div>
            <div>
            </div>
          </div>

          

        </div>

        <div class="particles" aria-hidden="true"></div>
      </aside>
    </main>
  </div>

  <!-- webcam modal (simulated face-login) -->
  <div class="modal" id="modal">
    <div class="box">
      <h3>Facial Signup Demo</h3>
      <p style="color:var(--muted);margin-bottom:10px">This demo opens your webcam and simulates face detection. No data is sent anywhere.</p>
      <video id="video" autoplay playsinline></video>
      <div style="display:flex;gap:10px;justify-content:center;margin-top:12px;">
        <button id="closeModal" style="padding:8px 12px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);background:#0b1624;color:#e9fbff;cursor:pointer">Cancel</button>
        <button id="simulateLogin" style="padding:8px 12px;border-radius:8px;border:1px solid rgba(57,183,255,0.12);background:linear-gradient(90deg,#39b7ff,#6be0c8);color:#032;cursor:pointer;font-weight:700">Simulate Sign-in</button>
      </div>
    </div>
  </div>

<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>

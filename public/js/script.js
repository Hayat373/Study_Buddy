  // === Owl interactive animations using GSAP ===
  const leftWing = document.getElementById('leftWing');
  const rightWing = document.getElementById('rightWing');
  const head = document.getElementById('head');
  const owlWrap = document.getElementById('owlWrap');
  const leftEyelid = document.getElementById('leftEyelid');
  const rightEyelid = document.getElementById('rightEyelid');
  const beakTop = document.getElementById('beakTop');
  const beakBottom = document.getElementById('beakBottom');
  const owlSVG = document.getElementById('owlSVG');

  // set transform origins for rotation via GSAP
  gsap.set(leftWing, { transformOrigin: '20% 30%' });
  gsap.set(rightWing, { transformOrigin: '80% 30%' });
  gsap.set(head, { transformOrigin: '50% 40%' });
  gsap.set(beakTop, { transformOrigin: '50% 40%' });
  gsap.set(beakBottom, { transformOrigin: '50% 20%' });

  // subtle idle wing bob
  gsap.to([leftWing, rightWing], {
    rotation: '+=3',
    duration: 2.6,
    yoyo: true,
    repeat: -1,
    ease: "sine.inOut",
    stagger: 0.24
  });

  // blinking: animate eyelids to close then open
  function blinkOnce() {
    const tl = gsap.timeline();
    tl.to([leftEyelid, rightEyelid], { y: 8, duration: 0.12, ease: "power2.in" }) // move eyelids down
      .to([leftEyelid, rightEyelid], { scaleY: 0.15, transformOrigin: "50% 50%", duration: 0.08 }, "<")
      .to([leftEyelid, rightEyelid], { scaleY: 1, y:0, duration: 0.12, ease: "power2.out" }, "+=0.05");
  }

  // random blinking schedule
  function scheduleBlink(){
    const t = 1800 + Math.random() * 4200; // blink every ~1.8s to 6s
    setTimeout(() => {
      blinkOnce();
      scheduleBlink();
    }, t);
  }
  scheduleBlink();

  // mouse move mapping: wings open more when cursor near owl, head follows cursor
  owlWrap.addEventListener('pointermove', (ev) => {
    const rect = owlWrap.getBoundingClientRect();
    const cx = rect.left + rect.width/2;
    const cy = rect.top + rect.height/2;
    const dx = ev.clientX - cx;
    const dy = ev.clientY - cy;
    // normalized
    const nx = Math.max(-1, Math.min(1, dx / (rect.width/2)));
    const ny = Math.max(-1, Math.min(1, dy / (rect.height/2)));
    // wings rotate more with horizontal movement
    const wingAngle = nx * 28; // degrees
    gsap.to(leftWing, { rotation: -8 + wingAngle/1.4, duration: 0.28, ease: "power3.out" });
    gsap.to(rightWing, { rotation: -8 - wingAngle/1.4, duration: 0.28, ease: "power3.out" });
    // head tilt and slight position shift
    gsap.to(head, { rotation: ny * -10 + nx * 6, x: nx * 6, y: ny * 6, duration: 0.28, ease: "power3.out" });
    // slightly enlarge eyes based on proximity
    const eyeScale = 1 + Math.abs(nx)*0.06 + Math.abs(ny)*0.02;
    gsap.to('#leftEyeGroup circle:first-child, #rightEyeGroup circle:first-child', { scale: eyeScale, transformOrigin: "50% 50%", duration:0.3 });
  });

  // when leaving owl area, relax to default
  owlWrap.addEventListener('pointerleave', () => {
    gsap.to([leftWing, rightWing], { rotation: -8, duration: 0.6, ease: "elastic.out(1,0.6)" });
    gsap.to(head, { rotation: 0, x:0, y:0, duration: 0.6, ease: "elastic.out(1,0.6)" });
    gsap.to('#leftEyeGroup circle:first-child, #rightEyeGroup circle:first-child', { scale: 1, duration: 0.6 });
  });

  // Hover to open beak (shout)
  owlWrap.addEventListener('pointerenter', () => {
    // small twitch then open
    gsap.fromTo([beakTop, beakBottom], { scaleY: 0.95 }, { scaleY:1, duration:0.18 });
  });

  // open beak on hover and close on leave
  owlWrap.addEventListener('pointerover', (e) => {
    // open top up and bottom down
    gsap.to(beakTop, { y: -12, rotation: -6, duration: 0.18, ease: "power2.out" });
    gsap.to(beakBottom, { y: 12, rotation: 6, duration: 0.18, ease: "power2.out" });
    // playful shout scale/flash
    gsap.fromTo(owlWrap, { scale: 1 }, { scale: 1.02, duration: 0.18, yoyo: true, repeat: 1 });
  });
  owlWrap.addEventListener('pointerout', (e) => {
    gsap.to(beakTop, { y: 0, rotation:0, duration: 0.22, ease: "power2.out" });
    gsap.to(beakBottom, { y: 0, rotation:0, duration: 0.22, ease: "power2.out" });
  });

  // Clicking the owl can trigger a little callout
  owlWrap.addEventListener('click', () => {
    // short pulse + blink
    gsap.to(owlWrap, { scale: 1.03, duration: 0.08, yoyo:true, repeat:1 });
    blinkOnce();
  });

  // === Facial signup demo (simulated) ===
  const faceBtn = document.getElementById('faceBtn');
  const modal = document.getElementById('modal');
  const video = document.getElementById('video');
  const closeModal = document.getElementById('closeModal');
  const simulateLogin = document.getElementById('simulateLogin');
  let streamRef = null;

  faceBtn.addEventListener('click', async () => {
    // show modal and request camera permission (demo only)
    modal.style.display = 'flex';
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user", width: 640, height: 480 }, audio: false });
      streamRef = stream;
      video.srcObject = stream;
    } catch (err) {
      console.warn('Camera denied or not available', err);
      alert('Camera access was blocked or not available. This is a demo for facial signup.');
    }
  });

  closeModal.addEventListener('click', () => {
    modal.style.display = 'none';
    if (streamRef) {
      streamRef.getTracks().forEach(t => t.stop());
      streamRef = null;
      video.srcObject = null;
    }
  });

  simulateLogin.addEventListener('click', () => {
    // NOTE: This is a simulated flow for demo purposes only.
    simulateLogin.disabled = true;
    simulateLogin.textContent = 'Detecting...';
    setTimeout(() => {
      simulateLogin.textContent = 'Face Detected';
      simulateLogin.style.background = 'linear-gradient(90deg,#a4ffd6,#78f7d1)';
      setTimeout(() => {
        alert('Facial signup simulated. Welcome to Study Buddy!');
        closeModal.click();
        simulateLogin.disabled = false;
        simulateLogin.textContent = 'Simulate Sign-in';
        simulateLogin.style.background = '';
      }, 900);
    }, 1600);
  });

  // Accessibility: keyboard trigger for faceBtn
  faceBtn.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') faceBtn.click(); });

  // small decorative particle animation (CSS-level)
  (function makeParticles(){
    const p = document.querySelector('.particles');
    // animate the background via small GSAP pulse for subtle motion
    gsap.to(p, { opacity: 1, duration: 3, repeat: -1, yoyo: true, ease: "sine.inOut" });
  })();

  // Nice entrance animation for the page
  gsap.from('.container', { y: 30, opacity: 0, duration: 0.9, ease: "power2.out" });
  gsap.from('.owl', { y: 20, opacity: 0, duration: 0.9, delay:0.18, ease: "back.out(1.2)" });

  // make sure video stops when user navigates away
  window.addEventListener('beforeunload', () => {
    if (streamRef) streamRef.getTracks().forEach(t => t.stop());
  });

  // Final note in console
  console.log('Study Buddy demo loaded — owl interactive & facial signup simulated.');

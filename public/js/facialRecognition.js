const video = document.getElementById('video');
const modal = document.getElementById('modal');

// Load models
Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
  faceapi.nets.faceRecognitionNet.loadFromUri('/models')
]).then(startVideo);

function startVideo() {
  navigator.mediaDevices.getUserMedia({ video: {} })
    .then((stream) => {
      video.srcObject = stream;
      video.play();
    })
    .catch((err) => console.error('Error accessing webcam: ', err));
}

video.addEventListener('play', async () => {
  const canvas = faceapi.createCanvasFromMedia(video);
  document.body.append(canvas);
  
  // Detect faces
  setInterval(async () => {
    const results = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
    // Process results (e.g., compare descriptors with stored user data)
  }, 100);
});

// Function to handle facial recognition success
function handleFacialRecognitionSuccess() {
  // Redirect to dashboard or successful login
}

// Function to handle facial recognition failure
function handleFacialRecognitionFailure() {
  alert('Facial recognition failed. Please try again.');
}
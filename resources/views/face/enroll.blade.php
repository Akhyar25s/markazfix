<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran Wajah - Markaz</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#002d1a",
                        "primary-container": "#1a432f",
                        "on-primary-container": "#84b096",
                        "on-primary": "#ffffff",
                        "background": "#f8f9fa",
                        "on-background": "#191c1d",
                        "surface": "#ffffff",
                        "on-surface": "#191c1d",
                        "surface-variant": "#e1e3e4",
                        "on-surface-variant": "#414943",
                        "outline": "#717973",
                        "error": "#ba1a1a",
                        "on-error": "#ffffff",
                        "success": "#116d3a"
                    },
                    "fontFamily": {
                        "sans": ["Manrope", "sans-serif"]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background font-sans antialiased h-screen overflow-hidden flex flex-col justify-center items-center p-4">

<div class="max-w-xl w-full bg-surface rounded-3xl shadow-xl overflow-hidden border border-outline/20">
    <!-- Header -->
    <div class="bg-primary text-on-primary p-6 text-center">
        <span class="material-symbols-outlined text-[48px] mb-2 opacity-90">face</span>
        <h1 class="text-2xl font-bold tracking-tight">Pendaftaran Wajah</h1>
        <p class="text-on-primary/80 mt-1 text-sm">Persiapan sistem presensi otomatis I'tikaf.</p>
    </div>

    <!-- Content -->
    <div class="p-8">
        @if(isset($isRegistered) && $isRegistered)
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-6 text-center">
                <span class="material-symbols-outlined text-green-600 text-[48px] mb-2">check_circle</span>
                <h2 class="text-xl font-bold mb-2">Wajah Sudah Terdaftar!</h2>
                <p class="text-sm">Anda telah mendaftarkan profil wajah Anda di sistem. Anda siap mengikuti presensi otomatis.</p>
                <div class="mt-6 flex justify-center gap-3">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-surface border border-outline/30 rounded-xl font-semibold hover:bg-surface-variant transition-colors">Ke Dashboard</a>
                    <button onclick="startCamera()" class="px-5 py-2.5 bg-primary text-on-primary rounded-xl font-semibold hover:bg-primary-container transition-colors shadow-md">Daftar Ulang Wajah</button>
                </div>
            </div>
        @endif

        <div id="camera-container" class="{{ (isset($isRegistered) && $isRegistered) ? 'hidden' : 'block' }}">
            <!-- Camera Feed -->
            <div class="relative w-full aspect-square md:aspect-video bg-black rounded-2xl overflow-hidden shadow-inner mb-6 border-4 border-surface-variant">
                <video id="videoElement" autoplay playsinline class="w-full h-full object-cover transform scale-x-[-1]"></video>
                
                <!-- Overlay Guides -->
                <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                    <div class="w-48 h-64 border-2 border-dashed border-white/50 rounded-full flex items-center justify-center">
                        <div class="w-2 h-2 bg-white/50 rounded-full absolute top-1/3"></div>
                    </div>
                </div>

                <!-- Loading / Error State -->
                <div id="camera-overlay" class="absolute inset-0 bg-black/80 flex flex-col items-center justify-center text-white p-6 text-center">
                    <span class="material-symbols-outlined text-4xl mb-3 animate-spin">sync</span>
                    <p class="text-sm font-medium">Memulai kamera...</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-3 p-4 bg-surface-variant/30 rounded-xl">
                    <span class="material-symbols-outlined text-primary mt-0.5">lightbulb</span>
                    <div class="text-sm text-on-surface-variant">
                        <p class="font-semibold mb-1 text-on-surface">Tips Pendaftaran Optimal:</p>
                        <ul class="list-disc pl-4 space-y-1">
                            <li>Pastikan wajah Anda berada tepat di dalam garis oval putus-putus.</li>
                            <li>Gunakan pencahayaan yang terang, hindari membelakangi cahaya (backlight).</li>
                            <li>Lepaskan kacamata gelap atau masker.</li>
                        </ul>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('dashboard') }}" class="flex-1 px-5 py-3 text-center bg-surface border border-outline/30 text-on-surface rounded-xl font-semibold hover:bg-surface-variant transition-colors">Batal</a>
                    <button id="captureBtn" class="flex-[2] px-5 py-3 flex items-center justify-center gap-2 bg-primary text-on-primary rounded-xl font-bold hover:bg-primary-container transition-colors shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined">photo_camera</span>
                        Daftarkan Wajah
                    </button>
                </div>
            </div>
            
            <canvas id="canvasElement" class="hidden"></canvas>
        </div>
    </div>
</div>

<!-- Modal / Toast -->
<div id="toast" class="fixed top-6 right-6 transform transition-all duration-300 translate-x-[150%] opacity-0 bg-white shadow-2xl rounded-2xl p-4 flex items-center gap-4 border border-outline/10 z-50 min-w-[300px]">
    <div id="toast-icon-container" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
        <span id="toast-icon" class="material-symbols-outlined text-white"></span>
    </div>
    <div>
        <h4 id="toast-title" class="font-bold text-on-surface"></h4>
        <p id="toast-message" class="text-sm text-on-surface-variant"></p>
    </div>
</div>

<script>
    const video = document.getElementById('videoElement');
    const canvas = document.getElementById('canvasElement');
    const captureBtn = document.getElementById('captureBtn');
    const cameraOverlay = document.getElementById('camera-overlay');
    const cameraContainer = document.getElementById('camera-container');
    let stream = null;

    async function startCamera() {
        if(cameraContainer.classList.contains('hidden')) {
            cameraContainer.classList.remove('hidden');
        }

        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: "user",
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } 
            });
            video.srcObject = stream;
            
            video.onloadedmetadata = () => {
                cameraOverlay.style.display = 'none';
                captureBtn.disabled = false;
            };
        } catch (err) {
            console.error("Gagal mengakses kamera: ", err);
            cameraOverlay.innerHTML = `
                <span class="material-symbols-outlined text-error text-4xl mb-3">videocam_off</span>
                <p class="text-sm font-medium text-error mb-1">Gagal Mengakses Kamera</p>
                <p class="text-xs text-white/70">Mohon berikan izin browser untuk mengakses kamera Anda.</p>
            `;
            captureBtn.disabled = true;
        }
    }

    // Auto start if container is visible
    if(!cameraContainer.classList.contains('hidden')) {
        startCamera();
    }

    captureBtn.addEventListener('click', async () => {
        // Disable button and show loading
        const originalText = captureBtn.innerHTML;
        captureBtn.disabled = true;
        captureBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Memproses...';

        // Draw video frame to canvas
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        
        // Mirror the canvas context since video is mirrored via CSS
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Get base64 JPEG
        const base64Image = canvas.toDataURL('image/jpeg', 0.9);

        // Send to backend
        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch('/face/enroll', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ image: base64Image })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showToast('Berhasil', result.message, 'success');
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 2000);
            } else {
                showToast('Gagal', result.message || 'Terjadi kesalahan saat pendaftaran.', 'error');
                captureBtn.disabled = false;
                captureBtn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error submitting face:', error);
            showToast('Error Koneksi', 'Tidak dapat terhubung ke server.', 'error');
            captureBtn.disabled = false;
            captureBtn.innerHTML = originalText;
        }
    });

    function showToast(title, message, type) {
        const toast = document.getElementById('toast');
        const iconContainer = document.getElementById('toast-icon-container');
        const icon = document.getElementById('toast-icon');
        const titleEl = document.getElementById('toast-title');
        const msgEl = document.getElementById('toast-message');

        titleEl.textContent = title;
        msgEl.textContent = message;

        if(type === 'success') {
            iconContainer.className = 'w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-success';
            icon.textContent = 'check';
        } else {
            iconContainer.className = 'w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-error';
            icon.textContent = 'warning';
        }

        toast.classList.remove('translate-x-[150%]', 'opacity-0');
        
        setTimeout(() => {
            toast.classList.add('translate-x-[150%]', 'opacity-0');
        }, 4000);
    }
</script>
</body>
</html>

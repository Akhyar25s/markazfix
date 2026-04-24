<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scanner Absensi I'tikaf - Markaz</title>
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

<div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-6 bg-surface rounded-3xl shadow-xl overflow-hidden border border-outline/20">
    <!-- Camera Section -->
    <div class="p-6 bg-slate-50 border-r border-outline/10 flex flex-col items-center">
        <h2 class="text-xl font-bold text-primary mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">qr_code_scanner</span>
            Kamera Pemindai
        </h2>
        
        <div class="relative w-full aspect-[3/4] md:aspect-square bg-black rounded-2xl overflow-hidden shadow-inner border-4 border-primary/20">
            <video id="videoElement" autoplay playsinline class="w-full h-full object-cover transform scale-x-[-1]"></video>
            
            <!-- Overlay Guides -->
            <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                <div class="w-48 h-64 border-4 border-dashed border-primary-container/80 rounded-[4rem] flex items-center justify-center transition-all duration-300" id="scan-frame">
                    <!-- Scan line animation -->
                    <div id="scan-line" class="absolute w-full h-1 bg-primary shadow-[0_0_8px_rgba(0,45,26,0.8)] opacity-0 hidden"></div>
                </div>
            </div>

            <!-- Loading / Error State -->
            <div id="camera-overlay" class="absolute inset-0 bg-black/80 flex flex-col items-center justify-center text-white p-6 text-center">
                <span class="material-symbols-outlined text-4xl mb-3 animate-spin">sync</span>
                <p class="text-sm font-medium">Menghubungkan ke kamera...</p>
            </div>
        </div>

        <button id="scanBtn" class="w-full mt-6 px-5 py-4 flex items-center justify-center gap-2 bg-primary text-on-primary rounded-xl font-bold text-lg hover:bg-primary-container transition-colors shadow-lg active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
            <span class="material-symbols-outlined">document_scanner</span>
            Pindai Wajah Sekarang
        </button>
        
        <canvas id="canvasElement" class="hidden"></canvas>
    </div>

    <!-- Status & Information Section -->
    <div class="p-6 flex flex-col justify-between">
        <div>
            <div class="flex justify-between items-start mb-6 pb-6 border-b border-outline/10">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-primary">Absensi I'tikaf</h1>
                    <p class="text-on-surface-variant mt-1 text-sm">Sesi: Ramadhan 1445 H - Gelombang 1</p>
                </div>
                <a href="{{ route('dashboard') }}" class="p-2 hover:bg-surface-variant rounded-full transition-colors text-outline" title="Kembali ke Dashboard">
                    <span class="material-symbols-outlined">close</span>
                </a>
            </div>

            <div id="status-container" class="bg-surface-variant/30 border border-outline/20 rounded-2xl p-8 text-center flex flex-col items-center justify-center min-h-[250px]">
                <!-- Default State -->
                <div id="status-default" class="flex flex-col items-center">
                    <span class="material-symbols-outlined text-[64px] text-outline mb-4 opacity-50">face_retouching_natural</span>
                    <h3 class="text-xl font-bold text-on-surface mb-2">Menunggu Pemindaian</h3>
                    <p class="text-sm text-on-surface-variant max-w-xs">Arahkan wajah peserta ke dalam area bingkai pada kamera, lalu tekan tombol pindai.</p>
                </div>

                <!-- Loading State -->
                <div id="status-loading" class="flex flex-col items-center hidden">
                    <span class="material-symbols-outlined text-[48px] text-primary animate-spin mb-4">hourglass_empty</span>
                    <h3 class="text-xl font-bold text-primary">Memverifikasi Wajah...</h3>
                    <p class="text-sm text-on-surface-variant mt-2">Mencocokkan dengan data AWS Rekognition</p>
                </div>

                <!-- Success State -->
                <div id="status-success" class="flex flex-col items-center hidden">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4 text-green-600 border-4 border-green-200">
                        <span class="material-symbols-outlined text-[40px]">check</span>
                    </div>
                    <h3 class="text-2xl font-bold text-green-700 mb-1">Berhasil!</h3>
                    <p class="text-sm font-medium text-on-surface-variant mb-4" id="success-msg">Absensi tercatat untuk peserta.</p>
                    
                    <!-- Dummy info for simulation -->
                    <div class="w-full bg-white border border-green-100 rounded-xl p-4 text-left">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">P</div>
                            <div>
                                <p class="text-xs text-outline font-semibold uppercase tracking-wider">Identitas Dikenali</p>
                                <p class="text-sm font-bold text-on-surface" id="verified-user-id">User ID: #---</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error State -->
                <div id="status-error" class="flex flex-col items-center hidden">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4 text-red-600 border-4 border-red-200">
                        <span class="material-symbols-outlined text-[40px]">close</span>
                    </div>
                    <h3 class="text-xl font-bold text-red-700 mb-2">Gagal Dikenali</h3>
                    <p class="text-sm text-red-600/80 max-w-xs" id="error-msg">Wajah tidak cocok dengan data pendaftaran.</p>
                    <button onclick="resetStatus()" class="mt-6 px-4 py-2 border border-outline/30 rounded-lg text-sm font-semibold hover:bg-surface-variant transition-colors">Coba Lagi</button>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-outline/10 flex justify-between items-center text-xs text-outline">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">shield</span> Powered by AWS Rekognition</span>
            <span id="clock" class="font-mono">--:--:--</span>
        </div>
    </div>
</div>

<style>
    /* Custom scan line animation */
    @keyframes scan {
        0% { top: 0; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
    .animate-scan {
        display: block !important;
        animation: scan 1.5s ease-in-out infinite;
    }
</style>

<script>
    const video = document.getElementById('videoElement');
    const canvas = document.getElementById('canvasElement');
    const scanBtn = document.getElementById('scanBtn');
    const cameraOverlay = document.getElementById('camera-overlay');
    const scanLine = document.getElementById('scan-line');
    const scanFrame = document.getElementById('scan-frame');
    
    // Status Elements
    const sDefault = document.getElementById('status-default');
    const sLoading = document.getElementById('status-loading');
    const sSuccess = document.getElementById('status-success');
    const sError = document.getElementById('status-error');

    // Realtime Clock
    setInterval(() => {
        const now = new Date();
        document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID');
    }, 1000);

    let stream = null;

    async function startCamera() {
        try {
            // Priority to back camera for scanning others, but user camera is fine
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: "environment", // Use environment (back camera) ideally
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } 
            });
            
            // If the camera is front facing, we might want to mirror it. 
            // We kept the CSS transform scale-x-[-1] for testing, but typically back cameras shouldn't be mirrored.
            // For this demo, let's just keep the feed as is.
            video.srcObject = stream;
            
            video.onloadedmetadata = () => {
                cameraOverlay.style.display = 'none';
                scanBtn.disabled = false;
            };
        } catch (err) {
            console.error("Gagal mengakses kamera: ", err);
            cameraOverlay.innerHTML = `
                <span class="material-symbols-outlined text-error text-4xl mb-3">videocam_off</span>
                <p class="text-sm font-medium text-error mb-1">Gagal Mengakses Kamera</p>
                <p class="text-xs text-white/70">Periksa perizinan browser.</p>
            `;
            scanBtn.disabled = true;
        }
    }

    startCamera();

    function showStatus(state) {
        sDefault.classList.add('hidden');
        sLoading.classList.add('hidden');
        sSuccess.classList.add('hidden');
        sError.classList.add('hidden');

        if(state === 'loading') sLoading.classList.remove('hidden');
        else if(state === 'success') sSuccess.classList.remove('hidden');
        else if(state === 'error') sError.classList.remove('hidden');
        else sDefault.classList.remove('hidden');
    }

    function resetStatus() {
        showStatus('default');
        scanFrame.classList.remove('border-green-500', 'border-red-500');
        scanFrame.classList.add('border-primary-container/80');
    }

    scanBtn.addEventListener('click', async () => {
        scanBtn.disabled = true;
        scanLine.classList.add('animate-scan');
        showStatus('loading');
        
        scanFrame.classList.remove('border-red-500', 'border-green-500', 'border-primary-container/80');
        scanFrame.classList.add('border-primary');

        // Draw video frame to canvas
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        
        // Mirror compensation
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const base64Image = canvas.toDataURL('image/jpeg', 0.9);

        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch('/face/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ image: base64Image })
            });

            const result = await response.json();
            
            scanLine.classList.remove('animate-scan');

            if (response.ok && result.success) {
                document.getElementById('success-msg').textContent = result.message;
                document.getElementById('verified-user-id').textContent = 'User ID: #' + (result.user_id || '---');
                showStatus('success');
                
                scanFrame.classList.remove('border-primary');
                scanFrame.classList.add('border-green-500');
                
                // Auto reset after 3 seconds
                setTimeout(() => {
                    resetStatus();
                    scanBtn.disabled = false;
                }, 3000);

            } else {
                document.getElementById('error-msg').textContent = result.message || 'Wajah tidak dikenali.';
                showStatus('error');
                
                scanFrame.classList.remove('border-primary');
                scanFrame.classList.add('border-red-500');
                scanBtn.disabled = false;
            }
        } catch (error) {
            console.error('Error verifying face:', error);
            scanLine.classList.remove('animate-scan');
            document.getElementById('error-msg').textContent = 'Kesalahan jaringan. Tidak dapat terhubung ke server AWS.';
            showStatus('error');
            scanFrame.classList.remove('border-primary');
            scanFrame.classList.add('border-red-500');
            scanBtn.disabled = false;
        }
    });
</script>
</body>
</html>

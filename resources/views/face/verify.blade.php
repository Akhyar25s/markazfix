@extends('layouts.app')

@section('title', 'Absensi I\'tikaf - ' . $jadwal->nama_itikaf)

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in pb-8">
    <div class="flex items-center gap-4">
        <a href="/dashboard" class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-foreground">Absensi I'tikaf</h1>
            <p class="text-muted-foreground text-sm">{{ $jadwal->nama_itikaf }}</p>
        </div>
    </div>

    {{-- Jadwal Info Card --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-card/60 backdrop-blur border border-border/50 rounded-xl p-3 text-center">
            <div class="text-xs text-muted-foreground mb-1">Lokasi</div>
            <div class="text-sm font-semibold truncate" title="{{ $jadwal->nama_lokasi ?? '-' }}">{{ $jadwal->nama_lokasi ?? '-' }}</div>
        </div>
        <div class="bg-card/60 backdrop-blur border border-border/50 rounded-xl p-3 text-center">
            <div class="text-xs text-muted-foreground mb-1">Radius</div>
            <div class="text-sm font-semibold text-primary">{{ $jadwal->radius_meter }} m</div>
        </div>
        <div class="bg-card/60 backdrop-blur border border-border/50 rounded-xl p-3 text-center">
            <div class="text-xs text-muted-foreground mb-1">Status</div>
            <div class="text-sm font-semibold text-blue-400 animate-pulse">● {{ ucfirst($jadwal->status) }}</div>
        </div>
    </div>

    {{-- Main Card --}}
    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-2xl overflow-hidden relative">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 z-0 pointer-events-none"></div>

        <div class="relative z-10 p-4 sm:p-6">

            {{-- Step Indicator --}}
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-2 flex-1">
                    <div id="step1-indicator" class="h-8 w-8 rounded-full border-2 border-primary/50 flex items-center justify-center text-xs font-bold text-primary transition-all">1</div>
                    <div class="text-xs font-medium text-muted-foreground hidden sm:block">Verifikasi Lokasi</div>
                </div>
                <div class="h-px flex-1 bg-border mx-2" id="step-divider"></div>
                <div class="flex items-center gap-2 flex-1 justify-end">
                    <div class="text-xs font-medium text-muted-foreground hidden sm:block">Pindai Wajah</div>
                    <div id="step2-indicator" class="h-8 w-8 rounded-full border-2 border-border flex items-center justify-center text-xs font-bold text-muted-foreground transition-all">2</div>
                </div>
            </div>

            {{-- STEP 1: GPS Verification --}}
            <div id="step-1" class="flex flex-col items-center">
                <div id="gps-icon" class="h-24 w-24 rounded-full bg-primary/10 border-2 border-primary/20 flex items-center justify-center mb-6 transition-all">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-12 w-12 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                
                <h2 class="text-xl font-bold text-foreground mb-2 text-center">Verifikasi Lokasi</h2>
                <p class="text-muted-foreground text-sm text-center max-w-sm mb-6">Pastikan Anda berada di dalam area yang telah ditentukan. Sistem akan memeriksa lokasi GPS Anda secara otomatis.</p>

                <div id="gps-status" class="w-full max-w-sm bg-muted/30 rounded-xl p-4 text-sm text-center text-muted-foreground mb-6">
                    Tekan tombol di bawah untuk memulai verifikasi lokasi.
                </div>

                <x-button id="btn-check-location" type="button" class="w-full max-w-sm h-12 text-base shadow-lg shadow-primary/20">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5 mr-2"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Verifikasi Lokasi Saya
                </x-button>
            </div>

            {{-- STEP 2: Face Recognition --}}
            <div id="step-2" class="flex flex-col items-center hidden">
                
                <div class="relative w-full max-w-[340px] aspect-[3/4] bg-slate-900 rounded-3xl overflow-hidden border-4 border-primary/20 shadow-xl mb-6">
                    <video id="webcam" autoplay playsinline class="absolute inset-0 w-full h-full object-cover transform scale-x-[-1]"></video>
                    <canvas id="canvas" class="hidden"></canvas>

                    {{-- Frame Overlay --}}
                    <div class="absolute inset-0 pointer-events-none border-[12px] border-background/20 z-10"></div>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                        <div class="w-52 h-64 border-2 border-primary/60 rounded-[50%] relative overflow-hidden">
                            <div id="scan-line" class="absolute top-0 left-0 w-full h-1 bg-primary/80 shadow-[0_0_15px_3px_rgba(249,115,22,0.6)] opacity-0"></div>
                        </div>
                    </div>

                    {{-- Processing Overlay --}}
                    <div id="processing-overlay" class="absolute inset-0 bg-background/90 backdrop-blur-sm z-20 flex flex-col items-center justify-center hidden">
                        <div class="h-12 w-12 rounded-full border-4 border-primary/30 border-t-primary animate-spin mb-4"></div>
                        <p class="text-primary font-semibold animate-pulse">Memverifikasi...</p>
                    </div>
                </div>

                <x-button id="btn-scan" type="button" class="w-full max-w-[340px] h-12 text-base shadow-lg shadow-primary/20">
                    Pindai Wajah Sekarang
                </x-button>
            </div>

            {{-- RESULT --}}
            <div id="result-box" class="hidden mt-6">
                <div id="result-inner" class="p-6 rounded-2xl flex flex-col items-center text-center">
                    <div id="result-icon" class="h-16 w-16 rounded-full flex items-center justify-center mb-4"></div>
                    <h3 id="result-title" class="font-bold text-xl mb-1"></h3>
                    <p id="result-text" class="text-sm"></p>
                    <x-button id="btn-retry" type="button" variant="outline" class="mt-4 hidden">Coba Lagi</x-button>
                </div>
            </div>

        </div>
    </x-card>
</div>

{{-- Hidden data for JS --}}
<div id="jadwal-data"
    data-jadwal-id="{{ $jadwal->id }}"
    data-radius="{{ $jadwal->radius_meter }}"
    data-lat="{{ $jadwal->latitude }}"
    data-lng="{{ $jadwal->longitude }}"
    data-csrf="{{ csrf_token() }}"
    class="hidden">
</div>
@endsection

@push('styles')
<style>
    @keyframes scan {
        0%   { top: 0; opacity: 0; }
        10%  { opacity: 1; }
        90%  { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
    .animate-scan { animation: scan 2s linear infinite; }

    @keyframes ping-slow {
        0%, 100% { transform: scale(1); opacity: 0.7; }
        50%       { transform: scale(1.15); opacity: 1; }
    }
    .animate-ping-slow { animation: ping-slow 2s ease-in-out infinite; }
</style>
@endpush

@push('scripts')
<!-- Load face-api.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const jadwalData   = document.getElementById('jadwal-data');
    const JADWAL_ID    = jadwalData.dataset.jadwalId;
    const RADIUS       = parseFloat(jadwalData.dataset.radius);
    const CENTER_LAT   = parseFloat(jadwalData.dataset.lat);
    const CENTER_LNG   = parseFloat(jadwalData.dataset.lng);
    const CSRF         = jadwalData.dataset.csrf;

    const step1        = document.getElementById('step-1');
    const step2        = document.getElementById('step-2');
    const resultBox    = document.getElementById('result-box');
    const gpsStatus    = document.getElementById('gps-status');
    const gpsIcon      = document.getElementById('gps-icon');
    const step1Ind     = document.getElementById('step1-indicator');
    const step2Ind     = document.getElementById('step2-indicator');

    const btnCheckLoc  = document.getElementById('btn-check-location');
    const btnScan      = document.getElementById('btn-scan');
    const btnRetry     = document.getElementById('btn-retry');

    const video        = document.getElementById('webcam');
    const canvas       = document.getElementById('canvas');
    const scanLine     = document.getElementById('scan-line');
    const procOverlay  = document.getElementById('processing-overlay');

    let userLat = null, userLng = null;
    let cameraStream = null;
    let isCapturing = false;
    let modelsLoaded = false;

    const MODEL_URL = 'https://justadudewhohacks.github.io/face-api.js/models/';

    // =============================================
    // HAVERSINE (client-side preview)
    // =============================================
    function haversineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLon/2)**2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    // =============================================
    // STEP 1: GPS CHECK
    // =============================================
    btnCheckLoc.addEventListener('click', () => {
        gpsStatus.innerHTML = '<span class="text-primary animate-pulse">⟳ Mendapatkan lokasi GPS Anda...</span>';
        btnCheckLoc.disabled = true;

        if (!navigator.geolocation) {
            showGpsError('Browser Anda tidak mendukung GPS. Gunakan browser modern.');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                userLat = pos.coords.latitude;
                userLng = pos.coords.longitude;
                const accuracy = Math.round(pos.coords.accuracy);

                const distanceToCenter = haversineDistance(userLat, userLng, CENTER_LAT, CENTER_LNG);
                const isWithin = distanceToCenter <= RADIUS;

                if (isWithin) {
                    gpsStatus.innerHTML = `
                        <div class="text-green-500">
                            <div class="font-bold text-base mb-1">✓ Lokasi Terverifikasi</div>
                            <div>Jarak ke lokasi: <strong>${Math.round(distanceToCenter)} m</strong> (batas ${RADIUS} m)</div>
                            <div class="text-xs mt-1 text-green-500/70">Akurasi GPS: ±${accuracy} m</div>
                        </div>`;
                    gpsIcon.className = 'h-24 w-24 rounded-full bg-green-500/10 border-2 border-green-500/30 flex items-center justify-center mb-6 transition-all animate-ping-slow';
                    gpsIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-12 w-12 text-green-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';

                    // Mark step 1 done, transition to step 2
                    step1Ind.className = 'h-8 w-8 rounded-full bg-green-500 border-2 border-green-500 flex items-center justify-center text-xs font-bold text-white transition-all';
                    step1Ind.innerHTML = '✓';
                    step2Ind.className = 'h-8 w-8 rounded-full border-2 border-primary flex items-center justify-center text-xs font-bold text-primary transition-all';

                    setTimeout(() => {
                        step1.classList.add('hidden');
                        step2.classList.remove('hidden');
                        startCamera();
                    }, 1500);
                } else {
                    showGpsError(`Anda berada <strong>${Math.round(distanceToCenter)} m</strong> dari lokasi. Batas yang diizinkan adalah <strong>${RADIUS} m</strong>. Silakan mendekati lokasi pelaksanaan.`);
                }
            },
            (err) => {
                let msg = 'Gagal mendapatkan lokasi.';
                if (err.code === 1) msg = 'Izin lokasi ditolak. Aktifkan izin lokasi di browser Anda.';
                if (err.code === 2) msg = 'Lokasi tidak tersedia. Pastikan GPS aktif.';
                if (err.code === 3) msg = 'Waktu habis saat mencari lokasi. Coba lagi.';
                showGpsError(msg);
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    });

    function showGpsError(msg) {
        gpsStatus.innerHTML = `<div class="text-red-500"><div class="font-bold mb-1">✕ Lokasi di Luar Zona</div>${msg}</div>`;
        gpsIcon.className = 'h-24 w-24 rounded-full bg-red-500/10 border-2 border-red-500/30 flex items-center justify-center mb-6 transition-all';
        gpsIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-12 w-12 text-red-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        btnCheckLoc.disabled = false;
        btnCheckLoc.textContent = 'Coba Verifikasi Lagi';
    }

    // =============================================
    // STEP 2: CAMERA + FACE RECOGNITION
    // =============================================
    async function startCamera() {
        try {
            procOverlay.classList.remove('hidden');
            const overlayText = document.querySelector('#processing-overlay p');
            if (overlayText) overlayText.textContent = 'Memuat AI Wajah...';

            // Muat model face-api.js jika belum dimuat
            if (!modelsLoaded) {
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                modelsLoaded = true;
            }
            procOverlay.classList.add('hidden');
            if (overlayText) overlayText.textContent = 'Memverifikasi...';

            cameraStream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'user', width: 720, height: 960 } 
            });
            video.srcObject = cameraStream;
            scanLine.classList.add('animate-scan');
            scanLine.style.opacity = '1';
        } catch (err) {
            procOverlay.classList.add('hidden');
            showResult('error', 'Gagal Mengakses Kamera/AI', 'Pastikan izin kamera aktif. Error: ' + err.message, true);
        }
    }

    btnScan.addEventListener('click', () => {
        if (!cameraStream || isCapturing) return;
        captureAndVerify();
    });

    async function captureAndVerify() {
        isCapturing = true;
        procOverlay.classList.remove('hidden');
        scanLine.classList.remove('animate-scan');
        resultBox.classList.add('hidden');

        try {
            // Deteksi wajah dan ekstrak descriptor menggunakan TinyFaceDetector
            const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                procOverlay.classList.add('hidden');
                isCapturing = false;
                showResult('error', 'Wajah Tidak Terdeteksi', 'Posisikan wajah Anda di tengah kamera dengan pencahayaan yang cukup.', true);
                scanLine.classList.add('animate-scan');
                return;
            }

            // Ambil descriptor (array 128 float) dan ubah ke string JSON
            const descriptorArray = Array.from(detection.descriptor);
            const descriptorJson = JSON.stringify(descriptorArray);

            // POST ke server
            fetch('/face/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                },
                body: JSON.stringify({
                    image:     descriptorJson, // Mengirim deskriptor JSON wajah
                    latitude:  userLat,
                    longitude: userLng,
                    jadwal_id: JADWAL_ID,
                })
            })
            .then(res => res.json())
            .then(data => {
                procOverlay.classList.add('hidden');
                isCapturing = false;

                if (data.success) {
                    const isAlready = data.type === 'already_checked_in';
                    showResult(
                        isAlready ? 'warning' : 'success',
                        isAlready ? 'Sudah Absen Hari Ini' : '🎉 Absensi Berhasil!',
                        data.message,
                        true
                    );
                    // Stop camera after success
                    stopCamera();
                } else {
                    let errTitle = 'Verifikasi Gagal';
                    if (data.type === 'geofence_error')  errTitle = 'Lokasi Di Luar Zona';
                    if (data.type === 'face_mismatch')   errTitle = 'Wajah Tidak Cocok';
                    if (data.type === 'not_registered')  errTitle = 'Tidak Terdaftar';

                    showResult('error', errTitle, data.message, true);
                    scanLine.classList.add('animate-scan');
                }
            })
            .catch(() => {
                procOverlay.classList.add('hidden');
                isCapturing = false;
                showResult('error', 'Kesalahan Jaringan', 'Gagal menghubungi server. Periksa koneksi internet Anda.', true);
                scanLine.classList.add('animate-scan');
            });
        } catch (err) {
            procOverlay.classList.add('hidden');
            isCapturing = false;
            showResult('error', 'AI Error', 'Gagal memproses analisis wajah: ' + err.message, true);
            scanLine.classList.add('animate-scan');
        }
    }

    function stopCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(t => t.stop());
            cameraStream = null;
        }
    }

    function showResult(type, title, text, showBtn) {
        const colors = {
            success: { bg: 'bg-green-500/10 border border-green-500/30', icon: 'bg-green-500/20 text-green-500', title: 'text-green-500', text: 'text-green-500/80', svg: 'M5 13l4 4L19 7' },
            error:   { bg: 'bg-red-500/10 border border-red-500/30',     icon: 'bg-red-500/20 text-red-500',     title: 'text-red-500',   text: 'text-red-500/80',   svg: 'M6 18L18 6M6 6l12 12' },
            warning: { bg: 'bg-amber-500/10 border border-amber-500/30', icon: 'bg-amber-500/20 text-amber-500', title: 'text-amber-500', text: 'text-amber-500/80', svg: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' },
        };
        const c = colors[type] || colors.error;

        document.getElementById('result-inner').className = `p-6 rounded-2xl flex flex-col items-center text-center ${c.bg}`;
        document.getElementById('result-icon').className = `h-16 w-16 rounded-full flex items-center justify-center mb-4 ${c.icon}`;
        document.getElementById('result-icon').innerHTML = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-8 w-8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="${c.svg}"></path></svg>`;
        document.getElementById('result-title').className = `font-bold text-xl mb-1 ${c.title}`;
        document.getElementById('result-title').textContent = title;
        document.getElementById('result-text').className = `text-sm ${c.text}`;
        document.getElementById('result-text').textContent = text;

        btnRetry.classList.toggle('hidden', !showBtn || type === 'success' || type === 'warning');
        resultBox.classList.remove('hidden');
    }

    btnRetry.addEventListener('click', () => {
        resultBox.classList.add('hidden');
        if (!cameraStream) {
            startCamera();
        } else {
            scanLine.classList.add('animate-scan');
        }
    });
});
</script>
@endpush

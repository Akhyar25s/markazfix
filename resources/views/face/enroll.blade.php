@extends('layouts.app')

@section('title', 'Daftarkan Wajah')

@push('styles')
<style>
    #video-container { position: relative; display: inline-block; }
    #overlay-canvas { position: absolute; top: 0; left: 0; }
    .status-box { transition: all 0.4s; }
    #loading-models { display: flex; }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-black text-foreground">Pendaftaran Wajah</h1>
        <p class="text-muted-foreground mt-1">Daftarkan wajahmu untuk keperluan presensi I'tikaf.</p>
    </div>

    @if($isRegistered)
    <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/30 flex items-center gap-3">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-6 w-6 text-green-500 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <p class="font-semibold text-green-700">Wajah sudah terdaftar!</p>
            <p class="text-sm text-green-600">Kamu bisa melakukan presensi. Daftar ulang di bawah jika ingin memperbarui.</p>
        </div>
    </div>
    @endif

    <!-- Status Model Loading -->
    <div id="loading-models" class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/30 flex items-center gap-3">
        <div class="h-5 w-5 border-2 border-blue-500 border-t-transparent rounded-full animate-spin flex-shrink-0"></div>
        <p class="text-sm font-medium text-blue-700">Memuat model pengenalan wajah... (pertama kali mungkin 10-20 detik)</p>
    </div>

    <x-card class="p-6 space-y-5">
        <!-- Camera -->
        <div class="flex flex-col items-center gap-4">
            <div id="video-container" class="rounded-2xl overflow-hidden border-4 border-primary/30 shadow-xl">
                <video id="video" width="480" height="360" autoplay muted playsinline class="block bg-black rounded-xl"></video>
                <canvas id="overlay-canvas" width="480" height="360"></canvas>
            </div>

            <!-- Status box -->
            <div id="status-box" class="status-box w-full max-w-md p-3 rounded-xl bg-muted text-center text-sm text-muted-foreground">
                Menunggu kamera...
            </div>

            <!-- Capture Button -->
            <button id="btn-capture" disabled
                class="px-8 py-3 rounded-xl font-bold text-white bg-primary disabled:opacity-40 disabled:cursor-not-allowed hover:bg-primary/90 transition-all shadow-lg shadow-primary/30 text-lg w-full max-w-md">
                📸 Ambil Foto & Daftarkan Wajah
            </button>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@latest/model/';
let faceDetected = false;

async function loadModels() {
    try {
        await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
        await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
        await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
        document.getElementById('loading-models').innerHTML = 
            `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5 text-green-500 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
             <p class="text-sm font-medium text-green-700">Model AI siap digunakan!</p>`;
        startCamera();
    } catch(e) {
        document.getElementById('loading-models').innerHTML = 
            `<p class="text-sm font-medium text-red-600">Gagal memuat model. Periksa koneksi internet.</p>`;
    }
}

async function startCamera() {
    const video = document.getElementById('video');
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
        video.srcObject = stream;
        video.addEventListener('play', detectLoop);
        setStatus('Arahkan wajah ke kamera...', 'info');
    } catch(e) {
        setStatus('Kamera tidak dapat diakses. Izinkan akses kamera di browser.', 'error');
    }
}

async function detectLoop() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('overlay-canvas');
    const ctx = canvas.getContext('2d');
    const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.5 });

    setInterval(async () => {
        const detections = await faceapi.detectAllFaces(video, options).withFaceLandmarks();
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (detections.length === 1) {
            faceDetected = true;
            document.getElementById('btn-capture').disabled = false;
            setStatus('✅ Wajah terdeteksi! Siap untuk didaftarkan.', 'success');

            const resized = faceapi.resizeResults(detections, { width: 480, height: 360 });
            faceapi.draw.drawDetections(canvas, resized);
            faceapi.draw.drawFaceLandmarks(canvas, resized);
        } else if (detections.length === 0) {
            faceDetected = false;
            document.getElementById('btn-capture').disabled = true;
            setStatus('⚠️ Wajah tidak terdeteksi. Posisikan wajah di depan kamera.', 'warning');
        } else {
            faceDetected = false;
            document.getElementById('btn-capture').disabled = true;
            setStatus('⚠️ Lebih dari satu wajah terdeteksi. Pastikan hanya satu orang.', 'warning');
        }
    }, 500);
}

function setStatus(msg, type) {
    const box = document.getElementById('status-box');
    const colors = {
        info:    'bg-blue-500/10 text-blue-700 border border-blue-500/30',
        success: 'bg-green-500/10 text-green-700 border border-green-500/30',
        warning: 'bg-yellow-500/10 text-yellow-700 border border-yellow-500/30',
        error:   'bg-red-500/10 text-red-700 border border-red-500/30',
    };
    box.className = `status-box w-full max-w-md p-3 rounded-xl text-center text-sm font-medium ${colors[type] || ''}`;
    box.textContent = msg;
}

document.getElementById('btn-capture').addEventListener('click', async () => {
    const video = document.getElementById('video');
    const btn = document.getElementById('btn-capture');
    btn.disabled = true;
    btn.textContent = '⏳ Memproses...';

    const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.5 });
    const detection = await faceapi.detectSingleFace(video, options)
        .withFaceLandmarks()
        .withFaceDescriptor();

    if (!detection) {
        setStatus('❌ Gagal mendeteksi wajah saat pengambilan foto. Coba lagi.', 'error');
        btn.disabled = false;
        btn.textContent = '📸 Ambil Foto & Daftarkan Wajah';
        return;
    }

    // Kirim descriptor ke server
    const descriptor = Array.from(detection.descriptor);
    try {
        const resp = await fetch('/face/enroll', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ face_descriptor: JSON.stringify(descriptor) })
        });
        const data = await resp.json();
        if (data.success) {
            setStatus('🎉 ' + data.message, 'success');
            btn.textContent = '✅ Berhasil Terdaftar!';
        } else {
            setStatus('❌ ' + data.message, 'error');
            btn.disabled = false;
            btn.textContent = '📸 Ambil Foto & Daftarkan Wajah';
        }
    } catch(e) {
        setStatus('❌ Terjadi kesalahan. Periksa koneksi.', 'error');
        btn.disabled = false;
        btn.textContent = '📸 Ambil Foto & Daftarkan Wajah';
    }
});

loadModels();
</script>
@endpush

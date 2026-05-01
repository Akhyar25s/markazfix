@extends('layouts.app')

@section('title', 'Scan Kehadiran')

@push('styles')
<style>
    #video-container { position: relative; display: inline-block; }
    #overlay-canvas { position: absolute; top: 0; left: 0; }
    .status-box { transition: all 0.4s; }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-black text-foreground">Scan Kehadiran</h1>
        <p class="text-muted-foreground mt-1">
            Kegiatan: <span class="font-semibold text-primary">{{ $jadwal->nama_itikaf }}</span>
            &bull; {{ $jadwal->nama_lokasi }}
        </p>
    </div>

    <!-- Loading models -->
    <div id="loading-models" class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/30 flex items-center gap-3">
        <div class="h-5 w-5 border-2 border-blue-500 border-t-transparent rounded-full animate-spin flex-shrink-0"></div>
        <p class="text-sm font-medium text-blue-700">Memuat AI dan data wajah... harap tunggu.</p>
    </div>

    <x-card class="p-6 space-y-5">
        <div class="flex flex-col items-center gap-4">
            <div id="video-container" class="rounded-2xl overflow-hidden border-4 border-primary/30 shadow-xl">
                <video id="video" width="480" height="360" autoplay muted playsinline class="block bg-black rounded-xl"></video>
                <canvas id="overlay-canvas" width="480" height="360"></canvas>
            </div>

            <div id="status-box" class="status-box w-full max-w-md p-3 rounded-xl bg-muted text-center text-sm text-muted-foreground">
                Menunggu sistem siap...
            </div>

            <button id="btn-scan" disabled
                class="px-8 py-3 rounded-xl font-bold text-white bg-primary disabled:opacity-40 disabled:cursor-not-allowed hover:bg-primary/90 transition-all shadow-lg shadow-primary/30 text-lg w-full max-w-md">
                🔍 Scan & Catat Kehadiran
            </button>
        </div>
    </x-card>

    <!-- Log hasil scan -->
    <x-card class="p-6">
        <h3 class="font-bold text-foreground mb-3">Log Kehadiran Hari Ini</h3>
        <div id="log-list" class="space-y-2 max-h-60 overflow-y-auto">
            <p class="text-sm text-muted-foreground text-center py-4">Belum ada yang discan.</p>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@latest/model/';
let labeledDescriptors = [];
let faceMatcher = null;
let faceDetected = false;

async function loadModelsAndData() {
    try {
        // 1. Load AI models
        await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
        await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
        await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);

        // 2. Load face data dari server
        const resp = await fetch('/api/face-descriptors');
        const data = await resp.json();

        if (data.length === 0) {
            document.getElementById('loading-models').innerHTML = 
                `<p class="text-sm font-medium text-yellow-600">⚠️ Belum ada wajah yang terdaftar di database. Minta peserta untuk daftar wajah terlebih dahulu.</p>`;
            return;
        }

        // 3. Buat FaceMatcher dari data server
        labeledDescriptors = data.map(d => {
            const descriptor = new Float32Array(d.face_descriptor);
            return new faceapi.LabeledFaceDescriptors(
                JSON.stringify({ id: d.pengguna_id, name: d.nama }),
                [descriptor]
            );
        });

        faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.5); // threshold 0.5 = ketat

        document.getElementById('loading-models').innerHTML = 
            `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5 text-green-500 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
             <p class="text-sm font-medium text-green-700">✅ Siap! ${data.length} wajah terdaftar dalam sistem.</p>`;

        startCamera();
    } catch(e) {
        console.error(e);
        document.getElementById('loading-models').innerHTML = 
            `<p class="text-sm font-medium text-red-600">❌ Gagal memuat sistem. Periksa koneksi: ${e.message}</p>`;
    }
}

async function startCamera() {
    const video = document.getElementById('video');
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
        video.srcObject = stream;
        video.addEventListener('play', detectLoop);
        setStatus('Arahkan wajah ke kamera untuk dikenali...', 'info');
    } catch(e) {
        setStatus('❌ Kamera tidak dapat diakses.', 'error');
    }
}

async function detectLoop() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('overlay-canvas');
    const ctx = canvas.getContext('2d');
    const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.5 });

    setInterval(async () => {
        const detections = await faceapi.detectAllFaces(video, options).withFaceLandmarks().withFaceDescriptors();
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (detections.length > 0) {
            faceDetected = true;
            document.getElementById('btn-scan').disabled = false;

            const resized = faceapi.resizeResults(detections, { width: 480, height: 360 });

            // Gambar kotak dan nama yang cocok
            resized.forEach(det => {
                const match = faceMatcher ? faceMatcher.findBestMatch(det.descriptor) : null;
                const label = match && match.label !== 'unknown' 
                    ? JSON.parse(match.label).name + ` (${(100 - match.distance * 100).toFixed(0)}%)`
                    : '❓ Tidak dikenal';
                const color = match && match.label !== 'unknown' ? '#22c55e' : '#ef4444';

                // Draw box
                const box = det.detection.box;
                ctx.strokeStyle = color;
                ctx.lineWidth = 3;
                ctx.strokeRect(box.x, box.y, box.width, box.height);
                // Label
                ctx.fillStyle = color;
                ctx.fillRect(box.x, box.y - 24, box.width, 24);
                ctx.fillStyle = 'white';
                ctx.font = 'bold 13px sans-serif';
                ctx.fillText(label, box.x + 5, box.y - 6);
            });

            setStatus(`✅ ${detections.length} wajah terdeteksi. Klik Scan untuk catat kehadiran.`, 'success');
        } else {
            faceDetected = false;
            document.getElementById('btn-scan').disabled = true;
            setStatus('Arahkan wajah ke kamera...', 'info');
        }
    }, 600);
}

document.getElementById('btn-scan').addEventListener('click', async () => {
    const video = document.getElementById('video');
    const btn = document.getElementById('btn-scan');
    btn.disabled = true;
    btn.textContent = '⏳ Memindai...';

    const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.5 });
    const detection = await faceapi.detectSingleFace(video, options)
        .withFaceLandmarks()
        .withFaceDescriptor();

    if (!detection) {
        setStatus('❌ Tidak bisa mendeteksi wajah. Coba lagi.', 'error');
        btn.disabled = false;
        btn.textContent = '🔍 Scan & Catat Kehadiran';
        return;
    }

    const match = faceMatcher.findBestMatch(detection.descriptor);

    if (match.label === 'unknown') {
        addLog('❌ Wajah tidak dikenali dalam sistem.', 'error');
        setStatus('❌ Wajah tidak ada dalam database peserta.', 'error');
        btn.disabled = false;
        btn.textContent = '🔍 Scan & Catat Kehadiran';
        return;
    }

    const person = JSON.parse(match.label);

    // Catat absensi ke server
    try {
        const resp = await fetch('/face/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                pengguna_id: person.id,
                jadwal_id: {{ $jadwal->id }},
                latitude: null,
                longitude: null,
            })
        });
        const data = await resp.json();

        if (data.success) {
            if (data.type === 'already_checked_in') {
                addLog(`⚠️ ${data.nama} sudah absen hari ini.`, 'warning');
                setStatus('⚠️ ' + data.message, 'warning');
            } else {
                addLog(`✅ ${data.nama} — Kehadiran berhasil dicatat!`, 'success');
                setStatus('🎉 ' + data.message, 'success');
            }
        } else {
            addLog(`❌ ${data.message}`, 'error');
            setStatus('❌ ' + data.message, 'error');
        }
    } catch(e) {
        setStatus('❌ Gagal menghubungi server.', 'error');
    }

    setTimeout(() => {
        btn.disabled = false;
        btn.textContent = '🔍 Scan & Catat Kehadiran';
        setStatus('Arahkan wajah berikutnya...', 'info');
    }, 3000);
});

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

function addLog(msg, type) {
    const list = document.getElementById('log-list');
    const colors = {
        success: 'bg-green-500/10 text-green-700 border border-green-500/20',
        warning: 'bg-yellow-500/10 text-yellow-700 border border-yellow-500/20',
        error:   'bg-red-500/10 text-red-700 border border-red-500/20',
    };
    const time = new Date().toLocaleTimeString('id-ID');
    // Remove placeholder
    const placeholder = list.querySelector('p');
    if (placeholder) placeholder.remove();

    const el = document.createElement('div');
    el.className = `p-3 rounded-xl text-sm font-medium border ${colors[type] || ''} flex justify-between items-center`;
    el.innerHTML = `<span>${msg}</span><span class="text-xs opacity-60">${time}</span>`;
    list.prepend(el);
}

loadModelsAndData();
</script>
@endpush

@extends('layouts.app')

@section('title', 'Pendaftaran Wajah - Markaz')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in pb-8">
    <div class="flex flex-col gap-2">
        <h1 class="text-3xl font-extrabold tracking-tight text-foreground">Pendaftaran Wajah (Enrollment)</h1>
        <p class="text-muted-foreground">Daftarkan wajah Anda untuk absensi I'tikaf dan kegiatan lainnya menggunakan teknologi biometrik yang aman.</p>
    </div>

    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-xl overflow-hidden relative">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 z-0 pointer-events-none"></div>
        
        <div class="relative z-10 p-2 sm:p-6">
            @if($isRegistered)
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-500 p-4 rounded-xl flex items-start gap-3">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-6 w-6 mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-bold">Wajah Sudah Terdaftar</h3>
                    <p class="text-sm mt-1">Anda sudah melakukan pendaftaran wajah. Anda bisa mendaftar ulang jika ingin memperbarui data biometrik Anda.</p>
                </div>
            </div>
            @endif

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Camera Section -->
                <div class="flex-1 flex flex-col items-center">
                    <div class="relative w-full max-w-[320px] aspect-[3/4] bg-slate-900 rounded-2xl overflow-hidden border-2 border-primary/20 shadow-lg shadow-primary/10">
                        <video id="webcam" autoplay playsinline class="absolute inset-0 w-full h-full object-cover transform scale-x-[-1]"></video>
                        <canvas id="canvas" class="hidden"></canvas>
                        
                        <!-- Overlay Frame for Face Positioning -->
                        <div class="absolute inset-0 pointer-events-none border-[12px] border-background/20 z-10"></div>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                            <div class="w-48 h-64 border-2 border-dashed border-primary/70 rounded-[50%] animate-pulse"></div>
                        </div>

                        <!-- Status Overlay -->
                        <div id="status-overlay" class="absolute inset-0 bg-background/80 backdrop-blur-sm z-20 flex flex-col items-center justify-center hidden">
                            <div class="h-10 w-10 rounded-full border-4 border-primary/30 border-t-primary animate-spin mb-4"></div>
                            <p class="text-primary font-semibold">Memproses wajah...</p>
                        </div>
                    </div>

                    <div class="mt-6 w-full max-w-[320px] flex gap-3">
                        <x-button id="btn-start" type="button" class="flex-1" variant="outline">Nyalakan Kamera</x-button>
                        <x-button id="btn-capture" type="button" class="flex-1 hidden">Ambil Foto</x-button>
                    </div>
                </div>

                <!-- Instructions Section -->
                <div class="flex-1">
                    <h3 class="font-bold text-lg mb-4 text-foreground">Panduan Pendaftaran:</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div class="h-6 w-6 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">1</div>
                            <p class="text-sm text-muted-foreground">Pastikan Anda berada di ruangan dengan pencahayaan yang cukup. Hindari cahaya langsung dari belakang (backlight).</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="h-6 w-6 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">2</div>
                            <p class="text-sm text-muted-foreground">Posisikan wajah Anda tepat di dalam bingkai oval putus-putus pada layar kamera.</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="h-6 w-6 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">3</div>
                            <p class="text-sm text-muted-foreground">Lepaskan masker, kacamata hitam, atau topi yang menutupi bagian wajah Anda.</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="h-6 w-6 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">4</div>
                            <p class="text-sm text-muted-foreground">Klik "Ambil Foto" saat Anda sudah siap. Sistem akan mengirim data wajah ke server yang aman.</p>
                        </li>
                    </ul>

                    <div id="result-message" class="mt-6 hidden p-4 rounded-xl text-sm font-medium"></div>
                </div>
            </div>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
<!-- Load face-api.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const btnStart = document.getElementById('btn-start');
        const btnCapture = document.getElementById('btn-capture');
        const statusOverlay = document.getElementById('status-overlay');
        const resultMessage = document.getElementById('result-message');
        
        let stream = null;
        let modelsLoaded = false;

        const MODEL_URL = 'https://justadudewhohacks.github.io/face-api.js/models/';

        // Initialize Camera
        btnStart.addEventListener('click', async () => {
            try {
                btnStart.textContent = 'Memuat AI...';
                btnStart.disabled = true;

                // Muat model face-api.js jika belum dimuat
                if (!modelsLoaded) {
                    await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                    await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                    await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                    modelsLoaded = true;
                }

                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'user', width: 720, height: 960 } 
                });
                video.srcObject = stream;
                btnStart.classList.add('hidden');
                btnCapture.classList.remove('hidden');
            } catch (err) {
                console.error("Error accessing webcam/AI:", err);
                showResult('Gagal mengakses kamera/AI. Pastikan izin kamera aktif. Error: ' + err.message, 'error');
            } finally {
                btnStart.textContent = 'Nyalakan Kamera';
                btnStart.disabled = false;
            }
        });

        // Capture and Upload Descriptor
        btnCapture.addEventListener('click', async () => {
            if (!stream) return;
            
            // Show loading
            statusOverlay.classList.remove('hidden');
            resultMessage.classList.add('hidden');
            
            btnCapture.disabled = true;
            btnCapture.textContent = 'Menganalisis...';

            try {
                // Deteksi wajah dan ekstrak descriptor menggunakan TinyFaceDetector
                const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) {
                    statusOverlay.classList.add('hidden');
                    showResult("Wajah tidak terdeteksi! Posisikan wajah Anda tepat di dalam bingkai oval dengan pencahayaan yang cukup.", "error");
                    return;
                }

                // Ambil descriptor (array 128 float) dan ubah ke string JSON
                const descriptorArray = Array.from(detection.descriptor);
                const descriptorJson = JSON.stringify(descriptorArray);
                
                // Send to server
                fetch('/face/enroll', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ image: descriptorJson }) // Mengirim deskriptor JSON
                })
                .then(response => response.json())
                .then(data => {
                    statusOverlay.classList.add('hidden');
                    if (data.success) {
                        showResult(data.message, 'success');
                        // Stop camera after success
                        const tracks = stream.getTracks();
                        tracks.forEach(track => track.stop());
                        video.srcObject = null;
                        btnStart.textContent = 'Daftar Ulang';
                        btnStart.classList.remove('hidden');
                        btnCapture.classList.add('hidden');
                    } else {
                        showResult(data.message || 'Gagal mendaftarkan wajah', 'error');
                    }
                })
                .catch(error => {
                    statusOverlay.classList.add('hidden');
                    showResult('Terjadi kesalahan jaringan atau server.', 'error');
                    console.error('Error:', error);
                });
            } catch (err) {
                statusOverlay.classList.add('hidden');
                showResult('Kesalahan AI: ' + err.message, 'error');
            } finally {
                btnCapture.disabled = false;
                btnCapture.textContent = 'Ambil Foto';
            }
        });

        function showResult(message, type) {
            resultMessage.textContent = message;
            resultMessage.className = 'mt-6 p-4 rounded-xl text-sm font-medium ' + 
                (type === 'success' ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-red-500/10 text-red-500 border border-red-500/20');
            resultMessage.classList.remove('hidden');
        }
    });
</script>
@endpush

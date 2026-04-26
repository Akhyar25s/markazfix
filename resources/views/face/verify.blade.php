@extends('layouts.app')

@section('title', 'Verifikasi Wajah - Markaz')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in pb-8">
    <div class="flex flex-col gap-2 text-center md:text-left">
        <h1 class="text-3xl font-extrabold tracking-tight text-foreground">Absensi I'tikaf (Verifikasi Wajah)</h1>
        <p class="text-muted-foreground">Arahkan wajah Anda ke kamera untuk melakukan presensi kehadiran secara otomatis.</p>
    </div>

    <x-card class="backdrop-blur-md bg-card/80 border-primary/10 shadow-2xl overflow-hidden relative">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 z-0 pointer-events-none"></div>
        
        <div class="relative z-10 p-2 sm:p-6 flex flex-col items-center">
            
            <!-- Camera Section -->
            <div class="relative w-full max-w-[400px] aspect-[3/4] bg-slate-900 rounded-3xl overflow-hidden border-4 border-muted shadow-xl">
                <video id="webcam" autoplay playsinline class="absolute inset-0 w-full h-full object-cover transform scale-x-[-1]"></video>
                <canvas id="canvas" class="hidden"></canvas>
                
                <!-- Scanning Effect Overlay -->
                <div class="absolute inset-0 pointer-events-none border-[12px] border-background/20 z-10"></div>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                    <div class="w-56 h-72 border-2 border-primary/50 rounded-[50%] relative overflow-hidden">
                        <!-- Scan line animation -->
                        <div id="scan-line" class="absolute top-0 left-0 w-full h-1 bg-primary/80 shadow-[0_0_15px_rgba(var(--primary),0.8)] opacity-0"></div>
                    </div>
                </div>

                <!-- Status Overlay -->
                <div id="status-overlay" class="absolute inset-0 bg-background/80 backdrop-blur-sm z-20 flex flex-col items-center justify-center hidden transition-opacity">
                    <div class="h-12 w-12 rounded-full border-4 border-primary/30 border-t-primary animate-spin mb-4"></div>
                    <p class="text-primary font-semibold text-lg animate-pulse">Memverifikasi...</p>
                </div>
            </div>

            <div class="mt-8 w-full max-w-[400px]">
                <x-button id="btn-start" type="button" class="w-full h-12 text-lg shadow-lg shadow-primary/20">Mulai Pemindaian</x-button>
            </div>

            <!-- Result Message -->
            <div id="result-message" class="mt-6 w-full max-w-[400px] hidden">
                <div class="p-6 rounded-2xl flex flex-col items-center text-center shadow-lg">
                    <div id="result-icon" class="h-16 w-16 rounded-full flex items-center justify-center mb-4"></div>
                    <h3 id="result-title" class="font-bold text-xl mb-1"></h3>
                    <p id="result-text" class="text-sm font-medium"></p>
                </div>
            </div>
            
        </div>
    </x-card>
</div>
@endsection

@push('styles')
<style>
    @keyframes scan {
        0% { top: 0; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
    .animate-scan {
        animation: scan 2s linear infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const btnStart = document.getElementById('btn-start');
        const statusOverlay = document.getElementById('status-overlay');
        const resultMessage = document.getElementById('result-message');
        const resultIcon = document.getElementById('result-icon');
        const resultTitle = document.getElementById('result-title');
        const resultText = document.getElementById('result-text');
        const scanLine = document.getElementById('scan-line');
        
        let stream = null;

        // Initialize Camera
        btnStart.addEventListener('click', async () => {
            if (!stream) {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: { facingMode: 'user', width: 720, height: 960 } 
                    });
                    video.srcObject = stream;
                    btnStart.textContent = 'Pindai Sekarang';
                    scanLine.classList.add('animate-scan');
                    resultMessage.classList.add('hidden');
                } catch (err) {
                    console.error("Error accessing webcam:", err);
                    showErrorResult('Gagal mengakses kamera. Pastikan izin kamera telah diberikan.');
                }
            } else {
                captureAndVerify();
            }
        });

        function captureAndVerify() {
            // Show loading
            statusOverlay.classList.remove('hidden');
            resultMessage.classList.add('hidden');
            scanLine.classList.remove('animate-scan');
            
            // Capture image
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageBase64 = canvas.toDataURL('image/jpeg', 0.8);
            
            // Send to server
            fetch('/face/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ image: imageBase64 })
            })
            .then(response => response.json())
            .then(data => {
                statusOverlay.classList.add('hidden');
                if (data.success) {
                    showSuccessResult(data.message);
                } else {
                    showErrorResult(data.message || 'Wajah tidak dikenali');
                    scanLine.classList.add('animate-scan');
                    btnStart.textContent = 'Coba Lagi';
                }
            })
            .catch(error => {
                statusOverlay.classList.add('hidden');
                showErrorResult('Terjadi kesalahan jaringan atau server AWS Rekognition.');
                scanLine.classList.add('animate-scan');
                btnStart.textContent = 'Coba Lagi';
                console.error('Error:', error);
            });
        }

        function showSuccessResult(message) {
            resultMessage.classList.remove('hidden');
            resultMessage.firstElementChild.className = 'p-6 rounded-2xl flex flex-col items-center text-center shadow-lg bg-green-500/10 border border-green-500/30 text-green-500';
            
            resultIcon.className = 'h-16 w-16 rounded-full flex items-center justify-center mb-4 bg-green-500/20 text-green-500';
            resultIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-8 w-8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>';
            
            resultTitle.textContent = 'Verifikasi Berhasil!';
            resultTitle.className = 'font-bold text-xl mb-1 text-green-500';
            resultText.textContent = message;
            resultText.className = 'text-sm font-medium text-green-500/80';
            
            btnStart.textContent = 'Pindai Peserta Lain';
            scanLine.classList.remove('animate-scan');
        }

        function showErrorResult(message) {
            resultMessage.classList.remove('hidden');
            resultMessage.firstElementChild.className = 'p-6 rounded-2xl flex flex-col items-center text-center shadow-lg bg-red-500/10 border border-red-500/30 text-red-500';
            
            resultIcon.className = 'h-16 w-16 rounded-full flex items-center justify-center mb-4 bg-red-500/20 text-red-500';
            resultIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-8 w-8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            
            resultTitle.textContent = 'Verifikasi Gagal';
            resultTitle.className = 'font-bold text-xl mb-1 text-red-500';
            resultText.textContent = message;
            resultText.className = 'text-sm font-medium text-red-500/80';
        }
    });
</script>
@endpush

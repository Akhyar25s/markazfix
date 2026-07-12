@extends('layouts.app')

@section('title', 'Rekam Kegiatan - MARKAZ')

@section('content')
<div class="max-w-2xl mx-auto animate-in fade-in zoom-in-95 duration-500">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-black text-foreground tracking-tight">Rekam Kegiatan</h1>
        <p class="text-muted-foreground mt-2">Pilih kegiatan yang baru saja Anda lakukan dan rekam wajah untuk absensi.</p>
    </div>

    <div class="glass-card p-8 rounded-[2rem] shadow-2xl border border-white/60 relative overflow-hidden">
        {{-- Decorative Elements --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-primary/10 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 rounded-full bg-secondary/10 blur-2xl"></div>

        <form action="{{ route('absensi-kegiatan.store') }}" method="POST" id="absensiForm" class="relative z-10 space-y-6">
            @csrf
            <input type="hidden" name="photo" id="photo_input" required>

            {{-- Pilihan Kegiatan --}}
            <div>
                <label for="jenis_kegiatan_id" class="block text-sm font-bold text-foreground/80 mb-2">Jenis Kegiatan <span class="text-red-500">*</span></label>
                <select id="jenis_kegiatan_id" name="jenis_kegiatan_id" required
                        class="w-full px-4 py-3 bg-white/80 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground font-semibold">
                    <option value="">-- Pilih Jenis Kegiatan --</option>
                    @foreach($jenisKegiatans as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->nama_kegiatan }}</option>
                    @endforeach
                </select>
                @error('jenis_kegiatan_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Face Recognition Area --}}
            <div class="space-y-4">
                <div class="relative w-full aspect-[4/3] bg-black/5 rounded-2xl overflow-hidden border-2 border-dashed border-border group flex flex-col items-center justify-center">
                    
                    {{-- Video Element for Webcam --}}
                    <video id="webcam" class="absolute inset-0 w-full h-full object-cover hidden" autoplay playsinline></video>
                    
                    {{-- Canvas for Capturing Photo (Hidden) --}}
                    <canvas id="canvas" class="hidden"></canvas>
                    
                    {{-- Image Element to show captured photo --}}
                    <img id="captured_photo" class="absolute inset-0 w-full h-full object-cover hidden" />

                    {{-- Overlay Placeholder when camera is off --}}
                    <div id="camera_placeholder" class="text-center p-6 flex flex-col items-center justify-center h-full">
                        <div class="w-20 h-20 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-foreground font-bold text-lg">Kamera Belum Aktif</p>
                        <p class="text-muted-foreground text-sm mt-1 max-w-xs">Izinkan akses kamera dan posisikan wajah Anda di tengah layar.</p>
                    </div>

                    {{-- Scanning Overlay (Hidden by default) --}}
                    <div id="scanning_overlay" class="absolute inset-0 bg-primary/20 backdrop-blur-[2px] hidden flex flex-col items-center justify-center">
                        <div class="w-16 h-16 border-4 border-white border-t-primary rounded-full animate-spin"></div>
                        <p class="text-white font-bold mt-4 drop-shadow-md">Memverifikasi Wajah...</p>
                    </div>
                </div>

                {{-- Camera Controls --}}
                <div class="flex gap-3 justify-center">
                    <button type="button" id="start_camera_btn" class="flex-1 py-3 px-4 bg-secondary text-secondary-foreground font-bold rounded-xl hover:bg-secondary/80 transition-all shadow-sm">
                        Nyalakan Kamera
                    </button>
                    <button type="button" id="capture_btn" class="flex-1 py-3 px-4 bg-primary text-primary-foreground font-bold rounded-xl hover:bg-primary/90 transition-all shadow-md shadow-primary/30 hidden">
                        Ambil Foto
                    </button>
                    <button type="button" id="retake_btn" class="flex-1 py-3 px-4 bg-secondary text-secondary-foreground font-bold rounded-xl hover:bg-secondary/80 transition-all shadow-sm hidden">
                        Ulangi Foto
                    </button>
                </div>
            </div>

            <button type="submit" id="submit_btn" disabled class="w-full py-4 px-6 bg-emerald-500 text-white text-lg font-black rounded-xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 hover:-translate-y-1 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:shadow-none">
                Verifikasi &amp; Simpan Kegiatan
            </button>
            <a href="{{ route('absensi-kegiatan.index') }}" class="block w-full py-3 text-center text-muted-foreground font-semibold hover:text-foreground transition-colors">
                Batal
            </a>
        </form>
    </div>
</div>

{{-- ============================================================ --}}
{{-- POPUP MODAL NOTIFIKASI --}}
{{-- ============================================================ --}}
<div id="notif-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div id="notif-backdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeNotifModal()"></div>

    {{-- Modal Card --}}
    <div id="notif-card" class="relative bg-card border border-border rounded-3xl shadow-2xl w-full max-w-sm p-8 flex flex-col items-center text-center transform scale-90 opacity-0 transition-all duration-300">
        
        {{-- Icon --}}
        <div id="notif-icon-wrap" class="w-20 h-20 rounded-full flex items-center justify-center mb-5">
            <svg id="notif-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-10 h-10"></svg>
        </div>

        {{-- Title --}}
        <h3 id="notif-title" class="text-xl font-black text-foreground mb-2"></h3>

        {{-- Message --}}
        <p id="notif-message" class="text-sm text-muted-foreground leading-relaxed mb-6"></p>

        {{-- Close Button --}}
        <button onclick="closeNotifModal()" id="notif-close-btn"
            class="w-full py-3 px-6 font-bold rounded-xl transition-all text-white">
            Mengerti
        </button>
    </div>
</div>

{{-- Load face-api.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

{{-- Script logic for Camera & face-api.js --}}
<script>
// ============================================================
// POPUP MODAL HELPER
// ============================================================
const NOTIF_TYPES = {
    error: {
        wrap:   'bg-red-500/15 border-2 border-red-500/30',
        icon:   'text-red-500',
        title:  'text-red-600',
        btn:    'bg-red-500 hover:bg-red-600 shadow-lg shadow-red-500/30',
        svg:    'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z',
    },
    warning: {
        wrap:   'bg-amber-500/15 border-2 border-amber-500/30',
        icon:   'text-amber-500',
        title:  'text-amber-600',
        btn:    'bg-amber-500 hover:bg-amber-600 shadow-lg shadow-amber-500/30',
        svg:    'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z',
    },
    info: {
        wrap:   'bg-primary/10 border-2 border-primary/30',
        icon:   'text-primary',
        title:  'text-primary',
        btn:    'bg-primary hover:bg-primary/90 shadow-lg shadow-primary/30',
        svg:    'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    },
};

function showNotif(type, title, message) {
    const cfg    = NOTIF_TYPES[type] || NOTIF_TYPES.error;
    const modal  = document.getElementById('notif-modal');
    const card   = document.getElementById('notif-card');
    const wrap   = document.getElementById('notif-icon-wrap');
    const svg    = document.getElementById('notif-icon-svg');
    const ttl    = document.getElementById('notif-title');
    const msg    = document.getElementById('notif-message');
    const btn    = document.getElementById('notif-close-btn');

    // Apply styles
    wrap.className = `w-20 h-20 rounded-full flex items-center justify-center mb-5 ${cfg.wrap}`;
    svg.innerHTML  = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${cfg.svg}"/>`;
    svg.className  = `w-10 h-10 ${cfg.icon}`;
    ttl.className  = `text-xl font-black mb-2 ${cfg.title}`;
    ttl.textContent = title;
    msg.textContent = message;
    btn.className  = `w-full py-3 px-6 font-bold rounded-xl transition-all text-white ${cfg.btn}`;

    // Show
    modal.classList.remove('hidden');
    // Animate in
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            card.classList.remove('scale-90', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    });
}

function closeNotifModal() {
    const modal = document.getElementById('notif-modal');
    const card  = document.getElementById('notif-card');
    card.classList.remove('scale-100', 'opacity-100');
    card.classList.add('scale-90', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 250);
}

// Close on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeNotifModal();
});

// ============================================================
// AUTO-TRIGGER dari session('error') server-side
// (Wajah tidak cocok, wajah tidak dikenali, dsb)
// ============================================================
@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotif(
            'error',
            'Verifikasi Wajah Gagal',
            @json(session('error'))
        );
    });
@endif

@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotif(
            'info',
            'Berhasil!',
            @json(session('success'))
        );
    });
@endif

// ============================================================
// KAMERA & FACE-API LOGIC
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const video           = document.getElementById('webcam');
    const canvas          = document.getElementById('canvas');
    const photoInput      = document.getElementById('photo_input');
    const capturedPhoto   = document.getElementById('captured_photo');
    const cameraPlaceholder = document.getElementById('camera_placeholder');
    
    const startBtn        = document.getElementById('start_camera_btn');
    const captureBtn      = document.getElementById('capture_btn');
    const retakeBtn       = document.getElementById('retake_btn');
    const submitBtn       = document.getElementById('submit_btn');
    const form            = document.getElementById('absensiForm');
    const scanningOverlay = document.getElementById('scanning_overlay');
    
    let stream       = null;
    let modelsLoaded = false;

    const MODEL_URL = 'https://justadudewhohacks.github.io/face-api.js/models/';

    // ── Start Camera ──────────────────────────────────────────
    startBtn.addEventListener('click', async () => {
        try {
            startBtn.innerHTML = 'Memuat AI Wajah...';
            startBtn.disabled  = true;

            if (!modelsLoaded) {
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                modelsLoaded = true;
            }

            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
            video.srcObject = stream;
            video.classList.remove('hidden');
            cameraPlaceholder.classList.add('hidden');
            startBtn.classList.add('hidden');
            captureBtn.classList.remove('hidden');
        } catch (err) {
            showNotif('error', 'Kamera Tidak Dapat Diakses', 'Tidak dapat mengakses kamera atau AI wajah. Pastikan izin kamera diaktifkan.\n\nDetail: ' + err.message);
        } finally {
            startBtn.innerHTML = 'Nyalakan Kamera';
            startBtn.disabled  = false;
        }
    });

    // ── Capture Photo & Extract Descriptor ────────────────────
    captureBtn.addEventListener('click', async () => {
        if (!stream) return;

        captureBtn.disabled  = true;
        captureBtn.innerHTML = 'Menganalisis...';

        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const photoDataUrl = canvas.toDataURL('image/jpeg');

        try {
            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                showNotif(
                    'warning',
                    'Wajah Tidak Terdeteksi',
                    'Wajah Anda tidak terdeteksi oleh kamera. Pastikan:\n• Wajah berada di tengah layar\n• Pencahayaan cukup terang\n• Tidak ada objek menghalangi wajah'
                );
                return;
            }

            const descriptorArray = Array.from(detection.descriptor);
            photoInput.value = JSON.stringify(descriptorArray);

            capturedPhoto.src = photoDataUrl;
            capturedPhoto.classList.remove('hidden');
            video.classList.add('hidden');
            
            captureBtn.classList.add('hidden');
            retakeBtn.classList.remove('hidden');
            submitBtn.disabled = false;
            
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        } catch (err) {
            showNotif('error', 'Kesalahan Analisis Wajah', 'Terjadi kesalahan saat menganalisis wajah Anda. Coba lagi.\n\nDetail: ' + err.message);
        } finally {
            captureBtn.disabled  = false;
            captureBtn.innerHTML = 'Ambil Foto';
        }
    });

    // ── Retake Photo ──────────────────────────────────────────
    retakeBtn.addEventListener('click', async () => {
        capturedPhoto.classList.add('hidden');
        photoInput.value = '';
        retakeBtn.classList.add('hidden');
        submitBtn.disabled = true;
        
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
            video.srcObject = stream;
            video.classList.remove('hidden');
            captureBtn.classList.remove('hidden');
        } catch (err) {
            showNotif('error', 'Kamera Tidak Dapat Diakses', 'Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.\n\nDetail: ' + err.message);
            startBtn.classList.remove('hidden');
            cameraPlaceholder.classList.remove('hidden');
        }
    });

    // ── Form Submit ───────────────────────────────────────────
    form.addEventListener('submit', function(e) {
        if (!photoInput.value) {
            e.preventDefault();
            showNotif('warning', 'Foto Belum Diambil', 'Silakan nyalakan kamera dan ambil foto wajah Anda terlebih dahulu sebelum menyimpan.');
            return;
        }
        
        const jenisKegiatan = document.getElementById('jenis_kegiatan_id').value;
        if (!jenisKegiatan) {
            e.preventDefault();
            showNotif('warning', 'Jenis Kegiatan Belum Dipilih', 'Silakan pilih jenis kegiatan yang baru saja Anda lakukan sebelum menyimpan.');
            return;
        }

        submitBtn.disabled    = true;
        retakeBtn.disabled    = true;
        scanningOverlay.classList.remove('hidden');
    });
});
</script>
@endsection

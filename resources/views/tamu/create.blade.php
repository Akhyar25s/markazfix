@extends('layouts.app')

@section('title', 'Daftarkan Tamu / Walk-in - MARKAZ')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500 max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ url('/dashboard') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white border border-border/50 text-muted-foreground hover:text-primary transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Pendaftaran Tamu / Walk-in</h1>
            <p class="text-sm text-muted-foreground mt-0.5">Daftarkan jamaah yang datang langsung tanpa akun.</p>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Error --}}
    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
        <ul class="list-disc pl-4 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Info Banner --}}
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-3 text-sm text-blue-700">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="font-bold mb-1">Cara Penggunaan</p>
            <p>Isi nama dan asal daerah tamu, lalu ambil foto wajah menggunakan kamera. Tamu akan langsung terdaftar dengan status <strong>Tamu</strong> dan siap melakukan absensi via face recognition.</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('tamu.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="glass-card p-8 rounded-2xl space-y-6 border border-white/60">

            {{-- Nama Lengkap --}}
            <div class="space-y-2">
                <label for="name" class="block text-sm font-bold text-foreground/80">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    placeholder="Masukkan nama lengkap tamu"
                    class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground placeholder:text-muted-foreground/50">
            </div>

            {{-- Asal Daerah --}}
            <div class="space-y-2">
                <label for="asal_daerah" class="block text-sm font-bold text-foreground/80">Asal Daerah <span class="text-red-500">*</span></label>
                <input type="text" id="asal_daerah" name="asal_daerah" value="{{ old('asal_daerah') }}" required
                    placeholder="Contoh: Yaman, Malaysia, Kalimantan Timur, dll."
                    class="w-full px-4 py-3 bg-white/60 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-foreground placeholder:text-muted-foreground/50">
            </div>

            {{-- Foto Wajah (Webcam) --}}
            <div class="space-y-3">
                <label class="block text-sm font-bold text-foreground/80">Foto Wajah <span class="text-red-500">*</span>
                    <span class="text-xs font-normal text-muted-foreground ml-1">(Arahkan wajah ke kamera, lalu ambil foto)</span>
                </label>

                <div class="flex flex-col items-center border border-border/50 rounded-2xl p-6 bg-white/30 backdrop-blur-md shadow-inner">
                    {{-- Camera Container --}}
                    <div id="camera-container" class="relative bg-black/5 rounded-2xl overflow-hidden flex flex-col items-center justify-center mb-4 shadow-md border border-white/50" style="width: 240px; height: 240px;">
                        <video id="video" class="absolute inset-0 w-full h-full object-cover hidden" style="transform: scaleX(-1);" autoplay playsinline></video>
                        <canvas id="canvas" class="hidden"></canvas>
                        <img id="photo-preview" class="absolute inset-0 w-full h-full object-cover hidden" />
                        <div id="camera-placeholder" class="text-muted-foreground/60 flex flex-col items-center justify-center py-12">
                            <svg class="w-14 h-14 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                            <span class="text-sm font-semibold tracking-wide">Kamera Nonaktif</span>
                        </div>
                    </div>

                    {{-- Controls --}}
                    <div class="flex gap-3">
                        <button type="button" id="start-camera"
                            class="px-5 py-2.5 bg-white border border-border text-foreground font-bold rounded-xl hover:bg-gray-50 hover:shadow-md transition-all text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Buka Kamera
                        </button>
                        <button type="button" id="take-photo"
                            class="px-5 py-2.5 bg-gradient-to-r from-secondary to-yellow-500 border border-transparent text-white font-bold rounded-xl hover:opacity-90 shadow-md transition-all text-sm flex items-center gap-2 hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                            Ambil Foto
                        </button>
                        <button type="button" id="retake-photo"
                            class="px-5 py-2.5 bg-white border border-border text-foreground font-bold rounded-xl hover:bg-gray-50 transition-all text-sm flex items-center gap-2 hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Ulangi
                        </button>
                    </div>

                    <input type="hidden" id="foto_wajah_depan" name="foto_wajah_depan" required>

                    <p id="photo-status" class="text-emerald-600 bg-emerald-50 px-4 py-2 rounded-lg text-xs font-bold mt-4 flex items-center gap-2 border border-emerald-200 hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Foto wajah berhasil diambil
                    </p>
                </div>
            </div>

        </div>

        {{-- Submit --}}
        <button type="submit" id="submit-btn"
            class="w-full flex justify-center items-center gap-2 py-4 px-4 rounded-xl text-sm font-bold text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-4 focus:ring-primary/30 transition-all shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:translate-y-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Daftarkan Tamu
        </button>
    </form>

</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const startBtn = document.getElementById('start-camera');
    const takeBtn = document.getElementById('take-photo');
    const retakeBtn = document.getElementById('retake-photo');
    const placeholder = document.getElementById('camera-placeholder');
    const photoPreview = document.getElementById('photo-preview');
    const photoStatus = document.getElementById('photo-status');
    const fotoInput = document.getElementById('foto_wajah_depan');
    const submitBtn = document.getElementById('submit-btn');
    let stream = null;

    startBtn.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
            video.srcObject = stream;
            video.classList.remove('hidden');
            placeholder.classList.add('hidden');
            startBtn.classList.add('hidden');
            takeBtn.classList.remove('hidden');
        } catch (err) {
            alert('Tidak dapat mengakses kamera: ' + err.message);
        }
    });

    takeBtn.addEventListener('click', () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
        fotoInput.value = dataUrl;
        photoPreview.src = dataUrl;
        photoPreview.style.transform = 'none';
        
        video.classList.add('hidden');
        photoPreview.classList.remove('hidden');
        takeBtn.classList.add('hidden');
        retakeBtn.classList.remove('hidden');
        photoStatus.classList.remove('hidden');

        if (stream) stream.getTracks().forEach(t => t.stop());
    });

    retakeBtn.addEventListener('click', () => {
        fotoInput.value = '';
        photoPreview.classList.add('hidden');
        photoStatus.classList.add('hidden');
        retakeBtn.classList.add('hidden');
        placeholder.classList.remove('hidden');
        startBtn.classList.remove('hidden');
    });

    document.querySelector('form').addEventListener('submit', (e) => {
        if (!fotoInput.value) {
            e.preventDefault();
            alert('Harap ambil foto wajah terlebih dahulu!');
        }
    });
</script>
@endsection

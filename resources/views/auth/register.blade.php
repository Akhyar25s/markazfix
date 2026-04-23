<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - MARKAZ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground antialiased min-h-screen flex items-center justify-center p-4 py-12 selection:bg-primary/30 relative overflow-x-hidden">

    <!-- Animated Background Blobs -->
    <div class="absolute top-[5%] right-[-10%] w-[50rem] h-[50rem] bg-secondary/10 rounded-full blur-[120px] -z-10 animate-blob"></div>
    <div class="absolute bottom-[5%] left-[-10%] w-[50rem] h-[50rem] bg-primary/10 rounded-full blur-[120px] -z-10 animate-blob" style="animation-delay: 2s;"></div>

    <div class="glass-card p-8 sm:p-12 w-full max-w-2xl rounded-3xl relative z-10 animate-in fade-in zoom-in duration-700 shadow-2xl shadow-primary/5 border border-white/60">
        
        <div class="text-center mb-10">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-secondary to-yellow-500 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-secondary/30 animate-float" style="animation-delay: 1s;">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold mb-2 tracking-tight">Buat Akun Baru</h1>
            <p class="text-muted-foreground font-medium text-sm">Bergabunglah dengan <span class="text-gradient">MARKAZ</span> sekarang</p>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-600 text-sm backdrop-blur-md">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/register" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Informasi Pribadi -->
            <div class="space-y-4">
                <h2 class="text-lg font-bold text-foreground border-b border-border pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Informasi Pribadi
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label for="name" class="block text-sm font-bold text-foreground/80 pl-1">Nama Lengkap *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Fulan bin Fulan" class="w-full px-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all placeholder:text-muted-foreground/50 text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                    </div>

                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-bold text-foreground/80 pl-1">Alamat Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com" class="w-full px-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all placeholder:text-muted-foreground/50 text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                    </div>

                    <div class="space-y-1">
                        <label for="no_telepon" class="block text-sm font-bold text-foreground/80 pl-1">Nomor HP *</label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required placeholder="Contoh: 08123456789" class="w-full px-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all placeholder:text-muted-foreground/50 text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-bold text-foreground/80 pl-1">Kata Sandi *</label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter" class="w-full px-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all placeholder:text-muted-foreground/50 text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                    </div>
                </div>
            </div>

            <!-- Organisasi -->
            <div class="space-y-4">
                <h2 class="text-lg font-bold text-foreground border-b border-border pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Lokasi & Organisasi
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label for="wilayah_id" class="block text-sm font-bold text-foreground/80 pl-1">Halaqah Wilayah</label>
                        <select id="wilayah_id" name="wilayah_id" class="w-full px-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                            <option value="">Pilih Wilayah (Opsional)</option>
                            @foreach(\App\Models\Wilayah::all() as $wilayah)
                                <option value="{{ $wilayah->id }}" {{ old('wilayah_id') == $wilayah->id ? 'selected' : '' }}>{{ $wilayah->nama_wilayah }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label for="mahallah_id" class="block text-sm font-bold text-foreground/80 pl-1">Mahallah (Masjid)</label>
                        <select id="mahallah_id" name="mahallah_id" class="w-full px-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                            <option value="">Pilih Mahallah (Opsional)</option>
                            @foreach(\App\Models\Mahallah::all() as $mahallah)
                                <option value="{{ $mahallah->id }}" {{ old('mahallah_id') == $mahallah->id ? 'selected' : '' }}>{{ $mahallah->nama_mahallah }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Enrollment Wajah -->
            <div class="space-y-4">
                <h2 class="text-lg font-bold text-foreground border-b border-border pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pendaftaran Wajah
                </h2>
                <p class="text-xs font-medium text-muted-foreground">Arahkan wajah Anda ke kamera dan pastikan pencahayaan cukup. Klik "Buka Kamera" lalu "Ambil Foto".</p>
                
                <div class="flex flex-col items-center border border-border/50 rounded-2xl p-6 bg-white/30 backdrop-blur-md shadow-inner">
                    <!-- Video Preview -->
                    <div id="camera-container" class="relative bg-black/5 rounded-2xl overflow-hidden flex flex-col items-center justify-center mb-6 shadow-md border border-white/50" style="width: 240px; height: 240px;">
                        <!-- Video stream (di-mirror agar seperti cermin) -->
                        <video id="video" class="absolute inset-0 w-full h-full object-cover hidden" style="transform: scaleX(-1);" autoplay playsinline></video>
                        <!-- Canvas untuk memproses tangkapan (tidak ditampilkan) -->
                        <canvas id="canvas" class="hidden"></canvas>
                        <!-- Image preview untuk menampilkan hasil jepretan -->
                        <img id="photo-preview" class="absolute inset-0 w-full h-full object-cover hidden" style="transform: scaleX(-1);" />
                        
                        <!-- Placeholder/Icon when camera is off -->
                        <div id="camera-placeholder" class="text-muted-foreground/60 flex flex-col items-center justify-center py-12 transition-all">
                            <svg class="w-14 h-14 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                            <span class="text-sm font-semibold tracking-wide">Kamera Nonaktif</span>
                        </div>
                    </div>

                    <!-- Camera Controls -->
                    <div class="flex gap-3">
                        <button type="button" id="start-camera" class="px-5 py-2.5 bg-white border border-border text-foreground font-bold rounded-xl hover:bg-gray-50 hover:shadow-md transition-all text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Buka Kamera
                        </button>
                        <button type="button" id="take-photo" class="px-5 py-2.5 bg-gradient-to-r from-secondary to-yellow-500 border border-transparent text-white font-bold rounded-xl hover:opacity-90 shadow-md shadow-secondary/20 transition-all text-sm flex items-center gap-2 hidden animate-in zoom-in">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                            Ambil Foto
                        </button>
                        <button type="button" id="retake-photo" class="px-5 py-2.5 bg-white border border-border text-foreground font-bold rounded-xl hover:bg-gray-50 hover:shadow-md transition-all text-sm flex items-center gap-2 hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Ulangi
                        </button>
                    </div>

                    <!-- Hidden Input for Base64 Image -->
                    <input type="hidden" id="foto_wajah_base64" name="foto_wajah_base64" required>
                    
                    <p id="photo-status" class="text-emerald-600 bg-emerald-50 px-4 py-2 rounded-lg text-xs font-bold mt-4 flex items-center gap-2 border border-emerald-200 shadow-sm hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Foto wajah berhasil direkam
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 rounded-xl text-sm font-bold text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-4 focus:ring-primary/30 transition-all shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 hover:-translate-y-0.5">
                    Daftar Sekarang
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
        
        <div class="mt-8 border-t border-border pt-6 text-center">
            <p class="text-sm font-medium text-muted-foreground">
                Sudah punya akun? 
                <a href="/login" class="font-bold text-primary hover:text-primary/80 transition-colors">Masuk di sini</a>
            </p>
        </div>
    </div>

    <!-- Script untuk WebCam -->
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const startBtn = document.getElementById('start-camera');
        const takeBtn = document.getElementById('take-photo');
        const retakeBtn = document.getElementById('retake-photo');
        const placeholder = document.getElementById('camera-placeholder');
        const base64Input = document.getElementById('foto_wajah_base64');
        const photoStatus = document.getElementById('photo-status');
        const form = document.querySelector('form');
        
        let stream = null;

        // Buka Kamera
        startBtn.addEventListener('click', async () => {
            try {
                // Tampilkan loading state
                startBtn.innerHTML = '<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memuat...';
                startBtn.disabled = true;

                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: "user" }, // Paksa gunakan kamera depan di HP
                    audio: false 
                });
                
                video.srcObject = stream;
                
                // Pastikan video dimainkan
                await video.play();

                video.classList.remove('hidden');
                placeholder.classList.add('hidden');
                canvas.classList.add('hidden');
                
                startBtn.classList.add('hidden');
                takeBtn.classList.remove('hidden');
                retakeBtn.classList.add('hidden');
                photoStatus.classList.add('hidden');
                base64Input.value = '';
            } catch (err) {
                alert("Gagal mengakses kamera. Error: " + err.message);
                console.error("Camera error:", err);
            } finally {
                startBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> Buka Kamera';
                startBtn.disabled = false;
            }
        });

        // Ambil Foto
        takeBtn.addEventListener('click', () => {
            if(!stream) return;
            
            // Set canvas size sama dengan video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Gambar frame video ke canvas
            const context = canvas.getContext('2d');
            
            // Mirror canvas horizontal (karena video di-mirror, hasil fotonya kita mirror juga agar konsisten)
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Ambil data base64 (format JPEG dengan quality 0.8)
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
            base64Input.value = dataUrl;
            
            // Tampilkan foto di img preview
            const preview = document.getElementById('photo-preview');
            preview.src = dataUrl;
            preview.classList.remove('hidden');
            
            // Sembunyikan video stream live
            video.classList.add('hidden');
            
            // Matikan stream kamera untuk menghemat baterai
            stream.getTracks().forEach(track => track.stop());
            stream = null;
            
            // Update UI Button
            takeBtn.classList.add('hidden');
            retakeBtn.classList.remove('hidden');
            photoStatus.classList.remove('hidden');
        });

        // Ulangi Foto
        retakeBtn.addEventListener('click', () => {
            // Sembunyikan preview
            document.getElementById('photo-preview').classList.add('hidden');
            startBtn.click(); // Panggil ulang fungsi buka kamera
        });

        // Validasi sebelum submit
        form.addEventListener('submit', (e) => {
            if(!base64Input.value) {
                e.preventDefault();
                alert('Anda harus mengambil foto wajah (Scan Kamera) terlebih dahulu!');
            }
        });
    </script>
</body>
</html>

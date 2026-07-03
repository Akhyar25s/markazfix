<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - MARKAZ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground antialiased min-h-screen flex items-center justify-center p-4 selection:bg-primary/30 relative overflow-hidden">

    <!-- Animated Background Blobs -->
    <div class="absolute top-[-10%] right-[-5%] w-[40rem] h-[40rem] bg-secondary/10 rounded-full blur-[100px] -z-10 animate-blob"></div>
    <div class="absolute bottom-[-10%] left-[-5%] w-[40rem] h-[40rem] bg-primary/10 rounded-full blur-[100px] -z-10 animate-blob" style="animation-delay: 2s;"></div>

    <div class="glass-card p-10 sm:p-12 w-full max-w-md rounded-3xl relative z-10 animate-in fade-in zoom-in duration-700">
        
        <div class="text-center mb-10">
            <a href="{{ route('password.request') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground hover:text-primary mb-6 transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Ganti Nomor / Email
            </a>
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-teal-500/20">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold mb-2 tracking-tight">Verifikasi OTP</h1>
            <p class="text-muted-foreground font-medium text-sm">Masukkan 6-digit kode verifikasi yang telah kami kirimkan.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-600 text-sm font-semibold backdrop-blur-md">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-600 text-sm backdrop-blur-md">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.otp.submit') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="flex justify-between gap-2 sm:gap-3">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" name="otp[]" maxlength="1" required
                        class="otp-input w-12 h-14 text-center text-2xl font-black bg-white/50 border border-border focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl outline-none transition-all shadow-sm backdrop-blur-sm"
                        autocomplete="off" inputmode="numeric" pattern="[0-9]*">
                @endfor
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-4 focus:ring-primary/30 transition-all shadow-md shadow-primary/20 hover:shadow-lg hover:shadow-primary/40 hover:-translate-y-0.5">
                    Verifikasi Kode
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>
                </button>
            </div>
        </form>
        
        <div class="mt-8 text-center">
            <p class="text-sm font-medium text-muted-foreground">
                Tidak menerima kode? 
                <form action="{{ route('password.email') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="login" value="{{ session('reset_identifier') }}">
                    <button type="submit" class="font-bold text-secondary hover:text-secondary/80 transition-colors bg-transparent border-none p-0 cursor-pointer">Kirim ulang</button>
                </form>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');
            
            inputs.forEach((input, index) => {
                // Focus input pertama secara otomatis
                if (index === 0) input.focus();

                input.addEventListener('input', function() {
                    // Hapus karakter non-angka
                    this.value = this.value.replace(/[^0-9]/g, '');

                    // Pindah ke input berikutnya jika diisi
                    if (this.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', function(e) {
                    // Pindah ke input sebelumnya jika menekan Backspace pada kolom kosong
                    if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Mempermudah paste kode OTP langsung (semua digit sekaligus)
                input.addEventListener('paste', function(e) {
                    const pasteData = e.clipboardData.getData('text').trim();
                    if (pasteData.length === 6 && /^\d+$/.test(pasteData)) {
                        inputs.forEach((inputEl, idx) => {
                            inputEl.value = pasteData[idx];
                        });
                        inputs[5].focus();
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>

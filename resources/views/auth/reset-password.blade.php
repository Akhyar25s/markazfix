<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - MARKAZ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground antialiased min-h-screen flex items-center justify-center p-4 selection:bg-primary/30 relative overflow-hidden">

    <!-- Animated Background Blobs -->
    <div class="absolute top-[-10%] right-[-5%] w-[40rem] h-[40rem] bg-secondary/10 rounded-full blur-[100px] -z-10 animate-blob"></div>
    <div class="absolute bottom-[-10%] left-[-5%] w-[40rem] h-[40rem] bg-primary/10 rounded-full blur-[100px] -z-10 animate-blob" style="animation-delay: 2s;"></div>

    <div class="glass-card p-10 sm:p-12 w-full max-w-md rounded-3xl relative z-10 animate-in fade-in zoom-in duration-700">
        
        <div class="text-center mb-10">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-purple-500/20">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold mb-2 tracking-tight">Password Baru</h1>
            <p class="text-muted-foreground font-medium text-sm">Buat kata sandi baru untuk mengamankan akun Anda.</p>
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

        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="space-y-1">
                <label for="password" class="block text-sm font-bold text-foreground/80 pl-1">Kata Sandi Baru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-muted-foreground">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter" class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all placeholder:text-muted-foreground/50 text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                </div>
            </div>

            <div class="space-y-1">
                <label for="password_confirmation" class="block text-sm font-bold text-foreground/80 pl-1">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-muted-foreground">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ketik ulang kata sandi baru" class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all placeholder:text-muted-foreground/50 text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-4 focus:ring-primary/30 transition-all shadow-md shadow-primary/20 hover:shadow-lg hover:shadow-primary/40 hover:-translate-y-0.5">
                    Perbarui Kata Sandi
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>
        </form>
        
    </div>
</body>
</html>

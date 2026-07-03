<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - MARKAZ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground antialiased min-h-screen flex items-center justify-center p-4 selection:bg-primary/30 relative overflow-hidden">

    <!-- Animated Background Blobs -->
    <div class="absolute top-[-10%] right-[-5%] w-[40rem] h-[40rem] bg-secondary/10 rounded-full blur-[100px] -z-10 animate-blob"></div>
    <div class="absolute bottom-[-10%] left-[-5%] w-[40rem] h-[40rem] bg-primary/10 rounded-full blur-[100px] -z-10 animate-blob" style="animation-delay: 2s;"></div>

    <div class="glass-card p-10 sm:p-12 w-full max-w-md rounded-3xl relative z-10 animate-in fade-in zoom-in duration-700">
        
        <div class="text-center mb-10">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground hover:text-primary mb-6 transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Login
            </a>
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-orange-500/20">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold mb-2 tracking-tight">Lupa Password?</h1>
            <p class="text-muted-foreground font-medium text-sm">Masukkan WhatsApp atau Email Anda. Kami akan mengirimkan kode verifikasi OTP.</p>
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

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-1">
                <label for="login" class="block text-sm font-bold text-foreground/80 pl-1">Nomor WhatsApp / Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-muted-foreground">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="08xxxxxxxxx atau email@contoh.com" required class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all placeholder:text-muted-foreground/50 text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-4 focus:ring-primary/30 transition-all shadow-md shadow-primary/20 hover:shadow-lg hover:shadow-primary/40 hover:-translate-y-0.5">
                    Kirim Kode OTP
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
        
    </div>
</body>
</html>

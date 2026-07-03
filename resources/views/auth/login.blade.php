<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MARKAZ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground antialiased min-h-screen flex items-center justify-center p-4 selection:bg-primary/30 relative overflow-y-auto overflow-x-hidden">

    <!-- Animated Background Blobs -->
    <div class="absolute top-[-10%] right-[-5%] w-[40rem] h-[40rem] bg-secondary/10 rounded-full blur-[100px] -z-10 animate-blob"></div>
    <div class="absolute bottom-[-10%] left-[-5%] w-[40rem] h-[40rem] bg-primary/10 rounded-full blur-[100px] -z-10 animate-blob" style="animation-delay: 2s;"></div>

    <div class="glass-card p-10 sm:p-12 w-full max-w-md rounded-3xl relative z-10 animate-in fade-in zoom-in duration-700">
        
        <div class="text-center mb-10">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-accent to-primary rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-primary/30 animate-float">
                <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 11.5v-3h4v3H8z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold mb-2 tracking-tight">Selamat Datang</h1>
            <p class="text-muted-foreground font-medium text-sm">Masuk untuk melanjutkan ke sistem <span class="text-gradient">MARKAZ</span></p>
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

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-1">
                <label for="login" class="block text-sm font-bold text-foreground/80 pl-1">Email / Nomor HP</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-muted-foreground">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </div>
                    <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="email@contoh.com atau 08123456789" required class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all placeholder:text-muted-foreground/50 text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                </div>
                @error('login')
                    <p class="text-xs text-red-500 pl-1 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <div class="flex items-center justify-between pl-1 pr-1">
                    <label for="password" class="block text-sm font-bold text-foreground/80">Kata Sandi</label>
                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-primary hover:text-primary/80 transition-colors px-3 py-2 rounded-lg hover:bg-primary/10 -mr-2 touch-manipulation" style="min-height: 44px; display: inline-flex; align-items: center;">
                        🔑 Lupa sandi?
                    </a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-muted-foreground">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl outline-none transition-all placeholder:text-muted-foreground/50 text-foreground shadow-sm backdrop-blur-sm hover:bg-white/70">
                </div>
            </div>

            <div class="flex items-center pt-2">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-primary bg-white border-border rounded focus:ring-primary/50 focus:ring-2 accent-primary transition-colors cursor-pointer">
                <label for="remember_me" class="ml-2 block text-sm font-medium text-muted-foreground cursor-pointer select-none">
                    Ingat sesi saya
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-4 focus:ring-primary/30 transition-all shadow-md shadow-primary/20 hover:shadow-lg hover:shadow-primary/40 hover:-translate-y-0.5">
                    Masuk
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
        
        <div class="mt-8 text-center">
            <p class="text-sm font-medium text-muted-foreground">Belum punya akun?</p>
            <a href="/register" class="mt-2 inline-flex items-center gap-1.5 font-bold text-secondary hover:text-secondary/80 transition-colors px-4 py-2 rounded-xl hover:bg-secondary/10 touch-manipulation" style="min-height: 44px;">
                Daftar sekarang →
            </a>
        </div>
    </div>
</body>
</html>


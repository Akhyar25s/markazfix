<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Markaz')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Let's add Alpine.js for some simple interactions like dropdowns and sidebar toggle -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-background text-foreground antialiased min-h-screen flex selection:bg-primary/30" x-data="{ sidebarOpen: true }">
    
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-0'" class="fixed inset-y-0 left-0 z-50 flex flex-col glass border-r transition-all duration-500 ease-in-out md:relative md:translate-x-0 shadow-lg" x-show="true">
        <div class="flex h-20 shrink-0 items-center border-b border-primary/10 px-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-accent/5 to-primary/5"></div>
            <a href="/dashboard" class="flex items-center gap-3 font-extrabold text-2xl tracking-tight relative z-10">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent to-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 animate-float">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 11.5v-3h4v3H8z"></path>
                    </svg>
                </div>
                <span class="text-gradient">MARKAZ</span>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-6 px-4">
            <div class="text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-4 px-2">Menu Utama</div>
            <ul class="space-y-2">
                <li>
                    <a href="/dashboard" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </li>
                
                @auth
                    @if(Auth::user()->role === 'pengurus_inti')
                        <li>
                            <a href="/jadwal" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('jadwal*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Jadwal I'tikaf
                            </a>
                        </li>
                        <li>
                            <a href="/wilayah" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('wilayah*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Data Wilayah
                            </a>
                        </li>
                        <li>
                            <a href="/mahallah" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('mahallah*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Data Mahallah
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('laporan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Laporan Presensi
                            </a>
                        </li>
                    @elseif(Auth::user()->role === 'pengurus_wilayah')
                        <li>
                            <a href="/peserta" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('peserta*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Daftarkan Peserta
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('jadwal-saya*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Jadwal Saya
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('scan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Scan Kehadiran
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </nav>
        
        <!-- Decorative blob in sidebar -->
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl -z-10 animate-blob"></div>
    </aside>
    
    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <!-- Background decorative blobs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-3xl -z-10 animate-blob" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-0 left-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl -z-10 animate-blob" style="animation-delay: 4s;"></div>

        <!-- Navbar -->
        <header class="flex h-20 shrink-0 items-center justify-between glass border-b border-primary/10 px-8 z-10">
            <button @click="sidebarOpen = !sidebarOpen" class="text-muted-foreground hover:text-primary transition-colors md:hidden p-2 bg-white/50 rounded-lg">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="hidden md:flex flex-1 items-center gap-4">
                <div class="h-8 w-1 bg-gradient-to-b from-primary to-secondary rounded-full"></div>
                <span class="text-sm font-semibold text-foreground/80 tracking-wide">Sistem Informasi Manajemen Organisasi</span>
            </div>
            
            <div class="flex items-center gap-6">
                @auth
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-foreground">{{ Auth::user()->name }}</div>
                    <div class="text-xs font-medium text-secondary capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</div>
                </div>
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center border border-primary/20 shadow-sm">
                    <span class="font-bold text-primary">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 transition-all border border-red-100 shadow-sm">
                        Keluar
                    </button>
                </form>
                @endauth
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8 z-10 scroll-smooth">
            <div class="mx-auto max-w-7xl">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>

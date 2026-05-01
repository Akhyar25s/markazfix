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
                            <a href="/laporan" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('laporan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
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
                        <li>
                            <a href="/face/enroll" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('face/enroll*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Pendaftaran Wajah
                            </a>
                        </li>
                        <li>
                            <a href="/face/verify" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('face/verify*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                Scan Kehadiran
                            </a>
                        </li>
                        <li>
                            <a href="/laporan" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('laporan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Laporan Presensi
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Akun Saya
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
            
            <div class="flex items-center gap-4 sm:gap-6">
                @auth
                <!-- Notification Bell -->
                @php
                    $activeItikafs = \App\Models\JadwalItikaf::where('status', 'berlangsung')->get();
                @endphp
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" class="relative p-2 text-muted-foreground hover:text-primary transition-colors focus:outline-none">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($activeItikafs->count() > 0)
                            <span class="absolute top-1 right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div x-show="notifOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-3 w-80 bg-white/90 backdrop-blur-xl border border-border rounded-2xl shadow-xl overflow-hidden z-50 origin-top-right hidden"
                         :class="{ 'hidden': !notifOpen }">
                        <div class="p-4 border-b border-border bg-muted/30">
                            <h3 class="font-bold text-sm text-foreground">Notifikasi</h3>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse($activeItikafs as $notif)
                            <a href="{{ route('dashboard') }}" class="block p-4 border-b border-border/50 hover:bg-primary/5 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="h-8 w-8 rounded-full bg-green-500/20 flex items-center justify-center text-green-600 flex-shrink-0 mt-0.5">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-foreground">I'tikaf Berlangsung!</p>
                                        <p class="text-xs text-muted-foreground mt-1 line-clamp-2">Kegiatan <span class="font-semibold">{{ $notif->nama_itikaf }}</span> saat ini sedang berlangsung. Harap segera instruksikan jamaah untuk presensi.</p>
                                        <p class="text-[10px] text-primary font-medium mt-2">{{ \Carbon\Carbon::parse($notif->updated_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="p-6 text-center text-muted-foreground">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-10 w-10 mx-auto opacity-20 mb-2"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm">Tidak ada notifikasi baru.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="text-right hidden sm:block border-l border-border pl-4">
                    <div class="text-sm font-bold text-foreground">{{ Auth::user()->name }}</div>
                    <div class="text-xs font-medium text-secondary capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</div>
                </div>
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center border border-primary/20 shadow-sm relative group cursor-pointer">
                    <span class="font-bold text-primary">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    <!-- Simple dropdown for logout on hover -->
                    <div class="absolute right-0 top-12 w-48 bg-white border border-border rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                        <form method="POST" action="{{ route('logout') }}" class="p-2">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 rounded-lg text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
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

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
<body class="bg-background text-foreground antialiased min-h-screen flex selection:bg-primary/30" x-data="{ sidebarOpen: window.innerWidth >= 768 }">
    
    <!-- Sidebar Mobile Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm transition-opacity md:hidden" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
    
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
            <!-- Mobile Close Button inside Sidebar -->
            <button @click="sidebarOpen = false" class="absolute right-4 top-1/2 -translate-y-1/2 md:hidden text-muted-foreground hover:text-primary p-1.5 bg-white/50 hover:bg-white rounded-lg z-20 transition-all shadow-sm">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
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
                            <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('laporan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Laporan Presensi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('persetujuan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('persetujuan-laporan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Persetujuan Laporan
                            </a>
                        </li>
                        <li class="pt-2">
                            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider px-3 mb-1">Kegiatan</div>
                        </li>
                        <li>
                            <a href="{{ route('jenis-kegiatan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('jenis-kegiatan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Master Kegiatan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('target-kegiatan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('target-kegiatan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Target Kegiatan
                            </a>
                        </li>
                         </li>
                        <li>
                            <a href="{{ route('tamu.create') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('tamu*') ? 'bg-blue-600 text-white shadow-md scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                Daftarkan Tamu
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
                            <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('laporan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Laporan Presensi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('persetujuan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('persetujuan-laporan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Persetujuan Laporan
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
                            <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('laporan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Laporan Presensi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('persetujuan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('persetujuan-laporan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Persetujuan Laporan
                            </a>
                        </li>
                    @else
                        @php
                            $isAmir = \Illuminate\Support\Facades\DB::table('peserta_itikafs')
                                ->where('pengguna_id', Auth::id())
                                ->where('adalah_amir', true)
                                ->exists();
                        @endphp
                        <li>
                            <a href="{{ route('jadwal.saya') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('jadwal-saya') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Jadwal Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('face.verify') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('face/verify') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Scan Kehadiran
                            </a>
                        </li>
                        @if($isAmir)
                            <li>
                                <a href="{{ route('tamu.create') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('tamu*') ? 'bg-blue-600 text-white shadow-md scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                    Daftarkan Tamu
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('amir.laporan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('amir*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Laporan Sesi (Amir)
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('absensi-kegiatan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 {{ request()->is('kegiatan*') ? 'bg-primary text-white shadow-md shadow-primary/20 scale-[1.02]' : 'text-muted-foreground hover:bg-white/50 hover:text-primary hover:shadow-sm' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Kegiatan Harian
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
                {{-- Notification Bell --}}
                @php
                    $unreadCount = \App\Models\Notifikasi::where('pengguna_id', Auth::id())->where('dibaca', false)->count();
                @endphp
                <a href="{{ route('notifikasi.index') }}" class="relative p-2 text-muted-foreground hover:text-primary transition-colors bg-white/50 hover:bg-white rounded-full shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($unreadCount > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-500 rounded-full border-2 border-white">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-foreground">{{ Auth::user()->name }}</div>
                    <div class="text-xs font-medium text-secondary capitalize flex items-center gap-1 justify-end">
                        {{ str_replace('_', ' ', Auth::user()->role) }}
                        @if(Auth::user()->status === 'tamu')
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700 font-bold text-[9px] border border-blue-200">TAMU</span>
                        @endif
                    </div>
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

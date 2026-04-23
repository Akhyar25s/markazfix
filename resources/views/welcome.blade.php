<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MARKAZ - Sistem Manajemen Organisasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground antialiased min-h-screen selection:bg-primary/30 relative overflow-x-hidden">

    <!-- Animated Background Blobs -->
    <div class="fixed top-[-10%] right-[-5%] w-[50rem] h-[50rem] bg-secondary/10 rounded-full blur-[120px] -z-10 animate-blob"></div>
    <div class="fixed bottom-[-10%] left-[-5%] w-[50rem] h-[50rem] bg-primary/10 rounded-full blur-[120px] -z-10 animate-blob" style="animation-delay: 2s;"></div>
    <div class="fixed top-[40%] left-[20%] w-[30rem] h-[30rem] bg-accent/5 rounded-full blur-[100px] -z-10 animate-blob" style="animation-delay: 4s;"></div>

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass border-b border-white/20 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 font-extrabold text-2xl tracking-tight group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent to-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform duration-300">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 11.5v-3h4v3H8z"></path>
                    </svg>
                </div>
                <span class="text-gradient">MARKAZ</span>
            </a>
            
            <div class="hidden md:flex items-center gap-8 font-semibold text-sm">
                <a href="#fitur" class="text-muted-foreground hover:text-primary transition-colors">Fitur</a>
                <a href="#visimisi" class="text-muted-foreground hover:text-primary transition-colors">Visi Misi</a>
                <a href="#tentang" class="text-muted-foreground hover:text-primary transition-colors">Tentang Kami</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="hidden sm:block text-sm font-bold text-foreground hover:text-primary transition-colors px-4 py-2">Masuk</a>
                <a href="/register" class="bg-primary text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 hover:-translate-y-0.5 transition-all">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-40 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 text-center z-10 relative">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass border border-primary/20 text-primary text-sm font-bold mb-8 animate-in slide-in-from-bottom-4 duration-700">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                </span>
                Sistem Informasi Terpadu Versi 2.0
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 animate-in slide-in-from-bottom-8 duration-700 delay-100">
                Manajemen <span class="text-gradient">Kegiatan & Absensi</span><br />Digital Masa Kini.
            </h1>
            
            <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto mb-10 leading-relaxed animate-in slide-in-from-bottom-8 duration-700 delay-200 font-medium">
                Sistem terintegrasi untuk mengelola kehadiran, I'tikaf, dan aktivitas jamaah dengan teknologi Face Recognition modern yang akurat dan real-time.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-in slide-in-from-bottom-8 duration-700 delay-300">
                <a href="/register" class="w-full sm:w-auto bg-primary text-white font-bold px-8 py-4 rounded-xl shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                    Daftar Sekarang
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="#fitur" class="w-full sm:w-auto glass border border-border text-foreground font-bold px-8 py-4 rounded-xl hover:bg-white/50 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                    Pelajari Fitur
                </a>
            </div>
        </div>

        <!-- Dashboard Preview Mockup -->
        <div class="max-w-5xl mx-auto px-6 mt-20 animate-in slide-in-from-bottom-12 duration-1000 delay-500 relative">
            <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent z-10 translate-y-10"></div>
            <div class="glass-card rounded-2xl md:rounded-[2rem] border border-white/50 shadow-2xl shadow-primary/10 overflow-hidden relative">
                <!-- Mac header -->
                <div class="bg-white/40 border-b border-white/50 px-4 py-3 flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                </div>
                <!-- Mockup Image / Content -->
                <div class="aspect-video bg-white/20 p-8 flex flex-col gap-6">
                    <div class="h-8 w-1/3 bg-primary/20 rounded-lg animate-pulse"></div>
                    <div class="grid grid-cols-3 gap-6">
                        <div class="h-32 bg-white/40 rounded-xl border border-white/50"></div>
                        <div class="h-32 bg-white/40 rounded-xl border border-white/50"></div>
                        <div class="h-32 bg-white/40 rounded-xl border border-white/50"></div>
                    </div>
                    <div class="flex-1 bg-white/40 rounded-xl border border-white/50"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi Misi Section -->
    <section id="visimisi" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4">Visi & <span class="text-gradient">Misi</span></h2>
                <p class="text-muted-foreground font-medium max-w-2xl mx-auto">Tujuan utama kami dalam membangun ekosistem digital yang modern dan efisien.</p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-10 items-center">
                <div class="glass-card p-10 rounded-[2rem] border-l-4 border-l-primary hover:-translate-y-1 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl -z-10"></div>
                    <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mb-6">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Visi Kami</h3>
                    <p class="text-muted-foreground leading-relaxed text-lg">Menjadi platform digital terpercaya yang mendukung pengelolaan kegiatan keagamaan dengan efisien, transparan, dan inovatif untuk meningkatkan partisipasi dan kohesivitas jamaah.</p>
                </div>

                <div class="space-y-6">
                    <div class="glass-card p-6 rounded-2xl flex gap-6 items-start hover:-translate-x-2 transition-transform duration-300">
                        <div class="w-12 h-12 shrink-0 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center">
                            <span class="font-black text-xl">1</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold mb-2">Absensi Terintegrasi</h4>
                            <p class="text-muted-foreground text-sm leading-relaxed">Menyediakan sistem absensi yang akurat dan terintegrasi untuk mencatat kehadiran jamaah secara mudah dan real-time.</p>
                        </div>
                    </div>
                    
                    <div class="glass-card p-6 rounded-2xl flex gap-6 items-start hover:-translate-x-2 transition-transform duration-300">
                        <div class="w-12 h-12 shrink-0 bg-accent/10 text-accent rounded-xl flex items-center justify-center">
                            <span class="font-black text-xl">2</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold mb-2">Pengelolaan I'tikaf</h4>
                            <p class="text-muted-foreground text-sm leading-relaxed">Memfasilitasi pengelolaan program I'tikaf, jadwal kegiatan, dan komunikasi yang efektif antara pengurus markaz dan jamaah.</p>
                        </div>
                    </div>

                    <div class="glass-card p-6 rounded-2xl flex gap-6 items-start hover:-translate-x-2 transition-transform duration-300">
                        <div class="w-12 h-12 shrink-0 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                            <span class="font-black text-xl">3</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold mb-2">Analitik Komprehensif</h4>
                            <p class="text-muted-foreground text-sm leading-relaxed">Menghadirkan analitik dan laporan komprehensif yang membantu evaluasi keaktifan jamaah dan pengambilan keputusan strategis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section id="fitur" class="py-24 bg-white/40">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4">Fitur <span class="text-gradient">Unggulan</span></h2>
                <p class="text-muted-foreground font-medium max-w-2xl mx-auto">Sistem yang dirancang dengan teknologi canggih untuk memberikan pengalaman terbaik.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary to-accent rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Face Recognition</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">Sistem presensi canggih berbasis pengenalan wajah untuk verifikasi kehadiran yang instan dan anti-kecurangan.</p>
                </div>

                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-secondary to-yellow-500 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-secondary/20 group-hover:scale-110 transition-transform">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Manajemen Jadwal</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">Kelola dan pantau seluruh jadwal kegiatan markaz dan I'tikaf secara terpusat dengan mudah dan efisien.</p>
                </div>

                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Laporan Analitik</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">Dashboard interaktif yang menampilkan data kehadiran dan statistik partisipasi jamaah secara real-time.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-accent via-primary to-secondary opacity-90"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4yKSIvPjwvc3ZnPg==')] opacity-30"></div>
        
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10 text-white">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6 tracking-tight">Siap Memulai Transformasi Digital?</h2>
            <p class="text-lg md:text-xl text-white/80 mb-10 max-w-2xl mx-auto font-medium">Bergabunglah bersama ratusan jamaah lainnya yang telah merasakan kemudahan sistem manajemen MARKAZ.</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/register" class="bg-white text-primary font-extrabold px-8 py-4 rounded-xl shadow-xl hover:scale-105 transition-all">
                    Buat Akun Sekarang
                </a>
                <a href="{{ route('login') }}" class="bg-white/20 backdrop-blur-md border border-white/30 text-white font-bold px-8 py-4 rounded-xl hover:bg-white/30 transition-all">
                    Masuk ke Sistem
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-foreground text-white py-16 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12 relative z-10">
            <div>
                <a href="/" class="flex items-center gap-3 font-extrabold text-2xl tracking-tight mb-6">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent to-primary flex items-center justify-center text-white shadow-lg">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 11.5v-3h4v3H8z"></path>
                        </svg>
                    </div>
                    <span>MARKAZ</span>
                </a>
                <p class="text-white/60 text-sm leading-relaxed max-w-xs">
                    Platform digital terpercaya untuk manajemen kegiatan keagamaan dan absensi jamaah secara terintegrasi dan modern.
                </p>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-6 text-white">Menu Cepat</h4>
                <ul class="space-y-3">
                    <li><a href="#fitur" class="text-white/60 hover:text-primary transition-colors text-sm">Fitur Unggulan</a></li>
                    <li><a href="#visimisi" class="text-white/60 hover:text-primary transition-colors text-sm">Visi & Misi</a></li>
                    <li><a href="/login" class="text-white/60 hover:text-primary transition-colors text-sm">Masuk Sistem</a></li>
                    <li><a href="/register" class="text-white/60 hover:text-primary transition-colors text-sm">Daftar Akun Baru</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-6 text-white">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="text-white/60 text-sm">info@markaz.id</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span class="text-white/60 text-sm">+62 812 3456 7890</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="text-white/60 text-sm leading-relaxed">Gedung Pusat Markaz, Jl. Al-Falah No. 1<br>Jakarta Selatan, 12120</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 mt-16 pt-8 border-t border-white/10 text-center relative z-10">
            <p class="text-white/40 text-sm">&copy; 2026 MARKAZ Digital System. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
</body>
</html>

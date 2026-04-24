<!DOCTYPE html>
<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Global Dashboard - Markaz</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Leaflet CSS & JS for Geospatial Dashboard -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                            "on-tertiary-fixed": "#002204",
                            "outline-variant": "#c1c8c1",
                            "on-surface": "#191c1d",
                            "surface-dim": "#d9dadb",
                            "tertiary-container": "#00460e",
                            "surface": "#f8f9fa",
                            "surface-bright": "#f8f9fa",
                            "primary": "#002d1a",
                            "tertiary-fixed": "#a3f69c",
                            "error": "#ba1a1a",
                            "primary-fixed": "#c0edd1",
                            "on-secondary-container": "#745c00",
                            "surface-variant": "#e1e3e4",
                            "on-surface-variant": "#414943",
                            "surface-container-highest": "#e1e3e4",
                            "inverse-primary": "#a4d1b5",
                            "on-tertiary-container": "#68b765",
                            "on-secondary": "#ffffff",
                            "on-primary-container": "#84b096",
                            "on-primary-fixed": "#002112",
                            "inverse-surface": "#2e3132",
                            "primary-container": "#1a432f",
                            "background": "#f8f9fa",
                            "secondary-fixed": "#ffe088",
                            "on-error": "#ffffff",
                            "secondary-container": "#fed65b",
                            "outline": "#717973",
                            "on-secondary-fixed": "#241a00",
                            "on-error-container": "#93000a",
                            "secondary-fixed-dim": "#e9c349",
                            "tertiary-fixed-dim": "#88d982",
                            "surface-tint": "#3e6750",
                            "primary-fixed-dim": "#a4d1b5",
                            "on-tertiary": "#ffffff",
                            "surface-container-high": "#e7e8e9",
                            "tertiary": "#002d06",
                            "on-primary": "#ffffff",
                            "surface-container-lowest": "#ffffff",
                            "secondary": "#735c00",
                            "surface-container-low": "#f3f4f5",
                            "on-primary-fixed-variant": "#264f3a",
                            "on-secondary-fixed-variant": "#574500",
                            "on-tertiary-fixed-variant": "#005312",
                            "surface-container": "#edeeef",
                            "error-container": "#ffdad6",
                            "on-background": "#191c1d",
                            "inverse-on-surface": "#f0f1f2"
                    },
                    "borderRadius": {
                            "DEFAULT": "0.25rem",
                            "lg": "0.5rem",
                            "xl": "0.75rem",
                            "full": "9999px"
                    },
                    "spacing": {
                            "lg": "24px",
                            "md": "16px",
                            "xs": "4px",
                            "sm": "8px",
                            "xl": "40px",
                            "xxl": "64px"
                    },
                    "fontFamily": {
                            "label-caps": [
                                    "Manrope"
                            ],
                            "h1": [
                                    "Manrope"
                            ],
                            "body-md": [
                                    "Manrope"
                            ],
                            "h3": [
                                    "Manrope"
                            ],
                            "h2": [
                                    "Manrope"
                            ],
                            "body-sm": [
                                    "Manrope"
                            ],
                            "body-lg": [
                                    "Manrope"
                            ],
                            "button": [
                                    "Manrope"
                            ]
                    },
                    "fontSize": {
                            "label-caps": [
                                    "12px",
                                    {
                                            "lineHeight": "16px",
                                            "letterSpacing": "0.05em",
                                            "fontWeight": "700"
                                    }
                            ],
                            "h1": [
                                    "40px",
                                    {
                                            "lineHeight": "48px",
                                            "letterSpacing": "-0.02em",
                                            "fontWeight": "700"
                                    }
                            ],
                            "body-md": [
                                    "16px",
                                    {
                                            "lineHeight": "24px",
                                            "fontWeight": "400"
                                    }
                            ],
                            "h3": [
                                    "24px",
                                    {
                                            "lineHeight": "32px",
                                            "fontWeight": "600"
                                    }
                            ],
                            "h2": [
                                    "32px",
                                    {
                                            "lineHeight": "40px",
                                            "letterSpacing": "-0.01em",
                                            "fontWeight": "600"
                                    }
                            ],
                            "body-sm": [
                                    "14px",
                                    {
                                            "lineHeight": "20px",
                                            "fontWeight": "400"
                                    }
                            ],
                            "body-lg": [
                                    "18px",
                                    {
                                            "lineHeight": "28px",
                                            "fontWeight": "400"
                                    }
                            ],
                            "button": [
                                    "15px",
                                    {
                                            "lineHeight": "20px",
                                            "fontWeight": "600"
                                    }
                            ]
                    }
            },
                },
        }
    </script>
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased h-screen overflow-hidden flex">
<!-- SideNavBar -->
<aside class="bg-white dark:bg-slate-900 w-64 h-screen fixed left-0 top-0 border-r border-slate-200 dark:border-slate-800 shadow-sm flex flex-col h-full py-6 z-50">
<div class="px-6 mb-8 flex items-center gap-4">
<div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center flex-shrink-0">
<img alt="Logo Markaz Islamic Center" class="w-6 h-6 rounded-full" data-alt="minimalist golden geometric Islamic star logo on dark green background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDO88DmNJEQ5kjsv3uQQMSJICyDM-g2JKnXqwMAPSd1Yk62cRNtoeh8Au7k5JCrIkYOGt3Ju4SJRc6bU8tSkGjeWiMOJHDbZPWzUH8Tg-cRhNCsEnTlbCLhVRjn0SVMH2b3RdWw2VzESFysZ03OETuJ3AvdPXo5gPyeX0_ntq_Rp2U1UqrVFfYk-YIck3wbirCy3Rw37iB2zbL2hvzQ61ylcE9m5ingf_Ronk13O46um3p0nRg2Z3IJBqz3CHDuLKWKwrP5lQD51G0"/>
</div>
<div>
<h1 class="font-['Manrope'] text-sm font-medium text-2xl font-black tracking-tighter text-emerald-900 dark:text-emerald-500">MARKAZ</h1>
<p class="font-['Manrope'] text-sm font-medium text-xs text-slate-500">Management System</p>
</div>
</div>
<div class="px-4 mb-6">
<button class="w-full bg-secondary-container text-on-secondary-container hover:bg-secondary-fixed transition-colors font-button text-button py-2 rounded-lg flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-[18px]">add</span>
                Buat Laporan Baru
            </button>
</div>
<nav class="flex-1 px-4 space-y-1">
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border-r-4 border-emerald-700 dark:border-emerald-500 font-bold active:scale-[0.98] transform transition-transform" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                Beranda
            </a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:text-emerald-700 dark:hover:text-emerald-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200 active:scale-[0.98] transform transition-transform" href="#">
<span class="material-symbols-outlined">group</span>
                Data Anggota
            </a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:text-emerald-700 dark:hover:text-emerald-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200 active:scale-[0.98] transform transition-transform" href="#">
<span class="material-symbols-outlined">calendar_month</span>
                Jadwal I'tikaf
            </a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:text-emerald-700 dark:hover:text-emerald-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200 active:scale-[0.98] transform transition-transform" href="#">
<span class="material-symbols-outlined">verified_user</span>
                Laporan Approval
            </a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:text-emerald-700 dark:hover:text-emerald-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200 active:scale-[0.98] transform transition-transform" href="#">
<span class="material-symbols-outlined">analytics</span>
                Statistik
            </a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:text-emerald-700 dark:hover:text-emerald-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200 active:scale-[0.98] transform transition-transform" href="#">
<span class="material-symbols-outlined">settings</span>
                Pengaturan
            </a>
</nav>
<div class="px-4 mt-auto space-y-1">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:text-emerald-700 dark:hover:text-emerald-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200" href="#">
<span class="material-symbols-outlined">help</span>
<span class="font-['Manrope'] text-sm font-medium">Pusat Bantuan</span>
</a>
<form method="POST" action="{{ route('logout') }}" class="w-full">
    @csrf
    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:text-emerald-700 dark:hover:text-emerald-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200">
        <span class="material-symbols-outlined">logout</span>
        <span class="font-['Manrope'] text-sm font-medium">Keluar</span>
    </button>
</form>
</div>
</aside>
<!-- Main Content Area -->
<div class="flex-1 ml-64 flex flex-col h-screen">
<!-- TopAppBar -->
<header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md fixed top-0 right-0 w-[calc(100%-16rem)] z-40 border-b border-slate-200 dark:border-slate-800 shadow-none flex items-center justify-between px-8 h-16">
<div class="flex items-center text-lg font-bold text-emerald-800 dark:text-emerald-400 font-['Manrope']">
                Markaz Admin Panel
            </div>
<div class="flex items-center gap-2">
<button class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors focus:ring-2 focus:ring-emerald-500/50 outline-none">
<span class="material-symbols-outlined">notifications</span>
</button>
<button class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors focus:ring-2 focus:ring-emerald-500/50 outline-none">
<span class="material-symbols-outlined">chat_bubble</span>
</button>
<button class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors focus:ring-2 focus:ring-emerald-500/50 outline-none flex items-center gap-2">
<img alt="Foto Profil Pengurus" class="w-8 h-8 rounded-full border border-slate-200" data-alt="professional headshot of an indonesian man in formal attire with neutral background" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&color=16a34a&background=f0fdf4"/>
</button>
</div>
</header>
<!-- Scrollable Content -->
<main class="flex-1 overflow-y-auto mt-16 p-8 bg-surface-container-low">
<div class="max-w-7xl mx-auto space-y-8">
<!-- Welcome Header -->
<div class="flex flex-col gap-2">
<h1 class="font-h1 text-h1 text-primary">Assalamu'alaikum, {{ Auth::user()->name ?? 'Admin' }}</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant">Berikut adalah ringkasan aktivitas dan laporan yang membutuhkan perhatian Anda hari ini.</p>
</div>
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<!-- Stat Card 1 -->
<div class="bg-surface rounded-xl p-6 border border-outline-variant/30 flex flex-col gap-4">
<div class="flex items-center justify-between">
<span class="material-symbols-outlined text-primary-container text-[28px]">groups</span>
<span class="text-secondary-fixed-dim text-sm font-semibold flex items-center bg-secondary-fixed/20 px-2 py-1 rounded-full">+12%</span>
</div>
<div>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Total Anggota</p>
<p class="font-h2 text-h2 text-primary">12,450</p>
</div>
</div>
<!-- Stat Card 2 -->
<div class="bg-surface rounded-xl p-6 border border-outline-variant/30 flex flex-col gap-4">
<div class="flex items-center justify-between">
<span class="material-symbols-outlined text-primary-container text-[28px]">event_available</span>
</div>
<div>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-1">I'tikaf Berjalan</p>
<p class="font-h2 text-h2 text-primary">8</p>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 text-xs">Di 5 Wilayah</p>
</div>
</div>
<!-- Stat Card 3 -->
<div class="bg-surface rounded-xl p-6 border border-outline-variant/30 flex flex-col gap-4 relative overflow-hidden">
<div class="absolute top-0 right-0 w-2 h-full bg-error-container"></div>
<div class="flex items-center justify-between">
<span class="material-symbols-outlined text-error text-[28px]">pending_actions</span>
</div>
<div>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Laporan Menunggu Approval</p>
<p class="font-h2 text-h2 text-error">24</p>
</div>
</div>
<!-- Stat Card 4 -->
<div class="bg-primary-container text-on-primary-container rounded-xl p-6 flex items-center justify-between">
<div class="flex flex-col gap-1">
<p class="font-body-sm text-body-sm text-on-primary-container/80">Target Kegiatan Bulan Ini</p>
<p class="font-h2 text-h2 text-on-primary">85%</p>
<p class="font-body-sm text-body-sm text-on-primary-container/80 mt-1 text-xs">17/20 Terlaksana</p>
</div>
<!-- Simple Progress Ring -->
<div class="relative w-16 h-16 flex items-center justify-center">
<svg class="w-full h-full transform -rotate-90" viewbox="0 0 36 36">
<path class="text-primary-container/50" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3"></path>
<path class="text-secondary-container" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-dasharray="85, 100" stroke-width="3"></path>
</svg>
</div>
</div>
</div>

<!-- Geospatial Dashboard Section -->
<div class="bg-surface border border-outline-variant/30 rounded-xl overflow-hidden flex flex-col mt-8 mb-8 shadow-sm">
    <div class="p-6 border-b border-outline-variant/30 flex justify-between items-center bg-surface-bright">
        <h2 class="font-h3 text-h3 text-primary flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-container">map</span> Peta Persebaran Mahallah
        </h2>
        <div class="flex gap-2">
            <select class="bg-surface-container-lowest border border-outline-variant/50 text-on-surface-variant text-sm rounded-lg focus:ring-primary focus:border-primary block p-2 outline-none">
                <option>Semua Wilayah</option>
                <option>Jakarta Selatan</option>
                <option>Bandung Raya</option>
                <option>Surabaya Timur</option>
                <option>Medan Utara</option>
            </select>
        </div>
    </div>
    <div class="p-0">
        <!-- Z-index set to 10 so it doesn't overlap the fixed header or sidebar -->
        <div id="mahallah-map" class="w-full h-[450px] z-10 bg-slate-100"></div>
    </div>
</div>

<!-- Main Grid Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- Table Section -->
<div class="lg:col-span-2 bg-surface border border-outline-variant/30 rounded-xl overflow-hidden flex flex-col">
<div class="p-6 border-b border-outline-variant/30 flex justify-between items-center bg-surface-bright">
<h2 class="font-h3 text-h3 text-primary">Antrean Persetujuan Laporan</h2>
<button class="text-primary hover:text-primary-container font-label-caps text-label-caps flex items-center gap-1">
                                Lihat Semua <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead class="bg-surface-container-low font-label-caps text-label-caps text-on-surface-variant uppercase">
<tr>
<th class="py-4 px-6 border-b border-outline-variant/20 font-medium">Sesi I'tikaf</th>
<th class="py-4 px-6 border-b border-outline-variant/20 font-medium">Nama Amir</th>
<th class="py-4 px-6 border-b border-outline-variant/20 font-medium">Wilayah</th>
<th class="py-4 px-6 border-b border-outline-variant/20 font-medium">Status</th>
<th class="py-4 px-6 border-b border-outline-variant/20 font-medium text-right">Action</th>
</tr>
</thead>
<tbody class="font-body-sm text-body-sm text-on-surface">
<tr class="hover:bg-surface-container-lowest transition-colors border-b border-outline-variant/10">
<td class="py-4 px-6">Ramadhan 1445 H - Gelombang 1</td>
<td class="py-4 px-6">Ahmad Faisal</td>
<td class="py-4 px-6">Jakarta Selatan</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-container text-on-error-container">
                                                Menunggu Inti
                                            </span>
</td>
<td class="py-4 px-6 text-right">
<button class="text-primary hover:text-primary-container font-medium text-sm">Review</button>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors border-b border-outline-variant/10">
<td class="py-4 px-6">Akhir Pekan - Safar</td>
<td class="py-4 px-6">Budi Santoso</td>
<td class="py-4 px-6">Bandung Raya</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-fixed text-on-secondary-fixed">
                                                Menunggu Wilayah
                                            </span>
</td>
<td class="py-4 px-6 text-right">
<button class="text-primary hover:text-primary-container font-medium text-sm">Review</button>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors border-b border-outline-variant/10">
<td class="py-4 px-6">Rutinan Bulanan - Rabiul Awal</td>
<td class="py-4 px-6">Hasanuddin</td>
<td class="py-4 px-6">Surabaya Timur</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-container text-on-error-container">
                                                Menunggu Inti
                                            </span>
</td>
<td class="py-4 px-6 text-right">
<button class="text-primary hover:text-primary-container font-medium text-sm">Review</button>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="py-4 px-6">Persiapan Ramadhan</td>
<td class="py-4 px-6">Zainal Abidin</td>
<td class="py-4 px-6">Medan Utara</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-fixed text-on-primary-fixed">
                                                Disetujui
                                            </span>
</td>
<td class="py-4 px-6 text-right">
<button class="text-outline hover:text-on-surface font-medium text-sm">Lihat</button>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Side Section: Schedule -->
<div class="bg-surface border border-outline-variant/30 rounded-xl p-6 flex flex-col gap-6">
<div class="flex justify-between items-center">
<h2 class="font-h3 text-h3 text-primary">Jadwal I'tikaf Mendatang</h2>
</div>
<div class="space-y-4">
<!-- Schedule Item 1 -->
<div class="flex gap-4 items-start p-4 rounded-lg bg-surface-container-low hover:bg-surface-container transition-colors border border-outline-variant/20">
<div class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-md w-14 h-14 flex-shrink-0">
<span class="font-label-caps text-[10px] uppercase">Okt</span>
<span class="font-h3 text-h3 leading-none">12</span>
</div>
<div class="flex flex-col gap-1">
<h4 class="font-body-md text-body-md font-semibold text-primary">I'tikaf Akbar Wilayah Jabar</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">location_on</span> Masjid Raya Bandung
                                    </p>
</div>
</div>
<!-- Schedule Item 2 -->
<div class="flex gap-4 items-start p-4 rounded-lg bg-surface-container-low hover:bg-surface-container transition-colors border border-outline-variant/20">
<div class="flex flex-col items-center justify-center bg-surface-variant text-on-surface-variant rounded-md w-14 h-14 flex-shrink-0">
<span class="font-label-caps text-[10px] uppercase">Okt</span>
<span class="font-h3 text-h3 leading-none">19</span>
</div>
<div class="flex flex-col gap-1">
<h4 class="font-body-md text-body-md font-semibold text-primary">Pembekalan Amir I'tikaf</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">location_on</span> Islamic Center Jakarta
                                    </p>
</div>
</div>
<!-- Schedule Item 3 -->
<div class="flex gap-4 items-start p-4 rounded-lg bg-surface-container-low hover:bg-surface-container transition-colors border border-outline-variant/20">
<div class="flex flex-col items-center justify-center bg-surface-variant text-on-surface-variant rounded-md w-14 h-14 flex-shrink-0">
<span class="font-label-caps text-[10px] uppercase">Nov</span>
<span class="font-h3 text-h3 leading-none">02</span>
</div>
<div class="flex flex-col gap-1">
<h4 class="font-body-md text-body-md font-semibold text-primary">I'tikaf Pemuda Hijrah</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">location_on</span> Masjid Al-Falah Surabaya
                                    </p>
</div>
</div>
</div>
<button class="w-full mt-auto py-2 border border-outline text-primary hover:bg-surface-variant rounded-lg font-button text-button transition-colors">
                            Lihat Kalender Lengkap
                        </button>
</div>
</div>
</div>
</main>
</div>
</div>

<!-- Map Initialization Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map, centered on Indonesia
        var map = L.map('mahallah-map').setView([-2.5489, 118.0149], 5);

        // Add OpenStreetMap tile layer with light theme
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        // Custom icon for Mahallah
        var mahallahIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="bg-primary-container text-on-primary-container w-10 h-10 rounded-full flex items-center justify-center shadow-[0_4px_10px_rgba(0,0,0,0.3)] border-[3px] border-surface-container-lowest transform transition-transform hover:scale-110"><span class="material-symbols-outlined text-[20px]">mosque</span></div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        });

        // Fetch data from API
        fetch('/api/mahallah-map')
            .then(response => response.json())
            .then(mahallahData => {
                var markers = L.featureGroup();

                // Add markers to map
                mahallahData.forEach(function(mahallah) {
                    var popupContent = `
                        <div class="p-2 min-w-[200px] font-['Manrope']">
                            <div class="flex items-center justify-between mb-2 pb-2 border-b border-outline-variant/30">
                                <h3 class="font-bold text-base text-primary">${mahallah.name}</h3>
                                <span class="bg-primary-fixed text-on-primary-fixed text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">${mahallah.status}</span>
                            </div>
                            <div class="space-y-1.5">
                                <p class="text-sm text-on-surface flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-outline">location_on</span> ${mahallah.wilayah}</p>
                                <p class="text-sm text-on-surface flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-outline">group</span> ${mahallah.members} Jamaah</p>
                            </div>
                            <div class="mt-3 pt-2 border-t border-outline-variant/30 flex justify-end">
                                <a href="/mahallah/${mahallah.id}" class="text-xs font-semibold text-primary hover:text-primary-container flex items-center gap-1">Detail <span class="material-symbols-outlined text-[14px]">chevron_right</span></a>
                            </div>
                        </div>
                    `;

                    var marker = L.marker([mahallah.lat, mahallah.lng], {icon: mahallahIcon})
                        .bindPopup(popupContent, {
                            className: 'custom-popup rounded-xl shadow-lg border-0',
                            minWidth: 220
                        });
                    markers.addLayer(marker);
                });
                
                map.addLayer(markers);

                // Fit map bounds to markers if there are any
                if (mahallahData.length > 0) {
                    map.fitBounds(markers.getBounds(), {padding: [50, 50], maxZoom: 12});
                }
            })
            .catch(error => console.error('Error fetching mahallah data:', error));
    });
</script>
<style>
    /* Leaflet popup customization to match theme */
    .leaflet-popup-content-wrapper {
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 0;
        line-height: 1.5;
    }
    .leaflet-container a.leaflet-popup-close-button {
        top: 8px;
        right: 8px;
        color: #717973;
    }
    .leaflet-container a.leaflet-popup-close-button:hover {
        color: #ba1a1a;
    }
</style>
</body></html>

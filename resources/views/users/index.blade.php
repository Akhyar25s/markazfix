@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6 animate-fade-in pb-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-foreground">Manajemen Pengguna</h1>
            <p class="text-muted-foreground mt-1 text-sm sm:text-base">Kelola daftar pengguna, peran (role), dan wilayah di Sistem I'tikaf Markaz.</p>
        </div>
    </div>

    <!-- Alert Success / Errors -->
    @if(session('success'))
        <x-alert type="success" message="{{ session('success') }}" />
    @endif
    
    @if(session('error'))
        <div id="session-error-trigger" data-message="{{ session('error') }}" class="hidden"></div>
    @endif
    
    @if($errors->any())
        <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-600 text-sm backdrop-blur-md">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filter Card -->
    <x-card class="border-primary/10 shadow-md backdrop-blur-md bg-card/80">
        <form action="{{ route('users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            <!-- Search -->
            <div class="space-y-1">
                <label for="search" class="text-xs font-bold text-muted-foreground uppercase tracking-wider pl-1">Cari Pengguna</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama, email, atau no telp..." class="w-full pl-3 pr-4 py-2 bg-white/50 border border-border focus:border-primary focus:ring-1 focus:ring-primary rounded-xl outline-none text-sm transition-all shadow-sm">
                </div>
            </div>

            <!-- Role Filter -->
            <div class="space-y-1">
                <label for="role" class="text-xs font-bold text-muted-foreground uppercase tracking-wider pl-1">Peran (Role)</label>
                <select name="role" id="role" class="w-full px-3 py-2 bg-white/50 border border-border focus:border-primary focus:ring-1 focus:ring-primary rounded-xl outline-none text-sm transition-all shadow-sm">
                    <option value="">Semua Peran</option>
                    <option value="pengurus_inti" {{ request('role') === 'pengurus_inti' ? 'selected' : '' }}>Pengurus Inti</option>
                    <option value="pengurus_wilayah" {{ request('role') === 'pengurus_wilayah' ? 'selected' : '' }}>Pengurus Wilayah</option>
                    <option value="anggota" {{ request('role') === 'anggota' ? 'selected' : '' }}>Anggota</option>
                </select>
            </div>

            <!-- Wilayah Filter (Hanya untuk Pengurus Inti) -->
            @if(Auth::user()->role === 'pengurus_inti')
            <div class="space-y-1">
                <label for="wilayah_id" class="text-xs font-bold text-muted-foreground uppercase tracking-wider pl-1">Wilayah</label>
                <select name="wilayah_id" id="wilayah_id" class="w-full px-3 py-2 bg-white/50 border border-border focus:border-primary focus:ring-1 focus:ring-primary rounded-xl outline-none text-sm transition-all shadow-sm">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahs as $w)
                        <option value="{{ $w->id }}" {{ request('wilayah_id') == $w->id ? 'selected' : '' }}>{{ $w->nama_wilayah }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <x-button type="submit" variant="default" class="w-full py-2 text-sm font-semibold rounded-xl">
                    Cari
                </x-button>
                <a href="{{ route('users.index') }}" class="w-full">
                    <x-button type="button" variant="outline" class="w-full py-2 text-sm font-semibold rounded-xl">
                        Reset
                    </x-button>
                </a>
            </div>
        </form>
    </x-card>

    <!-- Table Card -->
    <x-card class="overflow-hidden border-primary/10 shadow-xl relative backdrop-blur-md bg-card/80">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 z-0 pointer-events-none"></div>
        <div class="relative z-10">
            <x-table :headers="['No', 'Nama & Kontak', 'Role & Status', 'Wilayah & Mahallah', 'Aksi']">
                @forelse($users as $u)
                    <tr class="border-b border-border transition-colors hover:bg-muted/50">
                        <td class="p-4 align-middle text-muted-foreground text-sm">
                            {{ $loop->iteration + $users->firstItem() - 1 }}
                        </td>
                        <td class="p-4 align-middle">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold overflow-hidden shadow-sm">
                                    @if($u->foto_profil)
                                        <img src="{{ asset('storage/' . $u->foto_profil) }}" alt="{{ $u->name }}" class="h-full w-full object-cover">
                                    @else
                                        {{ substr($u->name, 0, 2) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-foreground text-sm">{{ $u->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $u->email }}</div>
                                    <div class="text-xs text-muted-foreground/80 mt-0.5">{{ $u->no_telepon }} ({{ $u->umur }} Thn)</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 align-middle">
                            <div class="space-y-1">
                                <!-- Role Badge -->
                                <div>
                                    @if($u->role === 'pengurus_inti')
                                        <x-badge class="bg-indigo-500/10 text-indigo-600 border-indigo-500/20 font-bold text-[10px]">👑 Pengurus Inti</x-badge>
                                    @elseif($u->role === 'pengurus_wilayah')
                                        <x-badge class="bg-emerald-500/10 text-emerald-600 border-emerald-500/20 font-bold text-[10px]">🟢 Pengurus Wilayah</x-badge>
                                    @else
                                        <x-badge class="bg-slate-500/10 text-slate-600 border-slate-500/20 font-bold text-[10px]">👤 Anggota</x-badge>
                                    @endif
                                </div>
                                <!-- Status Badge -->
                                <div>
                                    @if($u->status === 'tamu')
                                        <x-badge class="bg-blue-500/10 text-blue-600 border-blue-500/20 text-[9px] font-semibold">🌍 Tamu / Walk-in</x-badge>
                                    @elseif($u->status === 'aktif')
                                        <x-badge class="bg-green-500/10 text-green-600 border-green-500/20 text-[9px] font-semibold">Aktif</x-badge>
                                    @else
                                        <x-badge class="bg-red-500/10 text-red-600 border-red-500/20 text-[9px] font-semibold">Nonaktif</x-badge>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="p-4 align-middle text-sm">
                            @if($u->role === 'pengurus_inti')
                                <span class="text-muted-foreground text-xs italic">Akses Global (Semua Wilayah)</span>
                            @else
                                <div class="font-semibold text-foreground text-xs">
                                    {{ $u->wilayah->nama_wilayah ?? 'Tidak ada Wilayah' }}
                                </div>
                                <div class="text-xs text-muted-foreground mt-0.5">
                                    🏢 {{ $u->mahallah->nama_mahallah ?? '-' }}
                                </div>
                            @endif
                        </td>
                        <td class="p-4 align-middle">
                            @if(Auth::user()->role === 'pengurus_inti')
                                <div class="flex items-center gap-2">
                                    @if($u->status === 'tamu')
                                        <button disabled class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed" title="Tamu tidak dapat diubah rolenya">
                                            Ubah Role
                                        </button>
                                    @else
                                        <button onclick="openEditModal({{ json_encode($u) }})" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-primary/10 text-primary border border-primary/20 hover:bg-primary hover:text-white transition-all shadow-sm">
                                            Ubah Role
                                        </button>
                                    @endif

                                    {{-- Tombol Hapus: tidak muncul untuk diri sendiri atau sesama pengurus_inti --}}
                                    @if($u->id !== Auth::id() && $u->role !== 'pengurus_inti')
                                        <button
                                            onclick="openDeleteModal({{ $u->id }}, '{{ addslashes($u->name) }}')"
                                            class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-red-500/10 text-red-600 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all shadow-sm"
                                            title="Hapus akun pengguna ini">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-muted-foreground/60 italic">Read-only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-muted-foreground">
                            <div class="flex flex-col items-center justify-center">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-12 w-12 text-muted-foreground/50 mb-3"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <p class="text-sm font-semibold">Tidak ada data pengguna ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>
        
        @if($users->hasPages())
            <div class="p-4 border-t border-border">
                {{ $users->links() }}
            </div>
        @endif
    </x-card>
</div>

<!-- Modal Edit Role & Wilayah -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal Content -->
        <div class="inline-block align-bottom bg-card text-foreground rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-primary/10 relative z-50">
            <div class="px-6 py-4 bg-gradient-to-r from-primary/10 to-secondary/10 border-b border-border flex justify-between items-center">
                <h3 class="text-lg font-bold text-foreground" id="modal-title">Ubah Peran & Wilayah</h3>
                <button onclick="closeEditModal()" class="text-muted-foreground hover:text-foreground">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="edit-form" method="POST" action="" class="p-6 space-y-4">
                @csrf
                @method('PATCH')
                
                <!-- Target User Info -->
                <div class="p-3 bg-muted/50 rounded-xl flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm" id="modal-user-avatar">
                        AA
                    </div>
                    <div>
                        <div class="font-bold text-sm text-foreground" id="modal-user-name">User Name</div>
                        <div class="text-xs text-muted-foreground" id="modal-user-email">user@email.com</div>
                    </div>
                </div>

                <!-- Role Input -->
                <div class="space-y-1">
                    <label for="modal-role" class="block text-sm font-bold text-foreground/80 pl-1">Peran (Role)</label>
                    <select name="role" id="modal-role" onchange="toggleWilayahMahallah()" required class="w-full px-3 py-2 bg-white/50 border border-border focus:border-primary focus:ring-1 focus:ring-primary rounded-xl outline-none text-sm transition-all shadow-sm">
                        <option value="anggota">👤 Anggota</option>
                        <option value="pengurus_wilayah">🟢 Pengurus Wilayah</option>
                        <option value="pengurus_inti">👑 Pengurus Inti</option>
                    </select>
                </div>

                <!-- Wilayah Input -->
                <div id="modal-wilayah-group" class="space-y-1">
                    <label for="modal-wilayah-id" class="block text-sm font-bold text-foreground/80 pl-1">Wilayah</label>
                    <select name="wilayah_id" id="modal-wilayah-id" onchange="filterMahallah()" class="w-full px-3 py-2 bg-white/50 border border-border focus:border-primary focus:ring-1 focus:ring-primary rounded-xl outline-none text-sm transition-all shadow-sm">
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach($wilayahs as $w)
                            <option value="{{ $w->id }}">{{ $w->nama_wilayah }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Mahallah Input -->
                <div id="modal-mahallah-group" class="space-y-1">
                    <label for="modal-mahallah-id" class="block text-sm font-bold text-foreground/80 pl-1">Mahallah</label>
                    <select name="mahallah_id" id="modal-mahallah-id" class="w-full px-3 py-2 bg-white/50 border border-border focus:border-primary focus:ring-1 focus:ring-primary rounded-xl outline-none text-sm transition-all shadow-sm">
                        <option value="">-- Pilih Mahallah --</option>
                        @foreach($mahallahs as $m)
                            <option value="{{ $m->id }}" data-wilayah="{{ $m->wilayah_id }}">{{ $m->nama_mahallah }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="pt-4 flex justify-end gap-2">
                    <x-button type="button" onclick="closeEditModal()" variant="outline" class="rounded-xl px-4 py-2">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="default" class="rounded-xl px-4 py-2">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL KONFIRMASI HAPUS AKUN --}}
{{-- ============================================================ --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

        <div id="delete-modal-card" class="relative bg-card border border-border rounded-3xl shadow-2xl w-full max-w-md p-8 flex flex-col items-center text-center transform scale-90 opacity-0 transition-all duration-300 z-50">
            {{-- Icon Peringatan --}}
            <div class="w-20 h-20 rounded-full bg-red-500/15 border-2 border-red-500/30 flex items-center justify-center mb-5">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-10 h-10 text-red-500">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>

            <h3 class="text-xl font-black text-foreground mb-1">Hapus Akun Pengguna</h3>
            <p class="text-sm text-muted-foreground mb-2">Anda akan menghapus akun:</p>
            <p id="delete-user-name" class="font-bold text-red-600 text-base mb-4"></p>

            <div class="w-full bg-red-500/10 border border-red-500/20 rounded-xl p-3 mb-6 text-left">
                <p class="text-xs text-red-600 font-semibold mb-1">⚠️ Tindakan ini tidak dapat dibatalkan!</p>
                <ul class="text-xs text-red-500/80 space-y-0.5 pl-3 list-disc">
                    <li>Akun pengguna akan dihapus permanen</li>
                    <li>Data wajah (enrollment) akan ikut terhapus</li>
                    <li>Riwayat absensi tetap tersimpan</li>
                </ul>
            </div>

            <form id="delete-form" method="POST" action="" class="w-full">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 py-3 px-4 font-bold rounded-xl border border-border text-foreground hover:bg-muted transition-all">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 px-4 font-bold rounded-xl bg-red-500 text-white hover:bg-red-600 transition-all shadow-lg shadow-red-500/30">
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- POPUP ERROR SESSION (mis. hapus diri sendiri / pengurus inti) --}}
{{-- ============================================================ --}}
<div id="error-modal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeErrorModal()"></div>
        <div id="error-modal-card" class="relative bg-card border border-border rounded-3xl shadow-2xl w-full max-w-sm p-8 flex flex-col items-center text-center transform scale-90 opacity-0 transition-all duration-300 z-50">
            <div class="w-20 h-20 rounded-full bg-red-500/15 border-2 border-red-500/30 flex items-center justify-center mb-5">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-10 h-10 text-red-500">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-black text-red-600 mb-2">Aksi Tidak Diizinkan</h3>
            <p id="error-modal-msg" class="text-sm text-muted-foreground leading-relaxed mb-6"></p>
            <button onclick="closeErrorModal()" class="w-full py-3 px-6 font-bold rounded-xl bg-red-500 text-white hover:bg-red-600 transition-all shadow-lg shadow-red-500/30">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    function openEditModal(user) {
        const modal = document.getElementById('edit-modal');
        const form = document.getElementById('edit-form');
        const nameField = document.getElementById('modal-user-name');
        const emailField = document.getElementById('modal-user-email');
        const avatarField = document.getElementById('modal-user-avatar');
        
        // Set Action URL
        form.action = `/users/${user.id}/role`;
        
        // Set User Details
        nameField.innerText = user.name;
        emailField.innerText = user.email || '-';
        avatarField.innerText = user.name.substring(0, 2).toUpperCase();
        
        // Set Form Inputs
        document.getElementById('modal-role').value = user.role;
        document.getElementById('modal-wilayah-id').value = user.wilayah_id || '';
        document.getElementById('modal-mahallah-id').value = user.mahallah_id || '';
        
        // Initialize visibility
        toggleWilayahMahallah();
        filterMahallah();
        
        // Show Modal
        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        const modal = document.getElementById('edit-modal');
        modal.classList.add('hidden');
    }

    function toggleWilayahMahallah() {
        const role = document.getElementById('modal-role').value;
        const wilayahGroup = document.getElementById('modal-wilayah-group');
        const mahallahGroup = document.getElementById('modal-mahallah-group');
        const wilayahSelect = document.getElementById('modal-wilayah-id');

        if (role === 'pengurus_inti') {
            wilayahGroup.classList.add('hidden');
            mahallahGroup.classList.add('hidden');
            wilayahSelect.required = false;
        } else if (role === 'pengurus_wilayah') {
            wilayahGroup.classList.remove('hidden');
            mahallahGroup.classList.add('hidden');
            wilayahSelect.required = true;
        } else {
            // Anggota
            wilayahGroup.classList.remove('hidden');
            mahallahGroup.classList.remove('hidden');
            wilayahSelect.required = false;
        }
    }

    function filterMahallah() {
        const wilayahId = document.getElementById('modal-wilayah-id').value;
        const mahallahSelect = document.getElementById('modal-mahallah-id');
        const options = mahallahSelect.options;

        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            if (option.value === "") {
                option.style.display = "block";
                continue;
            }
            const optionWilayahId = option.getAttribute('data-wilayah');
            if (wilayahId === "" || optionWilayahId === wilayahId) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        }
    }
</script>

<script>
    // ── Delete Modal ───────────────────────────────────────────────
    function openDeleteModal(userId, userName) {
        const modal    = document.getElementById('delete-modal');
        const card     = document.getElementById('delete-modal-card');
        const nameEl   = document.getElementById('delete-user-name');
        const form     = document.getElementById('delete-form');

        nameEl.textContent = userName;
        form.action = `/users/${userId}`;

        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                card.classList.remove('scale-90', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        });
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        const card  = document.getElementById('delete-modal-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-90', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 250);
    }

    // ── Error Modal (untuk session error dari server) ──────────────
    function openErrorModal(msg) {
        const modal = document.getElementById('error-modal');
        const card  = document.getElementById('error-modal-card');
        document.getElementById('error-modal-msg').textContent = msg;
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                card.classList.remove('scale-90', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        });
    }

    function closeErrorModal() {
        const modal = document.getElementById('error-modal');
        const card  = document.getElementById('error-modal-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-90', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 250);
    }

    // ── Auto-trigger session error modal ─────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const trigger = document.getElementById('session-error-trigger');
        if (trigger) {
            openErrorModal(trigger.dataset.message);
        }
    });

    // ── Close on Escape ───────────────────────────────────────────
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeErrorModal();
        }
    });
</script>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wilayah;
use App\Models\Mahallah;
use App\Models\PendaftaranWajah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna (anggota & pengurus).
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        
        $query = User::with(['wilayah', 'mahallah']);
        
        // Filter: Pengurus Wilayah hanya dapat melihat anggota di wilayahnya sendiri
        if ($currentUser->role === 'pengurus_wilayah') {
            $query->where('wilayah_id', $currentUser->wilayah_id);
        }
        
        // Filter Pencarian (Nama, Email, No Telepon)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }
        
        // Filter Role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }
        
        // Filter Wilayah (Hanya untuk Pengurus Inti)
        if ($currentUser->role === 'pengurus_inti' && $request->filled('wilayah_id')) {
            $query->where('wilayah_id', $request->input('wilayah_id'));
        }
        
        $users = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();
        
        // Ambil data pendukung untuk dropdown di form filter & modal
        $wilayahs = Wilayah::where('status', 'aktif')->orderBy('nama_wilayah', 'asc')->get();
        $mahallahs = Mahallah::orderBy('nama_mahallah', 'asc')->get();
        
        return view('users.index', compact('users', 'wilayahs', 'mahallahs'));
    }
    
    /**
     * Memperbarui role dan wilayah pengguna (Hanya Pengurus Inti).
     */
    public function updateRole(Request $request, User $user)
    {
        // Tamu tidak boleh diubah rolenya sesuai request
        if ($user->status === 'tamu') {
            return back()->withErrors(['role' => 'Pengguna dengan status Tamu tidak dapat diubah perannya.']);
        }
        
        $request->validate([
            'role' => 'required|in:pengurus_inti,pengurus_wilayah,anggota',
            'wilayah_id' => 'nullable|required_if:role,pengurus_wilayah|exists:wilayahs,id',
            'mahallah_id' => 'nullable|exists:mahallahs,id',
        ], [
            'role.required' => 'Peran pengguna wajib dipilih.',
            'role.in' => 'Peran pengguna tidak valid.',
            'wilayah_id.required_if' => 'Wilayah wajib dipilih jika peran diubah menjadi Pengurus Wilayah.',
            'wilayah_id.exists' => 'Wilayah yang dipilih tidak valid.',
            'mahallah_id.exists' => 'Mahallah yang dipilih tidak valid.',
        ]);
        
        $role = $request->input('role');
        $wilayahId = $request->input('wilayah_id');
        $mahallahId = $request->input('mahallah_id');
        
        // Perbarui data pengguna
        $user->role = $role;
        
        if ($role === 'pengurus_inti') {
            // Pengurus Inti tidak terikat wilayah/mahallah khusus
            $user->wilayah_id = null;
            $user->mahallah_id = null;
        } elseif ($role === 'pengurus_wilayah') {
            $user->wilayah_id = $wilayahId;
            $user->mahallah_id = null; // Pengurus wilayah tidak memiliki mahallah spesifik
        } else {
            // Anggota biasa
            $user->wilayah_id = $wilayahId;
            $user->mahallah_id = $mahallahId;
        }
        
        $user->save();
        
        return redirect()->route('users.index')->with('success', "Peran dan Wilayah pengguna {$user->name} berhasil diperbarui.");
    }

    /**
     * Menghapus akun pengguna beserta data wajahnya (Hanya Pengurus Inti).
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        // Tidak boleh hapus diri sendiri
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Tidak boleh hapus sesama pengurus inti
        if ($user->role === 'pengurus_inti') {
            return back()->with('error', 'Akun Pengurus Inti tidak dapat dihapus melalui halaman ini.');
        }

        $nama = $user->name;

        // Hapus data wajah terlebih dahulu (jika ada)
        PendaftaranWajah::where('pengguna_id', $user->id)->delete();

        // Hapus akun pengguna
        $user->delete();

        return redirect()->route('users.index')->with('success', "Akun pengguna '{$nama}' beserta data wajahnya berhasil dihapus.");
    }
}

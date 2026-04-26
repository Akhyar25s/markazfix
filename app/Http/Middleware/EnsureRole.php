<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Memastikan user yang sedang login memiliki salah satu role yang diizinkan.
     *
     * Penggunaan di route: ->middleware('role:pengurus_inti')
     *                  atau ->middleware('role:pengurus_inti,pengurus_wilayah')
     *
     * @param string $roles Daftar role yang diizinkan, dipisah koma
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Izinkan jika role user ada di daftar yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika AJAX/API request, kembalikan JSON 403
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk melakukan tindakan ini.',
            ], 403);
        }

        // Redirect dengan pesan error yang informatif
        return redirect()->route('dashboard')->with(
            'error',
            'Akses ditolak. Halaman ini hanya untuk ' . implode(' atau ', array_map(
                fn($r) => match($r) {
                    'pengurus_inti'    => 'Pengurus Inti',
                    'pengurus_wilayah' => 'Pengurus Wilayah',
                    'anggota'          => 'Anggota',
                    default            => ucwords(str_replace('_', ' ', $r)),
                },
                $roles
            )) . '.'
        );
    }
}

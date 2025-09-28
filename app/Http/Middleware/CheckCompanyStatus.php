<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckCompanyStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Dapatkan user yang sedang login
        $user = Auth::user();

        // 2. Pastikan user adalah 'admin' dan memiliki relasi admin
        if ($user && $user->role == 'admin' && $user->admin) {
            
            // 3. Ambil data company melalui relasi
            $company = $user->admin->company;

            // 4. Periksa status company
            if ($company && $company->status !== 'approved') {
                // Jika status BUKAN 'approved' (misal: 'pending' atau 'rejected'),
                // arahkan ke halaman status khusus.
                return redirect()->route('admin.status');
            }
        }

        // 5. Jika semua baik-baik saja (user adalah superadmin atau company-nya approved),
        // lanjutkan ke halaman yang dituju (misal: dashboard).
        return $next($request);
    }
}

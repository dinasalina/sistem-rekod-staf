<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole; // Import Enum

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  // Terima satu atau lebih peranan
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) { // Jika pengguna tak login, teruskan ke proses login
            return redirect('login');
        }

        $user = Auth::user();

        foreach ($roles as $role) {
            // Bandingkan nilai string dari Enum
            if ($user->role->value === $role) {
                return $next($request); // Benarkan akses jika peranan sepadan
            }
        }

        // Jika tiada peranan yang sepadan, boleh hantar ke halaman 403 (Forbidden) atau dashboard
        // abort(403, 'ANDA TIDAK DIBENARKAN MENGAKSES HALAMAN INI.');
        return redirect('/dashboard')->with('error', 'Anda tidak dibenarkan mengakses halaman tersebut.');
    }
}
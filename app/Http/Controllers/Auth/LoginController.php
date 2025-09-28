<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    // untuk menuju ke halaman login
    public function showLoginForm() {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Menyiapkan kredensial & mengizinkan login via username atau email
        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $credentials = [
            $loginField => $request->username,
            'password' => $request->password
        ];

        // 3. Mencoba untuk login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Jika berhasil, regenerate session untuk keamanan
            $request->session()->regenerate();

            // 4. Arahkan berdasarkan role
            $role = Auth::user()->role;
            
            switch ($role) {
                case 'superadmin':
                    return redirect()->intended('/superadmin/dashboard');
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                case 'petugas':
                    return redirect()->intended('/petugas/dashboard');
                case 'pelanggan':
                    return redirect()->intended('/pelanggan/dashboard');
                default:
                    // Jika role tidak dikenal, arahkan ke halaman utama
                    return redirect()->intended('/');
            }
        }

        // 5. Jika login gagal
        return back()->with('error', 'Username atau Password salah!');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
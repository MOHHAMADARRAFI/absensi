<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'nis_nim';

        if (Auth::attempt([$loginField => $credentials['login'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return back()->withErrors([
            'login' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('login');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nis_nim' => 'required|string|max:50|unique:users',
            'sekolah_universitas' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_tgl_lahir' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nis_nim' => $validated['nis_nim'],
            'sekolah_universitas' => $validated['sekolah_universitas'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_tgl_lahir' => $validated['tempat_tgl_lahir'],
            'password' => bcrypt($validated['password']),
            'role' => 'peserta',
            'status_aktif' => 'aktif',
        ]);

        Auth::login($user);

        return redirect()->route('peserta.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showForgotPasswordForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        return view('auth.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nis_nim';

        $user = \App\Models\User::where($loginField, $request->login)->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'Data pengguna dengan Email atau NIS/NIM tersebut tidak ditemukan.',
            ])->onlyInput('login');
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan masuk menggunakan password baru.');
    }

    private function redirectBasedOnRole($role)
    {
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('peserta.dashboard');
    }
}

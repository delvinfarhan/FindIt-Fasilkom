<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isPetugas()
                ? redirect()->route('officer.dashboard')
                : redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isPetugas()) {
                return redirect()->intended(route('officer.dashboard'))
                    ->with('success', 'Selamat datang di Panel Operasional Security, ' . $user->name . '.');
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function quickLogin(string $role): RedirectResponse
    {
        if ($role === 'petugas') {
            $user = User::where('role', 'petugas')->first();
            if ($user) {
                Auth::login($user);
                request()->session()->regenerate();
                return redirect()->route('officer.dashboard')
                    ->with('success', 'Berhasil masuk cepat sebagai Petugas Security: ' . $user->name);
            }
        } else {
            $user = User::where('email', 'nadia.putri@example.ac.id')->first()
                ?? User::where('role', 'mahasiswa')->first();
            if ($user) {
                Auth::login($user);
                request()->session()->regenerate();
                return redirect()->route('dashboard')
                    ->with('success', 'Berhasil masuk cepat sebagai Civitas Akademika: ' . $user->name);
            }
        }

        return redirect()->route('login')->with('error', 'Pengguna demo tidak ditemukan.');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'identity_number' => ['required', 'string', 'max:30'], // NPM
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'min:10', 'max:15'],
            'department' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'identity_number.required' => 'NPM / Nomor Identitas wajib diisi.',
            'email.required' => 'Email kampus wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone_number.required' => 'Nomor kontak WhatsApp wajib diisi.',
            'phone_number.min' => 'Nomor kontak minimal 10 digit.',
            'phone_number.max' => 'Nomor kontak maksimal 15 digit.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'identity_number' => $validated['identity_number'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'department' => $validated['department'] ?? 'Fakultas Ilmu Komputer',
            'role' => 'mahasiswa',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di FindIt FASILKOM.');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): View|RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email kampus wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        return view('auth.reset-link-sent', [
            'email' => $request->input('email'),
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}

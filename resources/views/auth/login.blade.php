@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        <!-- Header -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#173B70] text-white font-bold text-2xl shadow-md mb-3">
                F
            </div>
            <h2 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Selamat Datang Kembali</h2>
            <p class="text-sm text-[#64748B] mt-1">Masuk dengan akun civitas akademika atau petugas pos security</p>
        </div>

        <!-- Login Form Card -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6 sm:p-8">
            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Email Kampus / Akun</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB] focus:border-transparent transition @error('email') border-red-500 bg-red-50/50 @enderror"
                        placeholder="contoh: nadia.putri@example.ac.id">
                    @error('email')
                        <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-[#0F172A]">Kata Sandi</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-medium text-[#2563EB] hover:underline">Lupa kata sandi?</a>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB] focus:border-transparent transition @error('password') border-red-500 @enderror"
                        placeholder="Masukkan kata sandi akun Anda">
                    @error('password')
                        <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 rounded text-[#2563EB] border-[#E2E8F0] focus:ring-[#2563EB]">
                    <label for="remember" class="ml-2 block text-xs text-[#64748B]">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-semibold text-sm shadow-xs transition">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-[#E2E8F0] space-y-2 text-center text-xs text-[#64748B]">
                <p>
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-semibold text-[#2563EB] hover:underline ml-1">Daftar akun civitas di sini</a>
                </p>
                <p class="text-[11px] text-[#94A3B8]">
                    Petugas Pos Keamanan silakan masuk menggunakan kredensial akun dinas internal FASILKOM.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

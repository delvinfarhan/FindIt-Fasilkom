@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        <div class="text-center">
            <h2 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Pemulihan Akses Akun</h2>
            <p class="text-sm text-[#64748B] mt-1">Masukkan alamat email kampus yang terdaftar untuk menerima tautan pemulihan kata sandi (masa berlaku 30 menit).</p>
        </div>

        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6 sm:p-8">
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Email Kampus Terdaftar</label>
                    <input id="email" type="email" name="email" value="{{ old('email', 'nadia.putri@example.ac.id') }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]"
                        placeholder="contoh: nadia.putri@example.ac.id">
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-semibold text-sm shadow-xs transition">
                    Kirim Tautan Pemulihan
                </button>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#0F172A]">&larr; Kembali ke halaman masuk</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

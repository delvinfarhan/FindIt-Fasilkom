@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center space-y-6 bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-8">
        <div class="w-16 h-16 rounded-full bg-blue-50 text-[#2563EB] flex items-center justify-center mx-auto">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h2 class="text-xl font-extrabold text-[#0F172A]">Tautan Pemulihan Terkirim</h2>
        <p class="text-sm text-[#64748B] leading-relaxed">
            Instruksi dan tautan pemulihan kata sandi telah dikirim ke alamat surel:
            <span class="block font-semibold text-[#0F172A] mt-1">{{ $email }}</span>
        </p>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800 text-left">
            <p class="font-semibold">Perhatian:</p>
            <p>Tautan pemulihan memiliki masa berlaku aktif 30 menit demi keamanan akses akun Anda.</p>
        </div>
        <a href="{{ route('login') }}" class="inline-block w-full py-2.5 px-4 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-semibold text-sm transition">
            Kembali ke Halaman Masuk
        </a>
    </div>
</div>
@endsection

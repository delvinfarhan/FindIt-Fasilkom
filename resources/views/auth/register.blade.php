@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Buat Akun Civitas Akademika</h2>
            <p class="text-sm text-[#64748B] mt-1">Daftarkan akun untuk melaporkan kehilangan dan mengajukan klaim barang temuan</p>
        </div>

        <!-- Registration Card (Layar 03 Figma) -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6 sm:p-8">
            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Nama Lengkap Sesuai KTM / KTP <span class="text-red-500">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB] @error('name') border-red-500 bg-red-50/50 @enderror"
                        placeholder="contoh: Mochammad Delvin Farhan Akbar">
                    @error('name')
                        <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="identity_number" class="block text-xs font-semibold text-[#0F172A] mb-1.5">NPM / NIP <span class="text-red-500">*</span></label>
                        <input id="identity_number" type="text" name="identity_number" value="{{ old('identity_number') }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB] @error('identity_number') border-red-500 @enderror"
                            placeholder="contoh: 2310631250063">
                        @error('identity_number')
                            <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Nomor WhatsApp Aktif <span class="text-red-500">*</span></label>
                        <input id="phone_number" type="tel" name="phone_number" value="{{ old('phone_number') }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB] @error('phone_number') border-red-500 @enderror"
                            placeholder="081234567890">
                        @error('phone_number')
                            <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="department" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Program Studi / Instansi</label>
                    <select id="department" name="department" class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
                        <option value="S1 Sistem Informasi">S1 Sistem Informasi - FASILKOM</option>
                        <option value="S1 Informatika">S1 Informatika - FASILKOM</option>
                        <option value="Dosen / Tenaga Kependidikan">Dosen / Tenaga Kependidikan FASILKOM</option>
                        <option value="Lainnya">Civitas Akademika Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Email Kampus / Aktif <span class="text-red-500">*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB] @error('email') border-red-500 @enderror"
                        placeholder="contoh: delvin@student.unsika.ac.id">
                    @error('email')
                        <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Kata Sandi (min. 6) <span class="text-red-500">*</span></label>
                        <input id="password" type="password" name="password" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB] @error('password') border-red-500 @enderror"
                            placeholder="Minimal 6 karakter">
                        @error('password')
                            <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Ulangi Kata Sandi <span class="text-red-500">*</span></label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]"
                            placeholder="Ketik ulang kata sandi">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-semibold text-sm shadow-xs transition">
                        Daftar Akun Baru
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-[#E2E8F0] text-center text-xs text-[#64748B]">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-semibold text-[#2563EB] hover:underline ml-1">Masuk ke sistem</a>
            </div>
        </div>
    </div>
</div>
@endsection

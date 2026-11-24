@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Profil &amp; Pengaturan Akun</h1>
        <p class="text-sm text-[#64748B] mt-1">Kelola data identitas akademik, nomor kontak darurat, dan kata sandi akun FindIt.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Left: Profile Identity Card (Layar 21 Figma) -->
        <div class="md:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs text-center">
                <div class="w-20 h-20 rounded-full bg-[#173B70] text-white flex items-center justify-center font-extrabold text-2xl mx-auto mb-4 shadow-sm">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>

                <h2 class="text-lg font-bold text-[#0F172A]">{{ $user->name }}</h2>
                <p class="text-xs text-[#64748B] mt-0.5">{{ $user->email }}</p>

                <div class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-semibold {{ $user->isPetugas() ? 'bg-amber-100 text-amber-900' : 'bg-blue-100 text-[#2563EB]' }}">
                    {{ $user->isPetugas() ? 'Petugas Security FASILKOM' : 'Civitas Akademika' }}
                </div>

                <div class="mt-6 pt-5 border-t border-[#E2E8F0] text-left text-xs space-y-3">
                    <div>
                        <span class="text-[#64748B] block">NPM / Nomor Identitas:</span>
                        <span class="font-mono font-semibold text-[#0F172A] mt-0.5 block">{{ $user->identity_number ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-[#64748B] block">Program Studi / Unit:</span>
                        <span class="font-medium text-[#0F172A] mt-0.5 block">{{ $user->department ?? 'FASILKOM UNSIKA' }}</span>
                    </div>
                    <div>
                        <span class="text-[#64748B] block">WhatsApp Terdaftar:</span>
                        <span class="font-mono font-semibold text-[#0F172A] mt-0.5 block">{{ $user->phone_number ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Privacy Policy Box (Layar 25 Figma) -->
            <div class="bg-blue-50/70 border border-blue-200/80 rounded-2xl p-5 text-xs text-blue-900 space-y-2">
                <h4 class="font-bold text-[#173B70] flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Kebijakan Privasi Data Kampus
                </h4>
                <p class="leading-relaxed text-blue-800">
                    Nomor identitas dan nomor kontak Anda hanya digunakan oleh petugas Pos Security FASILKOM untuk memverifikasi keabsahan klaim serta koordinasi serah terima barang.
                </p>
            </div>
        </div>

        <!-- Right: Update Settings Form (Layar 25 Figma) -->
        <div class="md:col-span-7">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6 sm:p-8">
                <h3 class="text-base font-bold text-[#0F172A] mb-4 pb-2 border-b border-[#E2E8F0]">Perbarui Informasi Akun</h3>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]">
                        @error('name')
                            <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Nomor WhatsApp Aktif</label>
                        <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]">
                        @error('phone_number')
                            <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="department" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Program Studi / Unit</label>
                        <input type="text" id="department" name="department" value="{{ old('department', $user->department) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]">
                    </div>

                    <div class="pt-4 border-t border-[#E2E8F0]">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-3">Ubah Kata Sandi (Opsional)</h4>

                        <div class="space-y-3">
                            <div>
                                <label for="current_password" class="block text-xs font-semibold text-[#0F172A] mb-1">Kata Sandi Saat Ini</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="w-full px-3.5 py-2 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]">
                                @error('current_password')
                                    <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="new_password" class="block text-xs font-semibold text-[#0F172A] mb-1">Kata Sandi Baru</label>
                                    <input type="password" id="new_password" name="new_password"
                                        class="w-full px-3.5 py-2 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]">
                                </div>
                                <div>
                                    <label for="new_password_confirmation" class="block text-xs font-semibold text-[#0F172A] mb-1">Ulangi Kata Sandi Baru</label>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                        class="w-full px-3.5 py-2 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#E2E8F0] flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-semibold text-xs shadow-xs transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('home') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB] flex items-center gap-1 mb-2">
            &larr; Kembali ke Beranda
        </a>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Laporkan Barang Temuan (Civitas)</h1>
        <p class="text-sm text-[#64748B] mt-1">Bantu rekan civitas akademika menemukan kembali barang miliknya yang tertinggal di area kampus.</p>
    </div>

    <!-- Alert / Notice Alur D (Bab 4.1.5 Figma) -->
    <div class="bg-blue-50 border border-blue-200/90 rounded-2xl p-4 mb-6 flex items-start gap-3 text-blue-900 text-xs">
        <div class="p-1.5 rounded-xl bg-blue-100 text-[#2563EB] shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="leading-relaxed">
            <p class="font-bold text-[#173B70]">Aturan Penyerahan Barang Temuan:</p>
            <p class="mt-0.5 text-blue-800">
                1. Melaporkan barang temuan <strong>bukan klaim kepemilikan Anda</strong>.<br>
                2. Laporan ini <strong>belum dipublikasikan ke katalog publik</strong> sebelum fisik barang Anda serahkan secara langsung ke <strong>Pos Security FASILKOM (Lantai 1)</strong>.
            </p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('civitas-reports.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="item_name" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Nama Barang yang Ditemukan <span class="text-red-500">*</span></label>
                <input type="text" id="item_name" name="item_name" value="{{ old('item_name') }}" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('item_name') border-red-500 bg-red-50/50 @enderror"
                    placeholder="contoh: Payung lipat biru tua">
                @error('item_name')
                    <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category_id" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Kategori Barang <span class="text-red-500">*</span></label>
                    <select id="category_id" name="category_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="found_date" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Tanggal Ditemukan <span class="text-red-500">*</span></label>
                    <input type="date" id="found_date" name="found_date" value="{{ old('found_date', date('Y-m-d')) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]">
                </div>
            </div>

            <div>
                <label for="found_location" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Lokasi Ditemukan di Kampus <span class="text-red-500">*</span></label>
                <input type="text" id="found_location" name="found_location" value="{{ old('found_location') }}" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]"
                    placeholder="contoh: Area parkir FASILKOM samping gazebo atau tangga lantai 2">
            </div>

            <div>
                <label for="public_description" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Deskripsi Singkat Barang <span class="text-red-500">*</span></label>
                <textarea id="public_description" name="public_description" rows="2" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]"
                    placeholder="Deskripsikan kondisi umum barang saat Anda temukan.">{{ old('public_description') }}</textarea>
            </div>

            <div>
                <label for="private_features" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Ciri Khusus / Rahasia (Opsional)</label>
                <textarea id="private_features" name="private_features" rows="2"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]"
                    placeholder="Jika ada stiker, ukiran, atau ciri tersembunyi yang Anda ketahui.">{{ old('private_features') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="temp_storage" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Posisi Barang Saat Ini <span class="text-red-500">*</span></label>
                    <input type="text" id="temp_storage" name="temp_storage" value="{{ old('temp_storage', 'Masih saya simpan, siap diserahkan ke pos security') }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]">
                </div>

                <div>
                    <label for="contact_phone" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Nomor WhatsApp Anda <span class="text-red-500">*</span></label>
                    <input type="tel" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone_number) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB]">
                </div>
            </div>

            <div>
                <label for="image" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Foto Barang Temuan (Opsional)</label>
                <input type="file" id="image" name="image" accept="image/*"
                    class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#64748B] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#2563EB] hover:file:bg-blue-100">
            </div>

            <!-- Agreement -->
            <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] text-xs text-[#64748B]">
                <label class="flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" required class="w-4 h-4 rounded text-[#2563EB] border-[#E2E8F0] focus:ring-[#2563EB] mt-0.5">
                    <span>
                        Saya menyatakan informasi ini benar dan bersedia menyerahkan fisik barang kepada Petugas Pos Security FASILKOM UNSIKA untuk diamankan.
                    </span>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E2E8F0]">
                <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#64748B] hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Laporan Temuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

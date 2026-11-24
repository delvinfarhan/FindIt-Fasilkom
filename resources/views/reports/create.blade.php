@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB] flex items-center gap-1 mb-2">
            &larr; Kembali ke Laporan Saya
        </a>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Formulir Lapor Kehilangan</h1>
        <p class="text-sm text-[#64748B] mt-1">Ceritakan barang yang hilang untuk dicocokkan oleh petugas security dengan inventaris pos.</p>
    </div>

    <!-- Stepper Indicator (Layar 10 & 11 Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-4 mb-6 shadow-xs">
        <div class="flex items-center justify-between text-xs font-semibold">
            <div class="flex items-center gap-2 text-[#2563EB]">
                <span class="w-6 h-6 rounded-full bg-[#2563EB] text-white flex items-center justify-center text-xs">1</span>
                <span>Isi Informasi Barang</span>
            </div>
            <div class="w-12 h-px bg-[#E2E8F0]"></div>
            <div class="flex items-center gap-2 text-[#64748B]">
                <span class="w-6 h-6 rounded-full bg-slate-100 text-[#64748B] flex items-center justify-center text-xs">2</span>
                <span>Tinjau &amp; Kirim Laporan</span>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-6" id="lostReportForm">
            @csrf

            <!-- Section 1: Data Barang Umum -->
            <div>
                <h3 class="text-sm font-bold text-[#173B70] uppercase tracking-wider mb-4 pb-2 border-b border-[#E2E8F0] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#2563EB]"></span>
                    Informasi Umum Barang
                </h3>

                <div class="space-y-4">
                    <div>
                        <label for="item_name" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Nama Barang yang Hilang <span class="text-red-500">*</span></label>
                        <input type="text" id="item_name" name="item_name" value="{{ old('item_name') }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('item_name') border-red-500 bg-red-50/50 @enderror"
                            placeholder="contoh: Dompet hitam lipat berbahan kulit">
                        @error('item_name')
                            <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category_id" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Kategori Barang <span class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('category_id') border-red-500 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="lost_date" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Perkiraan Tanggal Kehilangan <span class="text-red-500">*</span></label>
                            <input type="date" id="lost_date" name="lost_date" value="{{ old('lost_date', date('Y-m-d')) }}" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('lost_date') border-red-500 @enderror">
                            @error('lost_date')
                                <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="lost_location" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Lokasi Terakhir Terlihat / Diingat <span class="text-red-500">*</span></label>
                        <input type="text" id="lost_location" name="lost_location" value="{{ old('lost_location') }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('lost_location') border-red-500 @enderror"
                            placeholder="contoh: Lobi Gedung FASILKOM atau Lab Komputer 2 Lt. 2">
                        @error('lost_location')
                            <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="public_description" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Deskripsi Ringkas Barang <span class="text-red-500">*</span></label>
                        <textarea id="public_description" name="public_description" rows="2" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('public_description') border-red-500 @enderror"
                            placeholder="contoh: Dompet lipat hitam merk Baellerry, ukuran saku celana.">{{ old('public_description') }}</textarea>
                        @error('public_description')
                            <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Ciri Khusus Privat (PENTING untuk Verifikasi Security) -->
            <div>
                <div class="flex items-center justify-between pb-2 mb-3 border-b border-[#E2E8F0]">
                    <h3 class="text-sm font-bold text-[#173B70] uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Ciri Khusus Rahasia (Hanya Dilihat Petugas)
                    </h3>
                    <span class="text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">Privat</span>
                </div>

                <div class="bg-amber-50/60 border border-amber-200/80 rounded-xl p-3 text-xs text-amber-900 mb-3">
                    <p class="font-semibold">Mengapa ciri privat penting?</p>
                    <p class="mt-0.5 leading-relaxed">
                        Ciri khusus ini <strong>tidak akan pernah dipublikasikan</strong>. Petugas security akan mencocokkan ciri rahasia ini dengan barang fisik yang diamankan di pos sebelum menyerahkannya kepada Anda.
                    </p>
                </div>

                <div>
                    <label for="private_features" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Ciri Rahasia / Bukti Identifikasi Khusus <span class="text-red-500">*</span></label>
                    <textarea id="private_features" name="private_features" rows="3" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('private_features') border-red-500 @enderror"
                        placeholder="Jelaskan detail yang hanya Anda ketahui: warna benang jahitan, goresan tertentu, nomor seri, isi di dalam slot/kantong, atau stiker tertentu.">{{ old('private_features') }}</textarea>
                    @error('private_features')
                        <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section 3: Kontak & Foto Pendukung -->
            <div>
                <h3 class="text-sm font-bold text-[#173B70] uppercase tracking-wider mb-4 pb-2 border-b border-[#E2E8F0] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                    Kontak &amp; Foto Bukti
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="contact_phone" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Nomor WhatsApp Aktif <span class="text-red-500">*</span></label>
                        <input type="tel" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone_number) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('contact_phone') border-red-500 @enderror"
                            placeholder="081200000142">
                        @error('contact_phone')
                            <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="image" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Foto Barang / Referensi (Opsional)</label>
                        <input type="file" id="image" name="image" accept="image/*"
                            class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#64748B] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#2563EB] hover:file:bg-blue-100">
                        <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (maks. 3MB)</p>
                        @error('image')
                            <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Agreement -->
            <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] text-xs text-[#64748B]">
                <label class="flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" required class="w-4 h-4 rounded text-[#2563EB] border-[#E2E8F0] focus:ring-[#2563EB] mt-0.5">
                    <span>
                        Saya menyatakan dengan sungguh-sungguh bahwa data kehilangan yang saya laporkan adalah benar dan dapat dipertanggungjawabkan kepada petugas Pos Security FASILKOM UNSIKA.
                    </span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('reports.index') }}" class="px-5 py-2.5 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#64748B] hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Laporan Kehilangan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

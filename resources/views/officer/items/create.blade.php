@extends('layouts.officer', ['title' => 'Catat Barang Temuan', 'pageTitle' => 'Formulir Pencatatan Barang Temuan Baru'])

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('officer.items.index') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB]">&larr; Kembali ke Daftar Inventaris</a>
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight mt-1">Catat Barang Temuan Inventaris</h1>
            <p class="text-xs text-[#64748B]">Kirim barang temuan ke inventaris pos dengan pemisahan informasi publik dan catatan privat security.</p>
        </div>
    </div>

    <!-- 2-Column Form Card (Layar 03 Desktop Figma) -->
    <form method="POST" action="{{ route('officer.items.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Left Column: Informasi Publik (Ditampilkan di Katalog) -->
            <div class="md:col-span-7 bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6 space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-[#E2E8F0]">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#2563EB]"></span>
                    <h3 class="text-xs font-bold text-[#173B70] uppercase tracking-wider">1. Informasi Katalog Publik</h3>
                </div>

                <div>
                    <label for="name" class="block text-xs font-semibold text-[#0F172A] mb-1">Nama Umum Barang <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-3.5 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#2563EB]"
                        placeholder="Contoh: Dompet hitam lipat / Tumbler stainless">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="category_id" class="block text-xs font-semibold text-[#0F172A] mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#2563EB]">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="found_date" class="block text-xs font-semibold text-[#0F172A] mb-1">Tanggal Ditemukan <span class="text-red-500">*</span></label>
                        <input type="date" id="found_date" name="found_date" value="{{ old('found_date', date('Y-m-d')) }}" required
                            class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#2563EB]">
                    </div>
                </div>

                <div>
                    <label for="found_location" class="block text-xs font-semibold text-[#0F172A] mb-1">Lokasi Umum Ditemukan <span class="text-red-500">*</span></label>
                    <input type="text" id="found_location" name="found_location" value="{{ old('found_location') }}" required
                        class="w-full px-3.5 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#2563EB]"
                        placeholder="Contoh: Lobi Gedung FASILKOM / Lab Komputer 2">
                </div>

                <div>
                    <label for="public_description" class="block text-xs font-semibold text-[#0F172A] mb-1">Deskripsi Umum Publik <span class="text-red-500">*</span></label>
                    <textarea id="public_description" name="public_description" rows="3" required
                        class="w-full px-3.5 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#2563EB]"
                        placeholder="Deskripsi umum tanpa membocorkan ciri rahasia: Dompet lipat hitam ditemukan di area lobi utama.">{{ old('public_description') }}</textarea>
                    <p class="text-[11px] text-[#64748B] mt-1">Jangan mencantumkan ciri rahasia (seperti nomor seri atau isi dompet) di sini.</p>
                </div>

                <div>
                    <label for="image" class="block text-xs font-semibold text-[#0F172A] mb-1">Unggah Foto Barang</label>
                    <input type="file" id="image" name="image" accept="image/*"
                        class="w-full px-3 py-1.5 rounded-xl border border-[#E2E8F0] text-xs text-[#64748B] file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-50 file:text-[#2563EB]">
                </div>
            </div>

            <!-- Right Column: Informasi Privat Security (Ciri Khusus & Lokasi Rak Pos) -->
            <div class="md:col-span-5 space-y-6">
                <div class="bg-amber-50/70 rounded-2xl border border-amber-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-2 border-b border-amber-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <h3 class="text-xs font-bold text-amber-950 uppercase tracking-wider">2. Catatan Privat Security</h3>
                    </div>

                    <div>
                        <label for="storage_location" class="block text-xs font-semibold text-[#0F172A] mb-1">Lokasi Fisik Rak Penyimpanan <span class="text-red-500">*</span></label>
                        <input type="text" id="storage_location" name="storage_location" value="{{ old('storage_location', 'Lemari A • Rak 02') }}" required
                            class="w-full px-3.5 py-2 rounded-xl border border-amber-300 bg-white text-xs font-semibold text-[#173B70] focus:ring-2 focus:ring-[#2563EB]"
                            placeholder="Contoh: Lemari A • Rak 02 / Kotak Aksesori 1">
                        <p class="text-[11px] text-amber-800 mt-1">Digunakan untuk mengambil barang fisik secara cepat saat serah terima.</p>
                    </div>

                    <div>
                        <label for="private_description" class="block text-xs font-semibold text-[#0F172A] mb-1">Ciri Rahasia / Bukti Privat Verifikasi <span class="text-red-500">*</span></label>
                        <textarea id="private_description" name="private_description" rows="5" required
                            class="w-full px-3.5 py-2 rounded-xl border border-amber-300 bg-white text-xs focus:ring-2 focus:ring-[#2563EB]"
                            placeholder="Catat ciri-ciri rahasia untuk memverifikasi pemohon sah: jahitan benang biru, inisial N.P. di sudut dalam, isi uang tunai Rp 150rb, dsb.">{{ old('private_description') }}</textarea>
                        <p class="text-[11px] text-amber-900 mt-1">Ciri ini <strong>TIDAK PERNAH DITAMPILKAN</strong> ke publik.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('officer.items.index') }}" class="px-4 py-2.5 rounded-xl border border-[#E2E8F0] bg-white text-xs font-semibold text-[#64748B] hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition">
                        Simpan Barang ke Inventaris
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

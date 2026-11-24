@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-[#64748B] mb-6">
        <a href="{{ route('home') }}" class="hover:text-[#2563EB]">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('catalog.show', $foundItem) }}" class="hover:text-[#2563EB]">{{ $foundItem->name }}</a>
        <span>&rsaquo;</span>
        <span class="text-[#0F172A] font-semibold">Ajukan Klaim</span>
    </nav>

    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Formulir Pengajuan Klaim Kepemilikan</h1>
        <p class="text-sm text-[#64748B] mt-1">Berikan bukti dan deskripsi ciri khusus privat untuk membuktikan bahwa Anda adalah pemilik sah barang ini.</p>
    </div>

    <!-- Target Found Item Preview Card (Layar 15 Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-4 sm:p-5 shadow-xs mb-6 flex items-center gap-4">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
            @if($foundItem->image_path)
                <img src="{{ asset('storage/' . $foundItem->image_path) }}" alt="{{ $foundItem->name }}" class="w-full h-full object-cover">
            @else
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            @endif
        </div>
        <div class="min-w-0">
            <span class="font-mono text-xs font-bold text-[#2563EB]">{{ $foundItem->item_code }}</span>
            <h2 class="text-base sm:text-lg font-bold text-[#0F172A] truncate">{{ $foundItem->name }}</h2>
            <p class="text-xs text-[#64748B]">Ditemukan di: {{ $foundItem->found_location }} &bull; {{ $foundItem->found_date->translatedFormat('d M Y') }}</p>
        </div>
    </div>

    <!-- Alert Verification Notice (Layar 15 Figma) -->
    <div class="bg-amber-50/80 border border-amber-200/90 rounded-2xl p-4 mb-6 flex items-start gap-3 text-amber-900 text-xs">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <div class="leading-relaxed">
            <p class="font-bold">Klaim Perlu Diverifikasi:</p>
            <p class="mt-0.5">
                Pengajuan klaim tidak otomatis disetujui. Petugas security akan mencocokkan ciri khusus privat dan bukti yang Anda lampirkan dengan barang fisik yang disimpan di Pos Security FASILKOM.
            </p>
        </div>
    </div>

    <!-- Claim Form -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('claims.store', $foundItem) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Applicant Info (Auto Filled) -->
            <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0]">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-2">Identitas Pemohon</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-[#64748B] block">Nama Pemohon:</span>
                        <span class="font-bold text-[#0F172A] block mt-0.5">{{ auth()->user()->name }}</span>
                    </div>
                    <div>
                        <span class="text-[#64748B] block">NPM / Identitas:</span>
                        <span class="font-mono font-semibold text-[#0F172A] block mt-0.5">{{ auth()->user()->identity_number ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Field 1: Chronology -->
            <div>
                <label for="chronology" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Penjelasan Kronologi Kehilangan <span class="text-red-500">*</span></label>
                <textarea id="chronology" name="chronology" rows="3" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('chronology') border-red-500 bg-red-50/50 @enderror"
                    placeholder="Contoh: Hilang setelah kuliah praktikum pada hari Rabu sore tanggal 13 Nov saat berkumpul di area lobi depan.">{{ old('chronology') }}</textarea>
                <p class="text-[11px] text-[#64748B] mt-1">Jelaskan perkiraan waktu dan kegiatan terakhir saat Anda membawa barang tersebut.</p>
                @error('chronology')
                    <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field 2: Private proof features (Crucial!) -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="private_proof_features" class="block text-xs font-semibold text-[#0F172A]">Ciri Khusus yang Hanya Anda Ketahui <span class="text-red-500">*</span></label>
                    <span class="text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">Verifikasi Kunci</span>
                </div>
                <textarea id="private_proof_features" name="private_proof_features" rows="4" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('private_proof_features') border-red-500 @enderror"
                    placeholder="Jelaskan bukti fisik privat: warna jahitan tersembunyi, goresan unik, merk tersembunyi, isi spesifik di dalam dompet/tas, nomor seri, atau gantungan kunci.">{{ old('private_proof_features') }}</textarea>
                <p class="text-[11px] text-[#64748B] mt-1">Bukti ini yang akan dicocokkan petugas dengan data rahasia barang yang tidak diumumkan di publik.</p>
                @error('private_proof_features')
                    <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field 3: WhatsApp Contact -->
            <div>
                <label for="contact_phone" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Nomor WhatsApp Aktif untuk Dihubungi <span class="text-red-500">*</span></label>
                <input type="tel" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone_number) }}" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm focus:ring-2 focus:ring-[#2563EB] @error('contact_phone') border-red-500 @enderror"
                    placeholder="081200000142">
                @error('contact_phone')
                    <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field 4: Optional Proof Photo -->
            <div>
                <label for="proof_image" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Foto Bukti Kepemilikan (Opsional)</label>
                <input type="file" id="proof_image" name="proof_image" accept="image/*"
                    class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#64748B] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#2563EB] hover:file:bg-blue-100">
                <p class="text-[11px] text-[#64748B] mt-1">Dapat berupa: foto lama Anda bersama barang, nota pembelian, atau bukti nomor seri.</p>
                @error('proof_image')
                    <p class="text-xs text-[#DC2626] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#E2E8F0]">
                <a href="{{ route('catalog.show', $foundItem) }}" class="px-5 py-2.5 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#64748B] hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Pengajuan Klaim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

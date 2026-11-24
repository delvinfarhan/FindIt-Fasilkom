@extends('layouts.officer', ['title' => 'Terima Temuan ' . $report->report_code, 'pageTitle' => 'Penerimaan Fisik Barang Temuan Civitas'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('officer.civitas-reports.index') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB]">&larr; Kembali ke Daftar Temuan Civitas</a>
            <div class="flex items-center gap-2 mt-1">
                <span class="font-mono text-xs font-bold text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded">{{ $report->report_code }}</span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A]">{{ $report->item_name }}</h1>
            </div>
        </div>
    </div>

    <!-- 2 Column Receiving Form (Layar 21 & 22 Desktop Figma) -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Left: Detail Temuan dari Mahasiswa -->
        <div class="md:col-span-6 bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs space-y-4 text-xs">
            <h3 class="font-bold uppercase tracking-wider text-[#173B70] pb-2 border-b border-[#E2E8F0]">Rincian Temuan dari Civitas</h3>

            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-[#64748B] block">Penemu / Pelapor:</span>
                <strong class="text-[#0F172A] block">{{ $report->user->name }}</strong>
                <span class="text-[#64748B] block">WhatsApp: {{ $report->contact_phone }}</span>
            </div>

            <div>
                <span class="text-[#64748B] block">Lokasi &amp; Tanggal Ditemukan:</span>
                <span class="font-semibold text-[#0F172A] block mt-0.5">{{ $report->found_location }} &bull; {{ $report->found_date->translatedFormat('d M Y') }}</span>
            </div>

            <div>
                <span class="text-[#64748B] block">Deskripsi Barang:</span>
                <p class="mt-1 text-[#0F172A] bg-slate-50 p-2.5 rounded-lg border border-slate-100 leading-relaxed">{{ $report->public_description }}</p>
            </div>

            @if($report->private_features)
                <div>
                    <span class="text-[#64748B] block">Ciri Khusus Disebutkan Penemu:</span>
                    <p class="mt-1 text-amber-900 bg-amber-50 p-2.5 rounded-lg border border-amber-200">{{ $report->private_features }}</p>
                </div>
            @endif

            @if($report->image_path)
                <div>
                    <span class="text-[#64748B] block mb-1">Foto Barang dari Penemu:</span>
                    <img src="{{ asset('storage/' . $report->image_path) }}" alt="Foto Barang" class="w-full h-40 object-cover rounded-xl border border-[#E2E8F0]">
                </div>
            @endif
        </div>

        <!-- Right: Penerimaan Petugas (Layar 21 & 22 Figma) -->
        <div class="md:col-span-6 space-y-6">
            @if($report->status === 'menunggu_penyerahan')
                <!-- Form Penerimaan Fisik di Pos -->
                <div class="bg-white rounded-2xl border-2 border-emerald-300 p-6 shadow-xs text-xs space-y-4">
                    <div class="flex items-center gap-2 pb-2 border-b border-emerald-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#16A34A]"></span>
                        <h3 class="font-bold text-[#166534] uppercase tracking-wider">Formulir Penerimaan Fisik di Pos</h3>
                    </div>

                    <p class="text-[#64748B] leading-relaxed">
                        Saat mahasiswa penemu menyerahkan fisik barang ini ke Pos Security, catat lokasi rak fisik penyimpanan untuk mempublikasikannya secara otomatis ke katalog publik.
                    </p>

                    <form method="POST" action="{{ route('officer.civitas-reports.receive', $report) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="storage_location" class="block font-semibold text-[#0F172A] mb-1">Alokasi Rak Simpan Pos Security <span class="text-red-500">*</span></label>
                            <input type="text" id="storage_location" name="storage_location" value="{{ old('storage_location', 'Lemari B • Rak 01') }}" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-emerald-300 font-bold text-[#173B70] text-xs focus:ring-2 focus:ring-[#16A34A]"
                                placeholder="Contoh: Lemari B • Rak 01">
                        </div>

                        <div>
                            <label for="officer_notes" class="block font-semibold text-[#0F172A] mb-1">Catatan Pemeriksaan Fisik Barang (Opsional):</label>
                            <textarea id="officer_notes" name="officer_notes" rows="2"
                                class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#16A34A]"
                                placeholder="Kondisi fisik barang saat diterima di pos security."></textarea>
                        </div>

                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-900 border border-emerald-200 text-[11px] leading-relaxed">
                            <strong>Otomatisasi:</strong> Sistem akan otomatis menerbitkan nomor kode inventaris <code>FND-2026-XXXX</code> dan barang akan langsung tampil di katalog publik tanpa ciri privat.
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#16A34A] hover:bg-green-700 text-white font-bold text-xs shadow-xs transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Konfirmasi Penerimaan &amp; Masukkan Inventaris
                        </button>
                    </form>
                </div>
            @else
                <!-- Sudah Diterima (Layar 22 Figma) -->
                <div class="bg-emerald-50 border border-emerald-300 rounded-2xl p-6 text-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#16A34A] text-white flex items-center justify-center font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#166534]">Status: Selesai Diterima</span>
                            <h3 class="text-base font-bold text-[#166534]">Telah Masuk Inventaris Pos</h3>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-emerald-200 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-[#64748B]">Diterima oleh Petugas:</span>
                            <strong class="text-[#0F172A]">{{ $report->receiver->name ?? 'Dedi Saputra' }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#64748B]">Waktu Penerimaan:</span>
                            <span class="text-[#0F172A]">{{ $report->received_at ? $report->received_at->translatedFormat('d M Y, H:i') : '-' }} WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#64748B]">Lokasi Rak Fisik:</span>
                            <strong class="text-[#173B70]">{{ $report->storage_location }}</strong>
                        </div>
                    </div>

                    @if($report->foundItem)
                        <a href="{{ route('officer.items.show', $report->foundItem) }}" class="block w-full py-2.5 px-4 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-center text-xs transition">
                            Lihat di Inventaris Barang ({{ $report->foundItem->item_code }}) &rarr;
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

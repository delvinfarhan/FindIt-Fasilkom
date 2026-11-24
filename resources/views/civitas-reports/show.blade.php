@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-[#64748B] mb-6">
        <a href="{{ route('home') }}" class="hover:text-[#2563EB]">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('reports.index', ['tab' => 'civitas']) }}" class="hover:text-[#2563EB]">Temuan Saya</a>
        <span>&rsaquo;</span>
        <span class="text-[#0F172A] font-semibold truncate">{{ $report->report_code }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Left: Status Tracking (Layar 32 Figma) -->
        <div class="md:col-span-7 space-y-6">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-mono text-xs font-bold px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">
                        {{ $report->report_code }}
                    </span>
                    <span class="text-xs text-[#64748B]">{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>

                <h1 class="text-2xl font-extrabold text-[#0F172A] mb-1">{{ $report->item_name }}</h1>
                <p class="text-xs text-[#64748B] mb-6">Ditemukan di: <strong class="text-[#0F172A]">{{ $report->found_location }}</strong></p>

                <!-- Status Banner (Layar 32 Figma) -->
                @if($report->status === 'menunggu_penyerahan')
                    <div class="p-5 rounded-xl bg-amber-50 border border-amber-200 mb-6 space-y-3">
                        <div class="flex items-start gap-3">
                            <span class="w-3 h-3 rounded-full bg-amber-500 mt-1 shrink-0 animate-pulse"></span>
                            <div>
                                <h3 class="text-sm font-bold text-amber-950">Menunggu Penyerahan Fisik ke Pos Security</h3>
                                <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                                    Barang masih di tangan Anda. Mohon segera serahkan barang fisik ke <strong>Pos Security FASILKOM (Lantai 1)</strong> agar petugas dapat memeriksa dan mempublikasikannya ke katalog publik.
                                </p>
                            </div>
                        </div>

                        <div class="p-3 bg-white/80 rounded-lg border border-amber-200 text-xs text-slate-700">
                            <strong>Instruksi:</strong> Bawa barang ke pos security dan sebutkan nomor tiket <code class="font-bold text-[#2563EB]">{{ $report->report_code }}</code> kepada petugas yang berjaga.
                        </div>
                    </div>
                @elseif($report->status === 'diterima_security')
                    <div class="p-5 rounded-xl bg-emerald-50 border border-emerald-300 mb-6">
                        <div class="flex items-start gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#16A34A] mt-1 shrink-0"></span>
                            <div>
                                <h3 class="text-sm font-bold text-[#166534]">Barang Telah Diterima oleh Pos Security</h3>
                                <p class="text-xs text-emerald-800 mt-1 leading-relaxed">
                                    Terima kasih! Fisik barang telah diserahkan dan dimasukkan ke dalam inventaris katalog publik pos security.
                                </p>
                                @if($report->foundItem)
                                    <div class="mt-3">
                                        <a href="{{ route('catalog.show', $report->foundItem) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#2563EB] text-white text-xs font-semibold hover:bg-blue-700 transition">
                                            Lihat di Katalog Publik ({{ $report->foundItem->item_code }}) &rarr;
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Linimasa 3 Tahap Laporan Temuan (Layar 32 Figma) -->
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-4">Linimasa 3 Tahap Laporan Temuan</h3>
                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#E2E8F0]">
                    <!-- Tahap 1 -->
                    <div class="relative">
                        <span class="absolute -left-6 top-0 w-4 h-4 rounded-full bg-[#16A34A] border-2 border-white shadow-xs"></span>
                        <h4 class="text-xs font-bold text-[#0F172A]">1. Laporan berhasil dikirim oleh penemu</h4>
                        <p class="text-[11px] text-[#64748B]">{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                    </div>

                    <!-- Tahap 2 -->
                    <div class="relative">
                        <span class="absolute -left-6 top-0 w-4 h-4 rounded-full {{ $report->status === 'diterima_security' ? 'bg-[#16A34A]' : 'bg-amber-500' }} border-2 border-white shadow-xs"></span>
                        <h4 class="text-xs font-bold text-[#0F172A]">2. Penyerahan fisik kepada petugas security</h4>
                        <p class="text-[11px] text-[#64748B]">
                            {{ $report->received_at ? 'Diterima oleh petugas pada ' . $report->received_at->translatedFormat('d M Y, H:i') . ' WIB' : 'Menunggu Anda membawa barang fisik ke Pos Security' }}
                        </p>
                    </div>

                    <!-- Tahap 3 -->
                    <div class="relative">
                        <span class="absolute -left-6 top-0 w-4 h-4 rounded-full {{ $report->status === 'diterima_security' ? 'bg-[#16A34A]' : 'bg-slate-300' }} border-2 border-white shadow-xs"></span>
                        <h4 class="text-xs font-bold text-[#0F172A]">3. Pencatatan inventaris &amp; publikasi katalog</h4>
                        <p class="text-[11px] text-[#64748B]">
                            {{ $report->foundItem ? 'Dicatat di rak: ' . ($report->storage_location ?? 'Lemari Pos') : 'Belum dipublikasikan ke katalog sebelum penyerahan' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Detail Rincian -->
        <div class="md:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs text-xs space-y-4">
                <h3 class="font-bold uppercase tracking-wider text-[#64748B]">Data Laporan</h3>

                <div>
                    <span class="text-[#64748B] block">Kategori:</span>
                    <span class="font-bold text-[#0F172A] mt-0.5 block">{{ $report->category->name }}</span>
                </div>

                <div>
                    <span class="text-[#64748B] block">Tanggal Ditemukan:</span>
                    <span class="font-bold text-[#0F172A] mt-0.5 block">{{ $report->found_date->translatedFormat('l, d F Y') }}</span>
                </div>

                <div>
                    <span class="text-[#64748B] block">Deskripsi Barang:</span>
                    <p class="mt-1 text-[#0F172A] bg-slate-50 p-2.5 rounded-lg border border-slate-100">{{ $report->public_description }}</p>
                </div>

                <div>
                    <span class="text-[#64748B] block">Posisi Simpan Saat Melapor:</span>
                    <span class="font-medium text-[#0F172A] mt-0.5 block">{{ $report->temp_storage }}</span>
                </div>

                @if($report->image_path)
                    <div>
                        <span class="text-[#64748B] block mb-1">Foto Barang:</span>
                        <img src="{{ asset('storage/' . $report->image_path) }}" alt="Foto Barang" class="w-full h-36 object-cover rounded-xl border border-[#E2E8F0]">
                    </div>
                @endif

                <div class="pt-4 border-t border-[#E2E8F0]">
                    <a href="{{ route('reports.index', ['tab' => 'civitas']) }}" class="block w-full py-2 px-3 rounded-xl border border-[#E2E8F0] text-center font-semibold text-[#0F172A] hover:bg-slate-50 transition">
                        Kembali ke Temuan Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

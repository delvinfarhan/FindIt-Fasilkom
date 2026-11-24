@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-[#64748B] mb-6">
        <a href="{{ route('home') }}" class="hover:text-[#2563EB]">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('reports.index') }}" class="hover:text-[#2563EB]">Laporan Saya</a>
        <span>&rsaquo;</span>
        <span class="text-[#0F172A] font-semibold truncate">{{ $report->report_code }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Left: Status & Timeline (Layar 14 Figma) -->
        <div class="md:col-span-7 space-y-6">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-mono text-xs font-bold px-2.5 py-1 rounded-md bg-blue-50 text-[#2563EB]">
                        {{ $report->report_code }}
                    </span>
                    <span class="text-xs text-[#64748B]">{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>

                <h1 class="text-2xl font-extrabold text-[#0F172A] mb-2">{{ $report->item_name }}</h1>
                <p class="text-xs text-[#64748B] mb-6">Kategori: <strong class="text-[#0F172A]">{{ $report->category->name }}</strong></p>

                <!-- Status Timeline (Layar 14 Figma) -->
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-4">Linimasa Pelacakan Laporan</h3>
                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#E2E8F0]">
                    <!-- Step 1: Laporan Berhasil Dikirim -->
                    <div class="relative">
                        <span class="absolute -left-6 top-0 w-4 h-4 rounded-full bg-[#16A34A] border-2 border-white shadow-xs"></span>
                        <h4 class="text-xs font-bold text-[#0F172A]">Laporan berhasil dikirim</h4>
                        <p class="text-[11px] text-[#64748B] mt-0.5">{{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                    </div>

                    <!-- Step 2: Pemeriksaan Security -->
                    <div class="relative">
                        <span class="absolute -left-6 top-0 w-4 h-4 rounded-full {{ in_array($report->status, ['diproses', 'cocok', 'selesai']) ? 'bg-[#2563EB]' : 'bg-slate-300' }} border-2 border-white shadow-xs"></span>
                        <h4 class="text-xs font-bold text-[#0F172A]">Sedang diperiksa security</h4>
                        <p class="text-[11px] text-[#64748B] mt-0.5">Petugas sedang mencocokkan ciri barang dengan inventaris pos security.</p>
                    </div>

                    <!-- Step 3: Pencocokan / Selesai -->
                    <div class="relative">
                        <span class="absolute -left-6 top-0 w-4 h-4 rounded-full {{ $report->status === 'selesai' ? 'bg-[#16A34A]' : ($report->status === 'cocok' ? 'bg-[#60A5FA]' : 'bg-slate-300') }} border-2 border-white shadow-xs"></span>
                        @if($report->status === 'cocok')
                            <h4 class="text-xs font-bold text-[#2563EB]">Terdapat barang berpotensi cocok!</h4>
                            <p class="text-[11px] text-[#64748B] mt-0.5">Petugas menemukan barang serupa di inventaris. Silakan periksa barang dan ajukan klaim.</p>
                            @if($report->matchedFoundItem)
                                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                                    <p class="text-xs font-bold text-[#173B70]">{{ $report->matchedFoundItem->name }} ({{ $report->matchedFoundItem->item_code }})</p>
                                    <p class="text-[11px] text-blue-800">Ditemukan di {{ $report->matchedFoundItem->found_location }}</p>
                                    <a href="{{ route('catalog.show', $report->matchedFoundItem) }}" class="inline-block mt-2 px-3 py-1 bg-[#2563EB] text-white text-[11px] font-semibold rounded-lg hover:bg-blue-700">
                                        Lihat Barang &amp; Ajukan Klaim &rarr;
                                    </a>
                                </div>
                            @endif
                        @elseif($report->status === 'selesai')
                            <h4 class="text-xs font-bold text-[#16A34A]">Laporan Selesai / Barang Ditemukan</h4>
                            <p class="text-[11px] text-[#64748B] mt-0.5">Barang telah berhasil ditemukan dan diserahkan kepada Anda di Pos Security.</p>
                        @elseif($report->status === 'dibatalkan')
                            <h4 class="text-xs font-bold text-[#DC2626]">Laporan Dibatalkan</h4>
                            <p class="text-[11px] text-[#64748B] mt-0.5">Laporan telah dibatalkan oleh pelapor.</p>
                        @else
                            <h4 class="text-xs font-bold text-[#64748B]">Pencocokan Barang Temuan</h4>
                            <p class="text-[11px] text-[#64748B] mt-0.5">Kami akan memberi notifikasi saat ditemukan barang yang cocok.</p>
                        @endif
                    </div>
                </div>

                @if($report->officer_notes)
                    <div class="mt-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs">
                        <span class="font-bold text-amber-900 block mb-1">Catatan Petugas Security:</span>
                        <p class="text-amber-800 leading-relaxed">{{ $report->officer_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Information Summary -->
        <div class="md:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-4">Rincian Laporan</h3>

                <div class="space-y-4 text-xs">
                    <div>
                        <span class="text-[#64748B] block">Perkiraan Waktu Kehilangan:</span>
                        <span class="font-bold text-[#0F172A] mt-0.5 block">{{ $report->lost_date->translatedFormat('l, d F Y') }}</span>
                    </div>

                    <div>
                        <span class="text-[#64748B] block">Lokasi Terakhir:</span>
                        <span class="font-bold text-[#0F172A] mt-0.5 block">{{ $report->lost_location }}</span>
                    </div>

                    <div>
                        <span class="text-[#64748B] block">Deskripsi Umum:</span>
                        <p class="mt-1 text-[#0F172A] bg-slate-50 p-2.5 rounded-lg border border-slate-100">{{ $report->public_description }}</p>
                    </div>

                    <div>
                        <span class="text-[#64748B] block flex items-center gap-1">
                            Ciri Khusus Rahasia:
                            <span class="px-1.5 py-0.2 rounded bg-amber-100 text-amber-800 text-[10px] font-semibold">Privat</span>
                        </span>
                        <p class="mt-1 text-[#0F172A] bg-amber-50/50 p-2.5 rounded-lg border border-amber-100">{{ $report->private_features }}</p>
                    </div>

                    <div>
                        <span class="text-[#64748B] block">Kontak WhatsApp:</span>
                        <span class="font-mono font-semibold text-[#0F172A] mt-0.5 block">{{ $report->contact_phone }}</span>
                    </div>

                    @if($report->image_path)
                        <div>
                            <span class="text-[#64748B] block mb-1">Foto Referensi:</span>
                            <img src="{{ asset('storage/' . $report->image_path) }}" alt="Foto Barang" class="w-full h-36 object-cover rounded-xl border border-[#E2E8F0]">
                        </div>
                    @endif
                </div>

                @if($report->status === 'menunggu' || $report->status === 'diproses')
                    <div class="mt-6 pt-4 border-t border-[#E2E8F0]">
                        <form method="POST" action="{{ route('reports.cancel', $report) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan laporan ini?')">
                            @csrf
                            <button type="submit" class="w-full py-2 px-3 rounded-xl border border-red-200 text-xs font-semibold text-red-600 hover:bg-red-50 transition text-center">
                                Batalkan Laporan Ini
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

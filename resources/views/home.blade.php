@extends('layouts.app')

@section('content')
<!-- Hero Section (Layar 01 & 05 Figma) -->
<section class="bg-[#173B70] text-white pt-10 pb-16 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute -right-20 -top-20 w-96 h-96 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -left-20 -bottom-20 w-96 h-96 rounded-full bg-blue-400/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10">
        <!-- Campus Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 text-xs font-medium text-blue-100 mb-6 backdrop-blur-xs">
            <span class="w-2 h-2 rounded-full bg-[#60A5FA]"></span>
            Layanan Publik Lost &amp; Found Resmi &bull; FASILKOM UNSIKA
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight mb-4 text-white">
            Temukan Kembali,<br class="hidden sm:inline"> Lebih Mudah &amp; Terpercaya.
        </h1>
        <p class="text-base sm:text-lg text-blue-100 max-w-2xl mx-auto mb-8 font-normal leading-relaxed">
            Platform terpadu civitas akademika Fakultas Ilmu Komputer untuk mencari barang temuan, melaporkan kehilangan, dan mengajukan klaim secara transparan.
        </p>

        <!-- Search Bar in Hero -->
        <div class="max-w-2xl mx-auto mb-8">
            <form action="{{ route('catalog.index') }}" method="GET" class="relative flex items-center">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" placeholder="Cari nama barang temuan (contoh: dompet, tumbler, charger...)"
                        class="w-full pl-11 pr-28 py-3.5 rounded-2xl bg-white text-[#0F172A] placeholder-slate-400 text-sm font-medium shadow-lg focus:outline-hidden focus:ring-4 focus:ring-blue-400/30">
                </div>
                <button type="submit" class="absolute right-2 top-1.5 bottom-1.5 px-5 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-semibold text-xs transition flex items-center gap-1.5">
                    Cari
                </button>
            </form>
        </div>

        <!-- Quick Action Buttons (Layar 05 Figma) -->
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#2563EB] hover:bg-blue-600 text-white font-semibold text-sm shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari Barang Temuan
            </a>
            <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm border border-white/20 transition backdrop-blur-xs">
                <svg class="w-4 h-4 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                + Lapor Kehilangan
            </a>
            <a href="{{ route('civitas-reports.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm border border-white/20 transition backdrop-blur-xs">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Laporkan Barang Temuan
            </a>
        </div>
    </div>
</section>

<!-- 4 Core Process Steps (Bab 1.1: Laporkan -> Cari -> Verifikasi -> Ambil di Pos Security) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-6 sm:p-8">
        <div class="text-center max-w-xl mx-auto mb-6">
            <p class="text-xs font-bold uppercase tracking-wider text-[#2563EB]">Alur Pelayanan Publik</p>
            <h2 class="text-lg sm:text-xl font-bold text-[#0F172A] mt-1">"Digital untuk Informasi. Fisik untuk Serah Terima."</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 relative">
            <!-- Step 1 -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center text-center">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-[#2563EB] font-bold text-sm flex items-center justify-center mb-3">
                    01
                </div>
                <h3 class="text-sm font-bold text-[#0F172A] mb-1">Laporkan</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">Catat laporan kehilangan atau temuan barang dengan ciri khusus privat.</p>
            </div>

            <!-- Step 2 -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center text-center">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-[#2563EB] font-bold text-sm flex items-center justify-center mb-3">
                    02
                </div>
                <h3 class="text-sm font-bold text-[#0F172A] mb-1">Cari di Katalog</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">Telusuri katalog publik. Ciri rahasia barang disembunyikan demi keamanan.</p>
            </div>

            <!-- Step 3 -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center text-center">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-[#2563EB] font-bold text-sm flex items-center justify-center mb-3">
                    03
                </div>
                <h3 class="text-sm font-bold text-[#0F172A] mb-1">Verifikasi</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">Petugas mencocokkan bukti privat kepemilikan Anda dengan catatan fisik pos.</p>
            </div>

            <!-- Step 4 -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center text-center">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-[#16A34A] font-bold text-sm flex items-center justify-center mb-3">
                    04
                </div>
                <h3 class="text-sm font-bold text-[#0F172A] mb-1">Ambil di Pos Security</h3>
                <p class="text-xs text-[#64748B] leading-relaxed">Ambil fisik barang di Pos Security FASILKOM dengan menunjukkan KTM asli.</p>
            </div>
        </div>
    </div>
</section>

<!-- Category Pills Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-[#0F172A]">Kategori Barang</h2>
            <p class="text-xs text-[#64748B]">Telusuri barang temuan berdasarkan jenis kategori</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-[#2563EB] hover:underline flex items-center gap-1">
            Lihat semua &rarr;
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @foreach($categories as $cat)
            <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="p-4 rounded-2xl bg-white border border-[#E2E8F0] hover:border-[#2563EB] hover:shadow-xs transition flex flex-col items-center text-center group">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#173B70] group-hover:bg-[#2563EB] group-hover:text-white transition flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <span class="text-xs font-bold text-[#0F172A] group-hover:text-[#2563EB] transition line-clamp-1">{{ $cat->name }}</span>
                <span class="text-[11px] text-[#64748B] mt-1">{{ $cat->found_items_count }} barang</span>
            </a>
        @endforeach
    </div>
</section>

<!-- Recently Found Items Showcase (Layar 05 & 06 Figma) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-bold text-[#0F172A]">Baru Ditemukan di Kampus</h2>
                <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-emerald-100 text-emerald-800">Tersedia di Pos</span>
            </div>
            <p class="text-xs text-[#64748B]">Barang fisik telah diamankan oleh security dan siap diverifikasi pemiliknya</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-[#2563EB] hover:underline flex items-center gap-1">
            Buka Katalog Lengkap &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($recentItems as $item)
            <div class="bg-white rounded-2xl border border-[#E2E8F0] overflow-hidden shadow-xs hover:shadow-md transition flex flex-col group">
                <!-- Image Container -->
                <div class="h-48 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                    @if($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs">Foto Barang Diamankan</span>
                        </div>
                    @endif

                    <!-- Status Pill -->
                    <div class="absolute top-3 left-3">
                        @if($item->status === 'tersedia')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-[#DCFCE7] text-[#166534] shadow-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                                Barang tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-100 text-amber-800 shadow-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Klaim diproses
                            </span>
                        @endif
                    </div>

                    <!-- Category Pill -->
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 rounded-lg text-[10px] font-semibold bg-white/90 text-[#0F172A] shadow-xs backdrop-blur-xs">
                            {{ $item->category->name }}
                        </span>
                    </div>
                </div>

                <!-- Content Container -->
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex items-center justify-between text-xs text-[#64748B] mb-2">
                        <span class="font-mono text-[11px] text-[#2563EB] font-semibold">{{ $item->item_code }}</span>
                        <span>{{ $item->found_date->translatedFormat('d M Y') }}</span>
                    </div>

                    <h3 class="text-base font-bold text-[#0F172A] group-hover:text-[#2563EB] transition line-clamp-1 mb-2">
                        {{ $item->name }}
                    </h3>

                    <p class="text-xs text-[#64748B] line-clamp-2 mb-4 leading-relaxed">
                        {{ $item->public_description }}
                    </p>

                    <div class="mt-auto pt-3 border-t border-[#E2E8F0] flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-xs text-[#64748B]">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate max-w-[150px]">{{ $item->found_location }}</span>
                        </div>

                        <a href="{{ route('catalog.show', $item) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#2563EB] hover:text-blue-800">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-[#E2E8F0]">
                <p class="text-sm text-[#64748B]">Belum ada barang temuan yang dipublikasikan.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection

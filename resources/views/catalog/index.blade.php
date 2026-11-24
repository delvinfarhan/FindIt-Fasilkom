@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Katalog Barang Temuan</h1>
        <p class="text-sm text-[#64748B] mt-1">Daftar barang temuan yang saat ini diamankan oleh Petugas Security di Pos Kampus FASILKOM.</p>
    </div>

    <!-- Security Guardrail Banner (Bab 3.3 & Layar 06) -->
    <div class="bg-blue-50 border border-blue-200/80 rounded-2xl p-4 mb-6 flex items-start gap-3 text-blue-900 shadow-xs">
        <div class="p-1.5 rounded-xl bg-blue-100 text-[#2563EB] shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <div class="text-xs sm:text-sm">
            <p class="font-bold text-[#173B70]">Prinsip Perlindungan Privasi &amp; Keamanan</p>
            <p class="text-blue-800 leading-relaxed mt-0.5">
                Ciri unik, nomor identitas khusus, dan isi detail barang sengaja <strong>tidak ditampilkan secara publik</strong> untuk mencegah klaim palsu. Bukti kepemilikan Anda akan dicocokkan secara privat oleh petugas saat mengajukan klaim.
            </p>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-4 sm:p-5 shadow-xs mb-8">
        <form action="{{ route('catalog.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <!-- Search Input -->
                <div class="md:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Cari nama barang atau lokasi..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
                </div>

                <!-- Location Filter -->
                <div class="md:col-span-3">
                    <input type="text" name="location" value="{{ $selectedLocation }}" placeholder="Filter lokasi (contoh: Lobi, Lab...)"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
                </div>

                <!-- Sort Filter -->
                <div class="md:col-span-3 flex gap-2">
                    <select name="sort" class="w-full px-3 py-2.5 rounded-xl border border-[#E2E8F0] text-sm text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Terbaru Ditemukan</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Paling Lama</option>
                    </select>

                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-semibold text-sm transition">
                        Cari
                    </button>
                </div>
            </div>

            <!-- Category Filter Pills -->
            <div class="pt-3 border-t border-[#E2E8F0] flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                <span class="text-[#64748B] font-semibold shrink-0">Kategori:</span>
                <a href="{{ route('catalog.index', array_merge(request()->except('category', 'page'))) }}"
                    class="px-3 py-1.5 rounded-lg font-medium transition shrink-0 {{ empty($selectedCategory) ? 'bg-[#173B70] text-white font-semibold' : 'bg-slate-100 text-[#64748B] hover:bg-slate-200' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('catalog.index', array_merge(request()->except('category', 'page'), ['category' => $category->slug])) }}"
                        class="px-3 py-1.5 rounded-lg font-medium transition shrink-0 {{ $selectedCategory === $category->slug ? 'bg-[#173B70] text-white font-semibold' : 'bg-slate-100 text-[#64748B] hover:bg-slate-200' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>

    <!-- Active Filter Info -->
    @if($searchQuery || $selectedCategory || $selectedLocation)
        <div class="flex items-center justify-between mb-4 text-xs text-[#64748B]">
            <div>
                Menampilkan hasil untuk:
                @if($searchQuery) <span class="font-bold text-[#0F172A]">"{{ $searchQuery }}"</span> @endif
                @if($selectedCategory) <span class="ml-1 font-semibold text-[#2563EB]">[{{ $selectedCategory }}]</span> @endif
                @if($selectedLocation) <span class="ml-1 font-medium text-slate-600">di {{ $selectedLocation }}</span> @endif
                ({{ $items->total() }} barang ditemukan)
            </div>
            <a href="{{ route('catalog.index') }}" class="text-[#DC2626] hover:underline font-semibold">
                Reset Filter
            </a>
        </div>
    @endif

    <!-- Items Grid or Empty State -->
    @if($items->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($items as $item)
                <div class="bg-white rounded-2xl border border-[#E2E8F0] overflow-hidden shadow-xs hover:shadow-md transition flex flex-col group">
                    <div class="h-48 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs">Foto Barang Diamankan</span>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-3 left-3">
                            @if($item->status === 'tersedia')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-[#DCFCE7] text-[#166534] shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                                    Barang tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-100 text-amber-800 shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Klaim diverifikasi
                                </span>
                            @endif
                        </div>

                        <!-- Category Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 rounded-lg text-[10px] font-semibold bg-white/90 text-[#0F172A] shadow-xs backdrop-blur-xs">
                                {{ $item->category->name }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center justify-between text-xs text-[#64748B] mb-2">
                            <span class="font-mono text-[11px] text-[#2563EB] font-bold">{{ $item->item_code }}</span>
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
                                <span class="truncate max-w-[140px]">{{ $item->found_location }}</span>
                            </div>

                            <a href="{{ route('catalog.show', $item) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-[#2563EB] hover:bg-[#2563EB] hover:text-white transition">
                                Detail &amp; Klaim &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $items->links() }}
        </div>
    @else
        <!-- Empty State (Layar 08 Figma: Penanganan Kondisi Tanpa Hasil) -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-12 text-center max-w-lg mx-auto shadow-xs my-8">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-[#0F172A] mb-1">Barang belum ditemukan</h3>
            <p class="text-xs text-[#64748B] leading-relaxed mb-6">
                Coba gunakan kata kunci lain atau buat laporan kehilangan sekarang. Sistem dan petugas security akan mencocokkan jika ada barang serupa yang masuk inventaris.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#0F172A] hover:bg-slate-50 transition">
                    Reset Pencarian
                </a>
                <a href="{{ route('reports.create') }}" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white text-xs font-semibold transition">
                    Laporkan Kehilangan
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

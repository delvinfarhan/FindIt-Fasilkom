@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-[#64748B] mb-6">
        <a href="{{ route('home') }}" class="hover:text-[#2563EB]">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('catalog.index') }}" class="hover:text-[#2563EB]">Katalog Barang</a>
        <span>&rsaquo;</span>
        <span class="text-[#0F172A] font-semibold truncate">{{ $item->name }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-12">
        <!-- Left: Image Preview -->
        <div class="md:col-span-5">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] overflow-hidden p-3 shadow-xs">
                <div class="w-full h-72 sm:h-80 bg-slate-100 rounded-xl overflow-hidden flex items-center justify-center relative">
                    @if($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center text-slate-400">
                            <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs">Foto Barang Diamankan</span>
                        </div>
                    @endif

                    <div class="absolute bottom-3 right-3">
                        <span class="px-2.5 py-1 rounded-lg bg-black/60 text-white text-[11px] font-medium backdrop-blur-xs">
                            ID: {{ $item->item_code }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Operational Pos Information Box -->
            <div class="mt-4 p-4 rounded-2xl bg-slate-50 border border-[#E2E8F0] text-xs space-y-2">
                <div class="flex items-center gap-2 font-bold text-[#173B70]">
                    <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Pos Security FASILKOM UNSIKA
                </div>
                <p class="text-[#64748B] leading-relaxed">
                    Lokasi: Lantai 1 Gedung FASILKOM. Buka operasional Senin &ndash; Jumat, pukul 08.00 &ndash; 16.00 WIB.
                </p>
                <p class="text-[11px] text-slate-500 italic">
                    *Serah terima fisik hanya dilayani setelah klaim diverifikasi dan disetujui.
                </p>
            </div>
        </div>

        <!-- Right: Details & Claim CTA (Layar 09 Figma) -->
        <div class="md:col-span-7 flex flex-col">
            <!-- Header Badges -->
            <div class="flex flex-wrap items-center gap-2 mb-3">
                @if($item->status === 'tersedia')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534] border border-[#16A34A]/30">
                        <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                        Barang tersedia di pos
                    </span>
                @elseif($item->status === 'diverifikasi')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-900 border border-amber-300">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Klaim disetujui (Menunggu serah terima)
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                        {{ $item->status_label }}
                    </span>
                @endif

                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-[#2563EB]">
                    {{ $item->category->name }}
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mb-3">
                {{ $item->name }}
            </h1>

            <!-- Meta details row -->
            <div class="grid grid-cols-2 gap-3 p-3.5 bg-white rounded-xl border border-[#E2E8F0] mb-5 text-xs">
                <div>
                    <span class="text-[#64748B] block">Ditemukan pada:</span>
                    <span class="font-bold text-[#0F172A] mt-0.5 block">{{ $item->found_date->translatedFormat('l, d F Y') }}</span>
                </div>
                <div>
                    <span class="text-[#64748B] block">Lokasi penemuan:</span>
                    <span class="font-bold text-[#0F172A] mt-0.5 block">{{ $item->found_location }}</span>
                </div>
            </div>

            <!-- Public Description -->
            <div class="mb-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-2">Deskripsi Barang</h3>
                <div class="p-4 rounded-xl bg-white border border-[#E2E8F0] text-sm text-[#0F172A] leading-relaxed">
                    {{ $item->public_description }}
                </div>
            </div>

            <!-- Security Policy Alert (Layar 09 Figma) -->
            <div class="bg-amber-50/80 border border-amber-200/90 rounded-2xl p-4 mb-6 text-xs text-amber-900">
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <div>
                        <p class="font-bold">Informasi Khusus Disembunyikan:</p>
                        <p class="mt-0.5 leading-relaxed">
                            Ciri rahasia (seperti inisial, goresan khusus, nomor seri, isi dompet/tas) sengaja tidak diumumkan. Anda akan diminta mendeskripsikan ciri privat tersebut saat mengajukan klaim untuk verifikasi petugas keamanan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action CTA Area -->
            <div class="mt-auto pt-4 border-t border-[#E2E8F0] flex flex-col sm:flex-row items-center gap-3">
                @if($item->status === 'tersedia')
                    <a href="{{ route('claims.create', $item) }}" class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 py-3 px-6 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-sm shadow-md transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Ajukan Klaim Kepemilikan
                    </a>
                @elseif($item->status === 'diverifikasi')
                    <div class="w-full sm:flex-1 p-3 rounded-xl bg-amber-50 border border-amber-200 text-center text-xs text-amber-800 font-semibold">
                        Barang ini sedang dalam tahap verifikasi / telah disetujui untuk pemohon tertentu.
                    </div>
                @else
                    <div class="w-full sm:flex-1 p-3 rounded-xl bg-slate-100 text-center text-xs text-slate-600 font-semibold">
                        Barang ini telah diserahterimakan kepada pemiliknya.
                    </div>
                @endif

                <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-5 py-3 rounded-xl border border-[#E2E8F0] bg-white text-xs font-semibold text-[#0F172A] hover:bg-slate-50 transition text-center">
                    &larr; Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>

    <!-- Related Items in Same Category -->
    @if($relatedItems->count() > 0)
        <div class="border-t border-[#E2E8F0] pt-8">
            <h3 class="text-base font-bold text-[#0F172A] mb-4">Barang Terkait di Kategori {{ $item->category->name }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($relatedItems as $related)
                    <a href="{{ route('catalog.show', $related) }}" class="p-3 bg-white rounded-xl border border-[#E2E8F0] hover:border-[#2563EB] transition flex items-center gap-3">
                        <div class="w-14 h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0 flex items-center justify-center">
                            @if($related->image_path)
                                <img src="{{ asset('storage/' . $related->image_path) }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-[#0F172A] truncate">{{ $related->name }}</h4>
                            <p class="text-[11px] text-[#64748B]">{{ $related->found_date->translatedFormat('d M Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

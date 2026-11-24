@extends('layouts.officer', ['title' => 'Detail Barang ' . $item->item_code, 'pageTitle' => 'Rincian Data Inventaris'])

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('officer.items.index') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB]">&larr; Kembali ke Inventaris</a>
            <div class="flex items-center gap-2 mt-1">
                <span class="font-mono text-xs font-bold text-[#2563EB] bg-blue-50 px-2 py-0.5 rounded">{{ $item->item_code }}</span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A]">{{ $item->name }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('officer.items.edit', $item) }}" class="px-3 py-2 rounded-xl border border-[#E2E8F0] bg-white text-xs font-semibold hover:bg-slate-50 transition">
                Edit Data
            </a>
            @if($item->status !== 'diarsipkan')
                <form method="POST" action="{{ route('officer.items.archive', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin memindahkan barang ini ke status arsip?')">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-xl bg-slate-200 text-slate-800 text-xs font-semibold hover:bg-slate-300 transition">
                        Arsipkan Barang
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- 2 Column Details (Layar 12 Desktop Figma) -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Left: Image & Public Info -->
        <div class="md:col-span-6 bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs space-y-4">
            <div class="w-full h-56 bg-slate-100 rounded-xl overflow-hidden flex items-center justify-center">
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                @endif
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex items-center justify-between pb-2 border-b border-[#E2E8F0]">
                    <span class="text-[#64748B]">Status Inventaris:</span>
                    <span class="font-bold px-2 py-0.5 rounded-full {{ $item->status === 'tersedia' ? 'bg-emerald-100 text-[#166534]' : ($item->status === 'diverifikasi' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-[#2563EB]') }}">
                        {{ $item->status_label }}
                    </span>
                </div>

                <div>
                    <span class="text-[#64748B] block">Kategori:</span>
                    <span class="font-bold text-[#0F172A] mt-0.5 block">{{ $item->category->name }}</span>
                </div>

                <div>
                    <span class="text-[#64748B] block">Lokasi &amp; Tanggal Ditemukan:</span>
                    <span class="font-semibold text-[#0F172A] mt-0.5 block">{{ $item->found_location }} &bull; {{ $item->found_date->translatedFormat('d M Y') }}</span>
                </div>

                <div>
                    <span class="text-[#64748B] block">Deskripsi Katalog Publik:</span>
                    <p class="mt-1 text-[#0F172A] bg-slate-50 p-2.5 rounded-lg border border-slate-100 leading-relaxed">{{ $item->public_description }}</p>
                </div>

                <div class="pt-2 border-t border-[#E2E8F0] text-[11px] text-[#94A3B8]">
                    Dicatat oleh: {{ $item->creator->name ?? 'Petugas Security' }} pada {{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB
                </div>
            </div>
        </div>

        <!-- Right: Private Security Details & Claims List -->
        <div class="md:col-span-6 space-y-6">
            <!-- Lokasi Rak Fisik Pos Security (Layar 12 Figma) -->
            <div class="bg-blue-50/70 border border-blue-200 rounded-2xl p-5 space-y-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#173B70]">Lokasi Penyimpanan Fisik Pos Security:</span>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span class="text-base font-extrabold text-[#173B70]">{{ $item->storage_location }}</span>
                </div>
                <p class="text-[11px] text-blue-800">Petugas dapat mengambil fisik barang dari rak di atas saat pemilik hadir di pos security.</p>
            </div>

            <!-- Catatan Rahasia Privat Security -->
            <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-5 space-y-2 text-xs">
                <span class="font-bold text-amber-950 uppercase tracking-wider block">Catatan Rahasia Privat Petugas:</span>
                <p class="text-amber-900 leading-relaxed bg-white/70 p-3 rounded-xl border border-amber-200">
                    {{ $item->private_description }}
                </p>
                <p class="text-[10px] text-amber-800 italic">*Ciri rahasia ini dicocokkan dengan klaim pemohon.</p>
            </div>

            <!-- Claims History for this Item -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-5 shadow-xs text-xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-[#E2E8F0]">
                    <span class="font-bold text-[#0F172A]">Riwayat Klaim Masuk ({{ $item->claims->count() }})</span>
                </div>

                @if($item->claims->count() > 0)
                    <div class="space-y-2">
                        @foreach($item->claims as $claim)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-mono text-[10px] font-bold text-amber-900">{{ $claim->claim_code }}</span>
                                        <span class="font-bold text-[#0F172A]">{{ $claim->user->name }}</span>
                                    </div>
                                    <span class="text-[10px] text-[#64748B]">{{ $claim->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="px-2 py-0.5 text-[10px] font-semibold rounded {{ $claim->status === 'approved' ? 'bg-emerald-100 text-[#166534]' : ($claim->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100') }}">
                                        {{ $claim->status_label }}
                                    </span>
                                    <a href="{{ route('officer.claims.show', $claim) }}" class="px-2 py-1 rounded bg-[#2563EB] text-white font-bold text-[10px]">
                                        Tinjau
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[#64748B] py-3 text-center">Belum ada mahasiswa yang mengajukan klaim untuk barang ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

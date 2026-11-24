@extends('layouts.officer', ['title' => 'Bukti Serah Terima ' . $claim->claim_code, 'pageTitle' => 'Tanda Bukti Resmi Serah Terima Barang'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('officer.dashboard') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB]">&larr; Kembali ke Ringkasan Dashboard</a>
        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Tanda Bukti
        </button>
    </div>

    <!-- Official Handover Receipt Certificate (Layar 15 Desktop Figma) -->
    <div class="bg-white rounded-3xl border-2 border-[#173B70] shadow-md p-8 sm:p-10 space-y-6 print:border-none print:shadow-none print:p-0">
        <!-- Receipt Header -->
        <div class="text-center pb-6 border-b-2 border-slate-200">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#173B70] text-white font-extrabold text-xl mb-2">
                F
            </div>
            <h2 class="text-lg font-extrabold text-[#0F172A] tracking-tight uppercase">Tanda Bukti Serah Terima Barang Temuan</h2>
            <p class="text-xs text-[#64748B]">Layanan Publik Lost &amp; Found &bull; Pos Security Fakultas Ilmu Komputer UNSIKA</p>
            <div class="mt-3 inline-flex items-center gap-3 px-3 py-1 rounded-full bg-emerald-50 text-[#166534] border border-[#16A34A]/30 text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                BARANG RESMI TELAH DIKEMBALIKAN
            </div>
        </div>

        <!-- Receipt Code & Date -->
        <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-xl">
            <div>
                <span class="text-[#64748B] block">Nomor Berita Acara:</span>
                <span class="font-mono font-bold text-[#173B70] block mt-0.5">BAST-{{ str_replace('KLM-', '', $claim->claim_code) }}-{{ date('Ymd') }}</span>
            </div>
            <div class="text-right">
                <span class="text-[#64748B] block">Tanggal &amp; Waktu Serah Terima:</span>
                <span class="font-bold text-[#0F172A] block mt-0.5">{{ $claim->handed_over_at ? $claim->handed_over_at->translatedFormat('l, d F Y - H:i') : now()->translatedFormat('l, d F Y - H:i') }} WIB</span>
            </div>
        </div>

        <!-- Data Barang -->
        <div class="text-xs space-y-2">
            <h4 class="font-bold text-[#173B70] uppercase tracking-wider text-[11px] pb-1 border-b border-[#E2E8F0]">1. Rincian Barang</h4>
            <div class="grid grid-cols-2 gap-3 pt-1">
                <div>
                    <span class="text-[#64748B] block">Kode Inventaris:</span>
                    <span class="font-mono font-bold text-[#2563EB] block mt-0.5">{{ $claim->foundItem->item_code }}</span>
                </div>
                <div>
                    <span class="text-[#64748B] block">Nama Barang:</span>
                    <span class="font-bold text-[#0F172A] block mt-0.5">{{ $claim->foundItem->name }}</span>
                </div>
                <div>
                    <span class="text-[#64748B] block">Kategori:</span>
                    <span class="font-medium text-[#0F172A] block mt-0.5">{{ $claim->foundItem->category->name }}</span>
                </div>
                <div>
                    <span class="text-[#64748B] block">Lokasi Penyimpanan Sebelumnya:</span>
                    <span class="font-medium text-[#0F172A] block mt-0.5">{{ $claim->foundItem->storage_location }}</span>
                </div>
            </div>
        </div>

        <!-- Data Pihak Penerima (Mahasiswa) -->
        <div class="text-xs space-y-2">
            <h4 class="font-bold text-[#173B70] uppercase tracking-wider text-[11px] pb-1 border-b border-[#E2E8F0]">2. Pihak Penerima (Pemilik Sah)</h4>
            <div class="grid grid-cols-2 gap-3 pt-1">
                <div>
                    <span class="text-[#64748B] block">Nama Lengkap:</span>
                    <span class="font-bold text-[#0F172A] block mt-0.5">{{ $claim->user->name }}</span>
                </div>
                <div>
                    <span class="text-[#64748B] block">NPM / NIP:</span>
                    <span class="font-mono font-bold text-[#0F172A] block mt-0.5">{{ $claim->user->identity_number ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-[#64748B] block">Program Studi / Instansi:</span>
                    <span class="font-medium text-[#0F172A] block mt-0.5">{{ $claim->user->department ?? 'FASILKOM' }}</span>
                </div>
                <div>
                    <span class="text-[#64748B] block">Kontak WhatsApp:</span>
                    <span class="font-mono font-medium text-[#0F172A] block mt-0.5">{{ $claim->contact_phone }}</span>
                </div>
            </div>
        </div>

        <!-- Signatures Area -->
        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-[#E2E8F0] text-center text-xs">
            <div>
                <p class="text-[#64748B] mb-12">Pihak Penerima Barang,</p>
                <p class="font-bold text-[#0F172A] border-b border-dashed border-slate-400 pb-1 inline-block min-w-[150px]">{{ $claim->user->name }}</p>
                <p class="text-[10px] text-[#94A3B8] mt-1">NPM: {{ $claim->user->identity_number ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[#64748B] mb-12">Petugas Pos Security Penyerah,</p>
                <p class="font-bold text-[#0F172A] border-b border-dashed border-slate-400 pb-1 inline-block min-w-[150px]">{{ $claim->verifier->name ?? auth()->user()->name }}</p>
                <p class="text-[10px] text-[#94A3B8] mt-1">Pos Security FASILKOM UNSIKA</p>
            </div>
        </div>

        <!-- Watermark / Footer Note -->
        <div class="text-center pt-4 text-[10px] text-slate-400">
            Dicetak secara digital melalui sistem FindIt FASILKOM. Dokumen sah tanpa rekayasa.
        </div>
    </div>
</div>
@endsection

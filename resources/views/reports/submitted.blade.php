@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl border border-[#E2E8F0] shadow-sm p-8 text-center space-y-6">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-[#16A34A] flex items-center justify-center mx-auto">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>

        <div>
            <span class="text-xs font-bold text-[#16A34A] uppercase tracking-wider">Laporan Berhasil Diterbitkan</span>
            <h1 class="text-2xl font-extrabold text-[#0F172A] mt-1">Laporanmu Sudah Kami Terima</h1>
            <p class="text-xs text-[#64748B] mt-2 leading-relaxed">
                Security akan memeriksa informasi ini dan mencocokkannya secara berkala dengan inventaris barang temuan di Pos FASILKOM.
            </p>
        </div>

        <!-- Ticket Card (Layar 12 Figma) -->
        <div class="p-4 rounded-xl bg-slate-50 border border-[#E2E8F0] text-left">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] text-[#64748B]">Nomor Tiket Laporan:</span>
                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-blue-100 text-[#2563EB]">{{ $report->report_code }}</span>
            </div>
            <p class="text-sm font-bold text-[#0F172A]">{{ $report->item_name }}</p>
            <p class="text-xs text-[#64748B] mt-0.5">Hilang di: {{ $report->lost_location }}</p>
            <p class="text-[11px] text-[#94A3B8] mt-2">Didaftarkan pada: {{ $report->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
        </div>

        <div class="space-y-2 pt-2">
            <a href="{{ route('reports.show', $report) }}" class="block w-full py-2.5 px-4 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-semibold text-xs shadow-xs transition">
                Pantau Status Laporan
            </a>
            <a href="{{ route('home') }}" class="block w-full py-2.5 px-4 rounded-xl border border-[#E2E8F0] hover:bg-slate-50 text-[#0F172A] font-semibold text-xs transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection

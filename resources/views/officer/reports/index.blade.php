@extends('layouts.officer', ['title' => 'Laporan Kehilangan', 'pageTitle' => 'Manajemen Laporan Kehilangan Civitas'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight">Laporan Kehilangan Civitas</h1>
            <p class="text-xs text-[#64748B] mt-0.5">Kelola laporan kehilangan dari mahasiswa &amp; civitas akademika untuk dicocokkan dengan barang temuan di pos.</p>
        </div>
    </div>

    <!-- Filter (Layar 04 Desktop Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-4 shadow-xs">
        <form method="GET" action="{{ route('officer.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-8 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nomor tiket, nama pelapor, NPM, nama barang..."
                    class="w-full pl-9 pr-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
            </div>

            <div class="sm:col-span-4 flex gap-2">
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu pemeriksaan</option>
                    <option value="diproses" {{ $status === 'diproses' ? 'selected' : '' }}>Sedang diperiksa</option>
                    <option value="cocok" {{ $status === 'cocok' ? 'selected' : '' }}>Terdapat barang cocok</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai / Ditemukan</option>
                    <option value="dibatalkan" {{ $status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-[#173B70] text-white font-semibold text-xs hover:bg-[#235299] transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Reports Table (Layar 04 Desktop Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-[#E2E8F0] text-[#64748B] font-semibold">
                    <tr>
                        <th class="py-3 px-4">Tiket</th>
                        <th class="py-3 px-4">Pelapor (Mahasiswa)</th>
                        <th class="py-3 px-4">Barang Hilang</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Lokasi Hilang</th>
                        <th class="py-3 px-4">Tanggal Hilang</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-mono font-bold text-[#2563EB]">
                                {{ $report->report_code }}
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-[#0F172A]">{{ $report->user->name }}</p>
                                <p class="text-[11px] text-[#64748B] font-mono">{{ $report->user->identity_number ?? 'NPM -' }}</p>
                            </td>
                            <td class="py-3 px-4 font-semibold text-[#0F172A]">
                                {{ $report->item_name }}
                            </td>
                            <td class="py-3 px-4 text-[#64748B]">
                                {{ $report->category->name }}
                            </td>
                            <td class="py-3 px-4 text-[#64748B] truncate max-w-[140px]">
                                {{ $report->lost_location }}
                            </td>
                            <td class="py-3 px-4 text-[#64748B]">
                                {{ $report->lost_date->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($report->status === 'menunggu')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        Menunggu
                                    </span>
                                @elseif($report->status === 'diproses')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                        Diperiksa
                                    </span>
                                @elseif($report->status === 'cocok')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-[#2563EB]">
                                        Cocok!
                                    </span>
                                @elseif($report->status === 'selesai')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-[#DCFCE7] text-[#166534]">
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-red-100 text-red-700">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('officer.reports.show', $report) }}" class="px-2.5 py-1 rounded-lg bg-[#2563EB] text-white font-semibold text-[11px] hover:bg-blue-700 transition">
                                    Periksa Laporan &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-[#64748B]">
                                Tidak ada laporan kehilangan yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#E2E8F0]">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.officer', ['title' => 'Temuan Civitas', 'pageTitle' => 'Laporan Barang Temuan oleh Civitas (Alur D)'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight">Laporan Temuan Civitas (Alur D)</h1>
            <p class="text-xs text-[#64748B] mt-0.5">Daftar barang yang dilaporkan ditemukan oleh civitas akademika dan sedang menunggu penyerahan fisik ke pos security.</p>
        </div>
    </div>

    <!-- Alert Context Alur D (Layar 20 Desktop Figma) -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-xs text-blue-900 leading-relaxed flex items-start gap-3">
        <svg class="w-5 h-5 text-[#2563EB] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <strong>Prinsip Alur D:</strong> Civitas dapat melaporkan barang temuan milik orang lain terlebih dahulu secara online. Barang <strong>belum muncul di katalog publik</strong> sampai penemu menyerahkan fisik barang ke Pos Security dan petugas mencatat nomor rak simpan.
        </div>
    </div>

    <!-- Table (Layar 20 Desktop Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-[#E2E8F0] text-[#64748B] font-semibold">
                    <tr>
                        <th class="py-3 px-4">Nomor Tiket</th>
                        <th class="py-3 px-4">Penemu (Civitas)</th>
                        <th class="py-3 px-4">Nama Barang</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Lokasi Ditemukan</th>
                        <th class="py-3 px-4">Status Penyerahan</th>
                        <th class="py-3 px-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-mono font-bold text-emerald-800">
                                {{ $report->report_code }}
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-[#0F172A]">{{ $report->user->name }}</p>
                                <span class="text-[11px] text-[#64748B]">{{ $report->contact_phone }}</span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-[#0F172A]">
                                {{ $report->item_name }}
                            </td>
                            <td class="py-3 px-4 text-[#64748B]">
                                {{ $report->category->name }}
                            </td>
                            <td class="py-3 px-4 text-[#64748B]">
                                {{ $report->found_location }}
                            </td>
                            <td class="py-3 px-4">
                                @if($report->status === 'menunggu_penyerahan')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu penyerahan
                                    </span>
                                @elseif($report->status === 'diterima_security')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#DCFCE7] text-[#166534]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                                        Diterima di Pos
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('officer.civitas-reports.show', $report) }}" class="px-3 py-1 rounded-lg bg-[#2563EB] hover:bg-blue-700 text-white font-semibold text-[11px] transition">
                                    {{ $report->status === 'menunggu_penyerahan' ? 'Terima Barang &rarr;' : 'Detail' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-[#64748B]">
                                Belum ada laporan temuan barang dari civitas.
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

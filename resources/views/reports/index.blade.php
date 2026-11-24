@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">Semua Aktivitas Saya</h1>
            <p class="text-sm text-[#64748B] mt-1">Pantau status laporan kehilangan, pengajuan klaim, dan laporan penemuan barang Anda.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-[#2563EB] text-white hover:bg-blue-700 shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                + Lapor Kehilangan
            </a>
            <a href="{{ route('civitas-reports.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-white text-[#173B70] border border-[#E2E8F0] hover:bg-slate-50 transition">
                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Lapor Temuan
            </a>
        </div>
    </div>

    <!-- 3 Tabs Navigation (Layar 13 Figma) -->
    <div class="border-b border-[#E2E8F0] mb-6 flex gap-2">
        <a href="{{ route('reports.index', ['tab' => 'reports']) }}"
            class="pb-3 px-4 text-sm font-semibold border-b-2 transition flex items-center gap-2 {{ $tab === 'reports' ? 'border-[#2563EB] text-[#2563EB]' : 'border-transparent text-[#64748B] hover:text-[#0F172A]' }}">
            <span>Laporan Kehilangan</span>
            <span class="px-2 py-0.5 text-xs rounded-full {{ $tab === 'reports' ? 'bg-blue-100 text-[#2563EB]' : 'bg-slate-100 text-[#64748B]' }}">{{ $reports->total() }}</span>
        </a>

        <a href="{{ route('reports.index', ['tab' => 'claims']) }}"
            class="pb-3 px-4 text-sm font-semibold border-b-2 transition flex items-center gap-2 {{ $tab === 'claims' ? 'border-[#2563EB] text-[#2563EB]' : 'border-transparent text-[#64748B] hover:text-[#0F172A]' }}">
            <span>Klaim Saya</span>
            <span class="px-2 py-0.5 text-xs rounded-full {{ $tab === 'claims' ? 'bg-blue-100 text-[#2563EB]' : 'bg-slate-100 text-[#64748B]' }}">{{ $claims->total() }}</span>
        </a>

        <a href="{{ route('reports.index', ['tab' => 'civitas']) }}"
            class="pb-3 px-4 text-sm font-semibold border-b-2 transition flex items-center gap-2 {{ $tab === 'civitas' ? 'border-[#2563EB] text-[#2563EB]' : 'border-transparent text-[#64748B] hover:text-[#0F172A]' }}">
            <span>Temuan Saya</span>
            <span class="px-2 py-0.5 text-xs rounded-full {{ $tab === 'civitas' ? 'bg-blue-100 text-[#2563EB]' : 'bg-slate-100 text-[#64748B]' }}">{{ $civitasReports->total() }}</span>
        </a>
    </div>

    <!-- Tab 1: Laporan Kehilangan -->
    @if($tab === 'reports')
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
            @if($reports->count() > 0)
                <div class="divide-y divide-[#E2E8F0]">
                    @foreach($reports as $report)
                        <div class="p-5 hover:bg-slate-50/80 transition flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#2563EB] flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-blue-100 text-[#2563EB]">{{ $report->report_code }}</span>
                                        <span class="text-xs text-[#64748B]">&bull; {{ $report->created_at->translatedFormat('d M Y') }}</span>
                                        <span class="text-xs text-[#64748B]">&bull; {{ $report->category->name }}</span>
                                    </div>
                                    <h3 class="text-base font-bold text-[#0F172A]">{{ $report->item_name }}</h3>
                                    <p class="text-xs text-[#64748B] mt-0.5">Lokasi hilang: {{ $report->lost_location }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 sm:self-center shrink-0">
                                @if($report->status === 'menunggu')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700">Laporan terkirim</span>
                                @elseif($report->status === 'diproses')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-100 text-amber-800">Sedang diperiksa security</span>
                                @elseif($report->status === 'cocok')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-blue-100 text-[#2563EB]">Berpotensi cocok!</span>
                                @elseif($report->status === 'selesai')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-800">Selesai / Ditemukan</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-red-100 text-red-700">Dibatalkan</span>
                                @endif

                                <a href="{{ route('reports.show', $report) }}" class="px-3.5 py-1.5 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#0F172A] hover:bg-slate-100 transition">
                                    Lihat Detail &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t border-[#E2E8F0]">
                    {{ $reports->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <p class="text-sm text-[#64748B] mb-4">Anda belum memiliki laporan kehilangan barang.</p>
                    <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#2563EB] text-white text-xs font-semibold hover:bg-blue-700 transition">
                        + Buat Laporan Kehilangan Baru
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- Tab 2: Klaim Saya -->
    @if($tab === 'claims')
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
            @if($claims->count() > 0)
                <div class="divide-y divide-[#E2E8F0]">
                    @foreach($claims as $claim)
                        <div class="p-5 hover:bg-slate-50/80 transition flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-900">{{ $claim->claim_code }}</span>
                                        <span class="text-xs text-[#64748B]">&bull; Diajukan {{ $claim->created_at->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <h3 class="text-base font-bold text-[#0F172A]">{{ $claim->foundItem->name }}</h3>
                                    <p class="text-xs text-[#64748B] mt-0.5">Kode Barang: {{ $claim->foundItem->item_code }} &bull; Ditemukan di {{ $claim->foundItem->found_location }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 sm:self-center shrink-0">
                                @if($claim->status === 'pending')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-100 text-amber-800">Menunggu verifikasi (1-2 hari)</span>
                                @elseif($claim->status === 'approved')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-[#DCFCE7] text-[#166534]">Disetujui &bull; Siap Diambil di Pos</span>
                                @elseif($claim->status === 'rejected')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-red-100 text-red-700">Klaim ditolak</span>
                                @elseif($claim->status === 'completed')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-blue-100 text-[#2563EB]">Serah terima selesai</span>
                                @endif

                                <a href="{{ route('claims.show', $claim) }}" class="px-3.5 py-1.5 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#0F172A] hover:bg-slate-100 transition">
                                    Pelacakan &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t border-[#E2E8F0]">
                    {{ $claims->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <p class="text-sm text-[#64748B] mb-4">Anda belum pernah mengajukan klaim barang temuan.</p>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#2563EB] text-white text-xs font-semibold hover:bg-blue-700 transition">
                        Buka Katalog Barang Temuan &rarr;
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- Tab 3: Temuan Saya (Alur D) -->
    @if($tab === 'civitas')
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
            @if($civitasReports->count() > 0)
                <div class="divide-y divide-[#E2E8F0]">
                    @foreach($civitasReports as $cReport)
                        <div class="p-5 hover:bg-slate-50/80 transition flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">{{ $cReport->report_code }}</span>
                                        <span class="text-xs text-[#64748B]">&bull; Dilaporkan {{ $cReport->created_at->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <h3 class="text-base font-bold text-[#0F172A]">{{ $cReport->item_name }}</h3>
                                    <p class="text-xs text-[#64748B] mt-0.5">Ditemukan di: {{ $cReport->found_location }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 sm:self-center shrink-0">
                                @if($cReport->status === 'menunggu_penyerahan')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-100 text-amber-800">Menunggu Penyerahan ke Pos</span>
                                @elseif($cReport->status === 'diterima_security')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-[#DCFCE7] text-[#166534]">Diterima di Pos &bull; Masuk Katalog</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700">Dibatalkan</span>
                                @endif

                                <a href="{{ route('civitas-reports.show', $cReport) }}" class="px-3.5 py-1.5 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#0F172A] hover:bg-slate-100 transition">
                                    Rincian &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t border-[#E2E8F0]">
                    {{ $civitasReports->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <p class="text-sm text-[#64748B] mb-4">Anda belum pernah melaporkan penemuan barang milik orang lain.</p>
                    <a href="{{ route('civitas-reports.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition">
                        + Laporkan Barang Temuan
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

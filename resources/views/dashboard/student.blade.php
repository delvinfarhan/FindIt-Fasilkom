@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- User Greeting Banner (Layar 05 Figma) -->
    <div class="bg-gradient-to-r from-[#173B70] to-[#235299] text-white rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/30 text-blue-200 text-xs font-semibold backdrop-blur-xs">
                    Civitas Akademika FASILKOM
                </span>
                <span class="text-blue-300 text-xs">&bull; {{ $user->identity_number ?? 'Mahasiswa' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Halo, {{ $user->name }}</h1>
            <p class="text-sm text-blue-100 mt-2 leading-relaxed">
                "Hilang bukan berarti tidak bisa kembali." Pantau status laporan dan temuan barang Anda melalui layanan terpadu FindIt FASILKOM.
            </p>

            <div class="flex flex-wrap items-center gap-3 mt-6">
                <a href="{{ route('catalog.index') }}" class="px-4 py-2 rounded-xl bg-[#2563EB] hover:bg-blue-600 text-white font-semibold text-xs shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari Barang Temuan
                </a>
                <a href="{{ route('reports.create') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 transition flex items-center gap-1.5 backdrop-blur-xs">
                    <svg class="w-4 h-4 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    + Lapor Kehilangan
                </a>
                <a href="{{ route('civitas-reports.create') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/20 transition flex items-center gap-1.5 backdrop-blur-xs">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Lapor Barang Temuan
                </a>
            </div>
        </div>
    </div>

    <!-- 3 Metrics KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <a href="{{ route('reports.index', ['tab' => 'reports']) }}" class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-[#2563EB] transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-[#64748B]">Laporan Kehilangan</span>
                <span class="p-2 rounded-xl bg-blue-50 text-[#2563EB] group-hover:bg-[#2563EB] group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-[#0F172A] mb-1">{{ $stats['reports_count'] }}</div>
            <p class="text-xs text-[#64748B] flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span>{{ $stats['active_reports'] }} aktif sedang diperiksa</span>
            </p>
        </a>

        <a href="{{ route('reports.index', ['tab' => 'claims']) }}" class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-[#2563EB] transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-[#64748B]">Pengajuan Klaim Saya</span>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-[#0F172A] mb-1">{{ $stats['claims_count'] }}</div>
            <p class="text-xs text-[#64748B] flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-[#2563EB]"></span>
                <span>{{ $stats['pending_claims'] }} dalam proses verifikasi</span>
            </p>
        </a>

        <a href="{{ route('reports.index', ['tab' => 'civitas']) }}" class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs hover:border-[#2563EB] transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-[#64748B]">Temuan Barang (Alur D)</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-[#0F172A] mb-1">{{ $stats['found_reports_count'] }}</div>
            <p class="text-xs text-[#64748B] flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Barang temuan yang Anda laporkan</span>
            </p>
        </a>
    </div>

    <!-- Recent Activity / Notifications (Layar 05 Figma) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Recent Reports & Claims -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-[#0F172A]">Laporan Kehilangan Terakhir</h2>
                    <a href="{{ route('reports.index') }}" class="text-xs font-semibold text-[#2563EB] hover:underline">Semua &rarr;</a>
                </div>

                @if($recentReports->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentReports as $rep)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <span class="font-mono text-[10px] font-bold text-[#2563EB] bg-blue-100 px-1.5 py-0.2 rounded">{{ $rep->report_code }}</span>
                                        <span class="text-[11px] text-[#64748B]">{{ $rep->lost_date->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-[#0F172A] truncate">{{ $rep->item_name }}</h4>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($rep->status === 'cocok')
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-blue-100 text-[#2563EB]">Cocok!</span>
                                    @elseif($rep->status === 'diproses')
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-amber-100 text-amber-800">Diperiksa</span>
                                    @elseif($rep->status === 'selesai')
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-emerald-100 text-emerald-800">Selesai</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-slate-200 text-slate-700">Menunggu</span>
                                    @endif
                                    <a href="{{ route('reports.show', $rep) }}" class="text-xs text-[#2563EB] font-bold hover:underline">Detail</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-[#64748B] py-4 text-center">Belum ada laporan kehilangan.</p>
                @endif
            </div>

            <!-- Recent Claims -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-[#0F172A]">Klaim Kepemilikan Berjalan</h2>
                    <a href="{{ route('reports.index', ['tab' => 'claims']) }}" class="text-xs font-semibold text-[#2563EB] hover:underline">Semua &rarr;</a>
                </div>

                @if($recentClaims->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentClaims as $clm)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <span class="font-mono text-[10px] font-bold text-amber-900 bg-amber-100 px-1.5 py-0.2 rounded">{{ $clm->claim_code }}</span>
                                        <span class="text-[11px] text-[#64748B]">{{ $clm->created_at->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-[#0F172A] truncate">{{ $clm->foundItem->name }}</h4>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($clm->status === 'approved')
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-[#166534]">Disetujui</span>
                                    @elseif($clm->status === 'pending')
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-amber-100 text-amber-800">Menunggu</span>
                                    @elseif($clm->status === 'rejected')
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-red-100 text-red-700">Ditolak</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-blue-100 text-[#2563EB]">Selesai</span>
                                    @endif
                                    <a href="{{ route('claims.show', $clm) }}" class="text-xs text-[#2563EB] font-bold hover:underline">Pantau</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-[#64748B] py-4 text-center">Belum ada pengajuan klaim aktif.</p>
                @endif
            </div>
        </div>

        <!-- Right: Notifications Center & Quick Info -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-[#0F172A]">Kabar Terbaru untukmu</h2>
                    <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-[#2563EB] hover:underline">Semua</a>
                </div>

                @if($notifications->count() > 0)
                    <div class="space-y-3">
                        @foreach($notifications as $notif)
                            <div class="p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition {{ ! $notif->is_read ? 'bg-blue-50/40 border-blue-100' : 'bg-slate-50/50' }}">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h4 class="text-xs font-bold text-[#0F172A]">{{ $notif->title }}</h4>
                                    <span class="text-[10px] text-[#94A3B8] shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-[#64748B] leading-relaxed mb-2">{{ $notif->message }}</p>
                                @if($notif->link_url)
                                    <a href="{{ $notif->link_url }}" class="inline-block text-[11px] font-semibold text-[#2563EB] hover:underline">
                                        Buka Selengkapnya &rarr;
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-[#64748B] py-4 text-center">Belum ada pemberitahuan baru.</p>
                @endif
            </div>

            <!-- Jam Layanan Pos Security (Layar 20 Figma) -->
            <div class="bg-slate-50 rounded-2xl border border-[#E2E8F0] p-5 text-xs text-[#64748B] space-y-2">
                <h4 class="font-bold text-[#0F172A] flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#16A34A]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                    Jam Layanan Pos Security
                </h4>
                <p>Pengambilan fisik barang &amp; verifikasi identitas:</p>
                <p class="font-bold text-[#0F172A]">Senin &ndash; Jumat, pukul 08.00 &ndash; 16.00 WIB</p>
                <p class="text-[11px] text-slate-500">Pos Security Lantai 1 Gedung FASILKOM UNSIKA.</p>
            </div>
        </div>
    </div>
</div>
@endsection

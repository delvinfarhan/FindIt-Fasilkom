@extends('layouts.officer', ['title' => 'Dashboard Security', 'pageTitle' => 'Ringkasan Layanan & Statistik Operasional'])

@section('content')
<div class="space-y-6">
    <!-- Welcome Header (Layar 01 Desktop Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-900">POS SECURITY FASILKOM</span>
                <span class="text-xs text-[#64748B]">&bull; Petugas Piket: <strong>{{ auth()->user()->name }}</strong></span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight">Selamat Bertugas, Pak {{ auth()->user()->name }}</h1>
            <p class="text-xs text-[#64748B] mt-1">Pantau seluruh inventaris barang temuan, verifikasi klaim mahasiswa, dan kelola serah terima fisik secara tertib.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('officer.items.create') }}" class="px-4 py-2.5 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Catat Barang Temuan Baru
            </a>
            <a href="{{ route('officer.claims.index', ['status' => 'pending']) }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-amber-950 font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Tinjau Klaim ({{ $kpi['pending_claims'] }})
            </a>
        </div>
    </div>

    <!-- 5 KPI Indicator Cards (Layar 01 Desktop Figma: 128 Total, 24 Tersedia, 8 Klaim, 17 Laporan, 89 Dikembalikan) -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Barang -->
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
            <div class="flex items-center justify-between text-xs text-[#64748B] mb-2 font-medium">
                <span>Total Barang</span>
                <span class="p-1 rounded bg-slate-100 text-slate-600">All</span>
            </div>
            <div class="text-3xl font-extrabold text-[#0F172A]">{{ $kpi['total_items'] }}</div>
            <p class="text-[11px] text-[#64748B] mt-1">Sepanjang layanan</p>
        </div>

        <!-- Barang Tersedia -->
        <div class="p-5 rounded-2xl bg-white border border-emerald-200/80 shadow-xs">
            <div class="flex items-center justify-between text-xs text-[#166534] mb-2 font-semibold">
                <span>Barang Tersedia</span>
                <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
            </div>
            <div class="text-3xl font-extrabold text-[#16A34A]">{{ $kpi['available_items'] }}</div>
            <p class="text-[11px] text-[#64748B] mt-1">Siap diklaim mahasiswa</p>
        </div>

        <!-- Klaim Menunggu -->
        <div class="p-5 rounded-2xl bg-white border border-amber-200/80 shadow-xs">
            <div class="flex items-center justify-between text-xs text-amber-800 mb-2 font-semibold">
                <span>Klaim Menunggu</span>
                <span class="px-1.5 py-0.5 rounded text-[10px] bg-amber-100 text-amber-800 font-bold">Verifikasi</span>
            </div>
            <div class="text-3xl font-extrabold text-[#D97706]">{{ $kpi['pending_claims'] }}</div>
            <p class="text-[11px] text-[#64748B] mt-1">Perlu tinjauan security</p>
        </div>

        <!-- Laporan Aktif -->
        <div class="p-5 rounded-2xl bg-white border border-blue-200/80 shadow-xs">
            <div class="flex items-center justify-between text-xs text-[#2563EB] mb-2 font-semibold">
                <span>Laporan Aktif</span>
                <span class="p-1 rounded bg-blue-50 text-[#2563EB]">Civitas</span>
            </div>
            <div class="text-3xl font-extrabold text-[#2563EB]">{{ $kpi['active_reports'] }}</div>
            <p class="text-[11px] text-[#64748B] mt-1">Menunggu pencocokan</p>
        </div>

        <!-- Dikembalikan / Serah Terima Selesai -->
        <div class="p-5 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between text-xs text-[#64748B] mb-2 font-medium">
                <span>Dikembalikan</span>
                <span class="p-1 rounded bg-slate-100 text-[#16A34A]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-[#0F172A]">{{ $kpi['returned_items'] }}</div>
            <p class="text-[11px] text-[#64748B] mt-1">Serah terima selesai</p>
        </div>
    </div>

    <!-- Chart & Recent Activity Grid (Layar 01 Desktop Figma) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left: 7-Day Statistics Chart Bar -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Aktivitas 7 Hari Terakhir</h3>
                    <p class="text-xs text-[#64748B]">Perbandingan barang masuk inventaris vs barang yang dikembalikan</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-[#2563EB]"></span> Barang Masuk</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-[#16A34A]"></span> Dikembalikan</span>
                </div>
            </div>

            <!-- Visual Bar Chart -->
            <div class="h-56 flex items-end justify-between gap-3 pt-6 border-b border-[#E2E8F0] px-2">
                @foreach($chartDays as $day)
                    <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end group">
                        <div class="w-full flex items-end justify-center gap-1.5 h-40">
                            <!-- Incoming Bar -->
                            <div class="w-4 sm:w-6 bg-[#2563EB] rounded-t-md transition group-hover:brightness-110 relative" style="height: {{ max(15, ($day['incoming'] * 35)) }}px">
                                <span class="opacity-0 group-hover:opacity-100 transition absolute -top-6 left-1/2 -translate-x-1/2 bg-[#0F172A] text-white text-[10px] px-1.5 py-0.5 rounded pointer-events-none">
                                    {{ $day['incoming'] }}
                                </span>
                            </div>
                            <!-- Returned Bar -->
                            <div class="w-4 sm:w-6 bg-[#16A34A] rounded-t-md transition group-hover:brightness-110 relative" style="height: {{ max(10, ($day['returned'] * 35)) }}px">
                                <span class="opacity-0 group-hover:opacity-100 transition absolute -top-6 left-1/2 -translate-x-1/2 bg-[#0F172A] text-white text-[10px] px-1.5 py-0.5 rounded pointer-events-none">
                                    {{ $day['returned'] }}
                                </span>
                            </div>
                        </div>
                        <span class="text-[11px] font-medium text-[#64748B] mt-1">{{ $day['short'] }}</span>
                    </div>
                @endforeach
            </div>
            <p class="text-[11px] text-[#94A3B8] text-center mt-3">Statistik sinkronisasi otomatis Pos Keamanan FASILKOM</p>
        </div>

        <!-- Right: Recent Incoming Claims -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-[#0F172A]">Klaim Terbaru Menunggu</h3>
                <a href="{{ route('officer.claims.index') }}" class="text-xs font-semibold text-[#2563EB] hover:underline">Semua Klaim &rarr;</a>
            </div>

            @if($recentClaims->count() > 0)
                <div class="space-y-3 flex-1">
                    @foreach($recentClaims as $claim)
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3 hover:bg-slate-100/70 transition">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="font-mono text-[10px] font-bold text-amber-900 bg-amber-100 px-1.5 py-0.2 rounded">{{ $claim->claim_code }}</span>
                                    <span class="text-[11px] font-semibold text-[#0F172A] truncate">{{ $claim->user->name }}</span>
                                </div>
                                <p class="text-xs text-[#64748B] truncate">Klaim: <strong>{{ $claim->foundItem->name }}</strong></p>
                            </div>
                            <a href="{{ route('officer.claims.show', $claim) }}" class="px-2.5 py-1 rounded-lg bg-[#2563EB] text-white text-xs font-semibold hover:bg-blue-700 shrink-0">
                                Periksa
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-[#64748B] py-8 text-center my-auto">Tidak ada antrean klaim baru saat ini.</p>
            @endif
        </div>
    </div>
</div>
@endsection

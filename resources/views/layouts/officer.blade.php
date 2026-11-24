<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Panel Security' }} - FindIt FASILKOM Desktop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#F8FAFC] text-[#0F172A] font-['Inter',sans-serif] flex overflow-hidden antialiased">

    <!-- Fixed Left Sidebar (Layar 01-05 Desktop Figma: #173B70) -->
    <aside class="w-64 bg-[#173B70] text-white flex flex-col shrink-0 border-r border-[#0f284e] z-30">
        <!-- Sidebar Brand -->
        <div class="p-5 border-b border-blue-900/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#2563EB] flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                    F
                </div>
                <div>
                    <h1 class="font-extrabold text-base tracking-tight leading-none text-white">FindIt</h1>
                    <p class="text-[11px] text-blue-200 font-medium mt-1">Panel Pos Security FASILKOM</p>
                </div>
            </div>
        </div>

        <!-- Officer Persona Badge -->
        <div class="px-5 py-4 bg-[#0f284e]/70 border-b border-blue-900/40 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-400/40 text-amber-300 flex items-center justify-center font-bold text-sm">
                DS
            </div>
            <div class="overflow-hidden">
                <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-amber-300 font-medium">Petugas Keamanan (ID: {{ auth()->user()->identity_number ?? 'SEC-01' }})</p>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="w-2 h-2 rounded-full bg-[#16A34A] animate-pulse"></span>
                    <span class="text-[10px] text-slate-300">Pos Security Aktif</span>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
            <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-blue-300 mb-2">Navigasi Operasional</p>

            <a href="{{ route('officer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('officer.dashboard') ? 'bg-[#2563EB] text-white font-semibold shadow-xs' : 'text-blue-100 hover:bg-[#235299] hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Ringkasan Layanan</span>
            </a>

            <a href="{{ route('officer.items.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('officer.items.*') ? 'bg-[#2563EB] text-white font-semibold shadow-xs' : 'text-blue-100 hover:bg-[#235299] hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Inventaris Barang</span>
                </div>
                @php
                    $availableBadge = \App\Models\FoundItem::where('status', 'tersedia')->count();
                @endphp
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-900/80 text-blue-200">{{ $availableBadge }}</span>
            </a>

            <a href="{{ route('officer.reports.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('officer.reports.*') ? 'bg-[#2563EB] text-white font-semibold shadow-xs' : 'text-blue-100 hover:bg-[#235299] hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Laporan Kehilangan</span>
                </div>
                @php
                    $activeReportsCount = \App\Models\LostReport::whereIn('status', ['menunggu', 'diproses'])->count();
                @endphp
                @if($activeReportsCount > 0)
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-500 text-amber-950">{{ $activeReportsCount }}</span>
                @endif
            </a>

            <a href="{{ route('officer.claims.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('officer.claims.*') ? 'bg-[#2563EB] text-white font-semibold shadow-xs' : 'text-blue-100 hover:bg-[#235299] hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Verifikasi Klaim</span>
                </div>
                @php
                    $pendingClaimsCount = \App\Models\Claim::where('status', 'pending')->count();
                @endphp
                @if($pendingClaimsCount > 0)
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#DC2626] text-white">{{ $pendingClaimsCount }}</span>
                @endif
            </a>

            <a href="{{ route('officer.civitas-reports.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('officer.civitas-reports.*') ? 'bg-[#2563EB] text-white font-semibold shadow-xs' : 'text-blue-100 hover:bg-[#235299] hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Temuan Civitas (Alur D)</span>
                </div>
                @php
                    $pendingCivitasCount = \App\Models\CivitasFoundReport::where('status', 'menunggu_penyerahan')->count();
                @endphp
                @if($pendingCivitasCount > 0)
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-amber-400 text-amber-950">{{ $pendingCivitasCount }}</span>
                @endif
            </a>

            <div class="pt-4 mt-4 border-t border-blue-900/60">
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-blue-300 mb-2">Pintasan</p>
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-blue-200 hover:bg-[#235299] hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Lihat Web Publik
                </a>
            </div>
        </nav>

        <!-- Sidebar Footer & Logout -->
        <div class="p-4 border-t border-blue-900/60 bg-[#0f284e]/40">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-medium bg-red-500/20 text-red-200 hover:bg-red-500 hover:text-white border border-red-500/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Sesi Security
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Work Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Operational Header -->
        <header class="bg-white border-b border-[#E2E8F0] h-16 flex items-center justify-between px-6 shrink-0 z-20">
            <div class="flex items-center gap-4">
                <span class="text-sm font-semibold text-[#0F172A]">{{ $pageTitle ?? 'Panel Operasional Security FASILKOM' }}</span>
                <span class="hidden sm:inline-block w-px h-5 bg-[#E2E8F0]"></span>
                <span class="hidden sm:inline-block text-xs text-[#64748B]">Pos Security Gd. FASILKOM &bull; Jam Layanan 08.00 - 16.00 WIB</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('officer.items.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-[#2563EB] text-white hover:bg-blue-700 shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Catat Barang Temuan
                </a>
            </div>
        </header>

        <!-- Scrollable Body Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <!-- Flash Message -->
            @if(session('success'))
                <div class="rounded-xl bg-[#DCFCE7] border border-[#16A34A]/30 p-4 mb-5 flex items-start gap-3 text-[#166534] shadow-xs">
                    <svg class="w-5 h-5 text-[#16A34A] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <div class="text-sm font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-xl bg-[#FEE2E2] border border-[#DC2626]/30 p-4 mb-5 flex items-start gap-3 text-[#991B1B] shadow-xs">
                    <svg class="w-5 h-5 text-[#DC2626] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <div class="text-sm font-medium">{{ session('error') }}</div>
                </div>
            @endif

            @if(session('info'))
                <div class="rounded-xl bg-blue-50 border border-blue-200 p-4 mb-5 flex items-start gap-3 text-blue-900 shadow-xs">
                    <svg class="w-5 h-5 text-[#2563EB] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <div class="text-sm font-medium">{{ session('info') }}</div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

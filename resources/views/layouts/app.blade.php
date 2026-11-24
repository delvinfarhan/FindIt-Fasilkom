<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Layanan Lost & Found' }} - FindIt FASILKOM UNSIKA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-full flex flex-col bg-[#F8FAFC] text-[#0F172A] font-['Inter',sans-serif] antialiased">

    <!-- Main Navigation Bar -->
    <header class="bg-white border-b border-[#E2E8F0] sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-[#173B70] flex items-center justify-center text-white font-bold text-xl shadow-xs group-hover:bg-[#2563EB] transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-extrabold text-lg text-[#173B70] tracking-tight">FindIt</span>
                                <span class="text-xs font-semibold px-1.5 py-0.5 rounded bg-blue-100 text-[#2563EB]">FASILKOM</span>
                            </div>
                            <p class="text-[10px] text-[#64748B] font-medium leading-none">Universitas Singaperbangsa Karawang</p>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden md:flex items-center gap-1 ml-6">
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('home') ? 'bg-blue-50 text-[#2563EB] font-semibold' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-slate-50' }}">
                            Beranda
                        </a>
                        <a href="{{ route('catalog.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('catalog.*') && !request()->routeIs('claims.create') ? 'bg-blue-50 text-[#2563EB] font-semibold' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-slate-50' }}">
                            Katalog Barang Temuan
                        </a>
                        @auth
                            <a href="{{ route('reports.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports.index') || request()->routeIs('claims.*') ? 'bg-blue-50 text-[#2563EB] font-semibold' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-slate-50' }}">
                                Laporan &amp; Klaim Saya
                            </a>
                        @endauth
                    </nav>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Action Button: Lapor Kehilangan -->
                    <a href="{{ route('reports.create') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-[#2563EB] text-white hover:bg-blue-700 shadow-xs transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Lapor Kehilangan
                    </a>

                    <!-- Action Button: Lapor Temuan Civitas -->
                    <a href="{{ route('civitas-reports.create') }}" class="hidden lg:inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-white text-[#173B70] border border-[#E2E8F0] hover:bg-slate-50 transition">
                        <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Lapor Barang Temuan
                    </a>

                    @auth
                        <!-- Notification Bell -->
                        <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-xl text-[#64748B] hover:text-[#0F172A] hover:bg-slate-100 transition" title="Pusat Pemberitahuan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @php
                                $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-[#DC2626] text-[10px] font-bold text-white">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <!-- User Profile Dropdown Button -->
                        <div class="relative" id="userMenuContainer">
                            <button type="button" onclick="toggleUserMenu()" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition focus:outline-hidden">
                                <div class="w-8 h-8 rounded-full bg-[#173B70] text-white flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div class="hidden sm:block text-left text-xs">
                                    <p class="font-semibold text-[#0F172A] leading-tight line-clamp-1">{{ auth()->user()->name }}</p>
                                    <p class="text-[#64748B] text-[11px]">{{ auth()->user()->role === 'petugas' ? 'Petugas Security' : (auth()->user()->identity_number ?? 'Mahasiswa') }}</p>
                                </div>
                                <svg class="w-4 h-4 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-lg border border-[#E2E8F0] py-2 z-50">
                                <div class="px-4 py-2 border-b border-[#E2E8F0]">
                                    <p class="text-xs text-[#64748B]">Masuk sebagai</p>
                                    <p class="text-sm font-semibold text-[#0F172A] truncate">{{ auth()->user()->name }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-semibold rounded {{ auth()->user()->isPetugas() ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ auth()->user()->isPetugas() ? 'Petugas Security FASILKOM' : 'Civitas Akademika' }}
                                    </span>
                                </div>
                                @if(auth()->user()->isPetugas())
                                    <a href="{{ route('officer.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-[#0F172A] hover:bg-slate-50 font-medium">
                                        <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Panel Desktop Security
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-[#0F172A] hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        Dashboard Civitas
                                    </a>
                                    <a href="{{ route('reports.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-[#0F172A] hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        Laporan &amp; Klaim Saya
                                    </a>
                                @endif
                                <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-[#0F172A] hover:bg-slate-50">
                                    <svg class="w-4 h-4 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profil &amp; Pengaturan
                                </a>
                                <div class="border-t border-[#E2E8F0] my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-[#DC2626] hover:bg-red-50 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar Akun
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest Login / Register -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold text-[#173B70] hover:bg-slate-100 transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold bg-[#173B70] text-white hover:bg-[#0f284e] shadow-xs transition">
                                Daftar Akun
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="rounded-xl bg-[#DCFCE7] border border-[#16A34A]/30 p-4 mb-4 flex items-start gap-3 text-[#166534] shadow-xs">
                <svg class="w-5 h-5 mt-0.5 text-[#16A34A] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl bg-[#FEE2E2] border border-[#DC2626]/30 p-4 mb-4 flex items-start gap-3 text-[#991B1B] shadow-xs">
                <svg class="w-5 h-5 mt-0.5 text-[#DC2626] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div class="rounded-xl bg-blue-50 border border-blue-200 p-4 mb-4 flex items-start gap-3 text-blue-900 shadow-xs">
                <svg class="w-5 h-5 mt-0.5 text-[#2563EB] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                <div class="text-sm font-medium">{{ session('info') }}</div>
            </div>
        @endif

        @if(session('warning'))
            <div class="rounded-xl bg-[#FEF3C7] border border-[#D97706]/30 p-4 mb-4 flex items-start gap-3 text-[#92400E] shadow-xs">
                <svg class="w-5 h-5 mt-0.5 text-[#D97706] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <div class="text-sm font-medium">{{ session('warning') }}</div>
            </div>
        @endif
    </div>

    <!-- Main Content Body -->
    <main class="flex-1 pb-16 md:pb-8">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation Bar (Layar 04 Figma) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-[#E2E8F0] z-40 px-2 py-1 shadow-lg">
        <div class="grid grid-cols-4 text-center">
            <a href="{{ route('home') }}" class="flex flex-col items-center py-1.5 {{ request()->routeIs('home') ? 'text-[#2563EB] font-semibold' : 'text-[#64748B]' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[11px] mt-0.5">Beranda</span>
            </a>
            <a href="{{ route('catalog.index') }}" class="flex flex-col items-center py-1.5 {{ request()->routeIs('catalog.*') ? 'text-[#2563EB] font-semibold' : 'text-[#64748B]' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('catalog.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span class="text-[11px] mt-0.5">Cari</span>
            </a>
            <a href="{{ route('reports.index') }}" class="flex flex-col items-center py-1.5 {{ request()->routeIs('reports.*') ? 'text-[#2563EB] font-semibold' : 'text-[#64748B]' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('reports.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-[11px] mt-0.5">Laporan</span>
            </a>
            <a href="{{ auth()->check() ? route('profile') : route('login') }}" class="flex flex-col items-center py-1.5 {{ request()->routeIs('profile') || request()->routeIs('login') ? 'text-[#2563EB] font-semibold' : 'text-[#64748B]' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('profile') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-[11px] mt-0.5">Profil</span>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-[#E2E8F0] mt-auto text-xs text-[#64748B] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-[#173B70] flex items-center justify-center text-white font-bold">
                            F
                        </div>
                        <span class="font-bold text-base text-[#173B70]">FindIt FASILKOM</span>
                    </div>
                    <p class="text-sm text-[#64748B] max-w-md mb-3 leading-relaxed">
                        Sistem informasi Lost &amp; Found resmi terintegrasi Fakultas Ilmu Komputer, Universitas Singaperbangsa Karawang. Mengubah buku pencatatan manual menjadi layanan digital yang transparan dan tertib.
                    </p>
                    <p class="text-xs text-[#0F172A] font-semibold">
                        Prinsip Layanan: <span class="text-[#2563EB]">"Digital untuk Informasi. Fisik untuk Serah Terima."</span>
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-[#0F172A] mb-3">Pos Security &amp; Pengambilan</h4>
                    <p class="leading-relaxed mb-1">Gedung Fakultas Ilmu Komputer (Lantai 1)</p>
                    <p class="leading-relaxed mb-1">Universitas Singaperbangsa Karawang</p>
                    <p class="font-medium text-[#0F172A] mt-2">Jam Layanan Fisik:</p>
                    <p class="text-xs text-[#16A34A] font-semibold">Senin &ndash; Jumat, 08.00 &ndash; 16.00 WIB</p>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-[#0F172A] mb-3">Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('catalog.index') }}" class="hover:text-[#2563EB] transition">Katalog Barang Temuan</a></li>
                        <li><a href="{{ route('reports.create') }}" class="hover:text-[#2563EB] transition">Laporkan Kehilangan</a></li>
                        <li><a href="{{ route('civitas-reports.create') }}" class="hover:text-[#2563EB] transition">Laporkan Barang Temuan</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-[#2563EB] transition">Portal Petugas Pos Keamanan</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-[#E2E8F0] pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <p>&copy; 2026 FindIt FASILKOM &bull; Fakultas Ilmu Komputer, Universitas Singaperbangsa Karawang.</p>
                <p class="text-[11px] text-[#94A3B8]">Layanan Sistem Pengelolaan Barang Temuan &amp; Pelaporan Kehilangan Terintegrasi</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        // Close dropdown when clicked outside
        window.addEventListener('click', function(e) {
            const container = document.getElementById('userMenuContainer');
            const dropdown = document.getElementById('userDropdown');
            if (container && dropdown && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

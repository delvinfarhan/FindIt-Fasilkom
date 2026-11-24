@extends('layouts.officer', ['title' => 'Inventaris Barang', 'pageTitle' => 'Manajemen Inventaris Barang Temuan'])

@section('content')
<div class="space-y-6">
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight">Inventaris Barang Temuan</h1>
            <p class="text-xs text-[#64748B] mt-0.5">Daftar seluruh barang temuan yang tercatat dan diamankan di Pos Keamanan FASILKOM.</p>
        </div>

        <a href="{{ route('officer.items.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Catat Barang Temuan Baru
        </a>
    </div>

    <!-- Filters Bar (Layar 02 Desktop Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-4 shadow-xs">
        <form method="GET" action="{{ route('officer.items.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari ID barang, nama, lokasi rak..."
                    class="w-full pl-9 pr-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
            </div>

            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
                    <option value="">-- Semua Status --</option>
                    <option value="tersedia" {{ $status === 'tersedia' ? 'selected' : '' }}>Barang tersedia</option>
                    <option value="diverifikasi" {{ $status === 'diverifikasi' ? 'selected' : '' }}>Kepemilikan diverifikasi</option>
                    <option value="dikembalikan" {{ $status === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan (Selesai)</option>
                    <option value="diarsipkan" {{ $status === 'diarsipkan' ? 'selected' : '' }}>Diarsipkan</option>
                </select>
            </div>

            <div class="sm:col-span-3 flex gap-2">
                <select name="category_id" class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
                    <option value="">-- Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#173B70] text-white font-semibold text-xs hover:bg-[#235299] transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Inventory Table (Layar 02 Desktop Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-[#E2E8F0] text-[#64748B] font-semibold">
                    <tr>
                        <th class="py-3 px-4">Foto</th>
                        <th class="py-3 px-4">Kode ID</th>
                        <th class="py-3 px-4">Nama Barang</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Lokasi Rak Pos</th>
                        <th class="py-3 px-4">Tanggal Temu</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 overflow-hidden flex items-center justify-center shrink-0">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-[#2563EB]">
                                {{ $item->item_code }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-[#0F172A]">
                                <a href="{{ route('officer.items.show', $item) }}" class="hover:underline">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-[#64748B]">
                                {{ $item->category->name }}
                            </td>
                            <td class="py-3 px-4 font-medium text-[#173B70]">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-blue-50 border border-blue-200 text-blue-900 font-semibold text-[11px]">
                                    {{ $item->storage_location }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-[#64748B]">
                                {{ $item->found_date->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($item->status === 'tersedia')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-[#DCFCE7] text-[#166534]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                                        Tersedia
                                    </span>
                                @elseif($item->status === 'diverifikasi')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Diverifikasi
                                    </span>
                                @elseif($item->status === 'dikembalikan')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-[#2563EB]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]"></span>
                                        Dikembalikan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        Diarsipkan
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-1 shrink-0">
                                <a href="{{ route('officer.items.show', $item) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[#0F172A] font-semibold text-[11px] transition">
                                    Detail
                                </a>
                                <a href="{{ route('officer.items.edit', $item) }}" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#2563EB] font-semibold text-[11px] transition">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-[#64748B]">
                                Tidak ada data inventaris yang sesuai kriteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#E2E8F0]">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection

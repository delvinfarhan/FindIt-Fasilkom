@extends('layouts.officer', ['title' => 'Verifikasi Klaim', 'pageTitle' => 'Pusat Verifikasi Pengajuan Klaim Mahasiswa'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight">Verifikasi Klaim Kepemilikan</h1>
            <p class="text-xs text-[#64748B] mt-0.5">Cocokkan bukti privat dan ciri khusus pemohon dengan catatan rahasia inventaris fisik pos security.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-4 shadow-xs">
        <form method="GET" action="{{ route('officer.claims.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-8 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari tiket klaim (KLM-...), nama pemohon, atau kode barang..."
                    class="w-full pl-9 pr-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
            </div>

            <div class="sm:col-span-4 flex gap-2">
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs text-[#0F172A] focus:outline-hidden focus:ring-2 focus:ring-[#2563EB]">
                    <option value="">-- Semua Status Klaim --</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu verifikasi</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Disetujui (Menunggu serah terima)</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Serah terima selesai</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-[#173B70] text-white font-semibold text-xs hover:bg-[#235299] transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Claims Table -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-[#E2E8F0] text-[#64748B] font-semibold">
                    <tr>
                        <th class="py-3 px-4">Kode Tiket</th>
                        <th class="py-3 px-4">Pemohon</th>
                        <th class="py-3 px-4">Barang yang Diklaim</th>
                        <th class="py-3 px-4">Tanggal Pengajuan</th>
                        <th class="py-3 px-4">Status Klaim</th>
                        <th class="py-3 px-4 text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($claims as $claim)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-mono font-bold text-amber-900">
                                {{ $claim->claim_code }}
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-[#0F172A]">{{ $claim->user->name }}</p>
                                <p class="text-[11px] text-[#64748B] font-mono">{{ $claim->user->identity_number ?? 'Mahasiswa' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-[#0F172A]">{{ $claim->foundItem->name }}</p>
                                <span class="text-[11px] font-mono text-[#2563EB]">{{ $claim->foundItem->item_code }} &bull; {{ $claim->foundItem->storage_location }}</span>
                            </td>
                            <td class="py-3 px-4 text-[#64748B]">
                                {{ $claim->created_at->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            <td class="py-3 px-4">
                                @if($claim->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu verifikasi
                                    </span>
                                @elseif($claim->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#DCFCE7] text-[#166534]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                                        Disetujui &bull; Belum Diambil
                                    </span>
                                @elseif($claim->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-700">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-[#2563EB]">
                                        Serah Terima Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-1 shrink-0">
                                <a href="{{ route('officer.claims.show', $claim) }}" class="px-3 py-1 rounded-lg bg-[#2563EB] hover:bg-blue-700 text-white font-semibold text-[11px] transition">
                                    Verifikasi &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-[#64748B]">
                                Tidak ada pengajuan klaim yang sesuai kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#E2E8F0]">
            {{ $claims->links() }}
        </div>
    </div>
</div>
@endsection

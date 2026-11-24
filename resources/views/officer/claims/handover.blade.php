@extends('layouts.officer', ['title' => 'Serah Terima Fisik ' . $claim->claim_code, 'pageTitle' => 'Pemeriksaan Serah Terima Fisik di Pos Security'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('officer.claims.show', $claim) }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB]">&larr; Kembali ke Verifikasi Klaim</a>
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] mt-1">Konfirmasi Pengambilan &amp; Serah Terima Fisik</h1>
            <p class="text-xs text-[#64748B]">Verifikasi kehadiran fisik pemilik dan serah terima barang langsung di Pos Security FASILKOM.</p>
        </div>
    </div>

    <!-- Handover Checklist Card (Layar 08 & 19 Desktop Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6 sm:p-8 space-y-6 text-xs">
        <!-- Target Barang & Lokasi Rak -->
        <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <span class="font-mono text-xs font-bold text-[#2563EB]">{{ $claim->foundItem->item_code }}</span>
                <h3 class="text-base font-bold text-[#173B70]">{{ $claim->foundItem->name }}</h3>
                <p class="text-[11px] text-[#64748B] mt-0.5">Pemohon: <strong>{{ $claim->user->name }}</strong> ({{ $claim->user->identity_number ?? 'Mahasiswa' }})</p>
            </div>
            <div class="sm:text-right">
                <span class="text-[11px] text-[#64748B] block">Ambil Fisik Barang dari Rak:</span>
                <span class="inline-block mt-0.5 px-3 py-1 rounded-lg bg-[#173B70] text-white font-extrabold text-xs">
                    {{ $claim->foundItem->storage_location }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('officer.claims.complete-handover', $claim) }}" class="space-y-6">
            @csrf

            <!-- Checklist Daftar Periksa (Layar 08 Desktop Figma) -->
            <div>
                <h4 class="font-bold uppercase tracking-wider text-[#173B70] mb-3 pb-2 border-b border-[#E2E8F0] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                    Daftar Periksa Wajib Petugas Security
                </h4>

                <div class="space-y-3">
                    <label class="flex items-start gap-3 p-3.5 rounded-xl border border-[#E2E8F0] hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="ktm_verified" value="1" required class="w-4 h-4 rounded text-[#2563EB] border-[#E2E8F0] focus:ring-[#2563EB] mt-0.5">
                        <div class="min-w-0">
                            <span class="font-bold text-[#0F172A] block">1. Verifikasi Identitas Asli (KTM / KTP)</span>
                            <span class="text-[#64748B] leading-relaxed block mt-0.5">
                                Mahasiswa hadir secara fisik dan telah menunjukkan Kartu Tanda Mahasiswa (KTM) atau KTP asli dengan nama <strong>{{ $claim->user->name }}</strong> ({{ $claim->user->identity_number }}).
                            </span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3.5 rounded-xl border border-[#E2E8F0] hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="physical_item_checked" value="1" required class="w-4 h-4 rounded text-[#2563EB] border-[#E2E8F0] focus:ring-[#2563EB] mt-0.5">
                        <div class="min-w-0">
                            <span class="font-bold text-[#0F172A] block">2. Pengambilan Fisik Barang &amp; Pengecekan Bersama</span>
                            <span class="text-[#64748B] leading-relaxed block mt-0.5">
                                Fisik barang telah diambil dari rak <strong>{{ $claim->foundItem->storage_location }}</strong> dan dicocokkan bersama mahasiswa bersangkutan.
                            </span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3.5 rounded-xl border border-[#E2E8F0] hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="recipient_identity_confirmed" value="1" required class="w-4 h-4 rounded text-[#2563EB] border-[#E2E8F0] focus:ring-[#2563EB] mt-0.5">
                        <div class="min-w-0">
                            <span class="font-bold text-[#0F172A] block">3. Konfirmasi Keabsahan Pemilik Sah</span>
                            <span class="text-[#64748B] leading-relaxed block mt-0.5">
                                Petugas memastikan tidak ada sengketa klaim lain dan barang siap diserahterimakan seutuhnya.
                            </span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Catatan Berita Acara -->
            <div>
                <label for="handover_notes" class="block font-semibold text-[#0F172A] mb-1">Catatan Berita Acara Serah Terima (Opsional):</label>
                <textarea id="handover_notes" name="handover_notes" rows="2"
                    class="w-full px-3.5 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#2563EB]"
                    placeholder="Contoh: Barang diserahkan dalam keadaan baik dan lengkap kepada Nadia Putri disaksikan petugas security."></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E2E8F0]">
                <a href="{{ route('officer.claims.show', $claim) }}" class="px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#64748B] hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#16A34A] hover:bg-green-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Konfirmasi Serah Terima Selesai
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

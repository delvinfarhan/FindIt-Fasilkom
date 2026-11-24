@extends('layouts.officer', ['title' => 'Verifikasi Klaim ' . $claim->claim_code, 'pageTitle' => 'Pemeriksaan & Verifikasi Bukti Klaim'])

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('officer.claims.index') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB]">&larr; Kembali ke Daftar Klaim</a>
            <div class="flex items-center gap-2 mt-1">
                <span class="font-mono text-xs font-bold text-amber-900 bg-amber-100 px-2.5 py-0.5 rounded">{{ $claim->claim_code }}</span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A]">Verifikasi Klaim: {{ $claim->foundItem->name }}</h1>
            </div>
        </div>

        <!-- Current Status Badge -->
        <div>
            @if($claim->status === 'pending')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Menunggu Keputusan Verifikasi
                </span>
            @elseif($claim->status === 'approved')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#DCFCE7] text-[#166534] border border-[#16A34A]/30">
                    <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                    Disetujui &bull; Menunggu Pengambilan Fisik
                </span>
            @elseif($claim->status === 'rejected')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                    <span class="w-2 h-2 rounded-full bg-red-600"></span>
                    Klaim Ditolak
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-[#2563EB] border border-blue-300">
                    <span class="w-2 h-2 rounded-full bg-[#2563EB]"></span>
                    Serah Terima Fisik Selesai
                </span>
            @endif
        </div>
    </div>

    <!-- SIDE-BY-SIDE VERIFICATION GRID (Layar 05 Desktop Figma) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Kolom Kiri: Pemohon & Bukti Kepemilikan (Layar 05) -->
        <div class="bg-white rounded-2xl border-2 border-blue-200/80 p-6 shadow-xs space-y-4 text-xs">
            <div class="flex items-center justify-between pb-3 border-b border-[#E2E8F0]">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#2563EB]"></span>
                    <h3 class="font-bold text-[#173B70] uppercase tracking-wider text-xs">Klaim &amp; Bukti Pemohon</h3>
                </div>
                <span class="px-2 py-0.5 rounded bg-blue-50 text-[#2563EB] font-mono font-semibold">{{ $claim->claim_code }}</span>
            </div>

            <!-- Identitas Pemohon -->
            <div class="p-3.5 bg-slate-50 rounded-xl space-y-1.5 border border-slate-100">
                <div class="flex justify-between">
                    <span class="text-[#64748B]">Nama Pemohon:</span>
                    <strong class="text-[#0F172A]">{{ $claim->user->name }}</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#64748B]">NPM / NIP:</span>
                    <span class="font-mono font-semibold text-[#0F172A]">{{ $claim->user->identity_number ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#64748B]">Program Studi:</span>
                    <span class="text-[#0F172A]">{{ $claim->user->department ?? 'FASILKOM' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#64748B]">WhatsApp:</span>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $claim->contact_phone) }}" target="_blank" class="font-bold text-[#2563EB] hover:underline">
                        {{ $claim->contact_phone }} &nearr;
                    </a>
                </div>
            </div>

            <!-- Kronologi Kehilangan -->
            <div>
                <span class="text-[#64748B] font-semibold block mb-1">Kronologi Kehilangan Menurut Pemohon:</span>
                <p class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-[#0F172A] leading-relaxed">
                    "{{ $claim->chronology }}"
                </p>
            </div>

            <!-- Ciri Khusus Privat Pemohon (Sorotan Verifikasi) -->
            <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-4 space-y-1.5">
                <span class="font-bold text-[#173B70] uppercase tracking-wider block">Ciri Khusus yang Disebutkan Pemohon:</span>
                <p class="text-blue-950 font-medium leading-relaxed bg-white/80 p-3 rounded-lg border border-blue-100">
                    "{{ $claim->private_proof_features }}"
                </p>
            </div>

            <!-- Foto Bukti Pengaju -->
            @if($claim->proof_image_path)
                <div>
                    <span class="text-[#64748B] font-semibold block mb-1.5">Foto Bukti Privat Pemohon:</span>
                    <img src="{{ asset('storage/' . $claim->proof_image_path) }}" alt="Foto Bukti" class="w-full h-48 object-cover rounded-xl border border-[#E2E8F0]">
                </div>
            @endif
        </div>

        <!-- Kolom Kanan: Catatan Rahasia Inventaris Pos Security (Layar 05) -->
        <div class="bg-white rounded-2xl border-2 border-amber-200/80 p-6 shadow-xs space-y-4 text-xs">
            <div class="flex items-center justify-between pb-3 border-b border-[#E2E8F0]">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <h3 class="font-bold text-amber-950 uppercase tracking-wider text-xs">Cocokkan dengan Inventaris Pos</h3>
                </div>
                <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-900 font-mono font-bold">{{ $claim->foundItem->item_code }}</span>
            </div>

            <!-- Metadata Barang Temuan -->
            <div class="p-3.5 bg-slate-50 rounded-xl space-y-1.5 border border-slate-100">
                <div class="flex justify-between">
                    <span class="text-[#64748B]">Nama Barang:</span>
                    <strong class="text-[#0F172A]">{{ $claim->foundItem->name }}</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#64748B]">Lokasi Ditemukan:</span>
                    <span class="text-[#0F172A]">{{ $claim->foundItem->found_location }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#64748B]">Tanggal Ditemukan:</span>
                    <span class="text-[#0F172A]">{{ $claim->foundItem->found_date->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            <!-- Lokasi Lemari Fisik Pos Security -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-[#64748B] block">Lokasi Rak Fisik di Pos:</span>
                    <strong class="text-sm font-extrabold text-[#173B70]">{{ $claim->foundItem->storage_location }}</strong>
                </div>
                <span class="p-2 rounded-lg bg-blue-100 text-[#2563EB]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </span>
            </div>

            <!-- Ciri Khusus Privat Catatan Security (Pembanding) -->
            <div class="bg-amber-50 border border-amber-300 rounded-xl p-4 space-y-1.5">
                <span class="font-bold text-amber-950 uppercase tracking-wider block">Catatan Rahasia Privat Security (Kunci Validasi):</span>
                <p class="text-amber-950 font-bold leading-relaxed bg-white/90 p-3 rounded-lg border border-amber-200">
                    "{{ $claim->foundItem->private_description }}"
                </p>
                <p class="text-[10px] text-amber-800 italic mt-1">Periksa apakah deskripsi ciri pemohon cocok dengan catatan rahasia di atas.</p>
            </div>

            <!-- Foto Barang Fisik di Pos -->
            @if($claim->foundItem->image_path)
                <div>
                    <span class="text-[#64748B] font-semibold block mb-1.5">Foto Barang Inventaris:</span>
                    <img src="{{ asset('storage/' . $claim->foundItem->image_path) }}" alt="Foto Barang" class="w-full h-48 object-cover rounded-xl border border-[#E2E8F0]">
                </div>
            @endif
        </div>
    </div>

    <!-- DECISION ACTION BAR (Layar 05, 06, 07, 10 Desktop Figma) -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h4 class="text-sm font-bold text-[#0F172A]">Tindakan Keputusan Petugas Security</h4>
            <p class="text-xs text-[#64748B] mt-0.5">Setujui jika bukti cocok, atau tolak dengan menyertakan alasan tertulis bagi pemohon.</p>
        </div>

        <div class="flex items-center gap-3">
            @if($claim->status === 'pending')
                <!-- Tombol Tolak (Buka Modal Tolak) -->
                <button type="button" onclick="openRejectModal()" class="px-4 py-2.5 rounded-xl bg-red-50 text-[#DC2626] border border-red-200 hover:bg-red-100 font-bold text-xs transition">
                    Tolak Klaim (Isi Alasan)
                </button>

                <!-- Tombol Setujui (Buka Modal Konfirmasi) -->
                <button type="button" onclick="openApproveModal()" class="px-5 py-2.5 rounded-xl bg-[#16A34A] hover:bg-green-700 text-white font-bold text-xs shadow-xs transition">
                    Setujui Klaim Ini &rarr;
                </button>
            @elseif($claim->status === 'approved')
                <button type="button" onclick="openRejectModal()" class="px-3 py-2 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50">
                    Ubah ke Tolak
                </button>
                <a href="{{ route('officer.claims.handover', $claim) }}" class="px-5 py-2.5 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Proses Serah Terima Fisik di Pos &rarr;
                </a>
            @elseif($claim->status === 'completed')
                <a href="{{ route('officer.claims.receipt', $claim) }}" class="px-4 py-2.5 rounded-xl bg-blue-50 text-[#2563EB] border border-blue-200 hover:bg-blue-100 font-bold text-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Cetak Tanda Bukti Serah Terima Resmi
                </a>
            @else
                <button type="button" onclick="openApproveModal()" class="px-4 py-2 rounded-xl bg-[#2563EB] text-white font-bold text-xs">
                    Tinjau Ulang &amp; Setujui
                </button>
            @endif
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI PERSETUJUAN (Layar 06 Desktop Figma) -->
<div id="approveModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-emerald-100 text-[#16A34A] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-[#0F172A]">Konfirmasi Persetujuan Klaim</h3>
                <p class="text-xs text-[#64748B]">Klaim {{ $claim->claim_code }} oleh {{ $claim->user->name }}</p>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-900 leading-relaxed">
            <strong>Peringatan Prinsip Layanan:</strong> Menyetujui klaim <strong>belum mengubah status barang menjadi diserahkan</strong>. Mahasiswa pemohon tetap wajib hadir ke Pos Security membawa KTM asli untuk serah terima fisik.
        </div>

        <form method="POST" action="{{ route('officer.claims.approve', $claim) }}" class="space-y-4">
            @csrf
            <div>
                <label for="officer_notes" class="block text-xs font-semibold text-[#0F172A] mb-1">Catatan Tambahan untuk Pemohon (Opsional):</label>
                <textarea id="officer_notes" name="officer_notes" rows="2"
                    class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#2563EB]"
                    placeholder="Ciri khusus cocok. Silakan datang ke Pos Security FASILKOM jam kerja membawa KTM asli."></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeApproveModal()" class="px-4 py-2 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#64748B] hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#16A34A] hover:bg-green-700 text-white font-bold text-xs shadow-xs">
                    Ya, Setujui Klaim Ini
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KONFIRMASI PENOLAKAN (Layar 10 Desktop Figma) -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-100 text-[#DC2626] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-[#0F172A]">Tolak Pengajuan Klaim</h3>
                <p class="text-xs text-[#64748B]">Wajib menyertakan alasan penolakan bagi pemohon</p>
            </div>
        </div>

        <form method="POST" action="{{ route('officer.claims.reject', $claim) }}" class="space-y-4">
            @csrf
            <div>
                <label for="rejection_reason" class="block text-xs font-semibold text-[#0F172A] mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                    class="w-full px-3 py-2 rounded-xl border border-red-300 text-xs focus:ring-2 focus:ring-red-500"
                    placeholder="Contoh: Ciri khusus jahitan dan inisial yang disampaikan pemohon tidak sesuai dengan catatan fisik barang yang diamankan di pos."></textarea>
            </div>

            <p class="text-[11px] text-[#64748B]">
                Barang akan otomatis dikembalikan ke status <strong>Tersedia</strong> di katalog publik agar pemilik yang sah dapat mengajukan klaim.
            </p>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#64748B] hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#DC2626] hover:bg-red-700 text-white font-bold text-xs shadow-xs">
                    Konfirmasi Tolak Klaim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openApproveModal() {
        document.getElementById('approveModal').classList.remove('hidden');
    }
    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
    }
    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection

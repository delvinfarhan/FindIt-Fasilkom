@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-[#64748B] mb-6">
        <a href="{{ route('home') }}" class="hover:text-[#2563EB]">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('reports.index', ['tab' => 'claims']) }}" class="hover:text-[#2563EB]">Klaim Saya</a>
        <span>&rsaquo;</span>
        <span class="text-[#0F172A] font-semibold truncate">{{ $claim->claim_code }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Left: Status Tracking (Layar 16, 17, 18, 19 Figma) -->
        <div class="md:col-span-7 space-y-6">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs">
                <!-- Header Status -->
                <div class="flex items-center justify-between mb-4">
                    <span class="font-mono text-xs font-bold px-2.5 py-1 rounded-md bg-amber-50 text-amber-900 border border-amber-200">
                        {{ $claim->claim_code }}
                    </span>
                    <span class="text-xs text-[#64748B]">{{ $claim->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>

                <h1 class="text-2xl font-extrabold text-[#0F172A] mb-1">Pelacakan Klaim Barang</h1>
                <p class="text-xs text-[#64748B] mb-6">Barang: <strong class="text-[#0F172A]">{{ $claim->foundItem->name }}</strong> ({{ $claim->foundItem->item_code }})</p>

                <!-- Status Card Depending on Condition -->
                @if($claim->status === 'pending')
                    <!-- Kondisi 1: Layar 16 Figma (Klaim Diperiksa) -->
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 mb-6">
                        <div class="flex items-start gap-3">
                            <span class="w-3 h-3 rounded-full bg-amber-500 mt-1 shrink-0 animate-pulse"></span>
                            <div>
                                <h3 class="text-sm font-bold text-amber-950">Menunggu Verifikasi Petugas</h3>
                                <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                                    Pengajuan klaim Anda telah diterima. Petugas security sedang mencocokkan ciri khusus privat yang Anda berikan dengan catatan inventaris fisik pos (estimasi 1&ndash;2 hari kerja).
                                </p>
                            </div>
                        </div>
                    </div>

                @elseif($claim->status === 'approved')
                    <!-- Kondisi 2: Layar 17 Figma (Klaim Disetujui & Belum Diambil) -->
                    <div class="p-5 rounded-xl bg-emerald-50 border border-emerald-300 mb-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#16A34A] mt-1 shrink-0"></span>
                            <div>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-200 text-[#166534] uppercase tracking-wider">Terverifikasi</span>
                                <h3 class="text-base font-bold text-[#166534] mt-0.5">Klaim Disetujui &bull; Siap Diambil di Pos Security</h3>
                                <p class="text-xs text-emerald-800 mt-1 leading-relaxed">
                                    Kepemilikan barang telah diverifikasi oleh petugas. Fisik barang saat ini disimpan di <strong>Pos Security FASILKOM</strong> dan menunggu pengambilan langsung oleh Anda.
                                </p>
                            </div>
                        </div>

                        <!-- Instruksi Pengambilan Fisik (Layar 17 Figma) -->
                        <div class="bg-white/90 p-4 rounded-xl border border-emerald-200 text-xs space-y-2">
                            <h4 class="font-bold text-[#173B70] flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Instruksi Pengambilan Fisik Barang
                            </h4>
                            <ul class="space-y-1.5 text-slate-700 pl-5 list-disc">
                                <li>Datang ke <strong>Pos Security FASILKOM UNSIKA</strong> (Gedung FASILKOM Lantai 1).</li>
                                <li>Jam Layanan: <strong>Senin &ndash; Jumat, 08.00 &ndash; 16.00 WIB</strong>.</li>
                                <li>Wajib membawa <strong>KTM (Kartu Tanda Mahasiswa) atau KTP asli</strong> untuk verifikasi wajah dan identitas.</li>
                                <li>Tunjukkan kode tiket klaim ini: <code class="font-bold text-[#2563EB] bg-blue-50 px-1 py-0.5 rounded">{{ $claim->claim_code }}</code>.</li>
                            </ul>
                        </div>
                    </div>

                @elseif($claim->status === 'rejected')
                    <!-- Kondisi 3: Layar 18 Figma (Klaim Ditolak) -->
                    <div class="p-5 rounded-xl bg-red-50 border border-red-200 mb-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#DC2626] mt-1 shrink-0"></span>
                            <div>
                                <h3 class="text-base font-bold text-red-900">Klaim Ditolak oleh Petugas Security</h3>
                                <p class="text-xs text-red-800 mt-1 leading-relaxed">
                                    Mohon maaf, bukti atau ciri khusus yang Anda berikan belum sesuai dengan catatan privat fisik inventaris pos security.
                                </p>
                            </div>
                        </div>

                        <!-- Catatan Alasan Petugas Security (Layar 18 Figma) -->
                        <div class="bg-white p-4 rounded-xl border border-red-200 text-xs">
                            <span class="font-bold text-red-900 block mb-1">Catatan Verifikator Pos Security:</span>
                            <p class="text-slate-800 italic leading-relaxed">"{{ $claim->officer_notes ?? 'Ciri khusus yang disampaikan tidak cocok dengan fisik barang yang diamankan.' }}"</p>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('claims.create', $claim->foundItem) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#DC2626] text-white font-semibold text-xs hover:bg-red-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Ajukan Klaim Ulang dengan Bukti Tambahan
                            </a>
                        </div>
                    </div>

                @elseif($claim->status === 'completed')
                    <!-- Kondisi 4: Layar 19 Figma (Pengambilan Selesai) -->
                    <div class="p-5 rounded-xl bg-blue-50 border border-blue-300 mb-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#16A34A] text-white flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-200 text-[#166534] uppercase tracking-wider">Serah Terima Selesai</span>
                                <h3 class="text-base font-bold text-[#173B70] mt-0.5">Barang Telah Diserahkan Kepada Anda</h3>
                                <p class="text-xs text-blue-900 mt-1 leading-relaxed">
                                    Serah terima fisik barang telah diselesaikan secara tertib di Pos Security FASILKOM.
                                </p>
                            </div>
                        </div>

                        <!-- Bukti Tanda Terima (Layar 19 Figma) -->
                        <div class="bg-white p-4 rounded-xl border border-blue-200 text-xs space-y-2">
                            <div class="grid grid-cols-2 gap-2 text-[11px]">
                                <div>
                                    <span class="text-[#64748B]">Waktu Penyerahan:</span>
                                    <span class="font-bold text-[#0F172A] block mt-0.5">{{ $claim->handed_over_at ? $claim->handed_over_at->translatedFormat('d M Y, H:i') : now()->translatedFormat('d M Y') }} WIB</span>
                                </div>
                                <div>
                                    <span class="text-[#64748B]">Petugas Penyerah:</span>
                                    <span class="font-bold text-[#0F172A] block mt-0.5">{{ $claim->verifier->name ?? 'Dedi Saputra (Pos Security)' }}</span>
                                </div>
                            </div>
                            @if($claim->handover_notes)
                                <div class="pt-2 border-t border-slate-100">
                                    <span class="text-[#64748B] block">Catatan Berita Acara:</span>
                                    <p class="text-slate-800 mt-0.5 italic">{{ $claim->handover_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Stepper Progress Bar -->
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-4">Tahapan Proses Klaim</h3>
                <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#E2E8F0]">
                    <!-- Step 1 -->
                    <div class="relative">
                        <span class="absolute -left-6 top-0 w-4 h-4 rounded-full bg-[#16A34A] border-2 border-white shadow-xs"></span>
                        <h4 class="text-xs font-bold text-[#0F172A]">Klaim diajukan oleh pemohon</h4>
                        <p class="text-[11px] text-[#64748B]">{{ $claim->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative">
                        <span class="absolute -left-6 top-0 w-4 h-4 rounded-full {{ $claim->status !== 'pending' ? 'bg-[#16A34A]' : 'bg-[#2563EB]' }} border-2 border-white shadow-xs"></span>
                        <h4 class="text-xs font-bold text-[#0F172A]">Verifikasi bukti privat oleh security</h4>
                        <p class="text-[11px] text-[#64748B]">
                            {{ $claim->verified_at ? 'Diverifikasi pada ' . $claim->verified_at->translatedFormat('d M Y, H:i') . ' WIB' : 'Sedang dalam antrean pemeriksaan petugas' }}
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative">
                        <span class="absolute -left-6 top-0 w-4 h-4 rounded-full {{ $claim->status === 'completed' ? 'bg-[#16A34A]' : ($claim->status === 'approved' ? 'bg-[#2563EB]' : ($claim->status === 'rejected' ? 'bg-[#DC2626]' : 'bg-slate-300')) }} border-2 border-white shadow-xs"></span>
                        <h4 class="text-xs font-bold text-[#0F172A]">Pengambilan fisik di Pos Security FASILKOM</h4>
                        <p class="text-[11px] text-[#64748B]">
                            {{ $claim->status === 'completed' ? 'Selesai diserahterimakan dengan pemeriksaan identitas' : 'Menunggu kehadiran pemilik sah di pos' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Bukti Pengajuan Pemohon -->
        <div class="md:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-4">Bukti yang Anda Kirimkan</h3>

                <div class="space-y-4 text-xs">
                    <div>
                        <span class="text-[#64748B] block">Kronologi Kehilangan:</span>
                        <p class="mt-1 text-[#0F172A] bg-slate-50 p-2.5 rounded-lg border border-slate-100 leading-relaxed">{{ $claim->chronology }}</p>
                    </div>

                    <div>
                        <span class="text-[#64748B] block flex items-center gap-1">
                            Ciri Khusus Bukti Kepemilikan:
                            <span class="px-1.5 py-0.2 rounded bg-amber-100 text-amber-800 text-[10px] font-semibold">Privat</span>
                        </span>
                        <p class="mt-1 text-[#0F172A] bg-amber-50/50 p-2.5 rounded-lg border border-amber-100 leading-relaxed">{{ $claim->private_proof_features }}</p>
                    </div>

                    <div>
                        <span class="text-[#64748B] block">Nomor Kontak WhatsApp:</span>
                        <span class="font-mono font-semibold text-[#0F172A] mt-0.5 block">{{ $claim->contact_phone }}</span>
                    </div>

                    @if($claim->proof_image_path)
                        <div>
                            <span class="text-[#64748B] block mb-1">Foto Bukti Kepemilikan:</span>
                            <img src="{{ asset('storage/' . $claim->proof_image_path) }}" alt="Foto Bukti" class="w-full h-40 object-cover rounded-xl border border-[#E2E8F0]">
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-4 border-t border-[#E2E8F0]">
                    <a href="{{ route('catalog.show', $claim->foundItem) }}" class="block w-full py-2 px-3 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-center text-[#0F172A] hover:bg-slate-50 transition">
                        Lihat Halaman Barang di Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

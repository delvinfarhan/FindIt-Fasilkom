@extends('layouts.officer', ['title' => 'Detail Laporan ' . $report->report_code, 'pageTitle' => 'Pemeriksaan Laporan Kehilangan'])

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('officer.reports.index') }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB]">&larr; Kembali ke Daftar Laporan</a>
            <div class="flex items-center gap-2 mt-1">
                <span class="font-mono text-xs font-bold text-[#2563EB] bg-blue-50 px-2 py-0.5 rounded">{{ $report->report_code }}</span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A]">{{ $report->item_name }}</h1>
            </div>
        </div>
    </div>

    <!-- 2 Column Inspection (Layar 14 Desktop Figma) -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Left: Pelapor & Ciri Privat Laporan -->
        <div class="md:col-span-7 bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs space-y-4 text-xs">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#173B70] pb-2 border-b border-[#E2E8F0]">Data Pelapor &amp; Detail Kehilangan</h3>

            <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 rounded-xl">
                <div>
                    <span class="text-[#64748B] block">Nama Pelapor:</span>
                    <span class="font-bold text-[#0F172A] block mt-0.5">{{ $report->user->name }}</span>
                    <span class="text-[11px] text-[#64748B] font-mono">{{ $report->user->identity_number }}</span>
                </div>
                <div>
                    <span class="text-[#64748B] block">WhatsApp:</span>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $report->contact_phone) }}" target="_blank" class="font-bold text-[#2563EB] block mt-0.5 hover:underline">
                        {{ $report->contact_phone }} &nearr;
                    </a>
                </div>
            </div>

            <div>
                <span class="text-[#64748B] block">Kategori &amp; Lokasi Terakhir:</span>
                <span class="font-semibold text-[#0F172A] mt-0.5 block">{{ $report->category->name }} &bull; {{ $report->lost_location }}</span>
            </div>

            <div>
                <span class="text-[#64748B] block">Waktu Hilang:</span>
                <span class="font-semibold text-[#0F172A] mt-0.5 block">{{ $report->lost_date->translatedFormat('l, d F Y') }}</span>
            </div>

            <div>
                <span class="text-[#64748B] block">Deskripsi Barang:</span>
                <p class="mt-1 text-[#0F172A] bg-slate-50 p-3 rounded-xl border border-slate-100 leading-relaxed">{{ $report->public_description }}</p>
            </div>

            <!-- Ciri Khusus Rahasia Pelapor -->
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 space-y-1.5">
                <span class="font-bold text-amber-950 uppercase tracking-wider block">Ciri Khusus Rahasia dari Pelapor:</span>
                <p class="text-amber-900 leading-relaxed font-medium">
                    "{{ $report->private_features }}"
                </p>
                <p class="text-[10px] text-amber-800 italic">*Cocokkan ciri ini dengan barang temuan di pos security.</p>
            </div>

            @if($report->image_path)
                <div>
                    <span class="text-[#64748B] block mb-1">Foto Referensi Pelapor:</span>
                    <img src="{{ asset('storage/' . $report->image_path) }}" alt="Foto Barang" class="w-full h-44 object-cover rounded-xl border border-[#E2E8F0]">
                </div>
            @endif
        </div>

        <!-- Right: Matching Action & Status Update (Layar 14 Desktop Figma) -->
        <div class="md:col-span-5 space-y-6">
            <!-- Update Status Form -->
            <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs text-xs space-y-4">
                <h3 class="font-bold uppercase tracking-wider text-[#173B70] pb-2 border-b border-[#E2E8F0]">Tindak Lanjut &amp; Status Laporan</h3>

                <form method="POST" action="{{ route('officer.reports.status', $report) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="status" class="block font-semibold text-[#0F172A] mb-1">Perbarui Status Laporan:</label>
                        <select id="status" name="status" class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs font-semibold focus:ring-2 focus:ring-[#2563EB]">
                            <option value="menunggu" {{ $report->status === 'menunggu' ? 'selected' : '' }}>Menunggu pemeriksaan</option>
                            <option value="diproses" {{ $report->status === 'diproses' ? 'selected' : '' }}>Sedang diperiksa security</option>
                            <option value="cocok" {{ $report->status === 'cocok' ? 'selected' : '' }}>Terdapat barang yang berpotensi cocok!</option>
                            <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>Selesai / Barang telah diserahkan</option>
                            <option value="dibatalkan" {{ $report->status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label for="matched_found_item_id" class="block font-semibold text-[#0F172A] mb-1">Hubungkan dengan Barang Temuan Inventaris:</label>
                        <select id="matched_found_item_id" name="matched_found_item_id" class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#2563EB]">
                            <option value="">-- Pilih Barang Temuan (Opsional) --</option>
                            @foreach($potentialMatches as $match)
                                <option value="{{ $match->id }}" {{ $report->matched_found_item_id == $match->id ? 'selected' : '' }}>
                                    [{{ $match->item_code }}] {{ $match->name }} ({{ $match->storage_location }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="officer_notes" class="block font-semibold text-[#0F172A] mb-1">Catatan Security untuk Pelapor:</label>
                        <textarea id="officer_notes" name="officer_notes" rows="3"
                            class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#2563EB]"
                            placeholder="Tuliskan catatan, misal: 'Ciri dompet hitam cocok dengan inventaris FND-2026-0142 di Lemari A. Silakan ajukan klaim.'">{{ old('officer_notes', $report->officer_notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition">
                        Simpan &amp; Kirim Notifikasi ke Mahasiswa
                    </button>
                </form>
            </div>

            <!-- Potential Matching Candidates Box -->
            <div class="bg-blue-50/70 rounded-2xl border border-blue-200 p-5 text-xs space-y-3">
                <span class="font-bold text-[#173B70] uppercase tracking-wider block">Kandidat Barang Temuan di Pos:</span>
                @if($potentialMatches->count() > 0)
                    <div class="space-y-2">
                        @foreach($potentialMatches as $candidate)
                            <div class="p-2.5 rounded-xl bg-white border border-blue-100 flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <span class="font-mono text-[10px] font-bold text-[#2563EB]">{{ $candidate->item_code }}</span>
                                    <p class="font-bold text-[#0F172A] truncate">{{ $candidate->name }}</p>
                                    <p class="text-[10px] text-[#64748B]">{{ $candidate->storage_location }}</p>
                                </div>
                                <a href="{{ route('officer.items.show', $candidate) }}" target="_blank" class="px-2 py-1 rounded bg-slate-100 text-[#0F172A] hover:bg-blue-100 font-semibold text-[10px] shrink-0">
                                    Lihat &nearr;
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-[#64748B]">Belum ada barang temuan pada kategori ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

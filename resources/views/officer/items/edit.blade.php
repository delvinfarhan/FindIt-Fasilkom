@extends('layouts.officer', ['title' => 'Edit Barang', 'pageTitle' => 'Edit Data Inventaris'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('officer.items.show', $item) }}" class="text-xs font-semibold text-[#64748B] hover:text-[#2563EB]">&larr; Kembali ke Detail Barang</a>
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] mt-1">Edit Inventaris: {{ $item->item_code }}</h1>
        </div>
    </div>

    <form method="POST" action="{{ route('officer.items.update', $item) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs p-6 space-y-4 text-xs">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-semibold text-[#0F172A] mb-1">Nama Umum Barang</label>
            <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required
                class="w-full px-3.5 py-2 rounded-xl border border-[#E2E8F0] text-xs focus:ring-2 focus:ring-[#2563EB]">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label for="category_id" class="block font-semibold text-[#0F172A] mb-1">Kategori</label>
                <select id="category_id" name="category_id" required class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="storage_location" class="block font-semibold text-[#0F172A] mb-1">Lokasi Rak Pos</label>
                <input type="text" id="storage_location" name="storage_location" value="{{ old('storage_location', $item->storage_location) }}" required
                    class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs font-semibold text-[#173B70]">
            </div>

            <div>
                <label for="status" class="block font-semibold text-[#0F172A] mb-1">Status Barang</label>
                <select id="status" name="status" required class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs">
                    <option value="tersedia" {{ $item->status === 'tersedia' ? 'selected' : '' }}>Barang tersedia</option>
                    <option value="diverifikasi" {{ $item->status === 'diverifikasi' ? 'selected' : '' }}>Kepemilikan diverifikasi</option>
                    <option value="dikembalikan" {{ $item->status === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="diarsipkan" {{ $item->status === 'diarsipkan' ? 'selected' : '' }}>Diarsipkan</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="found_location" class="block font-semibold text-[#0F172A] mb-1">Lokasi Ditemukan</label>
                <input type="text" id="found_location" name="found_location" value="{{ old('found_location', $item->found_location) }}" required
                    class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs">
            </div>
            <div>
                <label for="found_date" class="block font-semibold text-[#0F172A] mb-1">Tanggal Ditemukan</label>
                <input type="date" id="found_date" name="found_date" value="{{ old('found_date', $item->found_date->format('Y-m-d')) }}" required
                    class="w-full px-3 py-2 rounded-xl border border-[#E2E8F0] text-xs">
            </div>
        </div>

        <div>
            <label for="public_description" class="block font-semibold text-[#0F172A] mb-1">Deskripsi Umum Publik</label>
            <textarea id="public_description" name="public_description" rows="2" required
                class="w-full px-3.5 py-2 rounded-xl border border-[#E2E8F0] text-xs">{{ old('public_description', $item->public_description) }}</textarea>
        </div>

        <div>
            <label for="private_description" class="block font-semibold text-amber-900 mb-1">Catatan Privat Security (Ciri Khusus)</label>
            <textarea id="private_description" name="private_description" rows="3" required
                class="w-full px-3.5 py-2 rounded-xl border border-amber-300 bg-amber-50/40 text-xs">{{ old('private_description', $item->private_description) }}</textarea>
        </div>

        <div>
            <label for="image" class="block font-semibold text-[#0F172A] mb-1">Ganti Foto Barang (Opsional)</label>
            <input type="file" id="image" name="image" accept="image/*"
                class="w-full px-3 py-1.5 rounded-xl border border-[#E2E8F0] text-xs">
        </div>

        <div class="pt-4 border-t border-[#E2E8F0] flex justify-end gap-2">
            <a href="{{ route('officer.items.show', $item) }}" class="px-4 py-2 rounded-xl border border-[#E2E8F0] text-xs font-semibold">Batal</a>
            <button type="submit" class="px-5 py-2 rounded-xl bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-xs">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

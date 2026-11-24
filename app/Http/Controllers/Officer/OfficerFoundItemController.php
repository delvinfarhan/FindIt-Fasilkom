<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FoundItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OfficerFoundItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = FoundItem::with(['category', 'claims', 'creator']);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('found_location', 'like', "%{$search}%")
                  ->orWhere('storage_location', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $items = $query->latest('found_date')->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('officer.items.index', compact('items', 'categories', 'search', 'status', 'categoryId'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('officer.items.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'found_date' => ['required', 'date'],
            'found_time' => ['nullable'],
            'found_location' => ['required', 'string', 'max:200'],
            'public_description' => ['required', 'string', 'min:10'],
            'private_description' => ['required', 'string', 'min:10'],
            'storage_location' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'name.required' => 'Nama umum barang wajib diisi.',
            'category_id.required' => 'Kategori barang wajib dipilih.',
            'found_date.required' => 'Tanggal ditemukan wajib diisi.',
            'found_location.required' => 'Lokasi umum penemuan wajib diisi.',
            'public_description.required' => 'Deskripsi umum publik wajib diisi.',
            'private_description.required' => 'Ciri khusus rahasia privat wajib diisi.',
            'storage_location.required' => 'Lokasi rak fisik di Pos Security wajib diisi (contoh: Lemari A • Rak 02).',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        // Generate Ticket code format: FND-2026-XXXX
        $count = FoundItem::whereYear('created_at', now()->year)->count() + 1;
        $itemCode = sprintf('FND-%s-%04d', now()->format('Y'), $count);

        $item = FoundItem::create([
            'item_code' => $itemCode,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'found_date' => $validated['found_date'],
            'found_time' => $validated['found_time'] ?? null,
            'found_location' => $validated['found_location'],
            'public_description' => $validated['public_description'],
            'private_description' => $validated['private_description'],
            'storage_location' => $validated['storage_location'],
            'image_path' => $imagePath,
            'status' => 'tersedia',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('officer.items.show', $item)
            ->with('success', 'Barang temuan berhasil dicatat ke inventaris dengan kode ' . $itemCode . '.');
    }

    public function show(FoundItem $item): View
    {
        $item->load(['category', 'creator', 'claims.user', 'claims.verifier', 'handedOverTo']);
        return view('officer.items.show', compact('item'));
    }

    public function edit(FoundItem $item): View
    {
        $categories = Category::all();
        return view('officer.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, FoundItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'found_date' => ['required', 'date'],
            'found_time' => ['nullable'],
            'found_location' => ['required', 'string', 'max:200'],
            'public_description' => ['required', 'string', 'min:10'],
            'private_description' => ['required', 'string', 'min:10'],
            'storage_location' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:tersedia,diverifikasi,dikembalikan,diarsipkan'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item->update($validated);

        return redirect()->route('officer.items.show', $item)
            ->with('success', 'Data inventaris barang berhasil diperbarui.');
    }

    public function archive(FoundItem $item): RedirectResponse
    {
        // Guardrail: check active claims before archiving (Screen 13 Desktop)
        $activeClaims = $item->claims()->whereIn('status', ['pending', 'approved'])->count();

        if ($activeClaims > 0) {
            return back()->with('error', 'Peringatan Sistem: Barang ' . $item->item_code . ' tidak dapat diarsipkan karena masih memiliki ' . $activeClaims . ' klaim aktif yang belum diselesaikan.');
        }

        $item->update(['status' => 'diarsipkan']);

        return back()->with('success', 'Barang ' . $item->item_code . ' berhasil dipindahkan ke status arsip penyimpanan.');
    }
}

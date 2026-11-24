<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CivitasFoundReport;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CivitasReportController extends Controller
{
    public function create(): View
    {
        $categories = Category::all();
        return view('civitas-reports.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'found_date' => ['required', 'date'],
            'found_location' => ['required', 'string', 'max:200'],
            'public_description' => ['required', 'string', 'min:10'],
            'private_features' => ['nullable', 'string'],
            'temp_storage' => ['required', 'string', 'max:200'],
            'contact_phone' => ['required', 'string', 'min:10', 'max:15'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'item_name.required' => 'Nama barang temuan wajib diisi.',
            'category_id.required' => 'Kategori barang wajib dipilih.',
            'found_date.required' => 'Tanggal ditemukan wajib diisi.',
            'found_location.required' => 'Lokasi ditemukan wajib diisi.',
            'public_description.required' => 'Deskripsi umum barang wajib diisi.',
            'temp_storage.required' => 'Lokasi penyimpanan sementara Anda wajib diisi.',
            'contact_phone.required' => 'Nomor kontak WhatsApp aktif wajib diisi.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('civitas-items', 'public');
        }

        // Generate Ticket code format: TMN-2026-XXXX
        $count = CivitasFoundReport::whereYear('created_at', now()->year)->count() + 1;
        $reportCode = sprintf('TMN-%s-%04d', now()->format('Y'), $count);

        $report = CivitasFoundReport::create([
            'report_code' => $reportCode,
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'item_name' => $validated['item_name'],
            'found_date' => $validated['found_date'],
            'found_location' => $validated['found_location'],
            'public_description' => $validated['public_description'],
            'private_features' => $validated['private_features'] ?? null,
            'temp_storage' => $validated['temp_storage'],
            'contact_phone' => $validated['contact_phone'],
            'image_path' => $imagePath,
            'status' => 'menunggu_penyerahan',
        ]);

        // Notify officers
        $officers = User::where('role', 'petugas')->get();
        foreach ($officers as $officer) {
            Notification::create([
                'user_id' => $officer->id,
                'title' => 'Laporan Temuan Civitas Baru (' . $reportCode . ')',
                'message' => Auth::user()->name . ' melaporkan temuan "' . $report->item_name . '" dan siap menyerahkan ke Pos Security.',
                'type' => 'civitas_item_pending',
                'link_url' => route('officer.civitas-reports.show', $report),
            ]);
        }

        return redirect()->route('civitas-reports.show', $report)
            ->with('success', 'Laporan temuan berhasil dikirim dengan kode ' . $reportCode . '. Harap segera serahkan fisik barang ke Pos Security FASILKOM.');
    }

    public function show(CivitasFoundReport $civitasReport): View
    {
        if (Auth::user()->isMahasiswa() && $civitasReport->user_id !== Auth::id()) {
            abort(403);
        }

        $civitasReport->load(['category', 'user', 'receiver', 'foundItem']);

        return view('civitas-reports.show', ['report' => $civitasReport]);
    }
}

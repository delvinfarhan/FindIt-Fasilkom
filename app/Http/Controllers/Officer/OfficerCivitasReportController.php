<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\CivitasFoundReport;
use App\Models\FoundItem;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OfficerCivitasReportController extends Controller
{
    public function index(): View
    {
        $reports = CivitasFoundReport::with(['user', 'category', 'foundItem'])
            ->latest()
            ->paginate(12);

        return view('officer.civitas.index', compact('reports'));
    }

    public function show(CivitasFoundReport $report): View
    {
        $report->load(['user', 'category', 'foundItem', 'receiver']);
        return view('officer.civitas.show', compact('report'));
    }

    public function receive(Request $request, CivitasFoundReport $report): RedirectResponse
    {
        $validated = $request->validate([
            'storage_location' => ['required', 'string', 'max:100'],
            'officer_notes' => ['nullable', 'string'],
        ], [
            'storage_location.required' => 'Lokasi rak penyimpanan di pos security wajib dicatat (misal: Lemari B • Rak 01).',
        ]);

        $officer = Auth::user();
        $now = now();

        // 1. Create entry in found_items
        $count = FoundItem::whereYear('created_at', now()->year)->count() + 1;
        $itemCode = sprintf('FND-%s-%04d', now()->format('Y'), $count);

        $foundItem = FoundItem::create([
            'item_code' => $itemCode,
            'category_id' => $report->category_id,
            'name' => $report->item_name,
            'found_date' => $report->found_date,
            'found_location' => $report->found_location,
            'public_description' => $report->public_description,
            'private_description' => $report->private_features ?? 'Diterima dari laporan civitas penemu: ' . $report->user->name,
            'storage_location' => $validated['storage_location'],
            'image_path' => $report->image_path,
            'status' => 'tersedia',
            'created_by' => $officer->id,
        ]);

        // 2. Update civitas report
        $report->update([
            'status' => 'diterima_security',
            'received_by' => $officer->id,
            'received_at' => $now,
            'found_item_id' => $foundItem->id,
            'storage_location' => $validated['storage_location'],
            'officer_notes' => $validated['officer_notes'] ?? 'Barang fisik telah diterima di Pos Security dan dimasukkan ke inventaris umum.',
        ]);

        // 3. Notify civitas founder
        Notification::create([
            'user_id' => $report->user_id,
            'title' => 'Barang Temuan Diterima Pos Security',
            'message' => 'Terima kasih! Barang temuan "' . $report->item_name . '" (' . $report->report_code . ') telah diterima oleh petugas ' . $officer->name . ' dan dipublikasikan ke katalog publik dengan kode ' . $itemCode . '.',
            'type' => 'civitas_item_received',
            'link_url' => route('catalog.show', $foundItem),
        ]);

        return redirect()->route('officer.civitas-reports.show', $report)
            ->with('success', 'Fisik barang temuan berhasil diterima di Pos Security dan telah dipublikasikan ke katalog publik dengan kode ' . $itemCode . '.');
    }
}

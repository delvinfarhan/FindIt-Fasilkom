<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\FoundItem;
use App\Models\LostReport;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfficerLostReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = LostReport::with(['user', 'category', 'matchedFoundItem']);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('report_code', 'like', "%{$search}%")
                  ->orWhere('item_name', 'like', "%{$search}%")
                  ->orWhere('lost_location', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('identity_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $reports = $query->latest()->paginate(12)->withQueryString();

        return view('officer.reports.index', compact('reports', 'search', 'status'));
    }

    public function show(LostReport $report): View
    {
        $report->load(['user', 'category', 'matchedFoundItem']);

        // Potential matching items from same category
        $potentialMatches = FoundItem::where('category_id', $report->category_id)
            ->whereIn('status', ['tersedia', 'diverifikasi'])
            ->latest('found_date')
            ->take(5)
            ->get();

        return view('officer.reports.show', compact('report', 'potentialMatches'));
    }

    public function updateStatus(Request $request, LostReport $report): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu,diproses,cocok,selesai,dibatalkan'],
            'matched_found_item_id' => ['nullable', 'exists:found_items,id'],
            'officer_notes' => ['nullable', 'string'],
        ]);

        $report->update($validated);

        // Notify student of status update
        $statusLabels = [
            'diproses' => 'Sedang diperiksa oleh security',
            'cocok' => 'Terdapat barang inventaris yang berpotensi cocok!',
            'selesai' => 'Laporan selesai / telah ditemukan',
            'dibatalkan' => 'Laporan dibatalkan',
        ];

        Notification::create([
            'user_id' => $report->user_id,
            'title' => 'Pembaruan Laporan ' . $report->report_code,
            'message' => 'Status laporan "' . $report->item_name . '" kini: ' . ($statusLabels[$report->status] ?? $report->status) . '. ' . ($report->officer_notes ? 'Catatan petugas: ' . $report->officer_notes : ''),
            'type' => 'report_status_changed',
            'link_url' => route('reports.show', $report),
        ]);

        return back()->with('success', 'Status laporan kehilangan ' . $report->report_code . ' berhasil diperbarui dan notifikasi telah dikirim ke pelapor.');
    }
}

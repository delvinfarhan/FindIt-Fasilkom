<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LostReport;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LostReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tab = $request->input('tab', 'reports');

        $reports = $user->lostReports()->with(['category', 'matchedFoundItem'])->latest()->paginate(10);
        $claims = $user->claims()->with(['foundItem.category'])->latest()->paginate(10);
        $civitasReports = $user->civitasFoundReports()->with('category')->latest()->paginate(10);

        return view('reports.index', [
            'tab' => $tab,
            'reports' => $reports,
            'claims' => $claims,
            'civitasReports' => $civitasReports,
        ]);
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('reports.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'lost_date' => ['required', 'date'],
            'lost_time' => ['nullable'],
            'lost_location' => ['required', 'string', 'max:200'],
            'public_description' => ['required', 'string', 'min:10'],
            'private_features' => ['required', 'string', 'min:10'],
            'contact_phone' => ['required', 'string', 'min:10', 'max:15'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'item_name.required' => 'Nama barang wajib diisi.',
            'category_id.required' => 'Kategori barang wajib dipilih.',
            'lost_date.required' => 'Tanggal perkiraan kehilangan wajib diisi.',
            'lost_location.required' => 'Lokasi terakhir terlihat wajib diisi.',
            'public_description.required' => 'Deskripsi umum barang wajib diisi.',
            'private_features.required' => 'Ciri khusus privat wajib diisi untuk verifikasi petugas.',
            'contact_phone.required' => 'Nomor kontak aktif wajib diisi.',
            'contact_phone.min' => 'Nomor kontak minimal 10 digit.',
            'contact_phone.max' => 'Nomor kontak maksimal 15 digit.',
            'image.max' => 'Ukuran foto maksimal 3MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('lost-reports', 'public');
        }

        // Generate Ticket code format: LPR-2026-XXXX
        $count = LostReport::whereYear('created_at', now()->year)->count() + 1;
        $reportCode = sprintf('LPR-%s-%04d', now()->format('Y'), $count);

        $report = LostReport::create([
            'report_code' => $reportCode,
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'item_name' => $validated['item_name'],
            'lost_date' => $validated['lost_date'],
            'lost_time' => $validated['lost_time'] ?? null,
            'lost_location' => $validated['lost_location'],
            'public_description' => $validated['public_description'],
            'private_features' => $validated['private_features'],
            'contact_phone' => $validated['contact_phone'],
            'image_path' => $imagePath,
            'status' => 'menunggu',
        ]);

        // Notify officers
        $officers = User::where('role', 'petugas')->get();
        foreach ($officers as $officer) {
            Notification::create([
                'user_id' => $officer->id,
                'title' => 'Laporan Kehilangan Baru (' . $reportCode . ')',
                'message' => Auth::user()->name . ' melaporkan kehilangan "' . $report->item_name . '" di ' . $report->lost_location . '.',
                'type' => 'lost_report_created',
                'link_url' => route('officer.reports.show', $report),
            ]);
        }

        return redirect()->route('reports.submitted', $report)
            ->with('success', 'Laporan kehilangan berhasil dikirim dengan kode ' . $reportCode . '.');
    }

    public function submitted(LostReport $lostReport): View
    {
        // Authorization check
        if ($lostReport->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reports.submitted', ['report' => $lostReport]);
    }

    public function show(LostReport $lostReport): View
    {
        // Student can only see their own report, officer can see any
        if (Auth::user()->isMahasiswa() && $lostReport->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat laporan ini.');
        }

        $lostReport->load(['category', 'user', 'matchedFoundItem']);

        return view('reports.show', ['report' => $lostReport]);
    }

    public function cancel(LostReport $lostReport): RedirectResponse
    {
        if ($lostReport->user_id !== Auth::id()) {
            abort(403);
        }

        $lostReport->update(['status' => 'dibatalkan']);

        return redirect()->route('reports.show', $lostReport)
            ->with('info', 'Laporan telah berhasil dibatalkan.');
    }
}

<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\CivitasFoundReport;
use App\Models\Claim;
use App\Models\FoundItem;
use App\Models\LostReport;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class OfficerDashboardController extends Controller
{
    public function index(): View
    {
        // 5 KPI Cards according to Figma Screen 01 Desktop
        $kpi = [
            'total_items' => FoundItem::count(),
            'available_items' => FoundItem::where('status', 'tersedia')->count(),
            'pending_claims' => Claim::where('status', 'pending')->count(),
            'active_reports' => LostReport::whereIn('status', ['menunggu', 'diproses', 'cocok'])->count(),
            'returned_items' => FoundItem::where('status', 'dikembalikan')->count(),
            'pending_civitas' => CivitasFoundReport::where('status', 'menunggu_penyerahan')->count(),
        ];

        // 7-day stats for chart visualization
        $chartDays = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $incoming = FoundItem::whereDate('created_at', $date)->count();
            $returned = FoundItem::whereDate('handed_over_at', $date)->count();

            $chartDays->push([
                'label' => $date->translatedFormat('D, d M'),
                'short' => $date->translatedFormat('D'),
                'incoming' => $incoming,
                'returned' => $returned,
            ]);
        }

        $maxVal = max(4, (int) $chartDays->max(fn($d) => max($d['incoming'], $d['returned'])));

        $chartDays = $chartDays->map(function ($day) use ($maxVal) {
            $day['incoming_pct'] = $day['incoming'] > 0
                ? max(12, min(100, (int) round(($day['incoming'] / $maxVal) * 100)))
                : 0;
            $day['returned_pct'] = $day['returned'] > 0
                ? max(12, min(100, (int) round(($day['returned'] / $maxVal) * 100)))
                : 0;
            return $day;
        });

        // Recent activity
        $recentClaims = Claim::with(['foundItem', 'user'])->latest()->take(5)->get();
        $recentReports = LostReport::with(['user', 'category'])->latest()->take(5)->get();
        $recentItems = FoundItem::with(['category', 'creator'])->latest()->take(5)->get();

        return view('officer.dashboard', compact(
            'kpi',
            'chartDays',
            'maxVal',
            'recentClaims',
            'recentReports',
            'recentItems'
        ));
    }
}

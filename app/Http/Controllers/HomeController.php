<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FoundItem;
use App\Models\LostReport;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount(['foundItems' => function ($q) {
            $q->whereIn('status', ['tersedia', 'diverifikasi']);
        }])->get();

        $recentItems = FoundItem::with('category')
            ->whereIn('status', ['tersedia', 'diverifikasi'])
            ->latest('found_date')
            ->take(6)
            ->get();

        $stats = [
            'available' => FoundItem::where('status', 'tersedia')->count(),
            'returned' => FoundItem::where('status', 'dikembalikan')->count(),
            'total_reports' => LostReport::count(),
        ];

        return view('home', compact('categories', 'recentItems', 'stats'));
    }
}

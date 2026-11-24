<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FoundItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::all();
        $query = FoundItem::with('category')
            ->whereIn('status', ['tersedia', 'diverifikasi']);

        // Search by keyword
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('public_description', 'like', "%{$search}%")
                  ->orWhere('found_location', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Filter by location
        if ($location = $request->input('location')) {
            $query->where('found_location', 'like', "%{$location}%");
        }

        // Sort
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('found_date', 'asc');
        } else {
            $query->orderBy('found_date', 'desc');
        }

        $items = $query->paginate(9)->withQueryString();

        return view('catalog.index', [
            'items' => $items,
            'categories' => $categories,
            'selectedCategory' => $categorySlug,
            'searchQuery' => $search,
            'selectedLocation' => $location,
            'sort' => $sort,
        ]);
    }

    public function show(FoundItem $foundItem): View
    {
        $foundItem->load('category');

        $relatedItems = FoundItem::where('category_id', $foundItem->category_id)
            ->where('id', '!=', $foundItem->id)
            ->whereIn('status', ['tersedia', 'diverifikasi'])
            ->take(3)
            ->get();

        return view('catalog.show', [
            'item' => $foundItem,
            'relatedItems' => $relatedItems,
        ]);
    }
}

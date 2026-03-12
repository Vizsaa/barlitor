<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->input('search', ''));

        if ($query !== '') {
            $results = Item::search($query)
                ->query(fn ($q) => $q->with(['images', 'primaryImage']))
                ->paginate(12)
                ->appends(['search' => $query]);

            return view('home', [
                'searchQuery' => $query,
                'searchResults' => $results,
                'items' => collect(),
            ]);
        }

        $featured = Item::with(['images', 'primaryImage'])
            ->orderByDesc('item_id')
            ->limit(8)
            ->get();

        return view('home', [
            'searchQuery' => '',
            'searchResults' => null,
            'items' => $featured,
        ]);
    }
}


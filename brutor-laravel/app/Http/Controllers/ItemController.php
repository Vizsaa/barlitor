<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword  = $request->input('search', '');
        $category = $request->input('category', '');
        $sort     = $request->input('sort', 'default');

        $query = Item::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        switch ($sort) {
            case 'price_asc':  $query->orderBy('sell_price', 'asc'); break;
            case 'price_desc': $query->orderBy('sell_price', 'desc'); break;
            case 'newest':     $query->orderBy('created_at', 'desc'); break;
            case 'oldest':     $query->orderBy('created_at', 'asc'); break;
            case 'type':       $query->orderBy('type')->orderBy('title'); break;
            default:           $query->orderBy('title', 'asc'); break;
        }

        $items = $query->get();

        $categories = Item::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('items.index', compact('items', 'categories', 'keyword', 'category', 'sort'));
    }

    public function show($id)
    {
        $item = Item::with('supplier', 'reviews.user')->findOrFail($id);

        $canReview = false;
        if (Auth::check() && Auth::user()->role === 'customer') {
            $userId = Auth::id();
            $hasPurchased = \App\Models\ProductSold::whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->where('product_id', $item->item_id)->exists();

            $hasRented = \App\Models\Rental::where('customer_id', $userId)
                ->where('item_id', $item->item_id)->exists();

            $canReview = $hasPurchased || $hasRented;
        }

        $reviews = $item->reviews()->with('user')->orderByDesc('created_at')->get();

        return view('items.show', compact('item', 'canReview', 'reviews'));
    }

    public function create()
    {
        $suppliers = Supplier::whereNull('deleted_at')->orderBy('name')->get();
        return view('items.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string|max:64',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'type' => 'required|in:product,tool',
            'stock_quantity' => 'required|integer|min:0',
            'image_path' => 'nullable|image|max:5120',
        ]);

        $imagePath = '';
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $filename = uniqid('item_', true) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/items'), $filename);
            $imagePath = 'uploads/items/' . $filename;
        }

        Item::create([
            'title' => $request->title,
            'description' => $request->description,
            'cost_price' => $request->cost_price,
            'sell_price' => $request->sell_price,
            'image_path' => $imagePath,
            'category' => $request->category,
            'stock_quantity' => $request->stock_quantity,
            'supplier_id' => $request->supplier_id ?: null,
            'type' => $request->type,
        ]);

        return redirect()->route('items.index')->with('success', 'Item added successfully!');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $suppliers = Supplier::whereNull('deleted_at')->orderBy('name')->get();
        return view('items.edit', compact('item', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string|max:64',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'type' => 'required|in:product,tool',
            'stock_quantity' => 'required|integer|min:0',
            'image_path' => 'nullable|image|max:5120',
        ]);

        $item = Item::findOrFail($id);

        $data = $request->only([
            'title', 'description', 'cost_price', 'sell_price',
            'category', 'type', 'stock_quantity',
        ]);
        $data['supplier_id'] = $request->supplier_id ?: null;

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/items'), $filename);
            $data['image_path'] = 'uploads/items/' . $filename;
        }

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Item updated successfully!');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Imports\ItemsImport;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('search', '');
        $category = $request->input('category', '');
        $sort = $request->input('sort', 'default');
        $minPrice = $request->input('min_price', '');
        $maxPrice = $request->input('max_price', '');
        $type = $request->input('type', '');

        $trashedCount = Item::onlyTrashed()->count();

        if (Auth::check() && Auth::user()->isAdmin()) {
            $items = Item::with(['supplier', 'images', 'primaryImage'])
                ->orderBy('title', 'asc')
                ->get();

            $categories = Item::whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->orderBy('category')
                ->pluck('category');

            return view('items.index', compact(
                'items',
                'categories',
                'keyword',
                'category',
                'sort',
                'trashedCount',
                'minPrice',
                'maxPrice',
                'type'
            ));
        }

        $query = Item::with(['supplier', 'images', 'primaryImage']);

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        // Type filter
        if ($type && in_array($type, ['product', 'tool'])) {
            $query->where('type', $type);
        }

        // Price range
        if ($minPrice !== '' && is_numeric($minPrice)) {
            $query->where('sell_price', '>=', (float) $minPrice);
        }
        if ($maxPrice !== '' && is_numeric($maxPrice)) {
            $query->where('sell_price', '<=', (float) $maxPrice);
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('sell_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('sell_price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'type':
                $query->orderBy('type')->orderBy('title');
                break;
            default:
                $query->orderBy('title', 'asc');
                break;
        }

        $items = $query->get();

        $categories = Item::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('items.index', compact(
            'items',
            'categories',
            'keyword',
            'category',
            'sort',
            'trashedCount',
            'minPrice',
            'maxPrice',
            'type'
        ));
    }

    public function show($id)
    {
        $item = Item::with(['supplier', 'reviews.user', 'images', 'primaryImage'])->findOrFail($id);

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
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|max:500',
            'cost_price'     => 'required|numeric|min:0',
            'sell_price'     => 'required|numeric|min:0',
            'category'       => 'required|string',
            'type'           => 'required|in:product,tool',
            'stock_quantity' => 'required|integer|min:0',
            'image_path'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'images'         => 'nullable|array',
            'images.*'       => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'title.required'          => 'Item title is required.',
            'title.max'               => 'Title cannot exceed 255 characters.',
            'description.required'    => 'Description is required.',
            'description.max'         => 'Description cannot exceed 500 characters.',
            'cost_price.required'     => 'Cost price is required.',
            'cost_price.numeric'      => 'Cost price must be a valid number.',
            'cost_price.min'          => 'Cost price cannot be negative.',
            'sell_price.required'     => 'Selling price is required.',
            'sell_price.numeric'      => 'Selling price must be a valid number.',
            'sell_price.min'          => 'Selling price cannot be negative.',
            'category.required'       => 'Please select a category.',
            'type.required'           => 'Please select an item type.',
            'type.in'                 => 'Item type must be either product or tool.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.integer'  => 'Stock quantity must be a whole number.',
            'stock_quantity.min'      => 'Stock quantity cannot be negative.',
            'images.*.image'          => 'Each uploaded file must be an image.',
            'images.*.mimes'          => 'Accepted image formats: JPG, PNG, WEBP.',
            'images.*.max'            => 'Each image must not exceed 5MB.',
        ]);

        $imagePath = '';
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $filename = uniqid('item_', true) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/items'), $filename);
            $imagePath = 'images/items/' . $filename;
        }

        $item = Item::create([
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

        if ($request->hasFile('images')) {
            $sortOrder = 0;

            foreach ($request->file('images') as $file) {
                $filename = uniqid('item_', true) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/items'), $filename);

                ItemImage::create([
                    'item_id' => $item->item_id,
                    'image_path' => 'images/items/' . $filename,
                    'is_primary' => $sortOrder === 0,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

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
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|max:500',
            'cost_price'     => 'required|numeric|min:0',
            'sell_price'     => 'required|numeric|min:0',
            'category'       => 'required|string',
            'type'           => 'required|in:product,tool',
            'stock_quantity' => 'required|integer|min:0',
            'image_path'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'images'         => 'nullable|array',
            'images.*'       => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'title.required'          => 'Item title is required.',
            'title.max'               => 'Title cannot exceed 255 characters.',
            'description.required'    => 'Description is required.',
            'description.max'         => 'Description cannot exceed 500 characters.',
            'cost_price.required'     => 'Cost price is required.',
            'cost_price.numeric'      => 'Cost price must be a valid number.',
            'cost_price.min'          => 'Cost price cannot be negative.',
            'sell_price.required'     => 'Selling price is required.',
            'sell_price.numeric'      => 'Selling price must be a valid number.',
            'sell_price.min'          => 'Selling price cannot be negative.',
            'category.required'       => 'Please select a category.',
            'type.required'           => 'Please select an item type.',
            'type.in'                 => 'Item type must be either product or tool.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.integer'  => 'Stock quantity must be a whole number.',
            'stock_quantity.min'      => 'Stock quantity cannot be negative.',
            'images.*.image'          => 'Each uploaded file must be an image.',
            'images.*.mimes'          => 'Accepted image formats: JPG, PNG, WEBP.',
            'images.*.max'            => 'Each image must not exceed 5MB.',
        ]);

        $item = Item::findOrFail($id);

        $data = $request->only([
            'title', 'description', 'cost_price', 'sell_price',
            'category', 'type', 'stock_quantity',
        ]);
        $data['supplier_id'] = $request->supplier_id ?: null;

        if ($request->hasFile('image_path')) {
            // Delete old legacy image if exists
            if ($item->image_path && file_exists(public_path($item->image_path))) {
                @unlink(public_path($item->image_path));
            }

            $file = $request->file('image_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/items'), $filename);
            $data['image_path'] = 'images/items/' . $filename;
        }

        $item->update($data);

        if ($request->hasFile('images')) {
            $hasPrimary = $item->images()->where('is_primary', true)->exists();
            $sortOrder = $item->images()->count();

            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . uniqid('item_', true) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/items'), $filename);

                ItemImage::create([
                    'item_id' => $item->item_id,
                    'image_path' => 'images/items/' . $filename,
                    'is_primary' => !$hasPrimary && $index === 0,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        return redirect()->route('items.index')->with('success', 'Item updated successfully!');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function trashed()
    {
        $items = Item::onlyTrashed()
            ->with(['supplier', 'images', 'primaryImage'])
            ->orderByDesc('deleted_at')
            ->get();

        return view('items.trashed', compact('items'));
    }

    public function restore($id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()
            ->route('admin.items.trashed')
            ->with('success', 'Item restored successfully.');
    }

    public function deleteImage($imageId)
    {
        $image = ItemImage::findOrFail($imageId);
        $itemId = $image->item_id;
        $wasPrimary = $image->is_primary;

        $fullPath = public_path($image->image_path);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }

        $image->delete();

        // If the deleted image was primary, make the next one primary
        if ($wasPrimary) {
            $nextImage = ItemImage::where('item_id', $itemId)->orderBy('sort_order')->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Image removed.');
    }

    public function downloadTemplate()
    {
        $headers = ['title', 'description', 'cost_price', 'sell_price', 'category', 'type', 'stock_quantity', 'supplier_id'];
        $example = ['Example Spark Plug', 'NGK Iridium plug for most engines', '150.00', '250.00', 'Engine', 'product', '50', '1'];

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        fputcsv($handle, $example);
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="items_import_template.csv"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ItemsImport, $request->file('import_file'));
            return redirect()->route('items.index')->with('success', 'Items imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}

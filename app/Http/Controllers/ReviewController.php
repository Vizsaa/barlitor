<?php

namespace App\Http\Controllers;

use App\Models\ItemReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function adminIndex()
    {
        $reviews = ItemReview::with('user', 'item')
            ->orderByDesc('review_id')
            ->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer|exists:item,item_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $existing = ItemReview::where('item_id', $request->item_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this item. You can edit your review instead.');
        }

        ItemReview::create([
            'item_id' => $request->item_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }

    public function edit($id)
    {
        $review = ItemReview::with('item')->findOrFail($id);

        if (Auth::id() != $review->user_id) {
            return back()->with('error', 'You can only edit your own reviews.');
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = ItemReview::findOrFail($id);

        if (Auth::id() != $review->user_id) {
            return back()->with('error', 'You can only edit your own reviews.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('items.show', $review->item_id)->with('success', 'Review updated!');
    }

    public function destroy($id)
    {
        $review = ItemReview::findOrFail($id);

        if (Auth::id() != $review->user_id && !Auth::user()->isAdmin()) {
            return back()->with('error', 'You cannot delete this review.');
        }

        $itemId = $review->item_id;
        $review->delete();

        $referer = request()->headers->get('referer');
        if ($referer && str_contains($referer, '/admin/')) {
            return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
        }

        return redirect()->route('items.show', $itemId)->with('success', 'Review deleted.');
    }
}

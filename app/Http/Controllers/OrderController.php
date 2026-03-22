<?php

namespace App\Http\Controllers;

use App\Models\OrderInfo;
use App\Models\ProductSold;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function myOrders(Request $request)
    {
        $userId = Auth::id();
        $dateFrom = $request->input('from', '');
        $dateTo = $request->input('to', '');

        $query = OrderInfo::where('user_id', $userId)
            ->with(['payment', 'productsSold.item', 'rentals.item']);

        if ($dateFrom) {
            $query->where('date_placed', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('date_placed', '<=', $dateTo);
        }

        $orders = $query->orderByDesc('date_placed')->orderByDesc('orderinfo_id')->get();

        // Get all item IDs the user has already reviewed
        $reviewedItemIds = \App\Models\ItemReview::where('user_id', $userId)->pluck('item_id')->toArray();

        return view('orders.my_orders', compact('orders', 'dateFrom', 'dateTo', 'reviewedItemIds'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdatedMail;
use App\Models\OrderInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = OrderInfo::with(['user', 'payment', 'productsSold.item', 'rentals.item'])
            ->orderByDesc('date_placed')
            ->orderByDesc('orderinfo_id')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Processing,Delivered,Canceled',
        ]);

        $order = OrderInfo::with(['user', 'payment', 'productsSold.item', 'rentals.item'])->findOrFail($id);
        $oldStatus = (string) $order->status;
        $newStatus = (string) $validated['status'];

        if ($oldStatus !== $newStatus) {
            $order->update(['status' => $newStatus]);

            $user = $order->user;
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new OrderStatusUpdatedMail($order, $oldStatus, $newStatus));
                } catch (\Exception $e) {
                    // Status updates should not fail due to email issues.
                    Log::warning("Order status email failed for #{$order->orderinfo_id}: " . $e->getMessage());
                }
            }
        }

        return back()->with('success', "Order #{$order->orderinfo_id} status updated to {$newStatus}.");
    }
}


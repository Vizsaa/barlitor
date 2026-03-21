<?php

namespace App\Http\Controllers;

use App\Models\OrderInfo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ReceiptController extends Controller
{
    public function download(Request $request, int $transactionId)
    {
        $order = OrderInfo::with(['user', 'payment', 'productsSold.item', 'rentals.item'])
            ->findOrFail($transactionId);

        $user = Auth::user();
        $isAdmin = $user && $user->role === 'admin';

        if (!$isAdmin && (int) $order->user_id !== (int) Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $products = [];
        $tools = [];

        $grandTotal = 0.0;

        foreach ($order->productsSold as $ps) {
            $lineTotal = (float) ($ps->quantity * $ps->rate_charged);
            $grandTotal += $lineTotal;
            $products[] = [
                'title' => $ps->item->title ?? 'N/A',
                'quantity' => (int) $ps->quantity,
                'price' => (float) $ps->rate_charged,
                'line_total' => $lineTotal,
            ];
        }

        foreach ($order->rentals as $r) {
            $grandTotal += (float) $r->rate_charged;
            $tools[] = [
                'title' => $r->item->title ?? 'N/A',
                'start_date' => (string) $r->start_date,
                'due_date' => (string) $r->due_date,
                'quantity' => (int) $r->quantity,
                'line_total' => (float) $r->rate_charged,
            ];
        }

        $amountPaid = (float) optional($order->payment)->amount;
        $change = max(0, $amountPaid - $grandTotal);

        $pdf = Pdf::loadView('receipts.pdf', [
            'transactionId' => $order->orderinfo_id,
            'receiptDate' => (string) $order->date_placed,
            'customerName' => $order->user->name ?? 'Customer',
            'products' => $products,
            'tools' => $tools,
            'grandTotal' => $grandTotal,
            'amountPaid' => $amountPaid,
            'change' => $change,
        ])->setPaper('a4');

        $filename = "receipt_{$order->orderinfo_id}.pdf";

        return $pdf->download($filename);
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\OrderInfo;
use App\Models\Payment;
use App\Models\ProductSold;
use App\Models\Rental;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', ['products' => [], 'tools' => []]);

        if (empty($cart['products']) && empty($cart['tools'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $grandTotal = 0.0;
        foreach ($cart['products'] as $product) {
            $grandTotal += $product['price'] * $product['quantity'];
        }
        foreach ($cart['tools'] as $tool) {
            $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
            $grandTotal += $tool['rate'] * $days * $tool['quantity'];
        }

        return view('cart.checkout', compact('cart', 'grandTotal'));
    }

    public function process(Request $request)
    {
        $cart = session('cart', ['products' => [], 'tools' => []]);

        if (empty($cart['products']) && empty($cart['tools'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $grandTotal = 0.0;
        foreach ($cart['products'] as $product) {
            $grandTotal += $product['price'] * $product['quantity'];
        }
        foreach ($cart['tools'] as $tool) {
            $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
            $grandTotal += $tool['rate'] * $days * $tool['quantity'];
        }

        $amountPaid = (float) $request->input('amount_paid', 0);
        if ($amountPaid < $grandTotal) {
            return redirect()->route('checkout.index')->with('error', 'Amount paid is less than the grand total.');
        }

        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $datePlaced = date('Y-m-d');

            $order = OrderInfo::create([
                'user_id' => $userId,
                'customer_id' => 0,
                'date_placed' => $datePlaced,
                'status' => 'Processing',
            ]);

            $transactionId = $order->orderinfo_id;

            // Insert products sold
            if (!empty($cart['products'])) {
                foreach ($cart['products'] as $pid => $product) {
                    $quantity = (int) $product['quantity'];
                    $rate = (float) $product['price'];

                    ProductSold::create([
                        'transaction_id' => $transactionId,
                        'product_id' => $pid,
                        'quantity' => $quantity,
                        'rate_charged' => $rate,
                    ]);

                    Item::where('item_id', $pid)
                        ->update(['stock_quantity' => DB::raw("GREATEST(0, stock_quantity - {$quantity})")]);
                }
            }

            // Insert tool rentals
            if (!empty($cart['tools'])) {
                foreach ($cart['tools'] as $tool) {
                    $pid = (int) $tool['id'];
                    $startDate = $tool['start_date'];
                    $dueDate = $tool['due_date'];
                    $quantity = (int) $tool['quantity'];
                    $days = (strtotime($dueDate) - strtotime($startDate)) / 86400 + 1;
                    $rateTotal = $tool['rate'] * $days * $quantity;

                    Rental::create([
                        'transaction_id' => $transactionId,
                        'customer_id' => $userId,
                        'item_id' => $pid,
                        'start_date' => $startDate,
                        'due_date' => $dueDate,
                        'rate_charged' => $rateTotal,
                        'quantity' => $quantity,
                    ]);

                    Item::where('item_id', $pid)
                        ->update(['stock_quantity' => DB::raw("GREATEST(0, stock_quantity - {$quantity})")]);
                }
            }

            // Record payment
            Payment::create([
                'transaction_id' => $transactionId,
                'amount_paid' => $amountPaid,
                'paid_on' => now(),
            ]);

            $change = $amountPaid - $grandTotal;

            // Generate receipt
            $receiptDir = storage_path('app/receipts');
            if (!is_dir($receiptDir)) {
                mkdir($receiptDir, 0777, true);
            }
            $receiptFilename = "receipt_{$transactionId}.txt";
            $receiptPath = $receiptDir . '/' . $receiptFilename;

            $lines = [];
            $lines[] = "Receipt for Transaction #{$transactionId}";
            $lines[] = "Date: " . date('Y-m-d H:i:s');
            $lines[] = "----------------------------------------";

            if (!empty($cart['products'])) {
                $lines[] = "Products:";
                foreach ($cart['products'] as $product) {
                    $lines[] = $product['title'] . " x " . $product['quantity'] . " @ ₱" . number_format($product['price'], 2) . " = ₱" . number_format($product['price'] * $product['quantity'], 2);
                }
            }

            if (!empty($cart['tools'])) {
                $lines[] = "Tool Rentals:";
                foreach ($cart['tools'] as $tool) {
                    $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
                    $lines[] = $tool['title'] . " | " . $tool['start_date'] . " to " . $tool['due_date'] . " x " . $tool['quantity'] . " units for {$days} days @ ₱" . number_format($tool['rate'], 2) . " = ₱" . number_format($tool['rate'] * $days * $tool['quantity'], 2);
                }
            }

            $lines[] = "----------------------------------------";
            $lines[] = "Grand Total: ₱" . number_format($grandTotal, 2);
            $lines[] = "Amount Paid: ₱" . number_format($amountPaid, 2);
            $lines[] = "Change: ₱" . number_format($change, 2);

            file_put_contents($receiptPath, implode(PHP_EOL, $lines));

            // Send receipt via email
            $user = Auth::user();
            $receiptText = implode(PHP_EOL, $lines);
            $productsForEmail = [];
            foreach ($cart['products'] as $product) {
                $productsForEmail[] = [
                    'title' => $product['title'],
                    'quantity' => (int) $product['quantity'],
                    'price' => (float) $product['price'],
                    'line_total' => (float) ($product['price'] * $product['quantity']),
                ];
            }

            $toolsForEmail = [];
            foreach ($cart['tools'] as $tool) {
                $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
                $toolsForEmail[] = [
                    'title' => $tool['title'],
                    'start_date' => $tool['start_date'],
                    'due_date' => $tool['due_date'],
                    'days' => (int) $days,
                    'quantity' => (int) $tool['quantity'],
                    'rate' => (float) $tool['rate'],
                    'line_total' => (float) ($tool['rate'] * $days * $tool['quantity']),
                ];
            }

            $receiptHtml = view('emails.receipt', [
                'transactionId' => $transactionId,
                'receiptDate' => date('Y-m-d H:i:s'),
                'customerName' => $user?->name ?? 'Customer',
                'downloadUrl' => route('receipts.download', $transactionId),
                'products' => $productsForEmail,
                'tools' => $toolsForEmail,
                'grandTotal' => (float) $grandTotal,
                'amountPaid' => (float) $amountPaid,
                'change' => (float) $change,
            ])->render();

            $pdf = Pdf::loadView('receipts.pdf', [
                'transactionId' => $transactionId,
                'receiptDate' => date('Y-m-d H:i:s'),
                'customerName' => $user?->name ?? 'Customer',
                'products' => $productsForEmail,
                'tools' => array_map(function ($t) {
                    return [
                        'title' => $t['title'],
                        'start_date' => $t['start_date'],
                        'due_date' => $t['due_date'],
                        'quantity' => $t['quantity'],
                        'line_total' => $t['line_total'],
                    ];
                }, $toolsForEmail),
                'grandTotal' => (float) $grandTotal,
                'amountPaid' => (float) $amountPaid,
                'change' => (float) $change,
            ])->setPaper('a4');

            try {
                $pdfFilename = "receipt_{$transactionId}.pdf";
                $pdfBinary = $pdf->output();

                Mail::send([], [], function ($message) use ($user, $transactionId, $receiptHtml, $receiptPath, $receiptFilename, $pdfFilename, $pdfBinary) {
                    $message->to($user->email, $user->name)
                            ->subject("Your Transaction Receipt #{$transactionId}")
                            ->html($receiptHtml)
                            ->attachData($pdfBinary, $pdfFilename, ['mime' => 'application/pdf'])
                            ->attach($receiptPath, ['as' => $receiptFilename]);
                });
            } catch (\Exception $e) {
                // Email failure should not break the checkout
                Log::warning("Email send failed for transaction #{$transactionId}: " . $e->getMessage());
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('cart.index')->with('success', 'Checkout successful! Receipt has been sent to your email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = session('cart', ['products' => [], 'tools' => []]);
        $productTotal = 0;
        $toolTotal = 0;

        foreach ($cart['products'] as $product) {
            $productTotal += $product['price'] * $product['quantity'];
        }
        foreach ($cart['tools'] as $tool) {
            $toolTotal += $tool['rate'] * $tool['quantity'];
        }

        return view('cart.index', compact('cart', 'productTotal', 'toolTotal'));
    }

    public function addToCart(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to add items to your cart.');
        }

        $productId = (int) $request->input('product_id');
        $type = $request->input('type', 'product');

        $product = Item::where('item_id', $productId)->whereNull('deleted_at')->first();
        if (!$product) {
            return redirect()->route('items.index')->with('error', 'Product not found.');
        }

        $cart = session('cart', ['products' => [], 'tools' => []]);

        if ($type === 'product') {
            $quantity = max(1, (int) $request->input('quantity', 1));

            if (isset($cart['products'][$productId])) {
                $newQty = $cart['products'][$productId]['quantity'] + $quantity;
                $cart['products'][$productId]['quantity'] = min($newQty, $product->stock_quantity);
            } else {
                $cart['products'][$productId] = [
                    'title' => $product->title ?: $product->description,
                    'price' => (float) $product->sell_price,
                    'quantity' => min($quantity, $product->stock_quantity),
                ];
            }

            session(['cart' => $cart]);
            return redirect()->route('cart.index')->with('success', ($product->title ?: $product->description) . ' added to cart.');

        } elseif ($type === 'tool') {
            $startDate = $request->input('start_date', '');
            $dueDate = $request->input('due_date', '');
            $quantity = max(1, (int) $request->input('quantity', 1));

            if (!$startDate || !$dueDate || strtotime($startDate) > strtotime($dueDate)) {
                return redirect()->route('items.show', $productId)->with('error', 'Invalid rental dates.');
            }

            $merged = false;
            foreach ($cart['tools'] as &$tool) {
                if ($tool['id'] == $productId && $tool['start_date'] === $startDate && $tool['due_date'] === $dueDate) {
                    $newQty = $tool['quantity'] + $quantity;
                    $tool['quantity'] = min($newQty, $product->stock_quantity);
                    $merged = true;
                    break;
                }
            }
            unset($tool);

            if (!$merged) {
                $cart['tools'][] = [
                    'id' => $productId,
                    'title' => $product->title ?: $product->description,
                    'rate' => (float) $product->sell_price,
                    'start_date' => $startDate,
                    'due_date' => $dueDate,
                    'quantity' => min($quantity, $product->stock_quantity),
                ];
            }

            session(['cart' => $cart]);
            return redirect()->route('cart.index')->with('success', ($product->title ?: $product->description) . ' rental added to cart.');
        }

        return redirect()->route('items.index')->with('error', 'Invalid product type.');
    }

    public function removeProduct($id)
    {
        $cart = session('cart', ['products' => [], 'tools' => []]);
        unset($cart['products'][$id]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

    public function removeTool($index)
    {
        $cart = session('cart', ['products' => [], 'tools' => []]);
        if (isset($cart['tools'][$index])) {
            unset($cart['tools'][$index]);
            $cart['tools'] = array_values($cart['tools']);
        }
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', 'Tool rental removed from cart.');
    }

    public function updateQuantities(Request $request)
    {
        $cart = session('cart', ['products' => [], 'tools' => []]);
        $quantities = $request->input('quantities', []);

        foreach ($quantities as $pid => $qty) {
            $pid = (int) $pid;
            $qty = (int) $qty;

            $item = Item::where('item_id', $pid)->whereNull('deleted_at')->first();
            $stockQty = $item ? $item->stock_quantity : 0;

            if ($qty <= 0) {
                unset($cart['products'][$pid]);
            } else {
                if (isset($cart['products'][$pid])) {
                    $cart['products'][$pid]['quantity'] = min($qty, $stockQty);
                }
            }
        }

        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }
}

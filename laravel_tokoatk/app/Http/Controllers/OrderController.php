<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity ?? 1;

            // Validate stock
            if ($product->stok < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ], 400);
            }

            $cart = session()->get('cart', []);

            // If item exists, update quantity
            if (isset($cart[$request->product_id])) {
                $cart[$request->product_id]['quantity'] += $quantity;
            } else {
                // If item doesn't exist, add new item
                $cart[$request->product_id] = [
                    'quantity' => $quantity,
                    'price' => $product->harga
                ];
            }

            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart_count' => count($cart)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk ke keranjang'
            ], 500);
        }
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        $products = new Collection();
        $total = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $products->push([
                    'product' => $product,
                    'quantity' => $details['quantity']
                ]);
                $total += $product->harga * $details['quantity'];
            }
        }

        return view('cart', compact('products', 'total'));
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart')->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong');
        }

        $products = new Collection();
        $total = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $products->push([
                    'product' => $product,
                    'quantity' => $details['quantity']
                ]);
                $total += $product->harga * $details['quantity'];
            }
        }

        return view('checkout', compact('products', 'total'));
    }

    public function processCheckout(Request $request)
    {
        // Validate user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melakukan checkout');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong');
        }

        try {
            // Generate unique order number
            $orderNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            // Begin transaction
            \DB::beginTransaction();

            foreach ($cart as $productId => $details) {
                $product = Product::find($productId);

                // Skip if product not found
                if (!$product) continue;

                // Check stock
                if ($product->stok < $details['quantity']) {
                    throw new \Exception("Stok produk {$product->nama_produk} tidak mencukupi");
                }

                // Calculate total
                $total = $product->harga * $details['quantity'];

                // Create order
                Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => auth()->id(),
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $product->harga,
                    'total' => $total,
                    'status' => 'pending'
                ]);

                // Update stock
                $product->update([
                    'stok' => $product->stok - $details['quantity']
                ]);
            }

            // Commit transaction
            \DB::commit();

            // Clear cart
            session()->forget('cart');

            return redirect()->route('order.success', ['order' => $orderNumber])
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            // Rollback transaction
            \DB::rollBack();

            return redirect()->route('cart')
                ->with('error', $e->getMessage());
        }
    }

    public function orderSuccess($orderNumber)
    {
        $orders = Order::where('order_number', $orderNumber)->get();

        if ($orders->isEmpty()) {
            return redirect()->route('shop');
        }

        $total = $orders->sum('total');

        return view('order_success', compact('orders', 'total', 'orderNumber'));
    }
}














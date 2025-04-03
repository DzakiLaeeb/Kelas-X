<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        // Debugging
        \Log::info('Cart contents:', ['cart' => $cart]);
        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $menu = Menu::find($request->id);
        
        if(!$menu) {
            return response()->json(['success' => false]);
        }

        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->id])) {
            $cart[$request->id]['jumlah']++;
        } else {
            $cart[$request->id] = [
                "nama" => $menu->nama,
                "jumlah" => 1,
                "harga" => $menu->harga,
                "gambar" => $menu->gambar
            ];
        }
        
        session()->put('cart', $cart);
        return response()->json(['success' => true]);
    }

    public function updateQuantity($id, $action)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            if ($action === 'increase') {
                $cart[$id]['jumlah']++;
            } else if ($action === 'decrease') {
                if ($cart[$id]['jumlah'] > 1) {
                    $cart[$id]['jumlah']--;
                } else {
                    unset($cart[$id]);
                }
            }
            
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false]);
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false]);
    }

    public function clearCart()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Keranjang belanja kosong!');
        }

        $pelangganData = session('idpelanggan');
        if (!$pelangganData) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $total = $this->calculateTotal($cart);
        return view('cart.checkout', compact('cart', 'total', 'pelangganData'));
    }

    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart');
        $pelangganData = session('idpelanggan');
        
        if (!$cart) {
            return redirect()->route('menu.index')->with('error', 'Keranjang belanja kosong!');
        }

        if (!$pelangganData) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        try {
            $response = app(OrderController::class)->store($request);
            if ($response->getData()->success) {
                return redirect()->route('order.success', ['idorder' => $response->getData()->idorder]);
            }
            return redirect()->back()->with('error', $response->getData()->message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat checkout. Silakan coba lagi.');
        }
    }

    private function calculateTotal($cart)
    {
        return collect($cart)->sum(function($item) {
            return $item['harga'] * $item['jumlah'];
        });
    }
}

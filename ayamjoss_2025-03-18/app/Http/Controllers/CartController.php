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
        \Log::info('Cart contents in index method:', ['cart' => $cart]);
        \Log::info('Session ID:', ['id' => session()->getId()]);
        \Log::info('All session data:', ['data' => session()->all()]);

        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        // Log request data untuk debugging
        \Log::info('Cart add request:', ['request' => $request->all()]);

        $menuId = $request->id;
        if (!$menuId) {
            \Log::error('Menu ID tidak ditemukan dalam request');
            return response()->json(['success' => false, 'message' => 'ID menu tidak ditemukan']);
        }

        $menu = Menu::find($menuId);

        if(!$menu) {
            \Log::error('Menu tidak ditemukan:', ['id' => $menuId]);
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan']);
        }

        // Ambil cart dari session
        $cart = session()->get('cart', []);
        \Log::info('Cart sebelum update:', ['cart' => $cart]);

        // Tambahkan item ke cart
        if(isset($cart[$menuId])) {
            $cart[$menuId]['jumlah']++;
        } else {
            $cart[$menuId] = [
                "nama" => $menu->nama,
                "jumlah" => 1,
                "harga" => $menu->harga,
                "gambar" => $menu->gambar
            ];
        }

        // Simpan cart ke session dengan beberapa cara berbeda untuk memastikan data tersimpan
        session(['cart' => $cart]);
        session()->put('cart', $cart);
        $request->session()->put('cart', $cart);

        // Verifikasi bahwa cart telah disimpan di session
        $updatedCart = session()->get('cart', []);
        \Log::info('Cart setelah update:', ['cart' => $updatedCart]);
        \Log::info('Session ID saat add:', ['id' => session()->getId()]);
        \Log::info('All session data saat add:', ['data' => session()->all()]);

        // Hitung total item di keranjang
        $cartCount = 0;
        foreach($updatedCart as $item) {
            $cartCount += $item['jumlah'];
        }

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'message' => 'Menu berhasil ditambahkan ke keranjang'
        ]);
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

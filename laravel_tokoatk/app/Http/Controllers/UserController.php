<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        
        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Display the user's orders.
     */
    public function orders()
    {
        $orders = DB::table('orders')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('user.orders', compact('orders'));
    }

    /**
     * Display a specific order.
     */
    public function showOrder($orderId)
    {
        $order = DB::table('orders')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$order) {
            return redirect()->route('orders')->with('error', 'Pesanan tidak ditemukan.');
        }
        
        $orderItems = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'order_items.*',
                'products.nama_produk as product_name',
                'products.gambar as product_image'
            )
            ->where('order_items.order_id', $orderId)
            ->get();
            
        return view('user.order_detail', compact('order', 'orderItems'));
    }

    /**
     * Display the user's settings.
     */
    public function settings()
    {
        return view('user.settings');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('settings')->with('success', 'Password berhasil diperbarui.');
    }
}

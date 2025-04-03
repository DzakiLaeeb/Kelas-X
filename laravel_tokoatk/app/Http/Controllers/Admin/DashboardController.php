<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts for dashboard
        $userCount = User::count();
        $productCount = DB::table('products')->count();
        $orderCount = DB::table('orders')->count();
        $totalSales = DB::table('orders')->sum('total');
        
        // Get recent orders
        $recentOrders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as user_name')
            ->orderBy('orders.created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('admin.dashboard', compact('userCount', 'productCount', 'orderCount', 'totalSales', 'recentOrders'));
    }
}

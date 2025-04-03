<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        try {
            $today = Carbon::today();
            $month_start = Carbon::now()->startOfMonth();

            // Dapatkan data dasar
            $today_sales = Order::whereDate('created_at', $today)->sum('total') ?? 0;
            $today_orders = Order::whereDate('created_at', $today)->count() ?? 0;
            $month_sales = Order::whereDate('created_at', '>=', $month_start)->sum('total') ?? 0;
            $month_orders = Order::whereDate('created_at', '>=', $month_start)->count() ?? 0;
            $total_customers = Pelanggan::count() ?? 0;

            // Data grafik yang lebih sederhana untuk testing
            $chart_labels = ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"];
            $chart_data = [0, 0, 0, 0, 0, 0, 0]; // Default semua 0

            // Log data untuk debugging
            \Log::info('Chart Data:', [
                'labels' => $chart_labels,
                'data' => $chart_data
            ]);

            return view('admin.laporan.index', compact(
                'today_sales',
                'today_orders',
                'month_sales',
                'month_orders',
                'total_customers',
                'chart_labels',
                'chart_data'
            ));

        } catch (\Exception $e) {
            \Log::error('Error in LaporanController:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan.');
        }
    }
}


<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticatePelanggan
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('idpelanggan')) {
            \Log::info('Session pelanggan tidak ditemukan');
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu'
                ], 401);
            }
            
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu')
                ->with('intended_url', $request->url());
        }

        \Log::info('Session pelanggan:', session('idpelanggan'));
        return $next($request);
    }
}

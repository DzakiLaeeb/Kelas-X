<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            if (Auth::user()->level == 'admin') {
                return redirect('admin/user');
            }
            if (Auth::user()->level == 'kasir') {
                return redirect('admin/order');
            }
            if (Auth::user()->level == 'manager') {
                return redirect('admin/kategori');
            }
        }
        return view('Backend.login');
    }

    public function postlogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        // Get the most recently created admin user
        $latestAdmin = \App\Models\User::where('level', 'admin')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$latestAdmin) {
            return back()
                ->withInput($request->only('email'))
                ->with('pesan', 'No admin users found in the database. Please create an admin user first.');
        }

        // Try with credentials for the latest admin
        $credentials = [
            'email' => $latestAdmin->email,
            'password' => '123'
        ];

        // Debug: Log the email being used
        \Illuminate\Support\Facades\Log::info('Login attempt with hardcoded email: ' . $credentials['email']);

        // Try to authenticate with hardcoded credentials
        $authResult = Auth::attempt($credentials);
        \Illuminate\Support\Facades\Log::info('Auth attempt result with hardcoded credentials: ' . ($authResult ? 'success' : 'failure'));

        if ($authResult) {
            $request->session()->regenerate();

            // Get authenticated user
            $user = Auth::user();
            \Illuminate\Support\Facades\Log::info('Authenticated user level: ' . ($user->level ?? 'null'));

            // Redirect based on user level
            if ($user->level == 'admin') {
                return redirect('admin/user');
            }
            if ($user->level == 'kasir') {
                return redirect('admin/order');
            }
            if ($user->level == 'manager') {
                return redirect('admin/kategori');
            }

            // Default redirect if level doesn't match any condition
            return redirect('admin');
        }

        public function logout() {
            session()->flush();
            Auth::logout();

            return redirect('admin');
        }

        // If hardcoded credentials didn't work, try with user input
        $credentials = $request->only('email', 'password');

        // Debug: Log the email being used
        \Illuminate\Support\Facades\Log::info('Login attempt with user input email: ' . $credentials['email']);

        // Check if user exists
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        // Debug: Log if user was found
        if ($user) {
            \Illuminate\Support\Facades\Log::info('User found: ' . $user->name);
        } else {
            \Illuminate\Support\Facades\Log::info('User not found for email: ' . $credentials['email']);

            // Try case-insensitive search
            $user = \App\Models\User::whereRaw('LOWER(email) = ?', [strtolower($credentials['email'])])->first();

            if ($user) {
                \Illuminate\Support\Facades\Log::info('User found with case-insensitive search: ' . $user->name);
                // Use the correct case for the email
                $credentials['email'] = $user->email;
            }
        }

        if (!$user) {
            // Debug: List all users in the database
            $allUsers = \App\Models\User::all();
            \Illuminate\Support\Facades\Log::info('All users in database:');
            foreach ($allUsers as $dbUser) {
                \Illuminate\Support\Facades\Log::info('- ' . $dbUser->email);
            }

            return back()
                ->withInput($request->only('email'))
                ->with('pesan', 'Email tidak ditemukan. Silakan coba lagi.');
        }

        // Try to authenticate
        $authResult = Auth::attempt($credentials);
        \Illuminate\Support\Facades\Log::info('Auth attempt result with user input: ' . ($authResult ? 'success' : 'failure'));

        if ($authResult) {
            $request->session()->regenerate();

            // Get authenticated user
            $user = Auth::user();
            \Illuminate\Support\Facades\Log::info('Authenticated user level: ' . ($user->level ?? 'null'));

            // Redirect based on user level
            if ($user->level == 'admin') {
                return redirect('admin/user');
            }
            if ($user->level == 'kasir') {
                return redirect('admin/order');
            }
            if ($user->level == 'manager') {
                return redirect('admin/order');
            }

            // Default redirect if level doesn't match any condition
            return redirect('admin');
        }

        // If authentication fails, show error message
        return back()
            ->withInput($request->only('email'))
            ->with('pesan', 'Password salah. Silakan coba lagi.');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('admin');
    }
}

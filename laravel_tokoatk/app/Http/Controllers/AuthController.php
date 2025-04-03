<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        // Hanya terapkan middleware guest kecuali untuk logout
        $this->middleware('guest', ['except' => ['logout']]);
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/'); // Ubah ke halaman index
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users|min:3',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        return redirect('/'); // Ubah ke halaman index
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Set session variables yang diperlukan
            session(['user_logged_in' => true]);
            session(['username' => Auth::user()->username]);
            session(['name' => Auth::user()->name]);
            session(['user_id' => Auth::user()->id]);
            
            // Tambahkan pesan sukses
            session(['success' => 'Login berhasil!']);
            
            return redirect('/'); // Ubah ke halaman index
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}









<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $pelanggan = Pelanggan::where('email', $request->email)->first();

        if ($pelanggan && Hash::check($request->password, $pelanggan->password)) {
            $data = [
                'idpelanggan' => $pelanggan->idpelanggan,
                'email' => $pelanggan->email,
                'nama' => $pelanggan->pelanggan,
                'telp' => $pelanggan->telp,
                'alamat' => $pelanggan->alamat
            ];
            
            session(['idpelanggan' => $data]);
            return redirect()->intended('/')->with('success', 'Login berhasil!');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:pelanggans,email',
            'password' => 'required|min:6|confirmed',
            'alamat' => 'required',
            'telp' => 'required'
        ]);

        $pelanggan = Pelanggan::create([
            'pelanggan' => $request->nama,  // menggunakan 'pelanggan' bukan 'nama'
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'telp' => $request->telp,
            'jeniskelamin' => 'P'  // default value
        ]);

        $data = [
            'idpelanggan' => $pelanggan->idpelanggan,
            'email' => $pelanggan->email,
            'nama' => $pelanggan->pelanggan
        ];
        
        session(['idpelanggan' => $data]);
        return redirect('/')->with('success', 'Registrasi berhasil!');
    }

    public function logout(Request $request)
    {
        session()->forget('idpelanggan');
        return redirect('/')->with('success', 'Logout berhasil!');
    }
}







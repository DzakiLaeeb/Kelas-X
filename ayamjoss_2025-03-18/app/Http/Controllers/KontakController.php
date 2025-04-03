<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class KontakController extends Controller
{
    public function index()
    {
        return view('kontak');
    }

    public function send(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

        // Untuk saat ini, kita hanya redirect dengan pesan sukses
        // Implementasi pengiriman email bisa ditambahkan nanti
        return redirect()->back()->with('success', 'Pesan Anda telah berhasil dikirim!');
    }
}



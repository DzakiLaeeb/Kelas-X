<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use App\Http\Requests\StorekategoriRequest;
use App\Http\Requests\UpdatekategoriRequest;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Backend.kategori.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Backend.kategori.insert');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorekategoriRequest $request)
    {
        $data = $request->validate([
            'kategori'=>'required'
        ]);

        Kategori::create([
            'kategori'=>$data['kategori']
        ]);

        return redirect('admin/kategori');
    }

    /**
     * Display the specified resource.
     */
    public function show($idkategori)
    {
        Kategori::where('idkategori',$idkategori)->delete();

        return redirect('admin/kategori');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kategori)
    {
        $kategori = Kategori::where('idkategori',$kategori)->first();
        return view('Backend.kategori.update',['kategori'=>$kategori]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatekategoriRequest $request, kategori $kategori)
    {
        $data = $request->validate([
            'kategori'=>'required'
        ]);

        Kategori::where('idkategori', $idkategori)->update([
            'kategori' => $data['kategori']
        ]);

        return redirect('admin/kategori');
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(kategori $kategori)
    {
        //
    }
}

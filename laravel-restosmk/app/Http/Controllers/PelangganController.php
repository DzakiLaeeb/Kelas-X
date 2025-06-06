<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Http\Requests\StorePelangganRequest;
use App\Http\Requests\UpdatePelangganRequest;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggans=Pelanggan::pagination(3);
        return view('Backend.pelanggan.select',['pelanggans'=>$pelanggans]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePelangganRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($idpelanggan)
    {
        $pelanggan=Pelanggan::where('idpelanggan',$idpelanggan)->first();

        if($pelanggan->aktif == 0){
            $status=1;
        }else{
            $status=0;
        }


        Pelanggan::where('idpelanggan',$idpelanggan)->update(['aktif'=>$status]);

        return redirect('admin/pelanggan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePelangganRequest $request, Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        //
    }
}

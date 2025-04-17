<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $User=User::all();
        return view('Backend.user.select',['Users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Backend.user.insert');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=$request->validate([
            'user'=>'required',
            'email'=>'required | email |unique:users',
            'password'=>'required |min:3',
            'level'=>'required'
        ]);
        User::create([
            'user'=>$data['user'],
            'email'=>$data['email'],
            'password'=> Hash::make($data['password']),
            'level'=>$request->level
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $users = User::where('id',$id);
        $levels = User::where('level' ,$users[0])->get();
        $jumlah = User::where('level' ,$users[0])->count();

        if ($jumlah == 1) {
            session()->flash('pesan','Data hanya satu tidak bisa dihapus');
        } else {
            User::where('id',$id)->delete();
        }
        

        return redirect('admin/user');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::where('iduser',$id)->first();
        return view('Backend.user.update');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'user'=>'required',
            'email'=>'required | email |unique:users',
            'password'=>'required |min:3',
            'level'=>'required'
        ]);
        User::where('iduser',$id)->update([
            'user'=>$data['user'],
            'email'=>$data['email'],
            'password'=> Hash::make($data['password']),
            'level'=>$request->level
        ]);
        return redirect('admin/user');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

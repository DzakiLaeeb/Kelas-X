<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function showUsers()
    {
        $users = User::all();
        return response()->json($users);
    }
}

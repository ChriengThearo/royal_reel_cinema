<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // count the total number of users in the database
        $total_users = User::all();
        return response()->json(['total_users' => $total_users]);
    }
    public function store()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com'
        ]);
        return response()->json($user, 201);
    }
}

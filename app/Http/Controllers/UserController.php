<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users')->with(compact('users'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'role' => 'required'
        ]);

        $user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return response('ok', 200);
    }

    public function get_user(User $user)
    {
        return $user;
    }

    public function update_user(Request $request, $id)
    {   
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required'
        ]);
        
        $students = User::find($id);
        
        $students->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return response('ok', 200);
    }

    public function destroy_user(User $user)
    {
        $user->delete();

        return response('ok', 200);
    }
}
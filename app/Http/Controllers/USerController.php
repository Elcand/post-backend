<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::with('address')->get();
    }

    public function show($id)
    {
        return User::with('address')->findOrFail($id);
        return $user->posts;
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'address.street' => 'required|string',
            'address.suite' => 'required|string',
            'address.city' => 'required|string',
            'address.zipcode' => 'required',
        ]);

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password')
        ]);

        $user->address()->create($request->address);

        return response()->json($user->load('address'), 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only('username', 'name', 'email'));

        if ($request->has('address')) {
            $user->address()->update($request->address);
        }

        return response()->json($user->load('address'));
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }
}

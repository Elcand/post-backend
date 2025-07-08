<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return User::with('address')->get();
    }

    public function show($id)
    {
        return User::with(['address', 'posts'])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'img_profile' => 'image|mimes:jpg,jpeg,png|max:2048',
            'address.street' => 'required|string',
            'address.suite' => 'required|string',
            'address.city' => 'required|string',
            'address.zipcode' => 'required',
        ]);

        $imgpath = null;
        if ($request->hasFile('img_profile')) {
            $imgpath = $request->file('img_profile')->store('img_profile', 'public');
        }

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            "img_profile" => $imgpath,
            'password' => bcrypt('password')
        ]);

        $user->address()->create($request->address);

        return response()->json($user->load(['address', 'posts']), 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'sometimes|required|string|unique:users,username,' . $id,
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'img_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'address' => 'nullable|array',
            'address.street' => 'sometimes|required|string',
            'address.suite' => 'sometimes|required|string',
            'address.city' => 'sometimes|required|string',
            'address.zipcode' => 'sometimes|required',
        ]);

        $user = User::findOrFail($id);
        if ($request->hasFile('img_profile')) {
            if ($user->img_profile && Storage::disk('public')->exists($user->img_profile)) {
                Storage::disk('public')->delete($user->img_profile);
            }

            $path = $request->file('img_profile')->store('img_profile', 'public');
            $user->img_profile = $path;
        }

        $user->update($request->only('username', 'name', 'email'));

        if ($request->has('address')) {
            $user->address()->update($request->address);
        }

        return response()->json($user->load(['address', 'posts']));
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }
}

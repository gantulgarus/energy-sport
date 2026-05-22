<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $sports = Sport::orderBy('sort_order')->get();
        return view('admin.users.form', ['user' => new User, 'sports' => $sports]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'is_admin'           => $request->boolean('is_admin'),
            'sport_id'           => $request->boolean('is_admin') ? null : $request->sport_id,
            'email_verified_at'  => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Хэрэглэгч нэмэгдлээ.');
    }

    public function edit(User $user)
    {
        $sports = Sport::orderBy('sort_order')->get();
        return view('admin.users.form', compact('user', 'sports'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->is_admin = $request->boolean('is_admin');
        $user->sport_id = $request->boolean('is_admin') ? null : $request->sport_id;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Хэрэглэгч шинэчлэгдлээ.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Өөрийн бүртгэлийг устгах боломжгүй.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Хэрэглэгч устгагдлаа.');
    }
}

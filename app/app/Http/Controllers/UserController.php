<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function user() {
        return User::with(['groups','organisations'])->where('id',Auth::id())->first();
    }

    public function login (Request $request) {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return response(['message' => 'The user has been authenticated successfully'], 200);
        }
        return response(['message' => 'The provided credentials do not match our records.'], 401);

    }
    public function logout (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return response(['message' => 'The user has been logged out successfully'], 200);
    }
    public function register (Request $request) {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
//        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);
        return response(['message' => 'The user has been registered out successfully'], 200);
    }
}

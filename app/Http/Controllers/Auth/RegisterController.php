<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    // Display the registration form
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'], // Menyimpan peran pengguna
        ]);
    }

    // Handle an incoming registration request
    public function store(Request $request)
{
    // Log incoming request data
    \Log::info('Register request data: ', $request->all());

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 0,  // assuming 0 is the default role for regular users
    ]);

    // Log created user
    \Log::info('User created: ', $user->toArray());

    return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
}
}

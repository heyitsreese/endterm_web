<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'account_type' => 'required|in:organization,company',
            'company_name' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'account_type' => $request->account_type,
            'company_name' => $request->company_name,
            'role' => 'client', // 👈 IMPORTANT
        ]);

        return redirect('/login')->with('success', 'Account created successfully!');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Authenticate
{
   public function handle(Request $request, Closure $next)
    {
        if (!session('user_id')) {
            return redirect('/login');
        }

        return $next($request);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            session([
                'user_id' => $user->id,
                'role' => $user->role // 👈 ADD THIS
            ]);

            // 🔥 Redirect based on role
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/client/dashboard');
        }

        return back()->with('error', 'Invalid credentials');
    }
}
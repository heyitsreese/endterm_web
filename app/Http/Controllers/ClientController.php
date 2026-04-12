<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class ClientController extends Controller
{
    public function dashboard()
    {

        $userId = session('user_id');

        $client = User::where('user_id', $userId)->first(); // 👈 ADD THIS

        $orders = Order::where('user_id', $userId)
                        ->latest()
                        ->get();

        return view('client.dashboard', compact('orders', 'client'));
    }
}

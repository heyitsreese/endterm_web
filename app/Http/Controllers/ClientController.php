<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class ClientController extends Controller
{
    public function dashboard()
    {
        $orders = Order::where('user_id', session('user_id'))
                    ->latest()
                    ->get();

        return view('client.dashboard', [
            'orders'      => $orders,
            'totalOrders' => $orders->count(),
            'inProgress'  => $orders->whereIn('status', ['in_progress', 'pending'])->count(),
            'completed'   => $orders->where('status', 'completed')->count(),
            'totalSpent'  => $orders->where('status', 'completed')->sum('total_amount'),
        ]);
    }
}

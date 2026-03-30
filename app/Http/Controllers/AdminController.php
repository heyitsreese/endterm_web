<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\User;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $admin = User::where('user_id', session('user_id'))->first();

        $totalOrders = Order::count();
        $revenue = Order::sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        $activeClients = Order::distinct('email')->count('email');

        $recentOrders = Order::with(['orderDetails.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'revenue',
            'pendingOrders',
            'activeClients',
            'recentOrders',
            'admin' // ✅ ADD THIS
        ));
    }
}

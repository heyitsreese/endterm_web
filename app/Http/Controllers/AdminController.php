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

    public function orders()
    {
        $admin = User::where('user_id', session('user_id'))->first();

        $orders = Order::with(['orderDetails.product'])->latest()->get();

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $inProgressOrders = Order::where('status', 'in_progress')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        return view('admin.orders', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'inProgressOrders',
            'deliveredOrders',
            'cancelledOrders',
            'admin'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|string'
        ]);

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated!');
    }

    public function settings()
    {
        $admin = User::where('user_id', session('user_id'))->first();

        // Also fetch totalOrders to show badge in the sidebar
        $totalOrders = Order::count();

        return view('admin.settings', compact('admin', 'totalOrders'));
    }
}

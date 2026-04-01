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

    public function index()
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

    public function show($id)
    {
        $order = \App\Models\Order::with('orderDetails.product')
                    ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = \App\Models\Order::with('orderDetails.product')
                    ->findOrFail($id);

        return view('admin.orders.edit', compact('order'));
    }

    public function destroy($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->delete();

        return redirect()->back()->with('success', 'Order deleted successfully');
    }

    public function update(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);

        $basePrices = [
            'Business Cards' => 30,
            'Flyers' => 50,
            'Posters' => 20,
            'Brochures' => 70,
            'Banners' => 150,
            'Booklets' => 130,
        ];

        $total = 0;

        foreach ($request->details as $detailData) {

            $detail = \App\Models\OrderDetail::where('order_details_id', $detailData['id'])->first();

            if ($detail) {

                $productName = $detail->product->product_name;
                $quantity = $detailData['quantity'];
                $color = $detailData['color'] ?? 'Black & White';
                $quality = $detailData['paper_quality'] ?? 'Standard';

                $basePrice = $basePrices[$productName] ?? 0;

                $discountRate = 0;
                if ($quantity >= 500) {
                    $discountRate = 0.20;
                } elseif ($quantity >= 100) {
                    $discountRate = 0.10;
                }

                $discountedPrice = $basePrice - ($basePrice * $discountRate);

                $colorFee = $color === 'Full Color' ? 10 : 0;
                $qualityFees = [
                    'Matte' => 0,
                    'Glossy' => -5,
                    'Premium' => 20,
                ];

                $qualityFee = $qualityFees[$quality] ?? 0;

                $finalPricePerUnit = $discountedPrice + $colorFee + $qualityFee;

                $subtotal = $finalPricePerUnit * $quantity;

                $total += $subtotal;

                $detail->update([
                    'quantity' => $quantity,
                    'color' => $color,
                    'paper_quality' => $quality,
                    'size' => $detailData['size'],
                    'special_instruction' => $detailData['instructions'],
                ]);
            }
        }

        // ✅ ONLY THIS UPDATE
        $order->update([
            'customer_name' => $request->customer_name,
            'status' => $request->status,
            'total_amount' => $total,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Updated!');
    }
}

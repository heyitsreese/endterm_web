<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class ClientController extends Controller
{
    public function dashboard()
    {
        $orders = Order::with('orderDetails.product')
                    ->where('user_id', session('user_id'))
                    ->latest()
                    ->get();

        return view('client.dashboard', [
            'orders'      => $orders,
            'totalOrders' => $orders->count(),
            'inProgress'  => $orders->whereIn('status', ['in_progress', 'pending'])->count(),
            'completed'   => $orders->whereIn('status', ['delivered', 'picked_up', 'completed'])->count(),
            'totalSpent'  => $orders->whereIn('status', ['delivered', 'picked_up', 'completed'])->sum('total_amount'),
        ]);
    }

    public function showOrder($id)
    {
        $order = Order::with('orderDetails.product')
                    ->where('user_id', session('user_id'))
                    ->where('order_id', $id)
                    ->firstOrFail();

        return response()->json([
            'order_id'      => $order->order_id,
            'code'          => 'ORD-' . str_pad($order->order_id, 3, '0', STR_PAD_LEFT),
            'customer_name' => $order->customer_name,
            'email'         => $order->email,
            'phone'         => $order->phone_number,
            'status'        => $order->status,
            'delivery_type' => $order->delivery_type,
            'total_amount'  => $order->total_amount,
            'order_date'    => $order->order_date,
            'created_at'    => $order->created_at->format('Y-m-d H:i'),
            'decline_reason'=> $order->decline_reason,
            'details'       => $order->orderDetails->map(function ($d) {
                return [
                    'product_name' => $d->product->product_name ?? 'N/A',
                    'quantity'     => $d->quantity,
                    'size'         => $d->size,
                    'color'        => $d->color,
                    'paper_quality'=> $d->paper_quality,
                    'instruction'  => $d->special_instruction,
                    'file_path'    => $d->file_path,
                ];
            }),
        ]);
    }

    public function downloadFile($id, $detailIndex)
    {
        $order = Order::with('orderDetails')
                    ->where('user_id', session('user_id'))
                    ->where('order_id', $id)
                    ->firstOrFail();

        $detail = $order->orderDetails->values()->get($detailIndex);

        if (!$detail || !$detail->file_path) {
            abort(404, 'File not found');
        }

        $path = storage_path('app/public/' . $detail->file_path);

        if (!file_exists($path)) {
            abort(404, 'File not found on disk');
        }

        return response()->download($path);
    }

    public function track(Request $request)
    {
        $order = null;

        if ($request->has('order_id')) {
            $input = $request->order_id;

            if (str_starts_with($input, 'ORD-')) {
                $input = (int) str_replace('ORD-', '', $input);
            }

            $order = Order::where('order_id', $input)->first();
        }

        return view('client.track', compact('order'));
    }
}
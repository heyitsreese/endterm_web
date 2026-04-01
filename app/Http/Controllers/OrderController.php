<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Order;

class OrderController extends Controller
{
    public function step4(Request $request)
    {
        // make sure files exist
        if ($request->hasFile('files')) {

            foreach ($request->file('files') as $file) {

                // generate unique name
                $filename = time() . '_' . $file->getClientOriginalName();

                // store file
                $path = $file->storeAs('uploads', $filename, 'public');

                // save to database
                OrderDetail::create([
                    'order_id' => session('order_id'), // make sure this exists
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('order.step4')
            ->with('success', 'Files uploaded!');
    }

public function track(Request $request)
    {
        $order = null;

        if ($request->has('order_id')) {

            $input = $request->order_id;

            // 🔥 Convert ORD-005 → 5
            if (str_starts_with($input, 'ORD-')) {
                $input = (int) str_replace('ORD-', '', $input);
            }

            $order = Order::where('order_id', $input)->first();
        }

        return view('track', compact('order'));
    }
}

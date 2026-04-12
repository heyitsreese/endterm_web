<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function step1Store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        session([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('order.step2');
    }
    
    public function step2Store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'paper_size' => 'required',
            'color' => 'required',
            'paper_quality' => 'required',
        ]);

        session([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'paper_size' => $request->paper_size,
            'color' => $request->color,
            'paper_quality' => $request->paper_quality,
            'instructions' => $request->instructions,
        ]);

        return redirect()->route('order.step3');
    }

    public function step2()
    {
        $products = Product::where('status', 'active')->get();

        return view('order-step2', compact('products'));
    }

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

    public function index(Request $request)
    {
        if ($request->product_id) {
            session(['product_id' => $request->product_id]);
        }

        return view('order-page');
    }

    public function store(Request $request)
        {
            $product = Product::find(session('product_id'));

            if (!$product) {
                return back()->with('error', 'Product not found.');
            }

            $quantity = session('quantity');
            $paperSize = session('paper_size');
            $color = session('color');
            $paperQuality = session('paper_quality');
            $instructions = session('instructions');

            $name = session('name');
            $email = session('email');
            $phone = session('phone');

            $deliveryType = $request->delivery_type ?? 'pickup';

            // ✅ PRICE
            $basePrice = $product->base_price;

            $discountRate = 0;
            if ($quantity >= 500) {
                $discountRate = 0.20;
            } elseif ($quantity >= 100) {
                $discountRate = 0.10;
            }

            $discountedPrice = $basePrice - ($basePrice * $discountRate);
            $colorFee = $color === 'Full Color' ? 10 : 0;
            $qualityFee = $paperQuality === 'Premium' ? 20 : 0;

            $pricePerUnit = $discountedPrice + $colorFee + $qualityFee;
            $total = $pricePerUnit * $quantity;

            $token = Str::random(40);

            // ✅ ORDER
            $order = Order::create([
                'customer_name' => $name,
                'email' => $email,
                'phone_number' => $phone ?? 'N/A',
                'status' => 'pending',
                'total_amount' => $total,
                'order_date' => now(),
                'order_token' => $token,
                'delivery_type' => $deliveryType,
            ]);

            // ✅ ORDER DETAILS
            foreach (session('files', []) as $file) {

                $filePath = is_array($file) ? ($file['path'] ?? null) : $file;

                if (!$filePath) continue;

                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'quantity' => $quantity,
                    'size' => $paperSize,
                    'color' => $color,
                    'paper_quality' => $paperQuality,
                    'special_instruction' => $instructions,
                    'file_path' => $filePath,
                ]);
            }

            $code = 'ORD-' . str_pad($order->order_id, 4, '0', STR_PAD_LEFT);

            // ✅ CLEAR SESSION
            session()->forget([
                'product_id',
                'quantity',
                'paper_size',
                'color',
                'paper_quality',
                'instructions',
                'files',
                'name',
                'email',
                'phone'
            ]);

            return view('order-success', [
                'code' => $code,
                'email' => $email
            ]);
        }
}

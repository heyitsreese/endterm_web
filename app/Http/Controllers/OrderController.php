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
        //dd(session()->all());
        // 🔥 GET DATA (request OR session fallback)
        $productId = $request->product_id ?? session('product_id');
        $quantity = $request->quantity ?? session('quantity');
        $paperSize = $request->paper_size ?? session('paper_size');
        $color = $request->color ?? session('color');
        $paperQuality = $request->paper_quality ?? session('paper_quality');
        $instructions = $request->instructions ?? session('instructions');
        $userId = session('user_id');

        $name = session('user_id')
            ? session('user_name')
            : ($request->customer_name ?? session('name'));

        $email = session('user_id')
            ? session('user_email')
            : ($request->email ?? session('email'));

        $phone = $request->phone ?? session('phone');

        $deliveryType = $request->delivery_type ?? session('delivery_type') ?? 'pickup';

        $request->validate([
            'phone' => ['required', 'regex:/^\+63\s9\d{2}\s\d{3}\s\d{4}$/']
        ]);

        // 🔥 PRODUCT
        $product = Product::find($productId);

        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        // =========================
        // PRICE CALCULATION
        // =========================
        $basePrice = $product->base_price;

        $discountRate = 0;
        if ($quantity >= 500) $discountRate = 0.20;
        elseif ($quantity >= 100) $discountRate = 0.10;

        $discountedPrice = $basePrice - ($basePrice * $discountRate);
        $colorFee = $color === 'Full Color' ? 10 : 0;
        $qualityFee = $paperQuality === 'Premium' ? 20 : 0;

        $pricePerUnit = $discountedPrice + $colorFee + $qualityFee;
        $total = $pricePerUnit * $quantity;

        // =========================
        // CREATE ORDER
        // =========================
        $order = Order::create([
            'user_id' => $userId ? $userId : null,
            'customer_name' => $name,
            'email' => $email,
            'phone_number' => $phone,
            'status' => 'pending',
            'order_token' => Str::random(10),
            'total_amount' => $total,
            'order_date' => now(),
        ]);

        // =========================
        // ORDER DETAILS
        // =========================
        $files = $request->file('files') ?? session('files', []);

        foreach ($files as $file) {

            // 🔥 HANDLE FILE (both cases)
            if (is_object($file)) {
                $filePath = $file->store('orders', 'public');
            } else {
                $filePath = is_array($file) ? ($file['path'] ?? null) : $file;
            }

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

        // =========================
        // CLEAR SESSION (ONLY IF USED)
        // =========================
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

        // =========================
        // SUCCESS
        // =========================
        $code = 'ORD-' . str_pad($order->order_id, 4, '0', STR_PAD_LEFT);

        if (session('user_id')) {
            return redirect()->route('client.order.success', $order->order_id);
        }

        return redirect()->route('order.success', $order->order_token);
    }
}

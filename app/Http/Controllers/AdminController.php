<?php // AdminController.php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $admin = User::where('user_id', session('user_id'))->first();
        $totalOrders = Order::count();
        $revenue = Order::sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();

        $unreadOrdersCount = Order::where('is_read', false)->count();
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
            'admin',
            'unreadOrdersCount'
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
        $unreadOrdersCount = Order::where('is_read', false)->count();
        $recentOrders = Order::with(['orderDetails.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.orders', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'inProgressOrders',
            'deliveredOrders',
            'cancelledOrders',
            'admin',
            'unreadOrdersCount',
            'recentOrders'
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

    public function products(Request $request)
    {
        $query = Product::query();

        if ($request->category && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $products = $query->get();

        $activeProducts = Product::where('status', 'active')->count();
        $categoryList = Product::select('category')->distinct()->pluck('category');
        $categoryCount = $categoryList->count();

        $unreadOrdersCount = Order::where('is_read', false)->count();
        $totalOrders = Order::count();
        $recentOrders = Order::with(['orderDetails.product'])
            ->latest()
            ->take(5)
            ->get();


        return view('admin.products', compact(
            'products',
            'activeProducts',
            'categoryList',
            'categoryCount',
            'recentOrders',
            'totalOrders',
            'unreadOrdersCount',
        ));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'category' => 'required|string',
            'min_quantity' => 'required|integer',
            'turnaround' => 'required|string',
            'status' => 'required|string'
        ]);

       if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = null;
        }

        Product::create([
            'product_name' => $request->product_name,
            'base_price' => $request->base_price,
            'category' => $request->category,
            'min_quantity' => $request->min_quantity,
            'turnaround' => $request->turnaround,
            'status' => $request->status,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Product added successfully!');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'category' => 'required|string',
            'min_quantity' => 'required|integer',
            'turnaround' => 'required|string',
            'status' => 'required|string'
        ]);

        if ($request->hasFile('image')) {

            // delete old image (optional but good)
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            $imagePath = $product->image;
        }

        $product->update([
            'product_name' => $request->product_name,
            'base_price' => $request->base_price,
            'category' => $request->category,
            'min_quantity' => $request->min_quantity,
            'turnaround' => $request->turnaround,
            'status' => $request->status,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Product updated!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Product deleted successfully!');
    }

    public function settings()
    {
        $admin = User::where('user_id', session('user_id'))->first();

        $totalOrders = Order::count(); // ✅ ADD THIS

        $recentOrders = Order::with(['orderDetails.product'])
            ->latest()
            ->take(5)
            ->get();
        $unreadOrdersCount = Order::where('is_read', false)->count();

        return view('admin.settings', compact(
            'admin',
            'totalOrders',
            'recentOrders',
            'unreadOrdersCount'
        ));
    }

    public function updateSettings(Request $request)
    {
        $admin = User::where('user_id', session('user_id'))->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Settings updated successfully!');
    }

    public function markAsRead($id)
    {
        $order = Order::where('order_id', $id)->firstOrFail();

        $order->is_read = true;
        $order->save();

        return response()->json(['success' => true]);
    }
}

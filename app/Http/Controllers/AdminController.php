<?php // AdminController.php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Product;
use App\Services\PriceService;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $admin = User::where('user_id', session('user_id'))->first();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalOrders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $revenue = Order::whereIn('status', ['delivered', 'picked_up'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');
            
        $pendingOrders = Order::where('status', 'pending')->count();

        $unreadOrdersCount = Order::where('status', 'pending')
            ->where('is_read', false)
            ->count();
        $activeClients = Order::distinct('email')->count('email');

        $recentOrders = Order::with(['orderDetails.product'])
        ->latest()
        ->take(5)
        ->get();

        $pendingNotifications = Order::with(['orderDetails.product'])
        ->where('status', 'pending')
        ->where('is_read', false)
        ->latest()
        ->take(5)
        ->get();

        $last7Days = collect(range(6, 0))->map(fn($i) => Carbon::now()->subDays($i)->toDateString());
 
        $dailyStats = Order::whereIn('status', ['delivered', 'picked_up'])
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total_sales, COUNT(*) as order_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        
        // Build a complete 7-day array (fill 0 for missing days)
        $dailySalesChart = $last7Days->map(fn($date) => [
            'date'        => $date,
            'label'       => Carbon::parse($date)->format('M d'),
            'total_sales' => $dailyStats->get($date)?->total_sales ?? 0,
            'order_count' => $dailyStats->get($date)?->order_count ?? 0,
        ]);
        
        // REPORTS: Most requested services (top 5 products by quantity)
        $topServices = OrderDetail::with('product')
            ->whereHas('order', fn($q) => $q->whereIn('status', ['delivered', 'picked_up']))
            ->selectRaw('product_id, SUM(quantity) as total_qty, COUNT(*) as order_count')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'revenue',
            'pendingOrders',
            'activeClients',
            'pendingNotifications',
            'recentOrders',
            'admin',
            'unreadOrdersCount',
            'dailySalesChart',
            'topServices',
        ));
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $id = $search ? (int) preg_replace('/[^0-9]/', '', $search) : null;

        $admin = User::where('user_id', session('user_id'))->first();

$orders = Order::with(['orderDetails.product'])
    ->when($status && $status != 'all', function ($q) use ($status) {
        $q->where('status', $status);
    })
    ->when($search, function ($q) use ($search, $id) {
        $q->where(function ($q2) use ($search, $id) {
            if ($id) {
                $q2->where('order_id', $id);
            }

            $q2->orWhere('customer_name', 'like', "%{$search}%");
        });
    })
    ->latest()
    ->get();

        $declinedOrders = Order::with(['orderDetails.product'])
            ->where('status', 'declined')
            ->when($search, function ($q) use ($search, $id) {
                $q->where(function ($q2) use ($search, $id) {
                    if ($id) {
                        $q2->where('order_id', $id);
                    }
                    $q2->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        $pickupStatuses = [
            'pending',
            'in_progress',
            'ready_for_pickup',
            'picked_up',
            'declined'
        ];

        $deliveryStatuses = [
            'pending',
            'in_progress',
            'out_for_delivery',
            'delivered',
            'declined'
        ];

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $inProgressOrders = Order::where('status', 'in_progress')->count();
        $completedOrders = Order::with(['orderDetails.product'])
            ->whereIn('status', ['delivered', 'picked_up'])
            ->when($search, function ($q) use ($search, $id) {
                $q->where(function ($q2) use ($search, $id) {
                    if ($id) {
                        $q2->where('order_id', $id);
                    }
                    $q2->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();
        $declinedOrdersCount = Order::where('status', 'declined')->count();
        $unreadOrdersCount = Order::where('status', 'pending')
            ->where('is_read', false)
            ->count();
        $recentOrders = Order::with(['orderDetails.product'])
            ->latest()
            ->take(5)
            ->get();
        $pendingNotifications = Order::with(['orderDetails.product'])
            ->where('status', 'pending')
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.orders', compact(
            'orders',
            'totalOrders',
            'pendingNotifications',
            'pendingOrders',
            'inProgressOrders',
            'completedOrders',
            'declinedOrdersCount',
            'declinedOrders',
            'admin',
            'unreadOrdersCount',
            'recentOrders',
            'pickupStatuses',
            'deliveryStatuses',
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('order_id', $id)->firstOrFail();

        $request->validate([
            'status' => 'required|string'
        ]);

        $order->status = $request->status;

        if ($request->status === 'declined') {
            $order->decline_reason = $request->decline_reason;
        }

        $order->save();

        return response()->json(['success' => true]);
        dd($request->all());
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
        $order = Order::findOrFail($id);

        $total = 0;

        foreach ($request->details as $detailData) {

            $detail = OrderDetail::where('order_details_id', $detailData['id'])->first();

            if ($detail) {

                $quantity = $detailData['quantity'];
                $color = $detailData['color'] ?? 'Black & White';
                $quality = $detailData['paper_quality'] ?? 'Matte';

                // 💰 PRICE CALCULATION (🔥 USING SERVICE)
                $result = PriceService::calculate(
                    $detail->product->base_price,
                    $quantity,
                    $color,
                    $quality
                );

                $subtotal = $result['subtotal'];
                $total += $subtotal;

                // 🧾 UPDATE DETAIL
                $detail->update([
                    'quantity' => $quantity,
                    'color' => $color,
                    'paper_quality' => $quality,
                    'size' => $detailData['size'],
                    'special_instruction' => $detailData['instructions'],
                    'subtotal' => $subtotal
                ]);
            }
        }

        // 🧾 UPDATE ORDER
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
        $pendingOrders = Order::where('status', 'pending')->count();
        $activeProducts = Product::where('status', 'active')->count();
        $categoryList = Product::select('category')->distinct()->pluck('category');
        $categoryCount = $categoryList->count();

        $unreadOrdersCount = Order::where('status', 'pending')
            ->where('is_read', false)
            ->count();
        $totalOrders = Order::count();
        $recentOrders = Order::with(['orderDetails.product'])
            ->latest()
            ->take(5)
            ->get();
        $pendingNotifications = Order::with(['orderDetails.product'])
            ->where('status', 'pending')
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();


        return view('admin.products', compact(
            'products',
            'pendingNotifications',
            'activeProducts',
            'categoryList',
            'categoryCount',
            'recentOrders',
            'totalOrders',
            'pendingOrders',
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

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        $recentOrders = Order::with(['orderDetails.product'])
            ->latest()
            ->take(5)
            ->get();

        $pendingNotifications = Order::with(['orderDetails.product'])
            ->where('status', 'pending')
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        $unreadOrdersCount = Order::where('status', 'pending')
            ->where('is_read', false)
            ->count();

        return view('admin.settings', compact(
            'admin',
            'totalOrders',
            'recentOrders',
            'pendingOrders',
            'unreadOrdersCount',
            'pendingNotifications'
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

    public function create()
    {
        $products = \App\Models\Product::where('status', 'active')->get();

        $recentOrders = Order::with(['orderDetails.product'])
            ->latest()
            ->take(5)
            ->get();

        $pendingNotifications = Order::with(['orderDetails.product'])
            ->where('status', 'pending')
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        $unreadOrdersCount = Order::where('status', 'pending')
            ->where('is_read', false)
            ->count();

        return view('admin.orders.create', compact(
            'products',
            'recentOrders',
            'pendingNotifications',
            'unreadOrdersCount',
            ));
    }

    public function store(Request $request)
    {

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^\+63[\s]?\d{3}[\s]?\d{3}[\s]?\d{4}$/'],
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'paper_size' => 'required',
            'color' => 'required',
            'paper_quality' => 'required',
            'files.*' => 'nullable|file|max:51200' 
        ]);

        // 📂 HANDLE FILES
        $paths = [];

        //dd($request->hasFile('files'), $request->allFiles(), $paths);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $stored = $file->store('uploads', 'public');

                $paths[] = [
                    'path' => $stored,
                    'name' => $file->getClientOriginalName()
                ];
            }
        }

        // 📦 PRODUCT
        $product = Product::findOrFail($request->product_id);

        $qty = $request->quantity;
        $color = $request->color;
        $quality = $request->paper_quality;

        // 💰 PRICE CALCULATION (🔥 USING SERVICE)
        $result = PriceService::calculate(
            $product->base_price,
            $qty,
            $color,
            $quality
        );

        $total = $result['subtotal'];

        // 🧾 CREATE ORDER
        $order = Order::create([
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'delivery_type' => $request->delivery_type ?? 'pickup',
            'status' => 'pending',
            'order_token' => Str::random(10),
            'total_amount' => $total,
            'order_date' => now(),
        ]);

        $orderCode = 'ORD-' . str_pad($order->order_id, 4, '0', STR_PAD_LEFT);

        // 📄 CREATE ORDER DETAIL
        OrderDetail::create([
            'order_id' => $order->order_id,
            'product_id' => $product->product_id,
            'quantity' => $qty,
            'size' => $request->paper_size,
            'color' => $color,
            'paper_quality' => $quality,
            'file_path' => $paths[0]['path'] ?? null,
            'special_instruction' => $request->instructions
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order created successfully!');
    }

public function clients(Request $request)
{
    $type = $request->get('type');

    // ======================
    // REGISTERED CLIENTS
    // ======================
    $registeredClients = User::where('role', 'client') // make sure lowercase matches DB
        ->withCount('orders')
        ->withSum('orders', 'total_amount')
        ->orderBy('created_at', 'desc')
        ->get();

    // ======================
    // WALK-IN CLIENTS
    // ======================
$walkInClients = Order::selectRaw('
    customer_name,
    email,
    phone_number,
    COUNT(*) as orders_count,
    COALESCE(SUM(total_amount), 0) as total_spent,
    MAX(created_at) as last_order,
    MAX(order_id) as latest_order_id
')
->whereNull('user_id')
->groupBy('customer_name', 'email', 'phone_number')
->orderByDesc('last_order')
->get();

    // ======================
    // 🔥 FIXED STATS
    // ======================

    // TOTAL CLIENTS
    $totalClients = $registeredClients->count() + $walkInClients->count();

    // ACTIVE THIS MONTH (clients who made orders this month)
    $activeClients = Order::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->distinct('email')
        ->count('email');

    // NEW CLIENTS THIS MONTH (registered only)
    $newClients = User::where('role', 'client')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    // AVG ORDER VALUE
    $avgOrderValue = Order::avg('total_amount') ?? 0;

    return view('admin.clients', compact(
        'registeredClients',
        'walkInClients',
        'totalClients',
        'activeClients',
        'newClients',
        'avgOrderValue'
    ));
}
    public function showClient($id)
{
    $client = User::where('user_id', $id)
        ->where('role', 'client')
        ->firstOrFail();

    return view('admin.client-view', compact('client'));
}

public function editClient($id)
{
    $client = User::where('user_id', $id)
        ->where('role', 'client')
        ->firstOrFail();

    return view('admin.client-edit', compact('client'));
}

public function deleteClient($id)
{
    $client = User::where('user_id', $id)
        ->where('role', 'client')
        ->firstOrFail();

    $client->delete();

    return redirect()->route('admin.clients')
        ->with('success', 'Client deleted successfully.');
}

public function updateClient(Request $request, $id)
{
    $client = User::where('user_id', $id)
        ->where('role', 'client')
        ->firstOrFail();

    $client->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
    ]);

    return redirect()->route('admin.clients')
        ->with('success', 'Client updated successfully.');
}
public function export(Request $request)
{
    $type = $request->type;

    if ($type == 'registered') {
        $clients = Client::where('type', 'registered')->get();
    } 
    elseif ($type == 'walkin') {
        $clients = Client::where('type', 'walkin')->get();
    } 
    else {
        $clients = Client::all();
    }

    $filename = "clients_export.csv";

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ];

    $callback = function() use ($clients) {
        $file = fopen('php://output', 'w');

        fputcsv($file, ['Name', 'Email', 'Orders', 'Total Spent']);

        foreach ($clients as $client) {
            fputcsv($file, [
                $client->name,
                $client->email,
                $client->orders_count,
                $client->total_spent
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
public function exportClients()
{
    $clients = User::where('role', 'client')->get();

    $filename = 'clients.csv';

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate",
        "Expires" => "0"
    ];

    $callback = function () use ($clients) {
        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'Client Name',
            'Email',
            'Phone',
            'Join Date'
        ]);

        foreach ($clients as $client) {
            fputcsv($file, [
                $client->name,
                $client->email,
                $client->phone ?? '',
                $client->created_at
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
public function filterClients(Request $request)
{
    $type = $request->input('type');

    $clients = User::where('role', 'client')
        ->when($type == 'walkin', function ($q) {
            $q->whereNull('email');
        })
        ->when($type == 'registered', function ($q) {
            $q->whereNotNull('email');
        })
        ->get();

    return view('admin.clients', compact('clients'));
}
}

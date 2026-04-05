<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    $products = Product::where('status', 'active')->latest()->get();
    return view('welcome', compact('products'));
});

Route::get('/track', [OrderController::class, 'track'])->name('track');

// LOGIN
Route::get('/login', function () {
    return view('auth.login');
});

// LOGIN 
Route::post('/login', function (Request $request) {

    $user = \App\Models\User::where('email', $request->email)->first();

    // DEBUG (optional)
    // dd($user);

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Invalid credentials');
    }

    // ✅ IMPORTANT FIX HERE
    session()->put('user_id', $user->user_id);
    session()->put('is_admin', true);

    return redirect('/admin/dashboard');

})->name('login');

// ADMIN

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');
    
    Route::get('/products', [AdminController::class, 'products'])
        ->name('products');
    
    Route::post('/products', [AdminController::class, 'storeProduct'])
        ->name('products.store');

    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])
        ->name('products.update');

    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])
        ->name('products.delete');

    Route::get('/orders', [AdminController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{id}', [AdminController::class, 'show'])
        ->name('orders.show');

    Route::get('/orders/{id}/edit', [AdminController::class, 'edit'])
        ->name('orders.edit');

    Route::put('/orders/{id}', [AdminController::class, 'update'])
        ->name('orders.update');

    Route::delete('/orders/{id}', [AdminController::class, 'destroy'])
        ->name('orders.destroy');

    Route::put('/orders/{id}/status', [AdminController::class, 'updateStatus'])
        ->name('orders.updateStatus');

    Route::post('/orders/{id}/read', [AdminController::class, 'markAsRead'])
        ->name('orders.read');

    Route::get('/settings', [AdminController::class, 'settings'])
        ->name('admin.settings');

    Route::post('/settings/update', [AdminController::class, 'updateSettings'])
        ->name('settings.update');
});

// LOGOUT
Route::post('/logout', function () {
    session()->flush(); // clear session
    return redirect('/login');
})->name('logout');

// ORDERS
Route::get('/order', function () {
    return view('order-page');
})->name('order');

Route::get('/order/step-2', function () {
    return view('order-step2');
})->name('order.step2');

Route::post('/order/step-2', function (Request $request) {

    session([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
    ]);

    return redirect()->route('order.step2');
});

Route::get('/order/step-3', function () {
    return view('order-step3');
})->name('order.step3');

Route::post('/order/step-3', function (Request $request) {

    // ✅ handle custom size
    $paperSize = $request->paper_size === 'Custom'
        ? $request->custom_size
        : $request->paper_size;

    session([
        'service' => $request->service,
        'quantity' => $request->quantity,
        'paper_size' => $paperSize,
        'color' => $request->color,
        'paper_quality' => $request->paper_quality,
        'instructions' => $request->instructions,
    ]);

    return redirect()->route('order.step3');
});

Route::get('/order/step-4', function () {
    return view('order-step4');
})->name('order.step4');

Route::post('/order/step-4', function (Request $request) {

    $paths = [];

    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            $paths[] = $file->store('uploads', 'public');
        }
    }

    session([
        'files' => $paths
    ]);

    return redirect()->route('order.step4');
});

Route::post('/order/store', function () {

    $service = session('service');
    $quantity = session('quantity');
    $paperSize = session('paper_size');
    $color = session('color');
    $paperQuality = session('paper_quality');
    $instructions = session('instructions');

    $name = session('name');
    $email = session('email');
    $phone = session('phone');

    // ✅ PRICE CALCULATION
    $basePrices = [
        'Business Cards' => 30,
        'Flyers' => 50,
        'Posters' => 20,
        'Brochures' => 70,
        'Banners' => 150,
        'Booklets' => 130,
    ];

    $basePrice = $basePrices[$service] ?? 0;

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

    // ✅ PRODUCT
    $product = Product::whereRaw('LOWER(product_name) = ?', [strtolower($service)])->first();

    if (!$product) {
        return back()->with('error', 'Product not found.');
    }

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
    ]);

    // ✅ ORDER DETAILS
    $files = session('files', []);

    foreach ($files as $filePath) {

        OrderDetail::create([
            'order_id' => $order->order_id, // ✅ NOW VALID
            'product_id' => $product->product_id,
            'quantity' => $quantity,
            'size' => $paperSize,
            'color' => $color,
            'paper_quality' => $paperQuality,
            'special_instruction' => $instructions,
            'file_path' => $filePath,
        ]);
    }

    $orderCode = 'ORD-' . str_pad($order->order_id, 4, '0', STR_PAD_LEFT);

    session()->forget([
        'service',
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
    return redirect()->route('order.success', ['token' => $token]);
})->name('order.store');

Route::get('/order/success/{token}', function ($token) {

    $order = Order::where('order_token', $token)->first();

    if (!$order) {
        abort(404);
    }

    $code = 'ORD-' . str_pad($order->order_id, 4, '0', STR_PAD_LEFT);

    return view('order-success', [
        'code' => $code,
        'email' => $order->email
    ]);

})->name('order.success');


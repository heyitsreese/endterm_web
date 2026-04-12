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

Route::middleware('admin.session')->prefix('admin')->name('admin.')->group(function () {

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

    Route::get('/orders/create', [AdminController::class, 'create'])
        ->name('orders.create');

    Route::post('/orders', [AdminController::class, 'store'])
        ->name('orders.store');

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
Route::get('/order', function (Request $request) {

    session()->forget('files');

    session()->forget([
        'files',
        'service',
        'quantity',
        'paper_size',
        'color',
        'paper_quality',
        'instructions',
    ]);

    $productId = $request->product_id;

    return view('order-page', compact('productId'));
})->name('order');

Route::post('/order/step-1', [OrderController::class, 'step1Store'])
    ->name('order.step1.store');

Route::get('/order/step-2', [OrderController::class, 'step2'])
    ->name('order.step2');

Route::post('/order/step-2', [OrderController::class, 'step2Store'])
    ->name('order.step2.store');

Route::get('/order/step-3', function () {
    return view('order-step3');
})->name('order.step3');

Route::post('/order/step-3', function (Request $request) {

    // ✅ handle custom size
    $paperSize = $request->paper_size === 'Custom'
        ? $request->custom_size
        : $request->paper_size;

    session([
        'product_id' => $request->product_id,
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

    $paths = session('files', []);

    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {

            $storedPath = $file->store('uploads', 'public');

            $paths[] = [
                'path' => $storedPath,
                'name' => $file->getClientOriginalName()
            ];
        }
    }

    session([
        'files' => $paths
    ]);

    return redirect()->route('order.step4');
});

Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');

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

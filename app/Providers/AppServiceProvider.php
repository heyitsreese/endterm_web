<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Order;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('admin.layouts.app', function ($view) {

            $admin = User::where('user_id', session('user_id'))->first();

            $totalOrders = Order::count(); // 👈 IMPORTANT (match dashboard)

            $view->with([
                'admin' => $admin,
                'totalOrders' => $totalOrders
            ]);
        });
    }
}

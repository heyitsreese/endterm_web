<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Order;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('admin.layouts.app', function ($view) {
            $view->with([
                'unreadOrdersCount'    => \App\Models\Order::where('status', 'pending')
                                            ->where('is_read', false)
                                            ->count(),
                'pendingNotifications' => \App\Models\Order::with(['orderDetails.product'])
                                            ->where('status', 'pending')
                                            ->where('is_read', false)
                                            ->latest()
                                            ->take(5)
                                            ->get(),
                'pendingOrders'        => \App\Models\Order::where('status', 'pending')
                                            ->count(),
            ]);
        });
    }
}
@extends('admin.layouts.app')

@section('content')

<!-- HEADER -->
@section('header')

<div class="flex items-center justify-between gap-4">
    
    <!-- LEFT SIDE -->
    <div class="flex items-center gap-3">

        <!-- 🍔 HAMBURGER BUTTON -->
        <button onclick="toggleSidebar()" class="md:hidden text-gray-600">
            <i data-feather="menu"></i>
        </button>

        <!-- TITLE -->
        <div>
            <h1 class="text-3xl font-semibold">Dashboard</h1>
            <p class="text-sm text-gray-500">
                Welcome back, {{ $admin->name ?? 'Admin' }}! Here's what's happening today.
            </p>
        </div>

    </div>

    <!-- SEARCH -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i data-feather="search" class="w-4 h-4"></i>
            </div>
            <input 
                type="text"
                placeholder="Search..."
                class="w-full pl-10 pr-4 py-2 rounded-xl bg-gray-50 border border-transparent
                       focus:outline-none focus:ring-2 focus:ring-pink-300 focus:bg-white
                       text-sm text-gray-600 placeholder-gray-400 font-medium">
        </div>
        <div class="relative">
            <button onclick="toggleNotif()" class="text-gray-500 hover:text-gray-700 transition">
                <i data-feather="bell" class="w-5 h-5"></i>

                @if($unreadOrdersCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                        {{ $unreadOrdersCount }}
                    </span>
                @endif
            </button>

            <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-xl p-4 z-50">
                <h3 class="font-semibold mb-2 text-sm">Recent Orders</h3>

                @forelse($recentOrders as $order)
                    <div data-id="{{ $order->order_id }}"
                        onclick="markAsRead(this.dataset.id)"
                        class="border-b py-2 text-xs text-gray-600 cursor-pointer hover:bg-gray-50 rounded px-2">
                        
                        Order #{{ $order->order_id }} - {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </div>
                @empty
                    <p class="text-gray-400 text-xs">No recent orders</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

<!-- CARDS -->
<div class="grid md:grid-cols-4 gap-4 mb-6">

    <!-- Total Orders -->
    <div class="bg-white p-5 rounded-xl shadow flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Total Orders</p>
            <h2 class="text-2xl font-bold">{{ $totalOrders }}</h2>
        </div>
        <div class="bg-blue-500 text-white p-3 rounded-lg">
            📦
        </div>
    </div>

    <!-- Revenue -->
    <div class="bg-white p-5 rounded-xl shadow flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Revenue</p>
            <h2 class="text-2xl font-bold">₱{{ number_format($revenue, 2) }}</h2>
        </div>
        <div class="bg-green-500 text-white p-3 rounded-lg">
            💰
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-white p-5 rounded-xl shadow flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Pending Orders</p>
            <h2 class="text-2xl font-bold">{{ $pendingOrders }}</h2>
        </div>
        <div class="bg-orange-500 text-white p-3 rounded-lg">
            ⏳
        </div>
    </div>

    <!-- Clients -->
    <div class="bg-white p-5 rounded-xl shadow flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Active Clients</p>
            <h2 class="text-2xl font-bold">{{ $activeClients }}</h2>
        </div>
        <div class="bg-purple-500 text-white p-3 rounded-lg">
            👥
        </div>
    </div>

</div>

<!-- RECENT ORDERS -->
<div class="bg-white rounded-2xl shadow p-4" style="border:solid #00000010; border-radius: 14px; border-width: 0.8px;">

    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="font-semibold">Recent Orders</h2>
            <p class="text-sm text-gray-500">Latest printing orders</p>
        </div>
    </div>

    <table class="w-full text-sm">
        <thead class="text-gray-500 text-left">
            <tr>
                <th>Order ID</th>
                <th>Client</th>
                <th>Service</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Date</th>
                <th>Amount</th>
            </tr>
        </thead>

        <tbody class="divide-y">

        @foreach($recentOrders as $order)

            @php
                $detail = $order->orderDetails->first();
            @endphp

            <tr class="hover:bg-gray-50">
                <td class="py-3">#ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $order->customer_name }}</td>

                <!-- SERVICE (Product Name) -->
                <td>{{ $detail?->product?->product_name ?? 'N/A' }}</td>

                <td>{{ $detail?->quantity ?? 0 }}</td>

                <!-- STATUS BADGE -->
                <td>
                    @if($order->status == 'pending')
                        <span class="bg-orange-100 text-orange-600 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-clock"></i> Pending</span>
                    @elseif($order->status == 'in_progress')
                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-spinner"></i> In Progress</span>
                    @elseif($order->status == 'ready_for_pickup')
                        <span class="bg-purple-100 text-purple-600 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-box"></i> Ready for Pickup</span>
                    @elseif($order->status == 'out_for_delivery')
                        <span class="bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-truck"></i> Out for Delivery</span>
                    @elseif($order->status == 'delivered')
                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-house"></i> Delivered</span>
                    @else
                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-times-circle"></i> Cancelled</span>
                    @endif
                </td>

                <td>{{ $order->order_date }}</td>
                <td>₱{{ number_format($order->total_amount, 2) }}</td>
            </tr>

        @endforeach

        </tbody>
    </table>

</div>

@endsection
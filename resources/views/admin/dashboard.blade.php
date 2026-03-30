@extends('admin.layouts.app')

@section('content')

<!-- HEADER -->
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Dashboard</h1>
    <p class="text-sm text-gray-500">
        Welcome back! Here's what's happening today.
    </p>
</div>

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
<div class="bg-white rounded-xl shadow p-5">

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

            <tr class="py-3">
                <td>#ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $order->customer_name }}</td>

                <!-- SERVICE (Product Name) -->
                <td>{{ $detail?->product?->product_name ?? 'N/A' }}</td>

                <td>{{ $detail?->quantity ?? 0 }}</td>

                <!-- STATUS BADGE -->
                <td>
                    @if($order->status == 'completed')
                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Completed</span>
                    @elseif($order->status == 'pending')
                        <span class="bg-orange-100 text-orange-600 px-2 py-1 rounded-full text-xs">Pending</span>
                    @elseif($order->status == 'in_progress')
                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs">In Progress</span>
                    @else
                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">Cancelled</span>
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
@extends('admin.layouts.app')

@section('content')

<!-- HEADER -->
@section('header')

<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-semibold">Orders</h1>
        <p class="text-sm text-gray-500">
            Manage all your printing orders.
        </p>
    </div>

    <!-- SEARCH -->
    <div class="relative w-64">
        <div class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <i data-feather="search" class="w-4 h-4"></i>
        </div>

        <input 
            type="text"
            placeholder="Search..."
            class="w-full pl-10 pr-4 py-2 rounded-xl bg-gray-100 
                   focus:outline-none focus:ring-2 focus:ring-pink-300 
                   text-sm placeholder-gray-400">
    </div>
</div>

@endsection

<div class="flex justify-end items-center mb-2">
            <button class="bg-white border px-4 py-2 rounded-lg text-sm hover:bg-gray-100">
                <i class="fa-solid fa-filter"></i> Filter
            </button>

            <button class="bg-white border px-4 py-2 rounded-lg text-sm hover:bg-gray-100">
                <i class="fa-solid fa-download"></i> Export
            </button>

            <a href="#" class="text-white px-4 py-2 rounded-lg text-sm" style="background-color: #D47497;">
                <i class="fa-solid fa-plus"></i> New Order
            </a>
        </div>

<!-- TABS -->
<div class="flex gap-2 mb-4 text-sm">
    <span class="px-4 py-1 text-pink-600" style="background-color: #EEF2FF; border: solid #C6D2FF 0.8px; border-radius: 8px" >
        All Orders ({{ $totalOrders }})
    </span>

    <span class="px-4 py-1 bg-gray-100" style="background-color: #FFFFFF; border: solid #00000010 0.8px; border-radius: 8px">
        Pending ({{ $pendingOrders }})
    </span>

    <span class="px-4 py-1 bg-gray-100" style="background-color: #FFFFFF; border: solid #00000010 0.8px; border-radius: 8px">
        In Progress ({{ $inProgressOrders }})
    </span>

    <span class="px-4 py-1 bg-gray-100" style="background-color: #FFFFFF; border: solid #00000010 0.8px; border-radius: 8px">
        Completed ({{ $deliveredOrders }})
    </span>

    <span class="px-4 py-1 bg-gray-100" style="background-color: #FFFFFF; border: solid #00000010 0.8px; border-radius: 8px">
        Cancelled ({{ $cancelledOrders }})
    </span>
</div>

<!-- TABLE -->
<div class="bg-white rounded-2xl shadow p-4" style="border:solid #00000010; border-radius: 14px; border-width: 0.8px;">
    <table class="w-full text-sm">
        <thead class="text-left text-gray-500 border-b">
            <tr>
                <th class="py-3">Order ID</th>
                <th>Client</th>
                <th>Service</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Date</th>
                <th>Amount</th>
                <th></th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @foreach($orders as $order)

            @php
                $detail = $order->orderDetails->first();
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="py-3 font-medium">#ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $detail?->product?->product_name ?? 'N/A' }}</td>
                <td>{{ $detail?->quantity ?? 0 }}</td>

                <td>
                    @php
                        $colors = [
                            'pending' => 'bg-orange-100 text-orange-600',
                            'in_progress' => 'bg-blue-100 text-blue-600',
                            'ready_for_pickup' => 'bg-purple-100 text-purple-600',
                            'out_for_delivery' => 'bg-indigo-100 text-indigo-600',
                            'delivered' => 'bg-green-100 text-green-600',
                            'cancelled' => 'bg-red-100 text-red-600',
                        ];

                        $statuses = [
                            'pending' => 'fa-clock',
                            'in_progress' => 'fa-spinner',
                            'ready_for_pickup' => 'fa-box',
                            'out_for_delivery' => 'fa-truck',
                            'delivered' => 'fa-house',
                            'cancelled' => 'fa-times-circle',
                        ];
                    @endphp

                    <div class="relative inline-block">

                        <!-- ✅ CLICKABLE STATUS BADGE -->
                        <button onclick="toggleDropdown('{{ $order->order_id }}')"
                            class="text-xs px-2 py-1 rounded-full flex items-center gap-1 
                                {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">

                            <i class="fa-solid {{ $statuses[$order->status] ?? 'fa-circle' }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </button>

                        <!-- ✅ DROPDOWN -->
                        <div id="dropdown-{{ $order->order_id }}"
                            class="hidden absolute mt-2 w-52 bg-white border rounded-xl shadow-lg z-10">

                            @foreach($statuses as $status => $icon)
                            <form action="{{ route('admin.orders.updateStatus', ['id' => $order->order_id]) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <button name="status" value="{{ $status }}"
                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">

                                    <i class="fa-solid {{ $icon }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </button>
                            </form>
                            @endforeach

                        </div>

                    </div>
                </td>

                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                <td>₱ {{ number_format($order->total_amount, 2) }}</td>
                <td>⋮</td>
            </tr>
            @endforeach
            </tbody>
    </table>
</div>

@endsection
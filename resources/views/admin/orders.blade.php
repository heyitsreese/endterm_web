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
            <h1 class="text-3xl font-semibold">Orders</h1>
            <p class="text-sm text-gray-500">
                Manage all your printing orders.
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
                <td>
                    <button onclick="toggleOrderExpand('{{ $order->order_id }}')"
                        class="text-lg px-2 py-1 rounded hover:bg-gray-200">
                        ⋮
                    </button>
                </td>
            </tr>
            
            <tr id="expand-{{ $order->order_id }}" class="hidden bg-gray-50">
                <td colspan="8" class="p-4">

                    <div class="flex justify-between items-start">

                        <!-- LEFT: ORDER DETAILS -->
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Client:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Service:</strong> {{ $detail?->product?->product_name ?? 'N/A' }}</p>
                            <p><strong>Quantity:</strong> {{ $detail?->quantity ?? 0 }}</p>
                            <p><strong>Total:</strong> ₱ {{ number_format($order->total_amount, 2) }}</p>
                        </div>

                        <!-- RIGHT: ACTION BUTTONS -->
                        <div class="flex gap-2">

                            <!-- VIEW -->
                            <a href="{{ route('admin.orders.show', $order->order_id) }}"
                            class="px-3 py-2 text-sm bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">
                                View
                            </a>

                            <!-- EDIT -->
                            <a href="{{ route('admin.orders.edit', $order->order_id) }}"
                            class="px-3 py-2 text-sm bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200">
                                Edit
                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('admin.orders.destroy', $order->order_id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    onclick="return confirm('Delete this order?')"
                                    class="px-3 py-2 text-sm bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                                    Delete
                                </button>
                            </form>

                        </div>

                    </div>

                </td>
            </tr>
            @endforeach
            </tbody>
    </table>
</div>

@endsection
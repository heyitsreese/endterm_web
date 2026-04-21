@extends('client.layouts.app')

@section('header')
<div class="flex items-center justify-between w-full">
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="md:hidden text-gray-600">
            <i data-feather="menu"></i>
        </button>
        <div class="leading-snug">
            <h1 class="text-xl font-semibold">My Orders</h1>
            <p class="text-sm text-gray-500">Track and manage your printing orders</p>
        </div>
    </div>
    <a href="{{ route('client.create-order') }}"
        class="flex items-center gap-2 px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white text-sm font-medium rounded-xl transition">
        <i data-feather="plus" class="w-4 h-4"></i> New Order
    </a>
</div>
@endsection

@section('content')

<!-- STAT CARDS -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">

    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
        <p class="text-xs text-gray-500 mb-1">Total Orders</p>
        <p class="text-2xl font-semibold text-gray-800">{{ $totalOrders }}</p>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
        <p class="text-xs text-gray-500 mb-1">In Progress</p>
        <p class="text-2xl font-semibold text-orange-400">{{ $inProgress }}</p>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
        <p class="text-xs text-gray-500 mb-1">Completed</p>
        <p class="text-2xl font-semibold text-green-500">{{ $completed }}</p>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
        <p class="text-xs text-gray-500 mb-1">Total Spent</p>
        <p class="text-2xl font-semibold text-gray-800">P {{ number_format($totalSpent, 0) }}</p>
    </div>

</div>

<!-- ORDER HISTORY TABLE -->
<div class="bg-white border border-gray-100 rounded-xl shadow-sm mb-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-5 border-b border-gray-100">
        <div>
            <h2 class="text-base font-semibold">Order History</h2>
            <p class="text-sm text-gray-500">View and manage all your printing orders</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" id="orderSearch" placeholder="Search orders..."
                    class="pl-9 pr-3 py-2 text-sm rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-pink-300">
            </div>
            <select id="statusFilter"
                class="text-sm px-3 py-2 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-300">
                <option value="">All Orders</option>
                <option value="completed">Completed</option>
                <option value="in_progress">In Progress</option>
                <option value="pending_review">Pending Review</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="p-10 text-center text-gray-400 text-sm">You have no orders yet.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="ordersTable">
                <thead>
                    <tr class="text-left text-gray-500 text-xs border-b border-gray-100">
                        <th class="px-5 py-3 font-medium">Order ID</th>
                        <th class="px-5 py-3 font-medium">Service</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Order Date</th>
                        <th class="px-5 py-3 font-medium">Delivery</th>
                        <th class="px-5 py-3 font-medium">Amount</th>
                        <th class="px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition order-row"
                        data-status="{{ $order->status }}"
                        data-search="{{ strtolower($order->order_id . ' ' . $order->status . ' ' . $order->delivery_type) }}">

                        <!-- ORDER ID -->
                        <td class="px-5 py-4 font-medium text-gray-800">
                            #ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}
                        </td>

                        <!-- SERVICE (delivery type) -->
                        <td class="px-5 py-4 text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $order->delivery_type ?? 'N/A')) }}
                        </td>

                        <!-- STATUS BADGE -->
                        <td class="px-5 py-4">
                            @php
                                $status = $order->status;
                                $badge = match($status) {
                                    'completed'      => 'bg-green-100 text-green-700',
                                    'in_progress'    => 'bg-blue-100 text-blue-700',
                                    'pending_review' => 'bg-orange-100 text-orange-700',
                                    'cancelled'      => 'bg-red-100 text-red-600',
                                    default          => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $badge }}">
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </span>
                        </td>

                        <!-- ORDER DATE -->
                        <td class="px-5 py-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}
                        </td>

                        <!-- DELIVERY TYPE -->
                        <td class="px-5 py-4 text-gray-600">
                            {{ ucfirst($order->delivery_type ?? '—') }}
                        </td>

                        <!-- AMOUNT -->
                        <td class="px-5 py-4 text-gray-800 font-medium">
                            P {{ number_format($order->total_amount, 2) }}
                        </td>

                        <!-- ACTIONS -->
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3 text-gray-400">
                                <button title="View" class="hover:text-pink-500 transition">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </button>
                                <button title="Download" class="hover:text-pink-500 transition">
                                    <i data-feather="download" class="w-4 h-4"></i>
                                </button>
                                <button title="Message" class="hover:text-pink-500 transition">
                                    <i data-feather="message-square" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

<!-- NEED HELP CARD -->
<div class="rounded-xl bg-blue-50 border border-blue-100 p-6 flex items-center justify-between">
    <div>
        <h3 class="font-semibold text-gray-800 mb-1">Need Help?</h3>
        <p class="text-sm text-gray-500">Our support team is here to assist you with your orders.</p>
        <button class="mt-3 px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-white transition text-gray-700">
            Contact Support
        </button>
    </div>
    <div class="hidden sm:flex w-12 h-12 rounded-xl bg-pink-100 items-center justify-center text-pink-500">
        <i data-feather="message-square" class="w-6 h-6"></i>
    </div>
</div>

<!-- SEARCH AND FILTER SCRIPT -->
<script>
const searchInput = document.getElementById('orderSearch');
const statusFilter = document.getElementById('statusFilter');

function filterRows() {
    const q = searchInput.value.toLowerCase();
    const s = statusFilter.value;
    document.querySelectorAll('.order-row').forEach(row => {
        const matchSearch = row.dataset.search.includes(q);
        const matchStatus = !s || row.dataset.status === s;
        row.style.display = matchSearch && matchStatus ? '' : 'none';
    });
}

searchInput.addEventListener('input', filterRows);
statusFilter.addEventListener('change', filterRows);
</script>

@endsection
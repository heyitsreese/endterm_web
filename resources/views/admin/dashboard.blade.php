@extends('admin.layouts.app')

@section('content')

<!-- HEADER -->

@section('header')
<div class="flex items-center gap-2 min-w-0">
    <button onclick="toggleSidebar()" class="md:hidden text-gray-600 shrink-0">
        <i data-feather="menu"></i>
    </button>
    <div class="min-w-0">
        <h1 class="text-lg sm:text-2xl font-semibold truncate">Dashboard</h1>
        <p class="text-xs text-gray-500 hidden sm:block">Welcome back, {{ $admin->name ?? 'Admin' }}! Here's what's happening today.</p>
    </div>
</div>
@endsection

<!-- CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

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

</div>

<!-- RECENT ORDERS -->
<div class="bg-white rounded-2xl shadow p-4" style="border:solid #00000010; border-radius: 14px; border-width: 0.8px;">

    <div class="mb-4">
        <h2 class="font-semibold">Recent Orders</h2>
        <p class="text-sm text-gray-500">Latest printing orders</p>
    </div>

    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table class="w-full text-sm" style="min-width: 600px;">
            <thead class="text-gray-500 text-left">
                <tr>
                    <th>Order ID</th>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($recentOrders as $order)
                @php $detail = $order->orderDetails->first(); @endphp
                <tr class="hover:bg-gray-50">
                    <td class="py-3">#ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $detail?->product?->product_name ?? 'N/A' }}</td>
                    <td>{{ $detail?->quantity ?? 0 }}</td>
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
                        @elseif($order->status == 'picked_up')
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-check-circle"></i> Picked Up</span>
                        @else
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs"><i class="fa-solid fa-times-circle"></i> Cancelled</span>
                        @endif
                    </td>
                    <td>{{ $order->order_date }}</td>
                    <td>
                        <button type="button" onclick="openOrderModal('{{ $order->order_id }}')" class="text-indigo-600 hover:underline">View</button>
                    </td>
                    <td>₱{{ number_format($order->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- Reports Dashboard -->
<div class="mt-6">
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Reports</h2>
        <p class="text-sm text-gray-500">Last 7 days overview</p>
    </div>

    {{-- ── TOP ROW: Daily Sales + Orders Per Day ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

        {{-- DAILY SALES CHART --}}
        <div class="bg-white rounded-2xl shadow p-5" style="border:solid #00000010; border-radius: 14px; border-width: 0.8px;">
            <div class="mb-4">
                <h3 class="font-semibold text-gray-700">Daily Sales</h3>
                <p class="text-xs text-gray-400">Revenue from completed orders</p>
            </div>
            <div class="relative" style="height: 160px;">
                <canvas id="dailySalesChart"></canvas>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between text-xs text-gray-500">
                <span>7-day total:</span>
                <span class="font-semibold text-gray-700">₱{{ number_format($dailySalesChart->sum('total_sales'), 2) }}</span>
            </div>
        </div>

        {{-- ORDERS PER DAY CHART --}}
        <div class="bg-white rounded-2xl shadow p-5" style="border:solid #00000010; border-radius: 14px; border-width: 0.8px;">
            <div class="mb-4">
                <h3 class="font-semibold text-gray-700">Orders Per Day</h3>
                <p class="text-xs text-gray-400">Number of completed orders daily</p>
            </div>
            <div class="relative" style="height: 160px;">
                <canvas id="dailyOrdersChart"></canvas>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between text-xs text-gray-500">
                <span>7-day total:</span>
                <span class="font-semibold text-gray-700">{{ $dailySalesChart->sum('order_count') }} orders</span>
            </div>
        </div>

    </div>

    {{-- ── MOST REQUESTED SERVICES ── --}}
    <div class="bg-white rounded-2xl shadow p-5" style="border:solid #00000010; border-radius: 14px; border-width: 0.8px;">
        <div class="mb-4">
            <h3 class="font-semibold text-gray-700">Most Requested Services</h3>
            <p class="text-xs text-gray-400">Ranked by total quantity ordered (all time)</p>
        </div>

        @php $maxQty = $topServices->max('total_qty') ?: 1; @endphp

        <div class="space-y-4">
            @forelse($topServices as $index => $service)
                @php
                    $barWidth = round(($service->total_qty / $maxQty) * 100);
                    $colors = ['bg-pink-400','bg-purple-400','bg-blue-400','bg-green-400','bg-orange-400'];
                    $color  = $colors[$index % count($colors)];
                @endphp

                <div class="flex items-center gap-4">
                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 shrink-0">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">
                                {{ $service->product?->product_name ?? 'Unknown' }}
                            </span>
                            <span class="text-gray-400 text-xs">
                                {{ number_format($service->total_qty) }} pcs · {{ $service->order_count }} orders
                            </span>
                        </div>
                        <div class="h-full {{ $color }} rounded-full transition-all duration-700" style="width: {{ $barWidth }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">No order data yet.</p>
            @endforelse
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const labels = @json($dailySalesChart->pluck('label')->toArray());
    const sales = @json($dailySalesChart->pluck('total_sales')->toArray());
    const orders = @json($dailySalesChart->pluck('order_count')->toArray());

    Chart.defaults.font.family = 'inherit';

    new Chart(document.getElementById('dailySalesChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales (₱)',
                data: sales,
                borderColor: '#60a5fa',
                backgroundColor: 'rgba(96,165,250,0.10)',
                borderWidth: 2.5,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 700, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleColor: '#9ca3af',
                    bodyColor: '#f9fafb',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(ctx) {
                            return ' ₱' + Number(ctx.parsed.y).toLocaleString('en-PH', { minimumFractionDigits: 2 });
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } },
                y: {
                    grid: { color: '#f3f4f6' },
                    beginAtZero: true,
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 11 },
                        callback: function(v) { return '₱' + Number(v).toLocaleString('en-PH'); }
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('dailyOrdersChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Orders',
                data: orders,
                backgroundColor: 'rgba(96,165,250,0.70)',
                hoverBackgroundColor: '#3b82f6',
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 700, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleColor: '#9ca3af',
                    bodyColor: '#f9fafb',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(ctx) {
                            return ' ' + ctx.parsed.y + (ctx.parsed.y === 1 ? ' order' : ' orders');
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } },
                y: {
                    grid: { color: '#f3f4f6' },
                    beginAtZero: true,
                    ticks: { color: '#9ca3af', font: { size: 11 }, precision: 0 }
                }
            }
        }
    });

});
</script>

@endsection
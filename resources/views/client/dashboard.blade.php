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
                <input type="text" id="orderSearch" placeholder="Search by order ID, service, status..."
                    class="pl-9 pr-3 py-2 text-sm rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-pink-300 w-64">
            </div>
            <select id="statusFilter"
                class="text-sm px-3 py-2 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-300">
                <option value="">All Orders</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="ready_for_pickup">Ready for Pickup</option>
                <option value="out_for_delivery">Out for Delivery</option>
                <option value="delivered">Delivered</option>
                <option value="picked_up">Picked Up</option>
                <option value="declined">Declined</option>
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
                        data-search="{{ strtolower('ord-' . str_pad($order->order_id, 3, '0', STR_PAD_LEFT) . ' ' . $order->order_id . ' ' . $order->status . ' ' . $order->delivery_type . ' ' . ($order->orderDetails->first()->product->product_name ?? '')) }}">

                        <!-- ORDER ID -->
                        <td class="px-5 py-4 font-medium text-gray-800">
                            #ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}
                        </td>

                        <!-- SERVICE (product name from details) -->
                        <td class="px-5 py-4 text-gray-700">
                            {{ $order->orderDetails->first()->product->product_name ?? ucfirst(str_replace('_', ' ', $order->delivery_type ?? 'N/A')) }}
                        </td>

                        <!-- STATUS BADGE -->
                        <td class="px-5 py-4">
                            @php
                                $status = $order->status;
                                $badge = match($status) {
                                    'delivered', 'picked_up', 'completed' => 'bg-green-100 text-green-700',
                                    'in_progress'    => 'bg-blue-100 text-blue-700',
                                    'pending'        => 'bg-orange-100 text-orange-700',
                                    'ready_for_pickup' => 'bg-purple-100 text-purple-700',
                                    'out_for_delivery' => 'bg-cyan-100 text-cyan-700',
                                    'declined'       => 'bg-red-100 text-red-600',
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
                                <button title="View Order" class="hover:text-pink-500 transition"
                                    onclick="viewOrder({{ $order->order_id }})">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </button>

                                @if($order->orderDetails->first() && $order->orderDetails->first()->file_path)
                                <a href="{{ route('client.order.download', [$order->order_id, 0]) }}"
                                   title="Download File" class="hover:text-pink-500 transition">
                                    <i data-feather="download" class="w-4 h-4"></i>
                                </a>
                                @else
                                <button title="No files" class="text-gray-300 cursor-not-allowed" disabled>
                                    <i data-feather="download" class="w-4 h-4"></i>
                                </button>
                                @endif

                                <a href="mailto:sprintphl@gmail.com?subject=Order%20%23ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}"
                                   title="Email Support" class="hover:text-pink-500 transition">
                                    <i data-feather="message-square" class="w-4 h-4"></i>
                                </a>
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
        <a href="mailto:sprintphl@gmail.com?subject=Support%20Request"
           class="mt-3 inline-block px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-white transition text-gray-700">
            Contact Support
        </a>
    </div>
    <div class="hidden sm:flex w-12 h-12 rounded-xl bg-pink-100 items-center justify-center text-pink-500">
        <i data-feather="message-square" class="w-6 h-6"></i>
    </div>
</div>

<!-- ===================== -->
<!-- ORDER DETAIL MODAL    -->
<!-- ===================== -->
<div id="orderModal" onclick="if(event.target === this) closeOrderModal()"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">

        <!-- Modal Header -->
        <div class="flex items-center justify-between p-5 border-b">
            <div>
                <h2 class="text-lg font-semibold" id="modalOrderCode">Order Details</h2>
                <p class="text-sm text-gray-500" id="modalOrderDate"></p>
            </div>
            <button onclick="closeOrderModal()" class="text-gray-400 hover:text-red-500 transition text-xl">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 space-y-4" id="modalBody">
            <div class="text-center text-gray-400 py-6">Loading...</div>
        </div>

    </div>
</div>

<!-- SEARCH AND FILTER SCRIPT -->
<script>
const searchInput = document.getElementById('orderSearch');
const statusFilter = document.getElementById('statusFilter');

function filterRows() {
    const q = searchInput.value.toLowerCase().replace('#', '');
    const s = statusFilter.value;
    document.querySelectorAll('.order-row').forEach(row => {
        const matchSearch = row.dataset.search.includes(q);
        const matchStatus = !s || row.dataset.status === s;
        row.style.display = matchSearch && matchStatus ? '' : 'none';
    });
}

searchInput.addEventListener('input', filterRows);
statusFilter.addEventListener('change', filterRows);

// =====================
// ORDER DETAIL MODAL
// =====================
function viewOrder(orderId) {
    const modal = document.getElementById('orderModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.getElementById('modalBody').innerHTML =
        '<div class="text-center text-gray-400 py-6">Loading...</div>';

    fetch(`/client/orders/${orderId}/json`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('modalOrderCode').innerText = data.code;
            document.getElementById('modalOrderDate').innerText = 'Placed on ' + data.created_at;

            let statusColor = {
                'delivered': 'bg-green-100 text-green-700',
                'picked_up': 'bg-green-100 text-green-700',
                'completed': 'bg-green-100 text-green-700',
                'in_progress': 'bg-blue-100 text-blue-700',
                'pending': 'bg-orange-100 text-orange-700',
                'ready_for_pickup': 'bg-purple-100 text-purple-700',
                'out_for_delivery': 'bg-cyan-100 text-cyan-700',
                'declined': 'bg-red-100 text-red-600',
            }[data.status] || 'bg-gray-100 text-gray-600';

            let statusLabel = data.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

            let html = `
                <!-- Status -->
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-medium ${statusColor}">${statusLabel}</span>
                    <span class="text-xs text-gray-400">${data.delivery_type ? data.delivery_type.charAt(0).toUpperCase() + data.delivery_type.slice(1) : '—'}</span>
                </div>

                ${data.status === 'declined' && data.decline_reason ? `
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-600">
                    <strong>Decline Reason:</strong> ${data.decline_reason}
                </div>` : ''}

                <!-- Customer Info -->
                <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Customer</span>
                        <span class="font-medium">${data.customer_name}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span>${data.email}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone</span>
                        <span>${data.phone || '—'}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total</span>
                        <span class="font-semibold text-pink-600">P ${parseFloat(data.total_amount).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                    </div>
                </div>
            `;

            // Order details (items)
            if (data.details && data.details.length > 0) {
                html += `<h3 class="font-medium text-sm text-gray-700 mt-2">Order Items</h3>`;
                data.details.forEach((d, i) => {
                    html += `
                    <div class="bg-white border rounded-xl p-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Product</span>
                            <span class="font-medium">${d.product_name}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Quantity</span>
                            <span>${d.quantity}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Size</span>
                            <span>${d.size || '—'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Color</span>
                            <span>${d.color || '—'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Paper Quality</span>
                            <span>${d.paper_quality || '—'}</span>
                        </div>
                        ${d.instruction ? `
                        <div>
                            <span class="text-gray-500 block">Instructions</span>
                            <span class="text-gray-700">${d.instruction}</span>
                        </div>` : ''}
                        ${d.file_path ? `
                        <a href="/client/orders/${data.order_id}/download/${i}"
                           class="inline-flex items-center gap-1 text-pink-500 hover:text-pink-600 text-xs font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download File
                        </a>` : ''}
                    </div>`;
                });
            }

            document.getElementById('modalBody').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('modalBody').innerHTML =
                '<div class="text-center text-red-500 py-6">Failed to load order details.</div>';
        });
}

function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

@endsection
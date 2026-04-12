<!-- orders.blade.php (ADMIN) -->

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
        <div class="relative inline-block">
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

                @forelse($pendingNotifications as $order)
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

            <a href="{{ route('admin.orders.create') }}" class="text-white px-4 py-2 rounded-lg text-sm" style="background-color: #D47497;">
                <i class="fa-solid fa-plus"></i> New Order
            </a>
        </div>

<h2 class="text-lg font-semibold mt-10 mb-4">
    Current Orders
</h2>

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
        Completed ({{ $completedOrders->count() }})
    </span>

    <span class="px-4 py-1 bg-gray-100" style="background-color: #FFFFFF; border: solid #00000010 0.8px; border-radius: 8px">
        Declined ({{ $declinedOrdersCount }})
    </span>
</div>

<!-- TABLE -->
<div class="bg-white rounded-2xl shadow p-4" style="border:solid #00000010; border-radius: 14px; border-width: 0.8px;">
    <div class="max-h-[500px] overflow-y-auto overflow-x-visible">
        <table class="w-full text-sm">
            <thead class="text-left text-gray-500 border-b">
                <tr>
                    <th class="py-3">Order ID</th>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Delivery</th>
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
                <tr id="order-row-{{ $order->order_id }}" class="hover:bg-gray-50">
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
                                'picked_up' => 'bg-green-100 text-green-600',
                                'out_for_delivery' => 'bg-indigo-100 text-indigo-600',
                                'delivered' => 'bg-green-100 text-green-600',
                                'declined' => 'bg-red-100 text-red-600',
                            ];

                            $statuses = [
                                'pending' => 'fa-clock',
                                'in_progress' => 'fa-spinner',
                                'ready_for_pickup' => 'fa-box',
                                'picked_up' => 'fa-check-circle',
                                'out_for_delivery' => 'fa-truck',
                                'delivered' => 'fa-house',
                                'declined' => 'fa-times-circle',
                            ];
                        @endphp

                        <div class="relative inline-block">

                            <!-- STATUS BUTTON -->
                            <button 
                                id="status-{{ $order->order_id }}"
                                type="button"
                                onclick="toggleDropdown('{{ $order->order_id }}')"
                                class="text-xs px-2 py-1 rounded-full flex items-center gap-1 {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                
                                <i class="fa-solid {{ $statuses[$order->status] ?? 'fa-circle' }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </button>

                            <!-- DROPDOWN -->
                            <div id="dropdown-{{ $order->order_id }}"  class="hidden fixed mt-2 w-52 bg-white border rounded-xl shadow-lg z-50">

                                @php
                                    $allowedStatuses = $order->delivery_type === 'pickup'
                                        ? $pickupStatuses
                                        : $deliveryStatuses;
                                @endphp

                                @foreach($statuses as $status => $icon)

                                    @if(!in_array($status, $allowedStatuses))
                                        @continue
                                    @endif

                                    <button 
                                        type="button"
                                        onclick="updateStatus('{{ $order->order_id }}', '{{ $status }}')"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                                        
                                        <i class="fa-solid {{ $icon }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </button>

                                @endforeach

                            </div>
                        </div>
                    </td>
                    <td>{{ ucfirst($order->delivery_type) }}</td>

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
                                <form id="delete-form-{{ $order->order_id }}" action="{{ route('admin.orders.destroy', $order->order_id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                        onclick="openDeleteModal('{{ $order->order_id }}')"
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

    <div id="deleteModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-lg">

            <h2 class="text-lg font-semibold mb-4">Delete Order</h2>

            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to delete this order? This action cannot be undone.
            </p>

            <div class="flex justify-end gap-3">

                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 border rounded-lg text-gray-600">
                    Cancel
                </button>

                <button id="confirmDeleteBtn"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg">
                    Delete
                </button>

            </div>

        </div>
    </div>
</div>

<!-- DECLINED TABLE -->

<h2 class="text-lg font-semibold mt-10 mb-4 text-red-500">
    Declined Orders
</h2>

<div class="bg-white rounded-2xl shadow p-4 mt-6" style="border:solid #00000010; border-radius: 14px; border-width: 0.8px;">
    <div class="max-h-[500px] overflow-y-auto overflow-x-visible">
        <table class="w-full text-sm">
            <thead class="text-left text-gray-500 border-b">
                <tr>
                    <th class="py-3">Order ID</th>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Delivery</th>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody id="declined-table-body" class="divide-y">
                @foreach($declinedOrders as $order)

                @php
                    $detail = $order->orderDetails->first();
                @endphp

                <tr id="order-row-{{ $order->order_id }}" class="hover:bg-gray-50">
                    <td class="py-3 font-medium">
                        #ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}
                    </td>

                    <td>{{ $order->customer_name }}</td>

                    <td>{{ $detail?->product?->product_name ?? 'N/A' }}</td>

                    <td>{{ $detail?->quantity ?? 0 }}</td>

                    <!-- STATUS BADGE -->
                    <td>
                        <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-600 flex items-center gap-1 w-fit">
                            <i class="fa-solid fa-times-circle"></i>
                            Declined
                        </span>
                    </td>
                    <td class="text-xs text-gray-500">{{ $order->decline_reason }}</td>

                    <td>{{ ucfirst($order->delivery_type) }}</td>

                    <td>{{ $order->created_at->format('Y-m-d') }}</td>

                    <td class="text-red-500 font-medium">
                        ₱ {{ number_format($order->total_amount, 2) }}
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="declineModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-lg">

        <h2 class="text-lg font-semibold mb-4">Decline Order</h2>

        <textarea id="declineReasonInput"
            class="w-full border rounded-lg p-2 text-sm"
            placeholder="Enter reason..."></textarea>

        <div class="flex justify-end gap-3 mt-4">
            <button onclick="closeDeclineModal()" class="px-4 py-2 border rounded-lg">
                Cancel
            </button>

            <button id="confirmDeclineBtn"
                class="px-4 py-2 bg-red-500 text-white rounded-lg">
                Confirm
            </button>
        </div>

    </div>
</div>

<div id="completeModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-lg">

        <h2 class="text-lg font-semibold mb-4">Confirm Completion</h2>

        <p class="text-sm text-gray-600 mb-6">
            Are you sure you want to mark this order as completed? This action cannot be undone.
        </p>

        <div class="flex justify-end gap-3">

            <button onclick="closeCompleteModal()"
                class="px-4 py-2 border rounded-lg text-gray-600">
                Cancel
            </button>

            <button id="confirmCompleteBtn"
                class="px-4 py-2 bg-green-500 text-white rounded-lg">
                Confirm
            </button>

        </div>

    </div>
</div>

<h2 class="text-lg font-semibold mt-10 mb-4 text-green-600">
    Completed Orders
</h2>

<div class="bg-white rounded-2xl shadow p-4 mt-6" style="border:solid #00000010; border-radius: 14px; border-width: 0.8px;">
    <div class="max-h-[500px] overflow-y-auto overflow-x-visible">
        <table class="w-full text-sm">
            <thead class="text-left text-gray-500 border-b">
                <tr>
                    <th class="py-3">Order ID</th>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Delivery</th>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody id="completed-table-body" class="divide-y">
                @foreach($completedOrders as $order)

                @php
                    $detail = $order->orderDetails->first();
                @endphp

                <tr id="order-row-{{ $order->order_id }}" class="hover:bg-gray-50">

                    <td class="py-3 font-medium">
                        #ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}
                    </td>

                    <td>{{ $order->customer_name }}</td>

                    <td>{{ $detail?->product?->product_name ?? 'N/A' }}</td>

                    <td>{{ $detail?->quantity ?? 0 }}</td>

                    <td>
                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-600 flex items-center gap-1 w-fit">
                            <i class="fa-solid fa-check-circle"></i>
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>

                    <td>{{ ucfirst($order->delivery_type) }}</td>

                    <td>{{ $order->created_at->format('Y-m-d') }}</td>

                    <td class="text-green-600 font-medium">
                        ₱ {{ number_format($order->total_amount, 2) }}
                    </td>

                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
let completeOrderId = null;
let completeStatus = null;

function openCompleteModal(orderId, status) {
    completeOrderId = orderId;
    completeStatus = status;

    document.getElementById('completeModal').classList.remove('hidden');
    document.getElementById('completeModal').classList.add('flex');
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
    document.getElementById('completeModal').classList.remove('flex');
}

document.getElementById('confirmCompleteBtn').addEventListener('click', function () {
    sendStatus(completeOrderId, completeStatus);
    closeCompleteModal();
});
</script>

<script>
function toggleDropdown(orderId) {
    const dropdown = document.getElementById(`dropdown-${orderId}`);
    const button = document.getElementById(`status-${orderId}`);

    const rect = button.getBoundingClientRect();

    dropdown.style.top = `${rect.bottom + window.scrollY}px`;
    dropdown.style.left = `${rect.left + window.scrollX}px`;

    dropdown.classList.toggle('hidden');
}
</script>

<script>
let declineOrderId = null;

function openDeclineModal(orderId) {
    declineOrderId = orderId;
    document.getElementById('declineModal').classList.remove('hidden');
    document.getElementById('declineModal').classList.add('flex');
}

function closeDeclineModal() {
    document.getElementById('declineModal').classList.add('hidden');
    document.getElementById('declineModal').classList.remove('flex');
}

document.getElementById('confirmDeclineBtn').addEventListener('click', function () {
    const reason = document.getElementById('declineReasonInput').value;

    if (!reason) {
        alert("Reason is required");
        return;
    }

    sendStatus(declineOrderId, 'declined', reason);
    closeDeclineModal();
});
</script>

<script>
function updateStatus(orderId, status) {

    if (status === 'declined') {
        openDeclineModal(orderId);
        return;
    }

    if (status === 'delivered' || status === 'picked_up') {
        openCompleteModal(orderId, status);
        return;
    }

    sendStatus(orderId, status);
}

function sendStatus(orderId, status, reason = null) {

    fetch(`/admin/orders/${orderId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            status: status,
            decline_reason: reason,
            _method: 'PUT'
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {

            const badge = document.getElementById(`status-${orderId}`);

            const colors = {
                'pending': 'bg-orange-100 text-orange-600',
                'in_progress': 'bg-blue-100 text-blue-600',
                'ready_for_pickup': 'bg-purple-100 text-purple-600',
                'picked_up': 'bg-green-100 text-green-600',
                'out_for_delivery': 'bg-indigo-100 text-indigo-600',
                'delivered': 'bg-green-100 text-green-600',
                'declined': 'bg-red-100 text-red-600',
            };

            const icons = {
                'pending': 'fa-clock',
                'in_progress': 'fa-spinner',
                'ready_for_pickup': 'fa-box',
                'picked_up': 'fa-check-circle',
                'out_for_delivery': 'fa-truck',
                'delivered': 'fa-house',
                'declined': 'fa-times-circle',
            };

            badge.className = `text-xs px-2 py-1 rounded-full flex items-center gap-1 ${colors[status]}`;

            badge.innerHTML = `
                <i class="fa-solid ${icons[status]}"></i>
                ${status.replaceAll('_', ' ').replace(/\b\w/g, c => c.toUpperCase())}
            `;

            if (status === 'declined') {
                moveToDeclined(orderId, reason);
            }

            if (status === 'delivered' || status === 'picked_up') {
                moveToCompleted(orderId, status);
            }

            document.getElementById(`dropdown-${orderId}`).classList.add('hidden');
        }
    });
}

function moveToCompleted(orderId, status) {
    const row = document.getElementById(`order-row-${orderId}`);
    const completedTable = document.getElementById('completed-table-body');

    if (row && completedTable) {
        const newRow = row.cloneNode(true);

        const statusCell = newRow.querySelector('td:nth-child(5)');

        statusCell.innerHTML = `
            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-600 flex items-center gap-1 w-fit">
                <i class="fa-solid fa-check-circle"></i>
                ${status.replaceAll('_', ' ').replace(/\b\w/g, c => c.toUpperCase())}
            </span>
        `;

        completedTable.prepend(newRow);
        row.remove();
    }
}

function moveToDeclined(orderId, reason) {
    const row = document.getElementById(`order-row-${orderId}`);
    const declinedTable = document.getElementById('declined-table-body');

    if (row && declinedTable) {
        const newRow = row.cloneNode(true);

        const statusCell = newRow.querySelector('td:nth-child(5)');
        const reasonCell = newRow.querySelector('td:nth-child(6)');

        // Update status
        statusCell.innerHTML = `
            <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-600 flex items-center gap-1 w-fit">
                <i class="fa-solid fa-times-circle"></i>
                Declined
            </span>
        `;

        // Update reason column properly
        if (reasonCell) {
            reasonCell.innerText = reason;
        }

        declinedTable.prepend(newRow);
        row.remove();
    }
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    let selectedOrderId = null;

    window.openDeleteModal = function(orderId) {
        selectedOrderId = orderId;

        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    window.closeDeleteModal = function() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (selectedOrderId) {
            document.getElementById('delete-form-' + selectedOrderId).submit();
        }
    });

});
</script>

@endsection
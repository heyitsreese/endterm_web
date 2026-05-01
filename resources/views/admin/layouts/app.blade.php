<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sprint PHL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://kit.fontawesome.com/97c3b6d53c.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image" href="{{ asset('images/logo.png') }}">
</head>

<body class="bg-white overflow-x-hidden">

<div class="flex min-h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside id="sidebar"
            class="fixed z-50 inset-y-0 left-0 w-64 bg-white border-r border-gray-200 
                transform -translate-x-full md:translate-x-0 transition-transform duration-300 
                flex flex-col justify-between">

        <!-- TOP -->
        <div>
            <!-- LOGO -->
            <div class="flex items-center gap-3 p-6 border-b">
                <img src="{{ asset('images/logo.jpg') }}"
                    class="w-10 h-10 rounded-full object-cover">
                <h1 class="font-semibold text-lg">Sprint PHL</h1>
            </div>

            <!-- NAV -->
            <nav class="p-4 space-y-2 text-sm">

                <!-- ACTIVE -->
                <a href="{{ url('admin/dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl {{ request()->is('admin/dashboard') ? 'bg-pink-100 text-pink-600 font-medium' : 'hover:bg-gray-100' }}">
                    <i data-feather="grid"></i> Dashboard
                </a>

                <!-- ORDERS -->
                <a href="{{ url('admin/orders') }}" class="flex items-center justify-between px-4 py-2 rounded-xl {{ request()->is('admin/orders') ? 'bg-pink-100 text-pink-600 font-medium' : 'hover:bg-gray-100' }}">
                    <div class="flex items-center gap-3">
                        <i data-feather="shopping-bag"></i> Orders
                    </div>

                    <span class="bg-pink-400 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $pendingOrders ?? 0 }}
                    </span>
                </a>

                <a href="{{ url('admin/clients') }}" class="flex items-center justify-between px-4 py-2 rounded-xl {{ request()->is('admin/clients') ? 'bg-pink-100 text-pink-600 font-medium' : 'hover:bg-gray-100' }}">
                    <div class="flex items-center gap-3">    
                        <i data-feather="users"></i>
                            Clients
                    </div>
                </a>

                <a href="{{ url('admin/products') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl {{ request()->is('admin/products') ? 'bg-pink-100 text-pink-600 font-medium' : 'hover:bg-gray-100' }}">
                    <i data-feather="box"></i>
                    Products
                </a>

                <a href="{{ url('admin/settings') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl {{ request()->is('admin/settings') ? 'bg-pink-100 text-pink-600 font-medium' : 'hover:bg-gray-100' }}">
                    <i data-feather="settings"></i>
                    Settings
                </a>

            </nav>
        </div>

        <!-- BOTTOM PROFILE -->
        <div class="p-4 border-t flex items-center justify-between">

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-pink-400 text-white flex items-center justify-center font-bold">
                    {{ strtoupper(substr($admin->name ?? 'A', 0, 2)) }}
                </div>

                <div>
                    <p class="text-sm font-medium">{{ $admin->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-500">{{ $admin->email ?? '' }}</p>
                </div>
            </div>

            <button type="button" onclick="openLogoutModal()" class="text-gray-500 hover:text-red-500 transition">
                <i data-feather="log-out"></i>
            </button>

        </div>

    </aside>

    <div id="sidebarOverlay" 
        onclick="toggleSidebar()" 
        class="fixed inset-0 bg-black bg-opacity-40 hidden z-40 md:hidden">
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 w-full md:ml-64 min-w-0 overflow-x-hidden max-w-full">

        <!-- PAGE HEADER -->
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 overflow-x-hidden">
            <div class="flex items-center justify-between gap-2">

                <!-- PAGE TITLE (from each page's @section('header')) -->
                <div class="flex-1 min-w-0">
                    @yield('header')
                </div>

                <!-- GLOBAL: search + bell -->
                <div class="flex items-center gap-2 shrink-0">
                    <form action="{{ url('admin/orders') }}" method="GET" class="relative">
                        <div class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i data-feather="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="search" placeholder="Search..."
                                value="{{ request('search') }}"
                                class="pl-9 pr-3 py-2 rounded-xl bg-gray-50 border border-transparent
                                    focus:outline-none focus:ring-2 focus:ring-pink-300 focus:bg-white
                                    text-sm text-gray-600 placeholder-gray-400 font-medium
                                    transition-all"
                                style="width: clamp(80px, 25vw, 224px);">
                    </form>

                    <div class="relative shrink-0">
                        <button id="notifBtn" class="text-gray-500 hover:text-gray-700 transition relative">
                            <i data-feather="bell" class="w-5 h-5"></i>
                            @if(($unreadOrdersCount ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                                    {{ $unreadOrdersCount }}
                                </span>
                            @endif
                        </button>
                        <div id="notifDropdown" class="hidden fixed top-14 right-2 w-64 bg-white shadow-lg rounded-xl p-4 z-[999]">
                            <h3 class="font-semibold mb-2 text-sm">Recent Orders</h3>
                            @forelse($pendingNotifications ?? [] as $order)
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
        </div>

        <!-- PAGE CONTENT -->
        <div class="p-4 sm:p-6">
            <div class="w-full max-w-full">
                @yield('content')
            </div>
        </div>
        </div>

    </main>

</div>


<!-- LOGOUT MODAL -->
<div id="logoutModal" onclick="if(event.target === this) closeLogoutModal()" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-xl w-80 p-6 text-center">

        <!-- ICON -->
        <div class="mx-auto mb-4 w-12 h-12 flex items-center justify-center rounded-full bg-red-100">
            <i data-feather="log-out" class="text-red-500"></i>
        </div>

        <!-- TEXT -->
        <h2 class="text-lg font-semibold mb-2">Logout</h2>
        <p class="text-sm text-gray-500 mb-6">
            Are you sure you want to log out?
        </p>

        <!-- BUTTONS -->
        <div class="flex gap-3">

            <button onclick="closeLogoutModal()"
                class="flex-1 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                Cancel
            </button>

            <form action="{{ route('logout') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">
                    Logout
                </button>
            </form>

        </div>

    </div>

</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

// ✅ OUTSIDE (only once)
window.addEventListener('resize', function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (window.innerWidth >= 768) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
    } else {
        // Only close if it was manually opened (overlay is visible)
        if (!overlay.classList.contains('hidden')) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }
});
</script>

<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
        document.getElementById('logoutModal').classList.add('flex');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
        document.getElementById('logoutModal').classList.remove('flex');
    }
</script>

<script>
    function refreshFeather() {
        if (typeof feather !== 'undefined') feather.replace();
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', refreshFeather);
    } else {
        refreshFeather();
    }
    window.addEventListener('load', refreshFeather);
    window.refreshFeather = refreshFeather;
</script>

<script>
function toggleDropdown(id) {
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        if (el.id !== 'dropdown-' + id) {
            el.classList.add('hidden');
        }
    });

    const el = document.getElementById('dropdown-' + id);
    el.classList.toggle('hidden');
}

// Close when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.relative')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});
</script>

<script>
function toggleOrderExpand(id) {
    const row = document.getElementById('expand-' + id);

    // Close all others (optional but nice UX)
    document.querySelectorAll('[id^="expand-"]').forEach(r => {
        if (r.id !== 'expand-' + id) {
            r.classList.add('hidden');
        }
    });

    // Toggle current
    row.classList.toggle('hidden');
}
</script>

<script>
function openAddModal() {
    document.getElementById('addProductModal').classList.remove('hidden');
    document.getElementById('addProductModal').classList.add('flex');
}

function closeAddModal() {
    document.getElementById('addProductModal').classList.add('hidden');
    document.getElementById('addProductModal').classList.remove('flex');
}
</script>

<script>
function openEditModal(button) {

    const product = JSON.parse(button.dataset.product);

    document.getElementById('editProductForm').action = `/admin/products/${product.product_id}`;

    document.getElementById('edit_name').value = product.product_name;
    document.getElementById('edit_price').value = product.base_price;
    document.getElementById('edit_category').value = product.category;
    document.getElementById('edit_min_quantity').value = product.min_quantity;
    document.getElementById('edit_turnaround').value = product.turnaround;
    document.getElementById('edit_status').value = product.status;

    document.getElementById('editProductModal').classList.remove('hidden');
    document.getElementById('editProductModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editProductModal').classList.add('hidden');
    document.getElementById('editProductModal').classList.remove('flex');
}
</script>

<script>
function openProductDeleteModal(button) {

    const productId = button.dataset.id;

    console.log(productId);

    document.getElementById('deleteForm').action = `/admin/products/${productId}`;

    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeProductDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
</script>

<script>
function openViewModal(button) {

    const product = JSON.parse(button.dataset.product);

    document.getElementById('view_name').innerText = product.product_name;
    document.getElementById('view_category').innerText = product.category ?? 'N/A';
    document.getElementById('view_price').innerText = parseFloat(product.base_price).toFixed(2);
    document.getElementById('view_min').innerText = product.min_quantity;
    document.getElementById('view_turnaround').innerText = product.turnaround;

    const statusEl = document.getElementById('view_status');
    statusEl.innerText = product.status;

    // Dynamic color
    if (product.status === 'active') {
        statusEl.className = "bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs";
    } else {
        statusEl.className = "bg-gray-200 text-gray-600 px-2 py-1 rounded-full text-xs";
    }

    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewModal').classList.add('flex');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.getElementById('viewModal').classList.remove('flex');
}
</script>

<script>
const addImage = document.getElementById('add_image');
if (addImage) addImage.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('add_preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>

<script>
const editImage = document.getElementById('edit_image');
if (editImage) editImage.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('edit_preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

</script>

<script>
function toggleDay(checkbox) {
    const row = checkbox.closest('.day-row');
    const inputs = row.querySelectorAll('.time-input');
    inputs.forEach(input => {
        input.disabled = !checkbox.checked;
    });
}
</script>

<script>
const notifBtn = document.getElementById('notifBtn');
const notifDropdown = document.getElementById('notifDropdown');

notifBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    
    // Position dropdown below the bell button
    const rect = notifBtn.getBoundingClientRect();
    notifDropdown.style.top = (rect.bottom + 8) + 'px';
    notifDropdown.style.right = (window.innerWidth - rect.right) + 'px';
    
    notifDropdown.classList.toggle('hidden');
});

document.addEventListener('click', function(e) {
    if (!notifDropdown.contains(e.target) && e.target !== notifBtn) {
        notifDropdown.classList.add('hidden');
    }
});
</script>

<script>
function markAsRead(orderId) {
    fetch(`/admin/orders/${orderId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(() => {
        location.reload(); // simple refresh (we can improve later)
    });
}
</script>

<!-- ORDER DETAIL MODAL -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[100] p-4 text-left">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 id="modalOrderId" class="text-xl font-bold">Order Details</h2>
            <button onclick="closeOrderModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <div id="modalContent" class="space-y-6">
                <!-- LOADING -->
                <div class="flex justify-center py-10">
                    <i class="fa-solid fa-spinner fa-spin text-3xl text-pink-500"></i>
                </div>
            </div>
        </div>
        <div class="p-4 border-t bg-gray-50 flex justify-end gap-3">
            <a id="modalEditLink" href="#" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-medium">Edit Order</a>
            <button onclick="closeOrderModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">Close</button>
        </div>
    </div>
</div>

<script>
function openOrderModal(id) {
    const modal = document.getElementById('orderModal');
    const content = document.getElementById('modalContent');
    const title = document.getElementById('modalOrderId');
    const editLink = document.getElementById('modalEditLink');

    title.innerText = `Order #ORD-${id.toString().padStart(3, '0')}`;
    editLink.href = `/admin/orders/${id}/edit`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    content.innerHTML = `<div class="flex justify-center py-10"><i class="fa-solid fa-spinner fa-spin text-3xl text-pink-500"></i></div>`;

    fetch(`/admin/orders/${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(order => {
        const detail = order.order_details[0] || {};
        const product = detail.product || {};
        
        let filesHtml = '<p class="text-gray-400 italic">No files uploaded</p>';
        if (order.file_uploads && order.file_uploads.length > 0) {
            filesHtml = '<ul class="space-y-2">';
            order.file_uploads.forEach(f => {
                filesHtml += `
                    <li class="flex items-center justify-between p-2 bg-gray-50 rounded border">
                        <span class="text-xs truncate max-w-[200px]">${f.file_name}</span>
                        <a href="/storage/${f.file_path}" target="_blank" class="text-[10px] text-blue-500 hover:underline">Download</a>
                    </li>
                `;
            });
            filesHtml += '</ul>';
        }

        content.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Customer Info</h3>
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-sm"><strong>Name:</strong> ${order.customer_name}</p>
                        <p class="text-sm"><strong>Email:</strong> ${order.email}</p>
                        <p class="text-sm"><strong>Phone:</strong> ${order.phone_number}</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Order Info</h3>
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-sm"><strong>Status:</strong> <span class="capitalize">${order.status.replace('_', ' ')}</span></p>
                        <p class="text-sm"><strong>Delivery:</strong> <span class="capitalize">${order.delivery_type}</span></p>
                        <p class="text-sm"><strong>Date:</strong> ${new Date(order.created_at).toLocaleDateString()}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Printing Details</h3>
                <div class="bg-white border rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left">Service</th>
                                <th class="px-4 py-2 text-left">Options</th>
                                <th class="px-4 py-2 text-right">Qty</th>
                                <th class="px-4 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-3">${product.product_name || 'N/A'}</td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    Size: ${detail.size}<br>
                                    Color: ${detail.color}<br>
                                    Paper: ${detail.paper_quality}
                                </td>
                                <td class="px-4 py-3 text-right">${detail.quantity}</td>
                                <td class="px-4 py-3 text-right font-medium">₱${parseFloat(order.total_amount).toLocaleString(undefined, {minimumFractionDigits:2})}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Special Instructions</h3>
                    <p class="text-sm p-3 bg-yellow-50 rounded-xl border border-yellow-100 italic">${detail.special_instruction || 'No instructions provided.'}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Uploaded Files</h3>
                    ${filesHtml}
                </div>
            </div>
        `;
    });
}

function closeOrderModal() {
    document.getElementById('orderModal').classList.add('hidden');
    document.getElementById('orderModal').classList.remove('flex');
}
</script>

</body>
</html>
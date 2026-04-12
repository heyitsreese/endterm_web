<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client - Sprint PHL</title>
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
            <div class="flex items-center gap-3 px-6 h-[88px] border-b">
                <img src="{{ asset('images/logo.jpg') }}"
                    class="w-10 h-10 rounded-full object-cover">
                <h1 class="font-semibold text-lg">Sprint PHL</h1>
            </div>

            <!-- NAV -->
            <nav class="p-4 space-y-2 text-sm">

                <a href="{{ route('client.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-xl 
                    {{ request()->is('client/dashboard') ? 'bg-pink-100 text-pink-600 font-medium' : 'hover:bg-gray-100' }}">
                    <i data-feather="grid"></i> Dashboard
                </a>

                <a href="{{ route('client.create-order') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-gray-100">
                    <i data-feather="plus-circle"></i> Create Order
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-gray-100">
                    <i data-feather="shopping-bag"></i> My Orders
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-gray-100">
                    <i data-feather="user"></i> Profile
                </a>

            </nav>
        </div>

        <!-- BOTTOM PROFILE -->
        <div class="p-4 border-t flex items-center justify-between">

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-pink-400 text-white flex items-center justify-center font-bold">
                    {{ strtoupper(substr(session('user_name') ?? 'U', 0, 2)) }}
                </div>

                <div>
                    <p class="text-sm font-medium">{{ session('user_name') ?? 'Client' }}</p>
                    <p class="text-xs text-gray-500">{{ session('user_email') ?? '' }}</p>
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
        <div class="px-6 h-[88px] border-b border-gray-200 flex items-center">
            <div class="flex items-center w-full">

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
    feather.replace()
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

</body>
</html>
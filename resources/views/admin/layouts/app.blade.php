<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Sprint PHL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://kit.fontawesome.com/97c3b6d53c.js" crossorigin="anonymous"></script>
</head>

<body class="bg-white">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between">

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
                        {{ $totalOrders ?? 0 }}
                    </span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-gray-100">
                    <i data-feather="users"></i>
                    Clients
                </a>

                <a href="{{ url('admin/products') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl {{ request()->is('admin/products') ? 'bg-pink-100 text-pink-600 font-medium' : 'hover:bg-gray-100' }}">
                    <i data-feather="box"></i>
                    Products
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-gray-100">
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

    <!-- MAIN CONTENT -->
    <main class="flex-1">

        <!-- PAGE HEADER -->
        <div class="px-6 py-4 border-b border-gray-200">
            @yield('header')
        </div>

        <!-- PAGE CONTENT -->
        <div class="p-6">
            @yield('content')
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
function calculateTotal() {
    let total = 0;

    document.querySelectorAll('.order-item').forEach(item => {
        const productName = item.querySelector('.product-name').innerText.trim();
        const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;

        const price = basePrices[productName] || 0;

        total += price * quantity;
    });

    document.getElementById('totalAmount').value = total.toFixed(2);
}

// trigger on change
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('input', calculateTotal);
});

// initial compute
calculateTotal();
</script>

<script>
const basePrices = {
    "Business Cards": 30,
    "Flyers": 50,
    "Posters": 20,
    "Brochures": 70,
    "Banners": 150,
    "Booklets": 130
};

function calculateTotal() {
    let total = 0;

    document.querySelectorAll('.order-item').forEach(item => {

        const productName = item.querySelector('.product-name').innerText.trim();
        const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;
        const color = item.querySelector('.color-input').value;
        const quality = item.querySelector('.quality-input').value;

        let basePrice = basePrices[productName] || 0;

        // discount
        let discountRate = 0;
        if (quantity >= 500) {
            discountRate = 0.20;
        } else if (quantity >= 100) {
            discountRate = 0.10;
        }

        let discountedPrice = basePrice - (basePrice * discountRate);

        // add-ons
        let colorFee = (color === 'Full Color') ? 10 : 0;

        const qualityFees = {
            "Matte": 0,
            "Glossy": -5,
            "Premium": 20
        };

        let qualityFee = qualityFees[quality] || 0;

        let finalPricePerUnit = discountedPrice + colorFee + qualityFee;

        let subtotal = finalPricePerUnit * quantity;

        total += subtotal;
    });

    document.getElementById('totalAmount').value = total.toFixed(2);
}

// listeners
document.querySelectorAll('.quantity-input, .color-input, .quality-input')
    .forEach(input => input.addEventListener('input', calculateTotal));

// initial
calculateTotal();
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
function openDeleteModal(button) {

    const productId = button.dataset.id;

    console.log(productId);

    document.getElementById('deleteForm').action = `/admin/products/${productId}`;

    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
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
document.getElementById('add_image').addEventListener('change', function(e) {
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
document.getElementById('edit_image').addEventListener('change', function(e) {
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

</body>
</html>
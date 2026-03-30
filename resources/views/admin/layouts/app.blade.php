<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Sprint PHL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r flex flex-col justify-between">

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
                <a href="dashboard" class="flex items-center gap-3 px-4 py-2 rounded-xl bg-pink-100 text-pink-600 font-medium">
                    <i data-feather="grid"></i>
                    Dashboard
                </a>

                <!-- ORDERS -->
                <a href="#" class="flex items-center justify-between px-4 py-2 rounded-xl hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        <i data-feather="shopping-bag"></i>
                        Orders
                    </div>

                    <!-- BADGE -->
                    <span class="bg-pink-400 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $totalOrders ?? 0 }}
                    </span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-gray-100">
                    <i data-feather="users"></i>
                    Clients
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-gray-100">
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
    <main class="flex-1 p-6">

        <!-- TOPBAR -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold">Dashboard</h1>

            <input type="text"
                   placeholder="Search..."
                   class="border rounded-lg px-3 py-1 text-sm">
        </div>

        @yield('content')

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

</body>
</html>
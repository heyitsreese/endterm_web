@extends('admin.layouts.app')

@section('content')

<!-- HEADER -->

@section('header')
<div class="flex items-center gap-2 min-w-0">
    <button onclick="toggleSidebar()" class="md:hidden text-gray-600 shrink-0">
        <i data-feather="menu"></i>
    </button>
    <div class="min-w-0">
        <h1 class="text-lg sm:text-2xl font-semibold truncate">Clients</h1>
        <p class="text-xs text-gray-500 hidden sm:block">View all your clients here.</p>
    </div>
</div>
@endsection

<div class="p-6">

    <h1 class="text-2xl font-semibold mb-1">Clients</h1>
    <p class="text-gray-500 mb-6">View and manage your clients</p>

    <h2 class="text-xl font-semibold mb-4">Client Management</h2>

    <!-- STATS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl border">
            <p class="text-gray-500 text-sm">Total Clients</p>
            <h2 class="text-xl font-bold">{{ $totalClients ?? 0 }}</h2>
        </div>

        <div class="bg-white p-4 rounded-xl border">
            <p class="text-gray-500 text-sm">Active This Month</p>
            <h2 class="text-xl font-bold">{{ $activeClients ?? 0 }}</h2>
        </div>

        <div class="bg-white p-4 rounded-xl border">
            <p class="text-gray-500 text-sm">New This Month</p>
            <h2 class="text-xl font-bold">{{ $newClients ?? 0 }}</h2>
        </div>

        <div class="bg-white p-4 rounded-xl border">
            <p class="text-gray-500 text-sm">Avg. Order Value</p>
            <h2 class="text-xl font-bold">₱ {{ number_format($avgOrderValue ?? 0, 0) }}</h2>
        </div>
    </div>

    <!-- FILTER -->
    <div class="flex justify-end items-center gap-3 mb-6">
        <form method="GET" action="{{ route('admin.clients') }}">
            <select name="type" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-4 py-2 bg-white text-sm">
                <option value="">All Clients</option>
                <option value="registered" {{ request('type')=='registered'?'selected':'' }}>Registered</option>
                <option value="walkin" {{ request('type')=='walkin'?'selected':'' }}>Walk-in</option>
            </select>
        </form>

        <div class="relative">
            <!-- EXPORT BUTTON -->
            <button onclick="toggleExportMenu()" 
                class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-xl text-sm hover:bg-gray-100">

                <!-- ICON -->
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="w-4 h-4" 
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M12 16v-8m0 0l-3 3m3-3l3 3M4 20h16"/>
                </svg>

                Export
            </button>

            <!-- DROPDOWN MENU -->
            <div id="exportMenu" 
                class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-md z-50">

                <a href="{{ route('admin.clients.export', ['type' => 'all']) }}" 
                class="block px-4 py-2 hover:bg-gray-100">
                Export All
                </a>

                <a href="{{ route('admin.clients.export', ['type' => 'registered']) }}" 
                class="block px-4 py-2 hover:bg-gray-100">
                Registered Only
                </a>

                <a href="{{ route('admin.clients.export', ['type' => 'walkin']) }}" 
                class="block px-4 py-2 hover:bg-gray-100">
                Walk-in Only
                </a>

            </div>
        </div>
    </div>

    <!-- ===================== -->
    <!-- REGISTERED CLIENTS -->
    <!-- ===================== -->
     @if(!request('type') || request('type') == 'registered')
    <h2 class="text-blue-600 text-xl font-semibold mb-3">Registered Clients</h2>

    <div class="bg-white border rounded-xl overflow-visible mb-8">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-3">Client</th>
                    <th class="text-left px-4 py-3">Contact</th>
                    <th class="text-left px-4 py-3">Total Orders</th>
                    <th class="text-left px-4 py-3">Total Spent</th>
                    <th class="text-left px-4 py-3">Join Date</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @foreach($registeredClients as $client)

                <tr class="border-t">
                    <td class="px-4 py-3 font-medium">
                        {{ $client->name }}
                    </td>

                    <td class="px-4 py-3">{{ $client->email }}</td>
                    <td class="px-4 py-3">{{ $client->orders_count }}</td>
                    <td class="px-4 py-3">
                        ₱ {{ number_format($client->orders_sum_total_amount ?? 0, 2) }}
                    </td>
                    <td class="px-4 py-3">{{ $client->created_at }}</td>

                    <td class="px-4 py-3 text-right">
                        <button
                            onclick="toggleRow('reg-row-{{ $loop->index }}')"
                            class="p-2 rounded hover:bg-gray-100">
                            ⋮
                        </button>
                    </td>
                </tr>

                <!-- EXPAND ACTIONS -->
                <tr id="reg-row-{{ $loop->index }}" class="hidden bg-gray-50">
                    <td colspan="6" class="px-6 py-4">
                        <div class="flex justify-end gap-3">

                            <!-- VIEW -->
                            <a href="{{ route('admin.clients.show', $client->user_id) }}"
                                class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg">
                                View
                            </a>

                            <!-- EDIT -->
                            <a href="{{ route('admin.clients.edit', $client->user_id) }}"
                                class="px-4 py-2 bg-yellow-100 text-yellow-600 rounded-lg">
                                Edit
                            </a>

                            <!-- DELETE -->
                            <form method="POST"
                                action="{{ route('admin.clients.destroy', $client->user_id) }}">
                                @csrf
                                @method('DELETE')

                                <button
                                    class="px-4 py-2 bg-red-100 text-red-600 rounded-lg">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- ===================== -->
    <!-- WALK-IN CLIENTS -->
    <!-- ===================== -->
     @if(!request('type') || request('type') == 'walkin')
    <h2 class="text-pink-500 text-xl font-semibold mb-3">Walk-in Clients</h2>

    <div class="bg-white border rounded-xl">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-3">Client</th>
                    <th class="text-left px-4 py-3">Contact</th>
                    <th class="text-left px-4 py-3">Total Orders</th>
                    <th class="text-left px-4 py-3">Total Spent</th>
                    <th class="text-left px-4 py-3">Last Order</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @foreach($walkInClients as $client)

                <tr class="border-t">
                    <td class="px-4 py-3 font-medium">{{ $client->customer_name }}</td>
                    <td class="px-4 py-3">{{ $client->email }}</td>
                    <td class="px-4 py-3">{{ $client->orders_count }}</td>
                    <td class="px-4 py-3">₱ {{ number_format($client->total_spent, 2) }}</td>
                    <td class="px-4 py-3">{{ $client->last_order }}</td>

                    <td class="px-4 py-3 text-right">
                        <button onclick="toggleRow('walk-row-{{ $loop->index }}')"
                            class="p-2 rounded hover:bg-gray-100">
                            ⋮
                        </button>
                    </td>
                </tr>

                <!-- EXPAND -->
                <tr id="walk-row-{{ $loop->index }}" class="hidden bg-gray-50">
                    <td colspan="6" class="px-6 py-4">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.orders.show', $client->latest_order_id) }}"
                                class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg">View</a>

                            <a href="{{ route('admin.orders.edit', $client->latest_order_id) }}"
                                class="px-4 py-2 bg-yellow-100 text-yellow-600 rounded-lg">Edit</a>

                            <form method="POST" action="{{ route('admin.orders.destroy', $client->latest_order_id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="px-4 py-2 bg-red-100 text-red-600 rounded-lg">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endif

<!-- ===================== -->
<!-- SCRIPTS -->
<!-- ===================== -->
@push('scripts')
<script>
function toggleRow(id) {
    document.querySelectorAll('[id^="walk-row-"]').forEach(row => {
        if (row.id !== id) row.classList.add('hidden');
    });

    const row = document.getElementById(id);
    if (row) row.classList.toggle('hidden');
}

function toggleDropdown(id) {
    const dropdown = document.getElementById(id);

    document.querySelectorAll('[id^="dropdown-reg-"]').forEach(el => {
        if (el.id !== id) el.classList.add('hidden');
    });

    if (dropdown) dropdown.classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('[data-dropdown]')) {
        document.querySelectorAll('[id^="dropdown-reg-"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});
</script>

<script>
function toggleExportMenu() {
    document.getElementById('exportMenu').classList.toggle('hidden');
}

// close when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.relative')) {
        document.getElementById('exportMenu').classList.add('hidden');
    }
});
</script>
@endpush

@endsection
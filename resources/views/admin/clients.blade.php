@extends('admin.layouts.app')

@section('header')
<div class="flex items-center gap-2 min-w-0">
    <button onclick="toggleSidebar()" class="md:hidden text-gray-600 shrink-0">
        <i data-feather="menu"></i>
    </button>
    <div class="min-w-0">
        <h1 class="text-lg sm:text-2xl font-semibold truncate">Clients</h1>
        <p class="text-xs text-gray-500 hidden sm:block">View and manage your clients</p>
    </div>
</div>
@endsection

@section('content')

<!-- PAGE HEADING + ACTIONS -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-semibold">Client Management</h2>
        <p class="text-sm text-gray-500">View and manage your client accounts</p>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" onclick="toggleClientsFilter()" class="flex items-center gap-2 px-4 py-2 text-sm border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition">
            <i data-feather="filter" class="w-4 h-4"></i> Filter
        </button>
        <button type="button" onclick="exportClients()" class="flex items-center gap-2 px-4 py-2 text-sm border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition">
            <i data-feather="download" class="w-4 h-4"></i> Export
        </button>
    </div>
</div>

<div id="clientsFilterPanel" class="hidden bg-white border rounded-xl p-4 mb-4">
    <input type="text" id="clientFilterInput" oninput="applyClientFilter()" placeholder="Filter by name or email..." class="w-full sm:w-72 border rounded-lg px-3 py-2 text-sm">
</div>

<!-- STAT CARDS -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">

    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Clients</p>
            <p class="text-2xl font-semibold text-gray-800">{{ $totalClients }}</p>
        </div>
        <div class="text-pink-400">
            <i data-feather="users" class="w-8 h-8"></i>
        </div>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-500 mb-1">Active This Month</p>
            <p class="text-2xl font-semibold text-gray-800">{{ $activeThisMonth }}</p>
        </div>
        <div class="text-green-400">
            <i data-feather="check-circle" class="w-8 h-8"></i>
        </div>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-500 mb-1">New This Month</p>
            <p class="text-2xl font-semibold text-gray-800">{{ $newThisMonth }}</p>
        </div>
        <div class="text-blue-400">
            <i data-feather="trending-up" class="w-8 h-8"></i>
        </div>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-500 mb-1">Avg. Order Value</p>
            <p class="text-2xl font-semibold text-gray-800">P {{ number_format($avgOrderValue, 0) }}</p>
        </div>
        <div class="text-green-500">
            <i data-feather="dollar-sign" class="w-8 h-8"></i>
        </div>
    </div>

</div>

<!-- TABS -->
<div class="bg-white border border-gray-100 rounded-xl shadow-sm">

    <div class="flex border-b border-gray-100 px-5 pt-4 gap-4">
        <button onclick="switchTab('registered')" id="tab-registered"
            class="pb-3 text-sm font-medium border-b-2 border-pink-500 text-pink-500 transition tab-btn">
            Registered Clients
        </button>
        <button onclick="switchTab('walkin')" id="tab-walkin"
            class="pb-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition tab-btn">
            Walk-in Clients
        </button>
    </div>

    <!-- REGISTERED CLIENTS -->
    <div id="panel-registered">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                        <th class="px-5 py-3 font-medium">Client</th>
                        <th class="px-5 py-3 font-medium">Contact</th>
                        <th class="px-5 py-3 font-medium">Total Orders</th>
                        <th class="px-5 py-3 font-medium">Total Spent</th>
                        <th class="px-5 py-3 font-medium">Join Date</th>
                        <th class="px-5 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($registeredClients as $client)
                    <tr class="hover:bg-gray-50 transition">

                        <!-- NAME + EMAIL -->
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-pink-100 text-pink-400 flex items-center justify-center font-semibold text-sm shrink-0">
                                    {{ strtoupper(substr($client->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $client->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $client->email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- CONTACT -->
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-1 text-gray-600">
                                <span class="flex items-center gap-1 text-xs">
                                    <i data-feather="mail" class="w-3 h-3 text-gray-400"></i>
                                    {{ $client->email }}
                                </span>
                                <span class="flex items-center gap-1 text-xs">
                                    <i data-feather="phone" class="w-3 h-3 text-gray-400"></i>
                                    {{ $client->phone ?? '—' }}
                                </span>
                            </div>
                        </td>

                        <!-- TOTAL ORDERS -->
                        <td class="px-5 py-4 text-gray-700">
                            {{ $client->orders_count }}
                        </td>

                        <!-- TOTAL SPENT -->
                        <td class="px-5 py-4 text-gray-800 font-medium">
                            P {{ number_format($client->orders_sum_total_amount ?? 0, 0) }}
                        </td>

                        <!-- JOIN DATE -->
                        <td class="px-5 py-4 text-gray-600">
                            {{ $client->created_at->format('Y-m-d') }}
                        </td>

                        <!-- ACTIONS -->
                        <td class="px-5 py-4">
                            <div class="relative">
                                <button type="button" onclick="toggleDropdown('reg-{{ $client->user_id }}')"
                                    class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                                    <i data-feather="more-vertical" class="w-4 h-4"></i>
                                </button>
                                <div id="dropdown-reg-{{ $client->user_id }}"
                                    class="hidden absolute right-0 mt-1 w-36 bg-white border border-gray-100 rounded-xl shadow-lg z-10 py-1 text-sm">
                                    <button type="button"
                                        onclick='viewClient(@json($client))'
                                        class="w-full text-left px-4 py-2 hover:bg-gray-50 text-gray-700 flex items-center gap-2">
                                        <i data-feather="eye" class="w-3.5 h-3.5"></i> View
                                    </button>
                                    <button type="button"
                                        onclick='editClient(@json($client))'
                                        class="w-full text-left px-4 py-2 hover:bg-gray-50 text-gray-700 flex items-center gap-2">
                                        <i data-feather="edit-2" class="w-3.5 h-3.5"></i> Edit
                                    </button>
                                    <button type="button"
                                        onclick="deleteClient('{{ $client->user_id }}', '{{ addslashes($client->name) }}')"
                                        class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-500 flex items-center gap-2">
                                        <i data-feather="trash-2" class="w-3.5 h-3.5"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No registered clients found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($registeredClients->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $registeredClients->links() }}
        </div>
        @endif
    </div>

    <!-- WALK-IN CLIENTS -->
    <div id="panel-walkin" class="hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Contact</th>
                        <th class="px-5 py-3 font-medium">Total Orders</th>
                        <th class="px-5 py-3 font-medium">Total Spent</th>
                        <th class="px-5 py-3 font-medium">Last Order</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($walkinClients as $walkin)
                    <tr class="hover:bg-gray-50 transition">

                        <!-- NAME -->
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-400 flex items-center justify-center font-semibold text-sm shrink-0">
                                    {{ strtoupper(substr($walkin->customer_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $walkin->customer_name }}</p>
                                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Walk-in</span>
                                </div>
                            </div>
                        </td>

                        <!-- CONTACT -->
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-1 text-gray-600">
                                <span class="flex items-center gap-1 text-xs">
                                    <i data-feather="mail" class="w-3 h-3 text-gray-400"></i>
                                    {{ $walkin->email ?? '—' }}
                                </span>
                                <span class="flex items-center gap-1 text-xs">
                                    <i data-feather="phone" class="w-3 h-3 text-gray-400"></i>
                                    {{ $walkin->phone_number ?? '—' }}
                                </span>
                            </div>
                        </td>

                        <!-- TOTAL ORDERS -->
                        <td class="px-5 py-4 text-gray-700">
                            {{ $walkin->orders_count }}
                        </td>

                        <!-- TOTAL SPENT -->
                        <td class="px-5 py-4 text-gray-800 font-medium">
                            P {{ number_format($walkin->total_spent, 0) }}
                        </td>

                        <!-- LAST ORDER DATE -->
                        <td class="px-5 py-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($walkin->last_order)->format('Y-m-d') }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No walk-in clients found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- TAB SCRIPT -->
<script>
function switchTab(tab) {
    document.getElementById('panel-registered').classList.add('hidden');
    document.getElementById('panel-walkin').classList.add('hidden');
    document.getElementById('panel-' + tab).classList.remove('hidden');

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-pink-500', 'text-pink-500');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    const active = document.getElementById('tab-' + tab);
    active.classList.add('border-pink-500', 'text-pink-500');
    active.classList.remove('border-transparent', 'text-gray-500');
}

function toggleClientsFilter() {
    document.getElementById('clientsFilterPanel').classList.toggle('hidden');
}

function applyClientFilter() {
    const q = (document.getElementById('clientFilterInput').value || '').toLowerCase();
    document.querySelectorAll('#panel-registered tbody tr, #panel-walkin tbody tr').forEach(tr => {
        if (!tr.querySelector('td')) return;
        tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
}

function exportClients() {
    const visiblePanel = document.querySelector('#panel-registered:not(.hidden), #panel-walkin:not(.hidden)');
    if (!visiblePanel) return;
    const rows = [];
    visiblePanel.querySelectorAll('thead tr, tbody tr').forEach(tr => {
        const cells = [...tr.querySelectorAll('th,td')]
            .map(c => c.innerText.replace(/\s+/g,' ').trim());
        if (cells.some(Boolean)) rows.push(cells);
    });
    const csv = rows.map(r => r.map(c => `"${c.replace(/"/g,'""')}"`).join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `clients-${new Date().toISOString().slice(0,10)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
}

function viewClient(client) {
    alert(
        'Client Details\n\n' +
        'Name: '   + (client.name || '—') + '\n' +
        'Email: '  + (client.email || '—') + '\n' +
        'Phone: '  + (client.phone || '—') + '\n' +
        'Orders: ' + (client.orders_count || 0) + '\n' +
        'Spent: P ' + Number(client.orders_sum_total_amount || 0).toLocaleString()
    );
}

function editClient(client) {
    alert('Edit client #' + client.user_id + ' (' + client.name + ') — coming soon.');
}

function deleteClient(userId, name) {
    if (confirm('Delete client "' + name + '"? This cannot be undone.')) {
        alert('Delete endpoint not implemented yet (user #' + userId + ').');
    }
}
</script>

@endsection
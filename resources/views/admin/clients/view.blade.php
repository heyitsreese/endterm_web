@extends('admin.layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold">Client Details</h1>
        <p class="text-gray-500">Full client information</p>
    </div>

    <a href="{{ route('admin.clients') }}"
        class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
        ← Back
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <!-- CLIENT INFORMATION -->
        <div>
            <h2 class="text-xl font-semibold mb-5">Client Information</h2>

            <div class="space-y-3 text-gray-700">
                <p><strong>Name:</strong> {{ $client->name }}</p>
                <p><strong>Email:</strong> {{ $client->email }}</p>
                <p><strong>Phone:</strong> {{ $client->phone ?? 'N/A' }}</p>
                <p><strong>Join Date:</strong> {{ $client->created_at->format('F d, Y') }}</p>
            </div>
        </div>

        <!-- ORDER SUMMARY -->
        <div>
            <h2 class="text-xl font-semibold mb-5">Order Summary</h2>

            <div class="space-y-3 text-gray-700">
                <p><strong>Total Orders:</strong> {{ $client->orders_count ?? 0 }}</p>
                <p><strong>Total Spent:</strong>
                    P {{ number_format($client->orders_sum_total_amount ?? 0, 2) }}
                </p>

                <p>
                    <strong>Status:</strong>
                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-600 text-sm">
                        Active Client
                    </span>
                </p>
            </div>
        </div>

    </div>

    <!-- RECENT ORDERS -->
    <div class="mt-10">
        <h2 class="text-xl font-semibold mb-5">Recent Orders</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="py-3">Order ID</th>
                        <th class="py-3">Service</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Date</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="py-4">Sample Order</td>
                        <td>Banners</td>
                        <td>
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-600 text-sm">
                                Pending
                            </span>
                        </td>
                        <td>P 5,000</td>
                        <td>{{ now()->format('Y-m-d') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
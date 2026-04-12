@extends('client.layouts.app')

@section('content')

<!-- HEADER -->

@section('header')
<div class="flex items-center justify-between w-full">

    <!-- LEFT SIDE -->
    <div class="flex items-center gap-3">

        <!-- MOBILE MENU -->
        <button onclick="toggleSidebar()" class="md:hidden text-gray-600">
            <i data-feather="menu"></i>
        </button>

        <!-- TITLE -->
        <div class="leading-snug">
            <h1 class="text-xl font-semibold">Dashboard</h1>
            <p class="text-sm text-gray-500">
                Welcome back, {{ session('user_name') ?? 'Client' }}!
            </p>
        </div>

    </div>

</div>
@endsection

<!-- CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    <!-- Total Orders -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h2 class="text-lg font-semibold mb-4">My Orders</h2>

        @if($orders->isEmpty())
            <p class="text-gray-500">You have no orders yet.</p>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">Order ID</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b">
                            <td class="py-2">
                                ORD-{{ str_pad($order->order_id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </td>
                            <td>
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

</div>
@endsection
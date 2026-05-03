@extends('client.layouts.app')

@section('header')
<div class="flex items-center justify-between w-full">
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="md:hidden text-gray-600">
            <i data-feather="menu"></i>
        </button>
        <div class="leading-snug">
            <h1 class="text-xl font-semibold">Track Order</h1>
            <p class="text-sm text-gray-500">Check the status of your orders</p>
        </div>
    </div>
    <a href="{{ route('client.create-order') }}"
        class="flex items-center gap-2 px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white text-sm font-medium rounded-xl transition">
        <i data-feather="plus" class="w-4 h-4"></i> New Order
    </a>
</div>
@endsection

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- SEARCH -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 mb-6">

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-pink-100">
                <i data-feather="search" class="w-5 h-5 text-pink-500"></i>
            </div>
            <div>
                <h2 class="font-semibold">Track Your Order</h2>
                <p class="text-sm text-gray-500">Enter your order ID to see the current status</p>
            </div>
        </div>

        <form action="{{ route('client.track') }}" method="GET" class="flex gap-2">
            <input type="text" name="order_id"
                value="{{ request('order_id') }}"
                placeholder="Enter order ID (e.g., ORD-0005)"
                class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:bg-white">
            <button class="bg-pink-500 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-pink-600 transition">
                Track
            </button>
        </form>

        <p class="text-xs text-gray-400 mt-2">
            Your order ID was sent to your email when you placed the order
        </p>

        <!-- ERROR -->
        @if(request('order_id') && !$order)
            <div class="bg-red-50 border border-red-200 text-red-600 p-3 mt-4 rounded-xl text-sm">
                ❌ Order not found. Please check your ID and try again.
            </div>
        @endif
    </div>

    <!-- RESULT -->
    @if($order)
        @php
            $isPickup = $order->delivery_type === 'pickup';

            if ($isPickup) {
                $statusSteps = [
                    'pending' => 1,
                    'in_progress' => 2,
                    'ready_for_pickup' => 3,
                    'picked_up' => 4,
                ];
            } else {
                $statusSteps = [
                    'pending' => 1,
                    'in_progress' => 2,
                    'out_for_delivery' => 3,
                    'delivered' => 4,
                ];
            }

            $status = strtolower($order->status);
            $currentStep = $statusSteps[$status] ?? 0;

            $isDeclined = $status === 'declined';
        @endphp

        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 mb-6">

            <!-- Order Header -->
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-lg">
                        #ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        Placed on {{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}
                    </p>
                </div>
                @php
                    $badgeColor = match($status) {
                        'delivered', 'picked_up' => 'bg-green-100 text-green-700',
                        'in_progress' => 'bg-blue-100 text-blue-700',
                        'pending' => 'bg-orange-100 text-orange-700',
                        'ready_for_pickup' => 'bg-purple-100 text-purple-700',
                        'out_for_delivery' => 'bg-cyan-100 text-cyan-700',
                        'declined' => 'bg-red-100 text-red-600',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $badgeColor }}">
                    {{ ucwords(str_replace('_', ' ', $status)) }}
                </span>
            </div>

            @if($isDeclined && $order->decline_reason)
                <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4 text-sm text-red-600">
                    <strong>Decline Reason:</strong> {{ $order->decline_reason }}
                </div>
            @endif

            <!-- Progress Steps -->
            @if(!$isDeclined)
            <div class="mt-4">
                <div class="flex items-center justify-between relative">
                    @php
                        if ($isPickup) {
                            $steps = [
                                1 => 'Pending',
                                2 => 'In Progress',
                                3 => 'Ready for Pickup',
                                4 => 'Picked Up',
                            ];
                        } else {
                            $steps = [
                                1 => 'Pending',
                                2 => 'In Progress',
                                3 => 'Out for Delivery',
                                4 => 'Delivered',
                            ];
                        }
                    @endphp

                    @foreach($steps as $step => $label)
                        <div class="flex flex-col items-center flex-1 relative">
                            @if($step != 1)
                                <div class="absolute top-4 -left-1/2 w-full h-1
                                    {{ $currentStep >= $step ? 'bg-green-500' : 'bg-gray-200' }}">
                                </div>
                            @endif

                            <div class="w-8 h-8 flex items-center justify-center rounded-full z-10 text-xs font-bold
                                {{ $currentStep >= $step ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                @if($currentStep >= $step)
                                    <i data-feather="check" class="w-4 h-4"></i>
                                @else
                                    {{ $step }}
                                @endif
                            </div>

                            <span class="text-xs mt-2 text-center {{ $currentStep >= $step ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                                {{ $label }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Order Details -->
            <div class="mt-6 pt-4 border-t border-gray-100 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Delivery</span>
                    <span>{{ ucfirst($order->delivery_type ?? '—') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total</span>
                    <span class="font-semibold text-pink-600">P {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

        </div>
    @endif

    <!-- QUICK LINKS -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
        <h3 class="font-semibold mb-3">Quick Actions</h3>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('client.dashboard') }}"
               class="flex-1 text-center px-4 py-2.5 border border-gray-200 rounded-xl text-sm hover:bg-gray-50 transition">
                <i data-feather="grid" class="w-4 h-4 inline-block mr-1"></i> Back to Dashboard
            </a>
            <a href="mailto:sprintphl@gmail.com?subject=Order%20Inquiry"
               class="flex-1 text-center px-4 py-2.5 border border-gray-200 rounded-xl text-sm hover:bg-gray-50 transition">
                <i data-feather="mail" class="w-4 h-4 inline-block mr-1"></i> Contact Support
            </a>
        </div>
    </div>

</div>

@endsection

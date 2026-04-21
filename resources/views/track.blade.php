<!-- track.blade.php -->
@extends('layouts.content')

@section('content')

<div class="flex flex-col items-center mt-16">

    <!-- ICON -->
    <div class="w-16 h-16 flex items-center justify-center rounded-full mb-4" style="background-color: #E0E7FF;">
        <i class="fa-solid fa-magnifying-glass fa-xl" style="color: #D47497;"></i>
    </div>

    <h1 class="text-3xl font-semibold">Track Your Order</h1>
    <p class="text-gray-500 mt-2">
        Enter your order ID to see the current status
    </p>

    <!-- WRAPPER -->
    <div class="min-h-[60vh] flex items-start justify-center pt-16 w-full">
        <div class="w-full max-w-4xl bg-white shadow-lg rounded-2xl p-6 mb-6">

            <!-- FORM -->
             <span><b>Order ID</b></span>
            <form action="{{ route('track') }}" method="GET" class="flex gap-2">

                <input type="text" name="order_id"
                    value="{{ request('order_id') }}"
                    placeholder="Enter your order ID (e.g., ORD-0005)"
                    class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-300">

                <button class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
                    Track
                </button>

            </form>

            <p class="text-xs text-gray-400 mt-2">
                Your order ID was sent to your email when you placed the order
            </p>

            <!-- ERROR -->
            @if(request('order_id') && !$order)
                <div class="bg-red-100 text-red-600 p-3 mt-4 rounded-lg text-sm">
                    ❌ Order not found. Please check your ID and try again.
                </div>
            @endif

            <!-- EXAMPLE IDS -->
            <div class="border border-pink-200 bg-pink-50 rounded-xl p-4 mt-6">

                <p class="text-sm font-semibold text-pink-600">
                    Try these example order IDs:
                </p>

                <div class="flex flex-wrap gap-4 mt-2 text-sm text-pink-500">

                    <span onclick="fillOrder('ORD-0001')" class="cursor-pointer hover:underline">
                        ORD-0001 (Pending)
                    </span>

                    <span onclick="fillOrder('ORD-0002')" class="cursor-pointer hover:underline">
                        ORD-0002 (In Progress)
                    </span>

                    <span onclick="fillOrder('ORD-0003')" class="cursor-pointer hover:underline">
                        ORD-0003 (Delivered)
                    </span>

                </div>

            </div>

            <!-- RESULT -->
            @if($order)
                @php
                    $isPickup = $order->delivery_type === 'pickup'; // 👈 MUST be here

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
                @endphptStep = $statusSteps[$status] ?? 0;
            @endphp

            <div class="mt-8">

                <p class="font-semibold text-center mb-6 text-gray-700">
                    Status: {{ ucwords(str_replace('_', ' ', $order->status)) }}
                </p>

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

                            <!-- LINE -->
                            @if($step != 1)
                                <div class="absolute top-4 -left-1/2 w-full h-1
                                    {{ $currentStep >= $step ? 'bg-green-500' : 'bg-gray-300' }}">
                                </div>
                            @endif

                            <!-- CIRCLE -->
                            <div class="w-8 h-8 flex items-center justify-center rounded-full z-10
                                {{ $currentStep >= $step ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                                <i class="fa-solid fa-check"></i>
                            </div>

                            <span class="text-xs mt-2 text-center">
                                {{ $label }}
                            </span>
                        </div>

                    @endforeach

                </div>

            </div>

            @endif

            <!-- HELP SECTION -->
            <div class="bg-pink-100 rounded-xl p-6 mt-10">

                <h3 class="font-semibold text-lg mb-2">Need Help?</h3>

                <p class="text-sm text-gray-700">
                    <strong>Can't find your order ID?</strong>
                    Check the confirmation email sent when you placed your order.
                </p>

                <p class="text-sm text-gray-700 mt-2">
                    <strong>Questions?</strong>
                    Contact us at <span class="text-pink-500">sprintphl@gmail.com</span>
                </p>

            </div>

        </div>
    </div>

</div>

<!-- SCRIPT -->
<script>
function fillOrder(id) {
    document.querySelector('input[name="order_id"]').value = id;
}
</script>

@endsection
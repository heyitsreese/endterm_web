@extends('layouts.content')

@section('content')

@php
    $service = session('service');
    $quantity = session('quantity') ?? 0;
    $color = session('color');
    $paperQuality = session('paper_quality');

    $basePrices = [
        'Business Cards' => 30,
        'Flyers' => 50,
        'Posters' => 20,
        'Brochures' => 70,
        'Banners' => 150,
        'Booklets' => 130,
    ];

    $basePrice = $basePrices[$service] ?? 0;

    $discountRate = 0;
    if ($quantity >= 500) {
        $discountRate = 0.20;
    } elseif ($quantity >= 100) {
        $discountRate = 0.10;
    }

    $discountedPrice = $basePrice - ($basePrice * $discountRate);

    $colorFee = $color === 'Full Color' ? 10 : 0;
    $qualityFee = $paperQuality === 'Premium' ? 20 : 0;

    $finalPricePerUnit = $discountedPrice + $colorFee + $qualityFee;

    $subtotal = $finalPricePerUnit * $quantity;
@endphp

<!-- STEPS -->
<section class="bg-white py-6 shadow-sm">
    <div class="max-w-5xl mx-auto flex justify-between items-center text-sm">

        <!-- Step 1 DONE -->
        <div class="text-center">
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto shadow"
                 style="background-color: #D47497;">
                <i class="fa-solid fa-check"></i>
            </div>
            <p class="mt-2 font-medium">Your Details</p>
            <p class="text-gray-400 text-xs">Name & Email</p>
        </div>

        <div class="flex-1 h-1 mx-2" style="background-color: #D47497;"></div>

        <!-- Step 2 DONE -->
        <div class="text-center">
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto shadow"
                 style="background-color: #D47497;">
                <i class="fa-solid fa-check"></i>
            </div>
            <p class="mt-2 font-medium">What to Print</p>
            <p class="text-gray-400 text-xs">Choose Service</p>
        </div>

        <div class="flex-1 h-1 mx-2" style="background-color: #D47497;"></div>

        <!-- Step 3 DONE -->
        <div class="text-center">
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto shadow"
                 style="background-color: #D47497;">
                <i class="fa-solid fa-check"></i></div>
            <p class="mt-2">Upload Files</p>
        </div>

        <div class="flex-1 h-1 mx-2" style="background-color: #D47497;"></div>

        <!-- Step 4 ACTIVE -->
        <div class="text-center">
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto shadow"
                 style="background-color: #D47497;">4</div>
            <p class="mt-2">Finalize</p>
        </div>

    </div>
</section>

<!-- CONTENT -->
<section class="bg-gradient-to-br from-blue-100 to-gray-200 py-12">

<div class="max-w-4xl mx-auto px-6">

    <h1 class="text-3xl font-bold text-center">Almost Done!</h1>
    <p class="text-center text-gray-500 mt-2">
        Review your order and complete payment
    </p>

    <form method="POST" action="{{ route('order.store') }}">
    @csrf

        <!-- ORDER SUMMARY -->

        <input type="hidden" name="service" value="{{ session('service') }}">
        <input type="hidden" name="quantity" value="{{ session('quantity') }}">
        <input type="hidden" name="paper_size" value="{{ session('paper_size') }}">
        <input type="hidden" name="color" value="{{ session('color') }}">
        <input type="hidden" name="paper_quality" value="{{ session('paper_quality') }}">
        <input type="hidden" name="instructions" value="{{ session('instructions') }}">

        <input type="hidden" name="name" value="{{ session('name') }}">
        <input type="hidden" name="email" value="{{ session('email') }}">
        <input type="hidden" name="phone" value="{{ session('phone') }}">
        
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-8 border border-pink-200">

            <h1 class="font-semibold mb-4 flex items-center gap-2 text-2xl">
                <i class="fa-solid fa-cart-shopping"></i> Your Order Summary
            </h1>

            <div class="space-y-3 text-sm">

                <div class="space-y-3 text-sm mb-4">
                    <h2 class="font-semibold mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-user"></i> Customer Details
                    </h2>
                    <div class="flex justify-between bg-gray-100 p-3 rounded-lg">
                        <span><strong>Name:</strong></span>
                        <span>{{ session('name') ?? '—' }}</span>
                    </div>

                    <div class="flex justify-between bg-gray-100 p-3 rounded-lg">
                        <span><strong>Email:</strong></span>
                        <span>{{ session('email') ?? '—' }}</span>
                    </div>

                    @if(session('phone'))
                    <div class="flex justify-between bg-gray-100 p-3 rounded-lg">
                        <span><strong>Phone:</strong></span>
                        <span>{{ session('phone') }}</span>
                    </div>
                    @endif

                </div>

                <h2 class="font-semibold mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-cart-shopping"></i> Order Details
                </h2>

                <div class="flex justify-between bg-gray-100 p-3 rounded-lg">
                    <span><strong>Service:</strong></span>
                    <span>{{ session('service') }}</span>
                </div>

                <div class="flex justify-between bg-gray-100 p-3 rounded-lg">
                    <span><strong>Quantity:</strong></span>
                    <span>{{ session('quantity') }} units</span>
                </div>

                <div class="flex justify-between bg-gray-100 p-3 rounded-lg">
                    <span><strong>Paper:</strong></span>
                    <span>
                        {{ session('paper_size') == 'Custom' ? session('custom_size') : session('paper_size') }}
                    </span>
                </div>

                <div class="flex justify-between bg-gray-100 p-3 rounded-lg">
                    <span><strong>Color:</strong></span>
                    <span>{{ session('color') }}</span>
                </div>

                @if(session('instructions'))
                <div class="flex justify-between bg-gray-100 p-3 rounded-lg">
                    <span><strong>Instructions:</strong></span>
                    <span>{{ session('instructions') }}</span>
                </div>
                @endif

            </div>

            <!-- SUBTOTAL -->
            <div class="mt-4 border-t pt-4 space-y-2 text-sm">

            <!-- Base -->
            <div class="flex justify-between">
                <span>Base Price:</span>
                <span>₱ {{ number_format($basePrice, 2) }}</span>
            </div>

            <!-- Discount -->
            @if($discountRate > 0)
            <div class="flex justify-between text-green-600">
                <span>Bulk Discount ({{ $discountRate * 100 }}%):</span>
                <span>- ₱ {{ number_format($basePrice * $discountRate, 2) }}</span>
            </div>
            @endif

            <!-- Color -->
            @if($colorFee > 0)
            <div class="flex justify-between">
                <span>Full Color Fee:</span>
                <span>+ ₱ {{ number_format($colorFee, 2) }}</span>
            </div>
            @endif

            <!-- Quality -->
            @if($qualityFee > 0)
            <div class="flex justify-between">
                <span>Premium Paper:</span>
                <span>+ ₱ {{ number_format($qualityFee, 2) }}</span>
            </div>
            @endif

            <!-- Divider -->
            <div class="border-t pt-2 flex justify-between font-medium">
                <span>Price per unit:</span>
                <span>₱ {{ number_format($finalPricePerUnit, 2) }}</span>
            </div>

            <!-- Quantity -->
            <div class="flex justify-between text-gray-500">
                <span>Quantity:</span>
                <span>{{ $quantity }}</span>
            </div>

            <!-- Subtotal -->
            <div class="flex justify-between bg-gray-100 p-3 rounded-lg mt-2">
                <span><strong>Subtotal:</strong></span>
                <span>₱ {{ number_format($subtotal, 2) }}</span>
            </div>

        </div>

            <!-- TOTAL -->
            <div class="mt-3 bg-pink-500 text-white p-4 rounded-lg flex justify-between text-lg font-semibold">
                <span>Total:</span>
                <span>₱ {{ number_format($subtotal, 2) }}</span>
            </div>

        </div>

        <!-- COFNRIMATION -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mt-8">

            <h2 class="font-semibold mb-4 flex items-center gap-2">
                <i class="fa-solid fa-envelope-circle-check"></i> What Happens Next?
            </h2>

            <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 p-4 rounded-lg text-sm leading-relaxed">

                <p class="font-medium mb-2">Thank you for your order!</p>

                <p>
                    Once your order is submitted, our team will review your request and verify all details.
                </p>

                <p class="mt-2">
                    <i class="fa-solid fa-envelope"></i> You will receive an email from our store containing:
                </p>

                <ul class="mt-2 ml-4 list-disc space-y-1">
                    <li>Order confirmation</li>
                    <li>Final pricing breakdown</li>
                    <li>Payment instructions</li>
                    <li>Estimated processing time</li>
                </ul>

                <div class="mt-4 p-3 bg-red-50 border border-red-300 rounded-lg text-red-700">
                    ⚠️ <strong>Important:</strong><br>
                    Submitting this order does <strong>not</strong> mean it has been processed immediately.
                    Your order will only begin once it has been paid.
                </div>

            </div>

        </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="text-white px-6 py-3 rounded-lg shadow"
                    style="background-color: #D47497;">
                    Submit Order →
                </button>
            </div>
    </form>

</div>

</section>
@endsection
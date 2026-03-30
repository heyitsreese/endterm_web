@extends('layouts.content')

@section('content')

<section class="bg-gradient-to-br from-blue-100 to-gray-200 min-h-screen py-16 flex items-center">

<div class="max-w-2xl mx-auto w-full px-6">

    <!-- SUCCESS ICON -->
    <div class="text-center">
        <div class="w-20 h-20 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto text-3xl shadow-lg">
            <i class="fa-solid fa-check"></i>
        </div>

        <h1 class="text-3xl font-bold mt-6">Order Placed Successfully!</h1>
        <p class="text-gray-500 mt-2">
            Thank you for your order. We'll get started on it right away.
        </p>
    </div>

    <!-- ORDER ID CARD -->
    <div class="bg-white rounded-2xl shadow-lg mt-8 overflow-hidden">

        <div class="text-center py-3" style="background: #EEF2FF;">
            <p class="font-medium">Your Order ID</p>
            <p class="text-sm text-gray-500">Save this ID to track your order anytime</p>
        </div>

        <div class="p-6 text-center">

            <div class="border-2 border-dashed border-blue-300 rounded-xl py-6 text-pink-500 text-2xl font-bold tracking-widest flex items-center justify-center gap-2">
                {{ $code }}

                <!-- COPY BUTTON -->
                <button onclick="copyOrderID()" class="text-sm text-gray-500 hover:text-black">
                    <i class="fa-regular fa-copy"></i>
                </button>
            </div>

            <p class="text-sm text-gray-500 mt-4">
                <i class="fa-regular fa-envelope"></i>
                Order confirmation sent to: {{ $email ?? 'your@email.com' }}
            </p>

        </div>
    </div>

    <!-- WHAT HAPPENS NEXT -->
    <div class="bg-white rounded-2xl shadow-lg mt-6 p-6">

        <h2 class="font-semibold mb-4">What Happens Next?</h2>

        <div class="space-y-4 text-sm">

            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-pink-200 text-pink-600 flex items-center justify-center text-xs">1</div>
                <div>
                    <p class="font-medium">Email Confirmation</p>
                    <p class="text-gray-500">Check your email for order details</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-pink-200 text-pink-600 flex items-center justify-center text-xs">2</div>
                <div>
                    <p class="font-medium">Order Processing</p>
                    <p class="text-gray-500">Our team will review your files</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-pink-200 text-pink-600 flex items-center justify-center text-xs">3</div>
                <div>
                    <p class="font-medium">Track Your Order</p>
                    <p class="text-gray-500">Use your Order ID anytime</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-pink-200 text-pink-600 flex items-center justify-center text-xs">4</div>
                <div>
                    <p class="font-medium">Delivery</p>
                    <p class="text-gray-500">Expected within 2–3 business days</p>
                </div>
            </div>

        </div>
    </div>

    <!-- BUTTONS -->
    <div class="flex flex-col md:flex-row gap-3 mt-6 border py-3">

        <a href="#" class="flex-1 text-center text-white py-3 rounded-lg" style="background-color: #D47497;">
            <i class="fa-solid fa-magnifying-glass"></i> Track Order
        </a>

        <a href="{{ route('order') }}" class="flex-1 text-center border py-3 bg-white rounded-lg">
            <i class="fa-solid fa-cart-shopping"></i> Place Another Order
        </a>

        <a href="/" class="flex-1 text-center border py-3 bg-white rounded-lg">
            <i class="fa-solid fa-house"></i> Back to Home
        </a>

    </div>

    <!-- FOOTER NOTE -->
    <div class="mt-6 text-center text-sm text-gray-500 border rounded-lg py-3" style="--bs-border-opacity: 0.8; background-color: #EFF6FF; border-color: #BEDBFF !important;">
        Questions? Contact us at 
        <span class="text-pink-500">sprintphl@gmail.com</span>
    </div>

</div>

</section>

<!-- COPY SCRIPT -->
<script>
function copyOrderID() {
    navigator.clipboard.writeText("{{ $code }}");
    alert("Order ID copied!");
}
</script>

@endsection
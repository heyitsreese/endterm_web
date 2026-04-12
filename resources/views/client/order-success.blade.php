@extends('client.layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-6 py-10">

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

    <!-- ORDER ID -->
    <div class="bg-white rounded-2xl shadow-lg mt-8 overflow-hidden">

        <div class="text-center py-3 bg-blue-50">
            <p class="font-medium">Your Order ID</p>
            <p class="text-sm text-gray-500">Save this ID to track your order anytime</p>
        </div>

        <div class="p-6 text-center">

            <div class="border-2 border-dashed border-blue-300 rounded-xl py-6 text-pink-500 text-2xl font-bold tracking-widest flex items-center justify-center gap-2">
                {{ $code }}

                <button onclick="copyOrderID()" class="text-sm text-gray-500 hover:text-black">
                    <i class="fa-regular fa-copy"></i>
                </button>
            </div>

            <p class="text-sm text-gray-500 mt-4">
                <i class="fa-regular fa-envelope"></i>
                An admin will send an order confirmation to
                <b>{{ $order->email ?? session('user_email') }}</b>
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
    <div class="flex flex-col md:flex-row gap-3 mt-6">

        <a href="#"
            class="flex-1 text-center text-white py-3 rounded-lg"
            style="background-color: #D47497;">
            <i class="fa-solid fa-magnifying-glass"></i> View Order
        </a>

        <a href="{{ route('client.create-order') }}"
            class="flex-1 text-center border py-3 bg-white rounded-lg">
            <i class="fa-solid fa-cart-shopping"></i> Create Another
        </a>

        <a href="{{ route('client.dashboard') }}"
            class="flex-1 text-center border py-3 bg-white rounded-lg">
            <i class="fa-solid fa-house"></i> Dashboard
        </a>

    </div>

</div>

<div id="copyModal"
     class="fixed inset-0 flex items-center justify-center bg-black/30 opacity-0 pointer-events-none transition-all duration-300 z-50">

    <div class="bg-white px-6 py-4 rounded-xl shadow-xl flex items-center gap-2 scale-90 transition-all duration-300">
        <i class="fa-solid fa-check text-pink-500"></i>
        <span class="font-medium">Copied!</span>
    </div>

</div>

<!-- COPY SCRIPT -->
<script>
function copyOrderID() {
    navigator.clipboard.writeText("{{ $code }}");

    const modal = document.getElementById('copyModal');
    const box = modal.firstElementChild;

    // show
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100');

    box.classList.remove('scale-90');
    box.classList.add('scale-100');

    // hide after 1.5s
    setTimeout(() => {
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0', 'pointer-events-none');

        box.classList.remove('scale-100');
        box.classList.add('scale-90');
    }, 1500);
}
</script>

@endsection
<!-- welcome.blade.php -->

@extends('layouts.content')

@section('content')

<!-- HERO -->
<section class="grid md:grid-cols-2 gap-10 px-8 py-16 items-center bg-gradient-to-br from-blue-100 to-gray-200">
    <div>
        <h1 class="text-4xl font-bold leading-tight">
            Professional Printing Made Simple
        </h1>
        <p class="mt-4 text-gray-600">
            High-quality printing services for businesses and individuals.
        </p>

        <div class="mt-6 flex gap-4">
            <a href="{{ url('/order') }}" class="text-white px-6 py-3 rounded-lg" style="background-color: #D47497; font-weight: 500">
                Start Your Order <i class="fa-solid fa-arrow-right-long"></i>
            </a>
            <a href="{{ url('/track') }}" class="bg-white px-6 py-3 rounded-lg border" style="font-weight: 500">
                Track Existing Order
            </a>
        </div>

        <div class="mt-6 flex gap-6 text-sm text-gray-600">
            <span><i class="fa-regular fa-circle-check fa-lg" style="color: green"></i> No Account Required</span>
            <span><i class="fa-regular fa-circle-check fa-lg" style="color: green"></i> Instant Order ID</span>
            <span><i class="fa-regular fa-circle-check fa-lg" style="color: green"></i> Easy Tracking</span>
        </div>
    </div>

    <!-- IMAGE PLACEHOLDER -->
    <img src="{{ asset('images/sprintphl.png') }}" class="w-full h-64 object-cover rounded-xl" alt="Hero Image">
</section>

<!-- FEATURES -->
<section class="grid grid-cols-2 md:grid-cols-3 text-center gap-6 px-8 py-12 bg-white">
    <div>
        <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center" style="background: #E0E7FF;">
            <i data-feather="clock" class="w-5 h-5 text-pink-600"></i>
        </div>
        <h3 class="mt-2 font-semibold">Fast Turnaround</h3>
        <p class="text-sm text-gray-500">2-3 business days</p>
    </div>

    <div>
        <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center" style="background: #E0E7FF;">
            <i data-feather="shield" class="w-5 h-5 text-pink-600"></i>
        </div>
        <h3 class="mt-2 font-semibold">Quality Guaranteed</h3>
        <p class="text-sm text-gray-500">100% satisfaction</p>
    </div>

    <div>
        <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center" style="background: #E0E7FF;">
            <i data-feather="truck" class="w-5 h-5 text-pink-600"></i>
        </div>
        <h3 class="mt-2 font-semibold">Free Delivery</h3>
        <p class="text-sm text-gray-500">Orders over ₱250</p>
    </div>
</section>

<!-- SERVICES -->
<section class="px-8 py-16" id="services">
    <h2 class="text-2xl font-bold text-center">Our Printing Services</h2>
    <p class="text-center text-gray-500 mb-10">
        Choose from our wide range of services
    </p>

    <div class="grid md:grid-cols-3 gap-6">

@foreach($products as $product)

<div class="bg-white rounded-2xl shadow-sm overflow-hidden border">

    <!-- IMAGE -->
    <img 
        src="
        @if($product->image)
            {{ asset('storage/'.$product->image) }}
        @elseif($product->category == 'Cards')
            {{ asset('images/card.jpg') }}
        @elseif($product->category == 'Marketing')
            {{ asset('images/flyer.jpg') }}
        @elseif($product->category == 'Large Format')
            {{ asset('images/posters.jpg') }}
        @else
            {{ asset('images/default.jpg') }}
        @endif
        "
        class="w-full h-44 object-cover">

    <div class="p-5">

        <!-- NAME + PRICE -->
        <div class="flex justify-between items-center">
            <h3 class="font-semibold text-lg">{{ $product->product_name }}</h3>
            <span class="text-pink-500 text-sm font-medium">
                From ₱{{ number_format($product->base_price, 2) }}
            </span>
        </div>

        <!-- DESCRIPTION -->
        <p class="text-sm text-gray-500 mt-2">
            {{ $product->category }} printing service
        </p>

        <!-- FEATURES -->
        <ul class="mt-4 space-y-2 text-sm text-gray-600">
            <li>Min Qty: {{ $product->min_quantity }}</li>
            <li>Turnaround: {{ $product->turnaround }}</li>
        </ul>

        <!-- BUTTON -->
       <a href="{{ url('/order?product_id='.$product->product_id) }}"
        class="mt-5 block text-center w-full text-white py-2 rounded-lg hover:bg-pink-600 transition"
        style="background-color: #D47497;">
            Order Now
        </a>

    </div>

</div>

@endforeach

</div>
</section>

<!-- HOW IT WORKS -->
<section class="px-8 py-16 bg-white text-center">
    <h2 class="text-2xl font-bold">How It Works</h2>
    <p class="text-gray-500 mb-10">Simple ordering process</p>

    <div class="grid md:grid-cols-4 gap-6">
        <div>
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto" style="background-color: #D47497;">1</div>
            <p class="mt-2">Choose Service</p>
        </div>

        <div>
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto" style="background-color: #D47497;">2</div>
            <p class="mt-2">Enter Email</p>
        </div>

        <div>
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto" style="background-color: #D47497;">3</div>
            <p class="mt-2">Get Order ID</p>
        </div>

        <div>
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto" style="background-color: #D47497;">4</div>
            <p class="mt-2">Track Order</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="text-center py-16 bg-[linear-gradient(to_right,_#F7A4C3,_#CA1959)] text-white">
    <h2 class="text-5xl">Ready to Get Started?</h2>
    <p class="mt-2 text-2xl">Order now without signing up, or create an account for easier tracking and business features</p>

    <div class="mt-6 flex justify-center gap-4">
        <button class="bg-white px-6 py-2 rounded-lg" style="color:#D47497; font-weight: 500">
            <i class="fa-solid fa-cart-shopping"></i> Quick Order
        </button>
    </div>
</section>
@endsection
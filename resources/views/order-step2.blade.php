@extends('layouts.content')

@section('content')

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
                2
            </div>
            <p class="mt-2 font-medium">What to Print</p>
            <p class="text-gray-400 text-xs">Choose Service</p>
        </div>

        <div class="flex-1 h-px bg-gray-300 mx-2"></div>

        <!-- Step 3 -->
        <div class="text-center opacity-40">
            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mx-auto">3</div>
            <p class="mt-2">Upload Files</p>
        </div>

        <div class="flex-1 h-px bg-gray-300 mx-2"></div>

        <!-- Step 4 -->
        <div class="text-center opacity-40">
            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mx-auto">4</div>
            <p class="mt-2">Finalize</p>
        </div>

    </div>
</section>

<!-- CONTENT -->
<section class="bg-gradient-to-br from-blue-100 to-gray-200 py-12">

<div class="max-w-4xl mx-auto px-6">

    <h1 class="text-3xl font-bold text-center">What Would You Like to Print?</h1>
    <p class="text-center text-gray-500 mt-2">
        Choose your printing options below
    </p>

    <!-- ✅ FORM START -->
    <form method="POST" action="{{ route('order.step2.store') }}">
        @csrf

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">

            <!-- TITLE -->
            <div class="flex items-center gap-2 mb-6">
                <i class="fa-regular fa-file text-pink-500"></i>
                <h2 class="text-lg font-semibold">Printing Details</h2>
            </div>

            <!-- SERVICE SELECT -->
            <h3 class="font-medium mb-4">Select Printing Service<span class="text-red-600">*</span></h3>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                    @foreach($products as $product)
                        <div class="service-card"
                            data-value="{{ $product->product_id }}">

                            <p class="font-medium leading-tight">
                                {{ $product->product_name }}
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                ₱{{ number_format($product->base_price, 2) }}
                            </p>

                        </div>
                    @endforeach
                </div>

            <!-- ✅ hidden input (unchanged but now inside form) -->
            <input type="hidden" name="product_id" id="product_id" value="{{ session('product_id') }}">

            <!-- QUANTITY -->
            <div class="mb-6">
                <label class="font-medium flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-gray-400"></i>
                    How Many Do You Need?<span class="text-red-600">*</span>
                </label>
                <input type="number" name="quantity"
                required
                value="{{ session('quantity') }}"
                placeholder="e.g., 100"
                class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 focus:ring-2 focus:ring-pink-400">
                <p class="text-xs text-gray-400 mt-1">
                    💡 Larger quantities often get better prices!
                </p>
            </div>

            <!-- PAPER SIZE -->
            <label class="font-medium flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-gray-400"></i>
                    Paper Size<span class="text-red-600">*</span>
            </label>
            <div class="grid grid-cols-2 gap-4 mb-4">

                <div class="option-card paper-size active" data-value="A4">
                    <p class="font-medium">A4 Size</p>
                    <p class="text-sm text-gray-500">210 × 297 mm (Standard)</p>
                </div>

                <div class="option-card paper-size" data-value="A5">
                    <p class="font-medium">A5 Size</p>
                    <p class="text-sm text-gray-500">148 × 210 mm (Smaller)</p>
                </div>

                <div class="option-card paper-size" data-value="Letter">
                    <p class="font-medium">Letter Size</p>
                    <p class="text-sm text-gray-500">8.5 × 11 inches (US)</p>
                </div>

                <div class="option-card paper-size" data-value="Custom">
                    <p class="font-medium">Custom Size</p>
                    <p class="text-sm text-gray-500">Enter your own size</p>
                </div>

            </div>

            <div id="custom-size-input" class="mt-4 hidden">
                <label class="text-sm font-medium">Enter Custom Size</label>
                <input type="text"
                    name="custom_size"
                    placeholder="e.g., 5 x 7 inches"
                    class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 focus:ring-2 focus:ring-pink-400">
            </div>

            <input type="hidden" name="paper_size" id="paper_size" required value="{{ session('paper_size') ?? 'A4' }}">

            <!-- COLOR -->
            <div class="mb-6">
                <label class="font-medium flex items-center gap-2 mb-3">
                    <i class="fa-solid fa-palette text-gray-400"></i>
                    Color Options<span class="text-red-600">*</span>
                </label>

                <div class="grid grid-cols-2 gap-4">
                    <div class="option-card color-option active" data-value="Full Color">🎨 Full Color</div>
                    <div class="option-card color-option" data-value="Black & White">⚫ Black & White</div>
                </div>

                <input type="hidden" name="color" id="color" required value="{{ session('color') ?? 'Full Color' }}">
            </div>

            <!-- PAPER QUALITY -->
            <div class="mb-6">
                <label class="font-medium">Paper Quality<span class="text-red-600">*</span></label>
                <select name="paper_quality"
                        class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100">
                    <option value="">Choose paper quality</option>
                    <option value="Glossy" {{ session('paper_quality') === 'Glossy' ? 'selected' : '' }}>Glossy</option>
                    <option value="Matte" {{ session('paper_quality') === 'Matte' ? 'selected' : '' }}>Matte</option>
                    <option value="Premium" {{ session('paper_quality') === 'Premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>

            <!-- SPECIAL INSTRUCTIONS -->
            <div>
                <label class="font-medium">
                    Special Instructions <span class="text-gray-400">(Optional)</span>
                </label>
                <textarea name="instructions" rows="3"
                          class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100"
                          placeholder="Tell us anything special about your order...">{{ session('instructions') }}</textarea>
            </div>

        </div>

        <!-- BUTTONS -->
        <div class="flex justify-between mt-6">
            <a href="{{ route('order') }}"
               class="px-5 py-2 rounded-lg border text-gray-600">
                ← Go Back
            </a>

            <!-- ✅ CHANGED TO SUBMIT -->
            <button type="submit"
                    class="text-white px-6 py-3 rounded-lg shadow hover:bg-pink-600 transition"
                    style="background-color: #D47497;">
                Continue →
            </button>
        </div>

    </form>
    <!-- ✅ FORM END -->

</div>

</section>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const cards = document.querySelectorAll('.service-card');
    const input = document.getElementById('product_id');

    const selected = "{{ session('product_id') ?? '' }}";

    cards.forEach(card => {

        // ✅ restore selected state
        if (card.dataset.value === selected) {
            card.classList.add('active');
            input.value = selected;
        }

        // ✅ click handler
        card.addEventListener('click', () => {
            cards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');

            input.value = card.dataset.value;
        });
    });

});
</script>

@endsection
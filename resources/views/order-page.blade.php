@extends('layouts.content')

@section('content')

<!-- STEPS -->
<section class="bg-white py-6 shadow-sm">
    <div class="max-w-5xl mx-auto flex justify-between items-center text-sm">

        <!-- Step 1 -->
        <div class="text-center">
            <div class="w-12 h-12 text-white rounded-full flex items-center justify-center mx-auto shadow"
                 style="background-color: #D47497;">1</div>
            <p class="mt-2 font-medium">Your Details</p>
            <p class="text-gray-400 text-xs">Name & Email</p>
        </div>

        <div class="flex-1 h-px bg-gray-300 mx-2"></div>

        <!-- Step 2 -->
        <div class="text-center opacity-40">
            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mx-auto">2</div>
            <p class="mt-2">What to Print</p>
            <p class="text-xs">Choose Service</p>
        </div>

        <div class="flex-1 h-px bg-gray-300 mx-2"></div>

        <!-- Step 3 -->
        <div class="text-center opacity-40">
            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mx-auto">3</div>
            <p class="mt-2">Upload Files</p>
            <p class="text-xs">Your Design</p>
        </div>

        <div class="flex-1 h-px bg-gray-300 mx-2"></div>

        <!-- Step 4 -->
        <div class="text-center opacity-40">
            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mx-auto">4</div>
            <p class="mt-2">Finalize</p>
            <p class="text-xs">Complete Order</p>
        </div>

    </div>
</section>

<!-- CONTENT -->
<section class="bg-gradient-to-br from-blue-100 to-gray-200 py-12">

    <div class="max-w-4xl mx-auto px-6">

        <!-- Title -->
        <h1 class="text-3xl font-bold text-center">Let's Get Started!</h1>
        <p class="text-center text-gray-500 mt-2">
            We just need a few details to get your order ready
        </p>

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">

            <!-- Header -->
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-regular fa-envelope text-pink-500"></i>
                <h2 class="text-lg font-semibold">Your Contact Information</h2>
            </div>

            <p class="text-sm text-gray-500 mb-6">
                No account needed - we'll email your order details
            </p>

            <!-- FORM -->
            <form class="space-y-5" method="POST" action="{{ route('order.step1.store') }}">
                @csrf

                <input type="hidden" name="product_id" value="{{ $productId ?? '' }}">

                <!-- Name -->
                <div>
                    <label class="text-sm font-medium flex items-center gap-2">
                        <i class="fa-regular fa-user text-gray-400"></i>
                        Your Full Name<span class="text-red-600">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           required
                           value="{{ session('name') }}"
                           placeholder="John Smith"
                           class="w-full mt-1 px-4 py-3 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-400">
                </div>

                <!-- Email -->
                <div>
                    <label class="text-sm font-medium flex items-center gap-2">
                        <i class="fa-regular fa-envelope text-gray-400"></i>
                        Email Address<span class="text-red-600">*</span>
                    </label>
                    <input type="email"
                    name="email"
                    required
                    value="{{ session('email') }}"
                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                    placeholder="john@example.com"
                    class="w-full mt-1 px-4 py-3 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-400">

                    <p class="text-xs text-gray-400 mt-1">
                        We'll send your order ID and updates here
                    </p>
                </div>

                <!-- Phone -->
                <div>
                    <label class="text-sm font-medium flex items-center gap-2">
                        <i class="fa-solid fa-phone text-gray-400"></i>
                        Phone Number<span class="text-red-600">*</span>
                    </label>
                    <input type="text"
                    name="phone"
                    id="phone"
                    required
                    value="{{ session('phone') }}"
                    pattern="^\+63\s\d{3}\s\d{3}\s\d{4}$"
                    maxlength="16"
                    class="w-full mt-1 px-4 py-3 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-400">
                </div>

                <!-- CONTINUE BUTTON -->
                <div class="flex justify-end mt-6">
                    <button type="submit" class="text-white px-6 py-3 rounded-lg shadow hover:bg-pink-600 transition"
                            style="background-color: #D47497;">
                        Submit →
                    </button>
                </div>
            </form>

            <!-- INFO BOX -->
            <div class="mt-6 border rounded-xl p-5"
                 style="background-color: #FCE7F3; border-color: #F9A8D4;">

                <p class="font-semibold text-pink-600">
                    ✨ Quick & Easy Ordering
                </p>

                <ul class="mt-3 space-y-2 text-sm text-pink-700">
                    <li>✔ No account registration needed</li>
                    <li>✔ Get your order ID instantly</li>
                    <li>✔ Track anytime with your ID</li>
                    <li>✔ Receive email updates automatically</li>
                </ul>
            </div>

        </div>

    </div>

</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const phoneInput = document.getElementById("phone");

    phoneInput.value = "+63 ";

    phoneInput.addEventListener("input", function () {

        // get only numbers
        let digits = phoneInput.value.replace(/\D/g, "");

        // remove leading 63 if user types it
        if (digits.startsWith("63")) {
            digits = digits.slice(2);
        }

        // ✅ limit to EXACTLY 10 digits after +63
        digits = digits.substring(0, 10);

        // format: +63 9XX XXX XXXX
        let formatted = "+63 ";

        if (digits.length > 0) {
            formatted += digits.substring(0, 3);
        }
        if (digits.length > 3) {
            formatted += " " + digits.substring(3, 6);
        }
        if (digits.length > 6) {
            formatted += " " + digits.substring(6, 10);
        }

        phoneInput.value = formatted;
    });

    // prevent deleting +63
    phoneInput.addEventListener("keydown", function (e) {
        if (phoneInput.selectionStart <= 4 && (e.key === "Backspace" || e.key === "Delete")) {
            e.preventDefault();
        }
    });
});
</script>

@endsection

<!-- order-step4.blade.php -->
@extends('layouts.content')

@section('content')

@php
    use App\Models\Product;
    use App\Services\PriceService;

    $product = Product::find(session('product_id'));

    $service = $product->product_name ?? '—';
    $quantity = session('quantity') ?? 0;
    $color = session('color');
    $paperQuality = session('paper_quality');

    $result = $product
        ? PriceService::calculate(
            $product->base_price,
            $quantity,
            $color,
            $paperQuality
        )
        : null;

    $finalPricePerUnit = $result['final_per_unit'] ?? 0;
    $subtotal = $result['subtotal'] ?? 0;
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

        <input type="hidden" name="product_id" value="{{ session('product_id') }}">
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
                    <span>{{ $service }}</span>
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
                    <span><strong>Quality:</strong></span>
                    <span>{{ session('paper_quality') }}</span>
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

                @if(session('files'))
                    <div class="bg-white rounded-2xl shadow-lg p-6 mt-8 border border-pink-200">

                        <h2 class="font-semibold mb-4 flex items-center gap-2 text-lg">
                            <i class="fa-solid fa-image"></i> Uploaded Files Preview
                        </h2>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                            @foreach(session('files') as $file)

                                @php
                                    $filePath = is_array($file) ? $file['path'] : $file;
                                    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                @endphp

                                <div class="bg-gray-100 p-3 rounded-lg text-center">

                                    <!-- IMAGE PREVIEW -->
                                    @if(in_array(strtolower($extension), ['jpg','jpeg','png']))
                                        <img src="{{ asset('storage/' . (is_array($file) ? $file['path'] : $file)) }}" class="w-full h-32 object-cover rounded mb-2">

                                        <button type="button" data-src="{{ is_array($file) ? $file['name'] : basename($file) }}" class="preview-btn text-xs text-pink-600 hover:underline">
                                            Preview
                                        </button>
                                    @else
                                        <div class="h-32 flex items-center justify-center bg-gray-200 rounded mb-2">
                                            <i class="fa-solid fa-file text-3xl text-gray-500"></i>
                                        </div>
                                    @endif

                                    <!-- FILE NAME -->
                                    <p class="text-xs text-gray-600 truncate">
                                        {{ is_array($file) ? $file['name'] : basename($file) }}
                                    </p>

                                </div>

                            @endforeach

                        </div>

                    </div>
                    @endif

            </div>

            <!-- SUBTOTAL -->
            <div class="mt-4 border-t pt-4 space-y-2 text-sm">

            <!-- Base -->
            <div class="flex justify-between">
                <span>Base Price:</span>
                <span>₱ {{ number_format($result['base_price'] ?? 0, 2) }}</span>
            </div>

            <!-- Discount -->
            @if(($result['discount_rate'] ?? 0) > 0)
            <div class="flex justify-between text-green-600">
                <span>Bulk Discount ({{ $result['discount_rate'] * 100 }}%):</span>
                <span>- ₱ {{ number_format($result['base_price'] * $result['discount_rate'], 2) }}</span>
            </div>
            @endif

            <!-- Color -->
            @if(($result['color_fee'] ?? 0) > 0)
            <div class="flex justify-between">
                <span>Full Color Fee:</span>
                <span>+ ₱ {{ number_format($result['color_fee'], 2) }}</span>
            </div>
            @endif

            <!-- Quality -->
            @if(($result['quality_fee'] ?? 0) > 0)
            <div class="flex justify-between">
                <span>Premium Paper:</span>
                <span>+ ₱ {{ number_format($result['quality_fee'], 2) }}</span>
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

            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-relaxed text-amber-900">
                <p>
                    <strong>Delivery Disclaimer:</strong> Delivery fees may vary depending on how far your location is from our store.
                    We use Maxim to deliver items, so the final delivery fee will depend on the assigned rider's route and distance.
                </p>
            </div>

        </div>

            <!-- TOTAL -->
            <div class="mt-3 bg-pink-500 text-white p-4 rounded-lg flex justify-between text-lg font-semibold">
                <span>Total:</span>
                <span>₱ {{ number_format($subtotal, 2) }}</span>
            </div>

        </div>

        <!-- DELIVERY METHOD -->

        <div class="bg-white rounded-2xl shadow-lg p-6 mt-8">

            <h2 class="font-semibold mb-4 flex items-center gap-2">
                <i class="fa-solid fa-truck"></i> Delivery Method
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <label for="delivery_pickup" class="option-card delivery-option cursor-pointer border border-gray-200 p-4 rounded-lg transition hover:border-pink-300">
                    <input id="delivery_pickup" type="radio" name="delivery_type" value="pickup" required class="sr-only delivery-type-input"
                        {{ session('delivery_type') == 'pickup' ? 'checked' : '' }}>
                    <span class="block font-medium">📦 Pick up Order</span>
                    <span class="text-xs text-gray-500">Claim your order at our store.</span>
                </label>

                <label for="delivery_dropoff" class="option-card delivery-option cursor-pointer border border-gray-200 p-4 rounded-lg transition hover:border-pink-300">
                    <input id="delivery_dropoff" type="radio" name="delivery_type" value="delivery" required class="sr-only delivery-type-input"
                        {{ session('delivery_type') == 'delivery' ? 'checked' : '' }}>
                    <span class="block font-medium">🚚 Have it Delivered</span>
                    <span class="text-xs text-gray-500">Delivered to your preferred address.</span>
                </label>

                <p class="text-xs text-gray-500 sm:col-span-2">
                    🚚 <strong>Disclaimer:</strong> Delivery fee may vary based on your distance from our store.
                    We use <strong>Maxim</strong> for deliveries, so the final delivery charge is confirmed after review.
                </p>

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

        <div class="flex justify-between mt-6">
            <!-- BACK BUTTON -->
            <a href="{{ route('order.step3') }}" class="px-5 py-2 border rounded-lg text-gray-600">
                ← Go Back
            </a>

            <!-- SUBMIT -->
            <button type="submit" class="text-white px-6 py-3 rounded-lg shadow" style="background-color: #D47497;">
                Submit Order →
            </button>
        </div>
    </form>

</div>

</section>

<!-- IMAGE PREVIEW MODAL -->
<div id="previewModal"
     class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">

    <div class="relative max-w-3xl w-full px-4">

        <!-- CLOSE BUTTON -->
        <button onclick="closePreview()"
                class="absolute top-2 right-4 text-white text-2xl font-bold">
            &times;
        </button>

        <!-- IMAGE -->
        <img id="previewImage"
             src=""
             class="w-full max-h-[80vh] object-contain rounded-lg shadow-lg">
    </div>

</div>

<script>
function openPreview(src) {
    const modal = document.getElementById('previewModal');
    const image = document.getElementById('previewImage');

    image.src = src;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closePreview() {
    const modal = document.getElementById('previewModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// close when clicking outside image
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});
</script>

<script>
document.querySelectorAll('.preview-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        openPreview(this.dataset.src);
    });
});
</script>

<script>
function updateDeliverySelection() {
    document.querySelectorAll('.delivery-option').forEach(option => {
        const input = option.querySelector('input[name="delivery_type"]');

        if (input && input.checked) {
            option.classList.add('border-pink-400', 'bg-pink-50', 'ring-2', 'ring-pink-200');
            option.classList.remove('border-gray-200');
        } else {
            option.classList.remove('border-pink-400', 'bg-pink-50', 'ring-2', 'ring-pink-200');
            option.classList.add('border-gray-200');
        }
    });
}

document.querySelectorAll('.delivery-type-input').forEach(input => {
    input.addEventListener('change', updateDeliverySelection);
});

updateDeliverySelection();
</script>

@endsection

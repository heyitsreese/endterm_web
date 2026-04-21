@extends('admin.layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">
            Edit Order #ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}
        </h1>

        <a href="{{ route('admin.orders.index') }}"
           class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200">
            ← Back
        </a>
    </div>

    <form action="{{ route('admin.orders.update', $order->order_id) }}" method="POST"
          class="bg-white p-6 rounded-2xl shadow space-y-6">

        @csrf
        @method('PUT')

        <!-- CUSTOMER -->
        <div>
            <label class="block text-sm mb-1">Customer Name</label>
            <input type="text" name="customer_name"
                   value="{{ $order->customer_name }}"
                   class="w-full px-4 py-2 border rounded-lg">
        </div>

        <!-- STATUS -->
        <div>
            <label class="block text-sm mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 border rounded-lg">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ $order->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <!-- ORDER ITEMS -->
        <div>
            <h2 class="font-semibold mb-2">Order Details</h2>

            @foreach($order->orderDetails as $index => $detail)
            <div class="border p-4 rounded-lg mb-3 order-item">

                <p class="font-medium mb-2 product-name">
                    {{ $detail->product->product_name ?? 'Product' }}
                </p>

                <!-- QUANTITY -->
                <label class="block text-sm">Quantity</label>
                <input type="number"
                    name="details[{{ $index }}][quantity]"
                    value="{{ $detail->quantity }}"
                    class="w-full px-3 py-2 border rounded-lg mb-2 quantity-input">

                <!-- COLOR -->
                <label class="block text-sm">Color</label>
                <select name="details[{{ $index }}][color]"
                    class="w-full px-3 py-2 border rounded-lg mb-2 color-input">
                    <option value="Black & White" {{ $detail->color == 'Black & White' ? 'selected' : '' }}>Black & White</option>
                    <option value="Full Color" {{ $detail->color == 'Full Color' ? 'selected' : '' }}>Full Color</option>
                </select>

                <!-- QUALITY -->
                <label class="block text-sm">Paper Quality</label>
                <select name="details[{{ $index }}][paper_quality]"
                    class="w-full px-3 py-2 border rounded-lg mb-2 quality-input">
                    <option value="Glossy" {{ $detail->paper_quality == 'Glossy' ? 'selected' : '' }}>Glossy</option>
                    <option value="Matte" {{ $detail->paper_quality == 'Matte' ? 'selected' : '' }}>Matte</option>
                    <option value="Premium" {{ $detail->paper_quality == 'Premium' ? 'selected' : '' }}>Premium</option>
                </select>

                <!-- PAPER SIZE -->
                 <label class="block text-sm">Paper Size</label>
                <input type="text"
                    name="details[{{ $index }}][size]"
                    value="{{ $detail->size }}"
                    class="w-full px-3 py-2 border rounded-lg mb-2">

                <!-- INSTRUCTIONS -->
                <label class="block text-sm">Instructions</label>
                <textarea name="details[{{ $index }}][instructions]"
                    class="w-full px-3 py-2 border rounded-lg">{{ $detail->special_instruction }}</textarea>

                <!-- ID -->
                <input type="hidden"
                    name="details[{{ $index }}][id]"
                    value="{{ $detail->order_details_id }}">

            </div>
            @endforeach

        <!-- TOTAL -->
        <div>
            <label class="block text-sm mb-1">Total Amount</label>
            <input type="text"
            id="totalAmount"
            value="{{ $order->total_amount }}"
            class="w-full px-4 py-2 border rounded-lg bg-gray-100"
            readonly>
        </div>

        <!-- SUBMIT -->
        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2 text-white rounded-lg"
                style="background-color: #D47497;">
                Update Order
            </button>
        </div>

    </form>

</div>

@endsection
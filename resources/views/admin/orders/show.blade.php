@extends('admin.layouts.app')

@section('content')

@php use Illuminate\Support\Str; @endphp

    <!-- HEADER -->
    @section('header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-semibold">
                Order #ORD-{{ str_pad($order->order_id, 3, '0', STR_PAD_LEFT) }}
            </h1>
            <p class="text-sm text-gray-500">
                Full order details
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200">
            ← Back
        </a>
    </div>
    @endsection

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow p-6 space-y-6">

        <!-- CUSTOMER INFO -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- LEFT -->
            <div>
                <h2 class="font-semibold text-lg mb-3">Customer Information</h2>

                <div class="space-y-2 text-sm">
                    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                    <p><strong>Phone:</strong> {{ $order->phone_number ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- RIGHT -->
            <div>
                <h2 class="font-semibold text-lg mb-3">Order Information</h2>

                <div class="space-y-2 text-sm">

                    <p>
                        <strong>Status:</strong>
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($order->status == 'pending') bg-orange-100 text-orange-600
                            @elseif($order->status == 'in_progress') bg-blue-100 text-blue-600
                            @elseif($order->status == 'ready_for_pickup') bg-purple-100 text-purple-600
                            @elseif($order->status == 'picked_up') bg-green-100 text-green-600
                            @elseif($order->status == 'out_for_delivery') bg-indigo-100 text-indigo-600
                            @elseif($order->status == 'delivered') bg-green-100 text-green-600
                            @elseif($order->status == 'declined') bg-red-100 text-red-600
                            @endif
                        ">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </p>

                    <p><strong>Delivery:</strong> {{ ucfirst($order->delivery_type) }}</p>

                    <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>

                    <p><strong>Order Code:</strong>
                        #ORD-{{ str_pad($order->order_id, 4, '0', STR_PAD_LEFT) }}
                    </p>

                </div>
            </div>

        </div>

        <!-- ORDER ITEMS -->
        <div>
            <h2 class="font-semibold text-lg mb-2">Print Details</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="p-3 text-left">Product</th>
                            <th class="p-3 text-center">Quantity</th>
                            <th class="p-3 text-center">Paper</th>
                            <th class="p-3 text-left">Instructions</th>
                            <th class="p-3 text-center">File</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($order->orderDetails as $detail)
                        <tr class="border-t align-middle">

                            <!-- PRODUCT -->
                            <td class="p-3 align-middle">
                                {{ $detail->product->product_name ?? 'N/A' }}
                            </td>

                            <!-- QUANTITY -->
                            <td class="p-3 text-center align-middle">
                                {{ $detail->quantity }}
                            </td>

                            <!-- PAPER QUALITY -->
                             <td class="p-3 text-center align-middle">
                                {{ $detail->size ?? 'N/A' }}
                            </td>

                            <!-- INSTRUCTIONS -->
                            <td class="p-3 align-middle">
                                {{ $detail->special_instruction ?? 'No instructions' }}
                            </td>

                            <!-- FILE -->
                            <td class="p-3 text-center align-middle">
                                @php
                                    $files = is_string($detail->file_path) 
                                        ? json_decode($detail->file_path, true) 
                                        : ($detail->file_path ?? []);
                                    $files = $files ?? [];
                                @endphp

                                @if(count($files) > 0)
                                    <div class="flex flex-col items-center gap-3">
                                        @foreach($files as $file)
                                            <div class="text-xs text-gray-600 font-medium truncate max-w-[120px]">
                                                {{ $file['name'] }}
                                            </div>

                                            <div class="flex gap-2">
                                                {{-- VIEW --}}
                                                <a href="{{ asset('storage/' . $file['path']) }}"
                                                    target="_blank"
                                                    class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-xs hover:bg-blue-200">
                                                    👁 View
                                                </a>

                                                {{-- DOWNLOAD --}}
                                                <a href="{{ asset('storage/' . $file['path']) }}"
                                                    download="{{ $file['name'] }}"
                                                    class="px-3 py-1 bg-green-100 text-green-600 rounded-lg text-xs hover:bg-green-200">
                                                    ⬇ Download
                                                </a>
                                            </div>

                                            {{-- THUMBNAIL PREVIEW FOR IMAGES --}}
                                            @if(Str::endsWith($file['path'], ['jpg','jpeg','png']))
                                                <img src="{{ asset('storage/' . $file['path']) }}"
                                                    class="w-20 h-20 object-cover rounded-lg border shadow-sm">
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">No file</span>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TOTAL -->
        <div class="flex justify-end">
            <div class="text-right">
                <p class="text-gray-500 text-sm">Total Amount</p>
                <p class="text-xl font-semibold">
                    ₱ {{ number_format($order->total_amount, 2) }}
                </p>
            </div>
        </div>

    </div>

</div>

@endsection
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
        <div>
            <h2 class="font-semibold text-lg mb-2">Customer Information</h2>
            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
            <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
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
                                @if($detail->file_path)

                                    <div class="flex flex-col items-center gap-2">

                                        <!-- VIEW -->
                                        <a href="{{ asset('storage/' . $detail->file_path) }}"
                                        target="_blank"
                                        class="text-blue-500 underline text-xs">
                                            View
                                        </a>

                                        <!-- PREVIEW -->
                                        @if(Str::endsWith($detail->file_path, ['jpg','jpeg','png']))
                                            <img src="{{ asset('storage/' . $detail->file_path) }}"
                                                class="w-16 h-16 object-cover rounded border">
                                        @endif

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
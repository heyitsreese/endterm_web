@extends('admin.layouts.app')

@section('content')

<!-- HEADER -->
@section('header')

<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-semibold">Products</h1>
        <p class="text-sm text-gray-500">
            Manage your products and services.
        </p>
    </div>

    <!-- SEARCH -->
    <div class="relative w-64">
        <div class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <i data-feather="search" class="w-4 h-4"></i>
        </div>

        <input 
            type="text"
            placeholder="Search..."
            class="w-full pl-10 pr-4 py-2 rounded-xl bg-gray-100 
                   focus:outline-none focus:ring-2 focus:ring-pink-300 
                   text-sm placeholder-gray-400">
    </div>
</div>

@endsection

<div class="p-6">

    @if(session('success'))
        <div class="mb-4 flex items-center gap-2 p-3 bg-green-100 text-green-700 rounded-xl shadow-sm">
            <i data-feather="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- ACTION BAR (FIXED POSITION + SPACING) -->
    <div class="flex justify-between items-center mb-6">
        
        <!-- LEFT (Title section like your UI) -->
        <div>
            <h2 class="text-xl font-semibold">Products & Services</h2>
            <p class="text-sm text-gray-500">
                Manage your printing services and pricing
            </p>
        </div>

        <!-- RIGHT (Buttons) -->
        <div class="flex items-center gap-3">
            
            <!-- FILTER -->
           <form method="GET" class="flex items-center gap-3">

                <select name="category" class="border px-3 py-2 rounded-lg text-sm">
                    <option value="all">All Categories</option>
                    @foreach($categoryList as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="border px-3 py-2 rounded-lg text-sm">
                    <option value="all">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button class="px-4 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200">
                    Apply
                </button>

            </form>

            <!-- ADD PRODUCT -->
            <a href="#" onclick="openAddModal()"
               class="px-4 py-2 bg-pink-500 text-white rounded-xl text-sm hover:bg-pink-600 flex items-center gap-2 shadow">
                <i data-feather="plus" class="w-4 h-4"></i>
                Add Product
            </a>
        </div>

    </div>

    <!-- TOP STATS WITH ICONS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="bg-white p-5 rounded-2xl shadow flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Total Products</p>
                <h2 class="text-2xl font-semibold">{{ $activeProducts }}</h2>
            </div>
            <div class="bg-pink-100 text-pink-500 p-3 rounded-xl">
                <i data-feather="box"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Active Products</p>
                <h2 class="text-2xl font-semibold">{{ $products->count() }}</h2>
            </div>
            <div class="bg-green-100 text-green-500 p-3 rounded-xl">
                <i data-feather="check-circle"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500">Categories</p>
                <h2 class="text-2xl font-semibold">{{ $categoryCount }}</h2>
            </div>
            <div class="bg-purple-100 text-purple-500 p-3 rounded-xl">
                <i data-feather="grid"></i>
            </div>
        </div>

    </div>

    <!-- PRODUCTS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($products as $product)
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition border border-gray-100">

            <div class="flex justify-between items-center mb-4">
                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-pink-500">

                    @if($product->category === 'Cards')
                        <i class="fa-regular fa-credit-card fa-lg"></i>

                    @elseif($product->category === 'Marketing')
                        <i class="fa-regular fa-file-lines fa-lg"></i>

                    @elseif($product->category === 'Large Format')
                        <i class="fa-regular fa-image fa-lg"></i>

                    @else
                        <i class="fa-solid fa-box fa-lg"></i>
                    @endif

                </div>

                <span class="text-xs px-3 py-1 rounded-full 
                    {{ $product->status === 'active' 
                        ? 'bg-green-100 text-green-600' 
                        : 'bg-gray-200 text-gray-600' }}">
                        
                    {{ $product->status }}
                </span>
            </div>

            <h3 class="text-lg font-semibold">{{ $product->product_name }}</h3>
            <p class="text-sm text-gray-500 mb-3">{{ $product->category ?? 'N/A' }}</p>

            <div class="text-sm text-gray-600 space-y-1 mb-4">
                <p>Base Price: ₱ {{ number_format($product->base_price, 2) }} /unit</p>
                <p>Min Quantity: {{ $product->min_quantity }}</p>
                <p>Turnaround: {{ $product->turnaround }}</p>
            </div>

            <div class="flex gap-2">
                <button 
                    type="button"
                    data-product='@json($product)'
                    onclick="openEditModal(this)"
                    class="flex-1 text-center py-2 border rounded-lg text-sm hover:bg-gray-100 flex items-center justify-center gap-1">
                    
                    <i class="fa-solid fa-pen"></i> Edit
                </button>

                <button 
                    type="button"
                    data-product='@json($product)'
                    onclick="openViewModal(this)"
                    class="flex-1 text-center py-2 border rounded-lg text-sm hover:bg-gray-100 flex items-center justify-center gap-1">
                    
                    <i class="fa-solid fa-eye"></i> View
                </button>

                <button 
                    type="button"
                    data-id="{{ $product->product_id }}"
                    onclick="openDeleteModal(this)"
                    class="flex-1 text-center py-2 border rounded-lg text-sm hover:bg-red-100 text-red-500 flex items-center justify-center gap-1">
                    
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>

        </div>
        @endforeach

    </div>

</div>

<!-- ADD PRODUCT MODAL -->
<div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg rounded-2xl p-6 shadow-xl">

        <h2 class="text-lg font-semibold mb-4">Add Product</h2>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-3">

                <input name="product_name" placeholder="Product Name"
                    class="w-full border rounded-lg px-3 py-2">

                <input name="base_price" type="number" step="0.01" placeholder="Base Price"
                    class="w-full border rounded-lg px-3 py-2">

                <input name="category" placeholder="Category (Cards, Marketing, etc)"
                    class="w-full border rounded-lg px-3 py-2">

                <input name="min_quantity" type="number" placeholder="Minimum Quantity"
                    class="w-full border rounded-lg px-3 py-2">

                <input name="turnaround" placeholder="Turnaround (e.g. 2-3 days)"
                    class="w-full border rounded-lg px-3 py-2">
                
                <input type="file" name="image"id="add_image"
                    class="w-full border rounded-lg px-3 py-2">

                <img id="add_preview" class="hidden w-full h-40 object-cover rounded-lg mt-2">

                <select name="status" class="w-full border rounded-lg px-3 py-2">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>

            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeAddModal()"
                    class="px-4 py-2 border rounded-lg">Cancel</button>

                <button type="submit"
                    class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600">
                    Save
                </button>
            </div>

        </form>

    </div>
</div>

<!-- EDIT PRODUCT MODAL -->
<div id="editProductModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg rounded-2xl p-6 shadow-xl">

        <h2 class="text-lg font-semibold mb-4">Edit Product</h2>

        <form id="editProductForm" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-3">

                <input id="edit_name" name="product_name"
                    class="w-full border rounded-lg px-3 py-2">

                <input id="edit_price" name="base_price" type="number" step="0.01"
                    class="w-full border rounded-lg px-3 py-2">

                <input id="edit_category" name="category"
                    class="w-full border rounded-lg px-3 py-2">

                <input id="edit_min_quantity" name="min_quantity" type="number"
                    class="w-full border rounded-lg px-3 py-2">

                <input id="edit_turnaround" name="turnaround"
                    class="w-full border rounded-lg px-3 py-2">

                <input type="file" name="image" id="edit_image"
                    class="w-full border rounded-lg px-3 py-2">
                    
                <img id="edit_preview"
                    class="hidden w-full h-40 object-cover rounded-lg mt-2">

                <select id="edit_status" name="status"
                    class="w-full border rounded-lg px-3 py-2">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>

            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 border rounded-lg">Cancel</button>

                <button type="submit"
                    class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600">
                    Update
                </button>
            </div>

        </form>

    </div>
</div>

<!-- DELETE PRODUCT MODAL -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl text-center">

        <!-- ICON -->
        <div class="mx-auto mb-4 w-12 h-12 flex items-center justify-center rounded-full bg-red-100">
            <i class="fa-solid fa-trash text-red-500"></i>
        </div>

        <h2 class="text-lg font-semibold mb-2">Delete Product</h2>
        <p class="text-sm text-gray-500 mb-6">
            Are you sure you want to delete this product? This action cannot be undone.
        </p>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 py-2 border rounded-lg hover:bg-gray-100">
                    Cancel
                </button>

                <button type="submit"
                    class="flex-1 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Delete
                </button>
            </div>
        </form>

    </div>
</div>

<!-- VIEW PRODUCT MODAL -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg rounded-2xl p-6 shadow-xl">

        <h2 class="text-lg font-semibold mb-4">Product Details</h2>

        <div class="space-y-3 text-sm text-gray-700">

            <p><strong>Name:</strong> <span id="view_name"></span></p>

            <p><strong>Category:</strong> <span id="view_category"></span></p>

            <p><strong>Base Price:</strong> ₱ <span id="view_price"></span></p>

            <p><strong>Minimum Quantity:</strong> <span id="view_min"></span></p>

            <p><strong>Turnaround:</strong> <span id="view_turnaround"></span></p>

            <p>
                <strong>Status:</strong> 
                <span id="view_status" class="px-2 py-1 rounded-full text-xs"></span>
            </p>

        </div>

        <div class="flex justify-end mt-6">
            <button onclick="closeViewModal()"
                class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                Close
            </button>
        </div>

    </div>
</div>

@endsection
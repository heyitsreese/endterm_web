@extends('admin.layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-3xl font-bold">Edit Client</h1>
        <p class="text-gray-500">Update client information</p>
    </div>

    <a href="{{ route('admin.clients') }}"
        class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
        ← Back
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

    <form action="{{ route('admin.clients.update', $client->user_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">

            <!-- CLIENT NAME -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Client Name
                </label>
                <input type="text"
                    name="name"
                    value="{{ old('name', $client->name) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-300">
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <input type="email"
                    name="email"
                    value="{{ old('email', $client->email) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-300">
            </div>

            <!-- PHONE -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Phone
                </label>
                <input type="text"
                    name="phone"
                    value="{{ old('phone', $client->phone) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-300">
            </div>

            <!-- STATUS -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select name="status"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-300">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- SAVE BUTTON -->
            <div class="pt-4">
                <button type="submit"
                    class="px-6 py-3 bg-pink-500 text-white rounded-xl hover:bg-pink-600 transition">
                    Save Changes
                </button>
            </div>

        </div>
    </form>

</div>

@endsection
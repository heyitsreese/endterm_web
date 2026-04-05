@extends('admin.layouts.app')

@section('content')
<!-- HEADER -->
@section('header')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
    
    <!-- LEFT SIDE -->
    <div class="flex items-center gap-3">

        <!-- 🍔 HAMBURGER BUTTON -->
        <button onclick="toggleSidebar()" class="md:hidden text-gray-600">
            <i data-feather="menu"></i>
        </button>

        <!-- TITLE -->
        <div>
            <h1 class="text-3xl font-semibold">Settings</h1>
            <p class="text-sm text-gray-500">
                Configure your business settings
            </p>
        </div>

    </div>

    <!-- SEARCH -->
    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i data-feather="search" class="w-4 h-4"></i>
            </div>
            <input 
                type="text"
                placeholder="Search..."
                class="w-full pl-10 pr-4 py-2 rounded-xl bg-gray-50 border border-transparent
                       focus:outline-none focus:ring-2 focus:ring-pink-300 focus:bg-white
                       text-sm text-gray-600 placeholder-gray-400 font-medium">
        </div>
        <div class="relative">
            <button onclick="toggleNotif()" class="text-gray-500 hover:text-gray-700 transition">
                <i data-feather="bell" class="w-5 h-5"></i>

                @if($unreadOrdersCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                        {{ $unreadOrdersCount }}
                    </span>
                @endif
            </button>

            <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-xl p-4 z-50">
                <h3 class="font-semibold mb-2 text-sm">Recent Orders</h3>

                @forelse($recentOrders as $order)
                    <div data-id="{{ $order->order_id }}"
                        onclick="markAsRead(this.dataset.id)"
                        class="border-b py-2 text-xs text-gray-600 cursor-pointer hover:bg-gray-50 rounded px-2">
                        
                        Order #{{ $order->order_id }} - {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </div>
                @empty
                    <p class="text-gray-400 text-xs">No recent orders</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

<div class="mb-6">
    <h2 class="text-2xl font-semibold">Settings</h2>
    <p class="text-gray-500 text-sm">Manage your business settings and preferences</p>
</div>

<div class="flex flex-col lg:flex-row gap-4 sm:gap-6">

    <!-- LEFT SIDEBAR: Nav -->
    <div class="w-full lg:w-64 flex-shrink-0 lg:sticky lg:top-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 flex flex-col gap-1">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-pink-50 text-pink-500 font-medium text-sm">
                <i data-feather="settings" class="w-4 h-4"></i>
                General
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium text-sm transition">
                <i data-feather="bell" class="w-4 h-4"></i>
                Notifications
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium text-sm transition">
                <i data-feather="dollar-sign" class="w-4 h-4"></i>
                Billing
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium text-sm transition">
                <i data-feather="users" class="w-4 h-4"></i>
                Team
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium text-sm transition">
                <i data-feather="box" class="w-4 h-4"></i>
                Integrations
            </a>
        </div>
    </div>

    <!-- RIGHT COLUMN: Forms -->
    <div class="flex-1 flex flex-col gap-6">

        <!-- CARD 1: Business Information -->
         @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif
        <form 
            action="{{ route('admin.settings.update') }}" method="POST" 
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 w-full">
            @csrf
            <h3 class="font-semibold text-gray-800">Business Information</h3>
            <p class="text-sm text-gray-500 mb-6">Update your business details and contact information</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Name</label>
                    <input type="text" name="name" value="{{ $admin->name }}" class="w-full bg-gray-50 border border-transparent rounded-lg px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:bg-white transition" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $admin->email }}" class="w-full bg-gray-50 border border-transparent rounded-lg px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:bg-white transition" />
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ $admin->phone }}" class="w-full bg-gray-50 border border-transparent rounded-lg px-4 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:bg-white transition" />
                </div>

            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#d27598] hover:bg-[#c26588] text-white px-6 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <i data-feather="save" class="w-4 h-4"></i>
                    Save Changes
                </button>
            </div>
        </form>

        <!-- CARD 2: Operating Hours -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800">Operating Hours</h3>
            <p class="text-sm text-gray-500 mb-6">Set your business operating hours</p>

            <div class="flex flex-col gap-4 mb-6">
                @php
                    $days = ['Monday' => true, 'Tuesday' => true, 'Wednesday' => true, 'Thursday' => true, 'Friday' => true, 'Saturday' => true, 'Sunday' => false];
                @endphp

                @foreach($days as $day => $isOpen)
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 day-row">
                    <div class="w-24 text-sm font-medium text-gray-700">{{ $day }}</div>

                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <input type="text" value="9:00 AM" class="time-input w-28 text-center bg-gray-50 border border-transparent rounded-lg px-3 py-1.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:bg-white disabled:opacity-50 disabled:cursor-not-allowed transition" {{ !$isOpen ? 'disabled' : '' }} />
                        <span class="text-gray-400 text-sm">to</span>
                        <input type="text" value="5:00 PM" class="time-input w-28 text-center bg-gray-50 border border-transparent rounded-lg px-3 py-1.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:bg-white disabled:opacity-50 disabled:cursor-not-allowed transition" {{ !$isOpen ? 'disabled' : '' }} />
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer w-20 justify-end">
                        <input type="checkbox" onchange="toggleDay(this)" class="peer sr-only" {{ $isOpen ? 'checked' : '' }}>
                        <div class="w-5 h-5 rounded border border-gray-300 bg-gray-800 flex items-center justify-center transition-colors peer-checked:border-pink-500 peer-checked:bg-[#d27598]">
                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-sm text-gray-600">Open</span>
                    </label>
                </div>
                @endforeach
            </div>

            <div class="flex justify-end">
                <button class="bg-[#d27598] hover:bg-[#c26588] text-white px-6 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <i data-feather="save" class="w-4 h-4"></i>
                    Save Hours
                </button>
            </div>
        </div>

        <!-- CARD 3: Email Notifications -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h3 class="font-semibold text-gray-800">Email Notifications</h3>
            <p class="text-sm text-gray-500 mb-6">Choose which notifications you want to receive</p>

            <div class="flex flex-col gap-4 mb-6">
                @php
                    $notifications = [
                        'New order notifications',
                        'Order status updates',
                        'Client messages',
                        'Daily sales summary',
                        'Weekly reports',
                        'Low inventory alerts'
                    ];
                @endphp
                @foreach($notifications as $notif)
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" class="peer sr-only">
                    <!-- Since screenshot doesn't show them checked, we will leave them unchecked visually, maybe a style difference. but wait, let's just make it standard checkbox or standard toggle? I'll use standard Tailwind accent checkbox for simplicity, wait, screenshot shows no checkboxes checked/visible or maybe they are on the right/left? Ah, screenshot has no checkboxes next to them, let me look closely at the screenshot crop... Actually the screenshot only lists them. Wait, look at 'Email Notifications' -> "New order notifications", "Order status updates", etc. Ah, there are no visible checkboxes in that specific area crop! Wait, the first screenshot didn't capture the far right side of that card maybe. Or maybe they are toggles on the right. Let's make them normal checkboxes on the left. -->
                    <input type="checkbox" class="w-4 h-4 text-[#d27598] bg-gray-100 border-gray-300 rounded focus:ring-pink-500 focus:ring-2 cursor-pointer">
                    <span class="text-sm text-gray-700">{{ $notif }}</span>
                </label>
                @endforeach
            </div>

            <div class="flex justify-end border-t pt-4">
                <button class="bg-[#d27598] hover:bg-[#c26588] text-white px-6 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <i data-feather="save" class="w-4 h-4"></i>
                    Save Preferences
                </button>
            </div>
        </div>

    </div>
</div>
@endsection
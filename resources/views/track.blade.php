<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - Sprint PHL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-slate-50 text-gray-800 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm px-6 py-4">
        <div class="flex justify-between items-center max-w-6xl mx-auto w-full">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.jpg') }}" class="w-10 h-10 rounded-full" alt="Sprint PHL Logo">
                <h1 class="font-semibold text-lg max-sm:hidden">Sprint PHL</h1>
            </div>

            <!-- Right Links -->
            <div class="flex items-center gap-4 text-sm font-medium">
                <a href="/order" class="text-gray-600 hover:text-gray-900">New Order</a>
                <a href="/login" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Sign In</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center pt-16 px-4 pb-12">
        <!-- Header Icon -->
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
            <i data-feather="search" class="text-pink-500 w-8 h-8"></i>
        </div>

        <!-- Heading -->
        <h2 class="text-3xl font-bold mb-2">Track Your Order</h2>
        <p class="text-gray-500 mb-10">Enter your order ID to see the current status</p>

        <!-- Main Card -->
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <label class="block text-sm font-semibold mb-2">Order ID</label>
            <div class="flex gap-3 mb-2">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-feather="box" class="text-gray-400 w-5 h-5"></i>
                    </div>
                    <input type="text"
                           class="w-full pl-10 pr-4 py-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400"
                           placeholder="Enter your order ID (e.g., ORD-ABC123)">
                </div>
                <button class="bg-pink-500 hover:bg-pink-600 text-white font-medium px-6 py-3 rounded-lg flex items-center gap-2 transition-colors shrink-0">
                    <i data-feather="search" class="w-4 h-4"></i>
                    Track
                </button>
            </div>
            <p class="text-xs text-gray-500 mb-6">Your order ID was sent to your email when you placed the order</p>

            <!-- Examples Box -->
            <div class="bg-pink-50 rounded-xl p-5 border border-pink-100">
                <h4 class="font-semibold text-pink-700 mb-3 text-sm">Try these example order IDs:</h4>
                <div class="flex flex-wrap gap-4 text-xs font-medium">
                    <span class="text-pink-600">ORD-ABC123 <span class="text-pink-400">(In Production)</span></span>
                    <span class="text-pink-600">ORD-XYZ789 <span class="text-pink-400">(Shipped)</span></span>
                    <span class="text-pink-600">ORD-DEF456 <span class="text-pink-400">(Delivered)</span></span>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="bg-pink-50 w-full max-w-3xl rounded-2xl p-8 mb-8">
            <h3 class="font-semibold text-lg mb-4 text-gray-900">Need Help?</h3>
            <ul class="space-y-3 text-sm text-gray-700">
                <li><span class="font-semibold">Can't find your order ID?</span> Check the confirmation email sent when you placed your order.</li>
                <li><span class="font-semibold">Have a registered account?</span> <a href="/login" class="text-pink-500 font-medium hover:underline">Sign in</a> to view all your orders in one place.</li>
                <li><span class="font-semibold">Questions?</span> Contact us at <a href="mailto:sprintphl@gmail.com" class="text-pink-500 font-medium hover:underline">sprintphl@gmail.com</a></li>
            </ul>
        </div>
    </main>

    <!-- Footer (Simplified) -->
    <footer class="bg-gray-900 text-gray-300 px-8 py-8 mt-auto">
        <div class="max-w-6xl mx-auto text-center">
            <p class="text-sm">© 2026 Sprint PHL. Printing made simple.</p>
        </div>
    </footer>

    <script>
        feather.replace()
    </script>
</body>
</html>

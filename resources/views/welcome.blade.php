<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprint PHL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-50 text-gray-800">

<!-- NAVBAR -->

 <nav class="bg-white shadow-sm px-6 py-4">
    <div class="flex justify-between items-center">

        <!-- Logo -->
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.jpg') }}" class="w-10 h-10 bg-gray-300 rounded-full" alt="Hero Image">
            <h1 class="font-semibold text-lg">Sprint PHL</h1>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex gap-6 text-sm">
            <a href="#">Services</a>
            <a href="#">Pricing</a>
            <a href="#">About</a>
            <a href="/track-order" class="hover:text-pink-500 transition">Track Order</a>
        </div>

        <!-- Buttons -->
        <div class="hidden md:flex gap-3 items-center">
            <a href="/login" class="px-4 py-1.5 border rounded-lg text-sm hover:bg-gray-50 transition">Sign In</a>
            <a href="/order" class="bg-pink-500 text-white px-4 py-1.5 rounded-lg text-sm hover:bg-pink-600 transition">Order Now</a>
        </div>

        <!-- Burger Button -->
        <button id="menu-btn" class="md:hidden">
            ☰
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="max-h-0 overflow-hidden transition-all duration-300 flex flex-col gap-4 text-sm">
        <a href="#">Services</a>
        <a href="#">Pricing</a>
        <a href="#">About</a>
        <a href="/track-order" class="hover:text-pink-500 transition">Track Order</a>

        <a href="/login" class="border px-4 py-2 rounded-lg text-center hover:bg-gray-50 transition">Sign In</a>
        <a href="/order" class="bg-pink-500 text-white px-4 py-2 rounded-lg text-center hover:bg-pink-600 transition">Order Now</a>
    </div>
</nav>

<!-- HERO -->
<section class="grid md:grid-cols-2 gap-10 px-8 py-16 items-center bg-gradient-to-br from-blue-100 to-gray-200">
    <div>
        <h1 class="text-4xl font-bold leading-tight">
            Professional Printing Made Simple
        </h1>
        <p class="mt-4 text-gray-600">
            High-quality printing services for businesses and individuals.
        </p>

        <div class="mt-6 flex gap-4 flex-wrap">
            <a href="/order" class="bg-pink-500 text-white px-6 py-3 rounded-lg hover:bg-pink-600 transition font-medium inline-block">
                Start Your Order →
            </a>
            <a href="/track-order" class="bg-white px-6 py-3 rounded-lg border hover:bg-gray-50 transition text-gray-700 font-medium inline-block">
                Track Existing Order
            </a>
        </div>

        <div class="mt-6 flex gap-6 text-sm text-gray-600">
            <span>✔ No Account Required</span>
            <span>✔ Instant Order ID</span>
            <span>✔ Easy Tracking</span>
        </div>
    </div>

    <!-- IMAGE PLACEHOLDER -->
    <img src="{{ asset('images/sprintphl.png') }}" class="w-full h-64 object-cover rounded-xl" alt="Hero Image">
</section>

<!-- FEATURES -->
<section class="grid grid-cols-2 md:grid-cols-3 text-center gap-6 px-8 py-12 bg-white">
    <div>
        <div class="w-12 h-12 mx-auto bg-pink-200 rounded-full flex items-center justify-center">
            <i data-feather="clock" class="w-5 h-5 text-pink-600"></i>
        </div>
        <h3 class="mt-2 font-semibold">Fast Turnaround</h3>
        <p class="text-sm text-gray-500">2-3 business days</p>
    </div>

    <div>
        <div class="w-12 h-12 mx-auto bg-pink-200 rounded-full flex items-center justify-center">
            <i data-feather="shield" class="w-5 h-5 text-pink-600"></i>
        </div>
        <h3 class="mt-2 font-semibold">Quality Guaranteed</h3>
        <p class="text-sm text-gray-500">100% satisfaction</p>
    </div>

    <div>
        <div class="w-12 h-12 mx-auto bg-pink-200 rounded-full flex items-center justify-center">
            <i data-feather="truck" class="w-5 h-5 text-pink-600"></i>
        </div>
        <h3 class="mt-2 font-semibold">Free Delivery</h3>
        <p class="text-sm text-gray-500">Orders over ₱250</p>
    </div>
</section>

<!-- SERVICES -->
<section class="px-8 py-16">
    <h2 class="text-2xl font-bold text-center">Our Printing Services</h2>
    <p class="text-center text-gray-500 mb-10">
        Choose from our wide range of services
    </p>

    <div class="grid md:grid-cols-3 gap-6">

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border">

            <!-- Image -->
            <img src="{{ asset('images/card.jpg') }}"
                class="w-full h-44 object-cover"
                alt="Business Cards">

            <!-- Content -->
            <div class="p-5">

                <!-- Title + Price -->
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-lg">Business Cards</h3>
                    <span class="text-pink-500 text-sm font-medium">From ₱30</span>
                </div>

                <!-- Description -->
                <p class="text-sm text-gray-500 mt-2">
                    Premium quality cards starting at ₱30
                </p>

                <!-- Features -->
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✔</span> Full Color
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✔</span> Multiple Finishes
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✔</span> Fast Turnaround
                    </li>
                </ul>

                <!-- Button -->
                <a href="/order" class="mt-5 block w-full text-center bg-pink-500 text-white py-2 rounded-lg hover:bg-pink-600 transition font-medium">
                    Order Now
                </a>

            </div>
        </div>

        <!-- FLYER -->

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border">

            <!-- Image -->
            <img src="{{ asset('images/flyer.jpg') }}"
                class="w-full h-44 object-cover"
                alt="Business Cards">

            <!-- Content -->
            <div class="p-5">

                <!-- Title + Price -->
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-lg">Flyers & Leaflets</h3>
                    <span class="text-pink-500 text-sm font-medium">From ₱50</span>
                </div>

                <!-- Description -->
                <p class="text-sm text-gray-500 mt-2">
                    Eye-catching designs for any occasion
                </p>

                <!-- Features -->
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✔</span> A4 & A5 Sizes
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✔</span> Glossy/Matte
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✔</span> Bulk Discounts
                    </li>
                </ul>

                <!-- Button -->
                <a href="/order" class="mt-5 block w-full text-center bg-pink-500 text-white py-2 rounded-lg hover:bg-pink-600 transition font-medium">
                    Order Now
                </a>

            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border">

            <!-- Image -->
            <img src="{{ asset('images/brochures.jpg') }}"
                class="w-full h-44 object-cover"
                alt="Business Cards">

            <!-- Content -->
            <div class="p-5">

                <!-- Title + Price -->
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-lg">Brochures</h3>
                    <span class="text-pink-500 text-sm font-medium">From ₱70</span>
                </div>

                <!-- Description -->
                <p class="text-sm text-gray-500 mt-2">
                    Professional multi-page brochures
                </p>

                <!-- Features -->
                <ul class="mt-4 space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✔</span> Bi-fold/Tri-fold
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✔</span> Premium Paper
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✔</span> Custom Design
                    </li>
                </ul>

                <!-- Button -->
                <a href="/order" class="mt-5 block w-full text-center bg-pink-500 text-white py-2 rounded-lg hover:bg-pink-600 transition font-medium">
                    Order Now
                </a>

            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="px-8 py-16 bg-white text-center">
    <h2 class="text-2xl font-bold">How It Works</h2>
    <p class="text-gray-500 mb-10">Simple ordering process</p>

    <div class="grid md:grid-cols-4 gap-6">
        <div>
            <div class="w-12 h-12 bg-pink-400 text-white rounded-full flex items-center justify-center mx-auto">1</div>
            <p class="mt-2">Choose Service</p>
        </div>

        <div>
            <div class="w-12 h-12 bg-pink-400 text-white rounded-full flex items-center justify-center mx-auto">2</div>
            <p class="mt-2">Enter Email</p>
        </div>

        <div>
            <div class="w-12 h-12 bg-pink-400 text-white rounded-full flex items-center justify-center mx-auto">3</div>
            <p class="mt-2">Get Order ID</p>
        </div>

        <div>
            <div class="w-12 h-12 bg-pink-400 text-white rounded-full flex items-center justify-center mx-auto">4</div>
            <p class="mt-2">Track Order</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="text-center py-16 bg-gradient-to-r from-pink-400 to-pink-600 text-white">
    <h2 class="text-2xl font-bold">Ready to Get Started?</h2>
    <p class="mt-2">Order now without signing up</p>

    <div class="mt-6 flex justify-center gap-4">
        <a href="/order" class="bg-white text-pink-600 px-8 py-3 rounded-lg hover:bg-gray-50 transition font-bold shadow-sm inline-block">
            Quick Order
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-gray-900 text-gray-300 px-8 py-10">
    <div class="grid md:grid-cols-4 gap-6">
        <div>
            <h3 class="font-semibold text-white">Sprint PHL</h3>
            <p class="text-sm mt-2">Printing made simple.</p>
        </div>

        <div>
            <h4 class="text-white">Quick Links</h4>
            <p class="text-sm mt-2">Services</p>
            <p class="text-sm">Pricing</p>
        </div>

        <div>
            <h4 class="text-white">Support</h4>
            <p class="text-sm mt-2">Help Center</p>
        </div>

        <div>
            <h4 class="text-white">Legal</h4>
            <p class="text-sm mt-2">Privacy Policy</p>
        </div>
    </div>

    <p class="text-center text-sm mt-10">© 2026 Sprint PHL</p>
</footer>

<script>
    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
        if (menu.classList.contains('max-h-0')) {
            menu.classList.remove('max-h-0');
            menu.classList.add('max-h-96');
        } else {
            menu.classList.add('max-h-0');
            menu.classList.remove('max-h-96');
        }
    });
</script>

<script>
    feather.replace()
</script>
</body>
</html>
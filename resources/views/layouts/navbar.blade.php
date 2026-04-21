<!-- NAVBAR -->

<nav class="bg-white shadow-sm px-6 py-4">
    <div class="flex justify-between items-center">

        <!-- Logo -->
        <a href="/">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.jpg') }}" class="w-10 h-10 bg-gray-300 rounded-full" alt="Logo">
                <h1 class="font-semibold text-lg">Sprint PHL</h1>
            </div>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex gap-6 text-sm">
            <a href="/#services" class="hover:text-pink-500">Services</a>
            <a href="/#about" class="hover:text-pink-500">About</a>
            <a href="{{ url('/track') }}" class="hover:text-pink-500">Track Order</a>
        </div>

        <!-- Buttons -->
        <div class="hidden md:flex gap-3">
            <a href="{{ url('/login') }}"
               class="px-4 py-1 border rounded-lg text-sm hover:bg-gray-100">
                Sign In
            </a>

            <a href="{{ url('/order') }}"
               class="text-white px-4 py-1 rounded-lg text-sm hover:bg-pink-600 transition"
               style="background-color: #D47497;">
                Order Now
            </a>
        </div>

        <!-- Burger Button -->
        <button id="menu-btn" class="md:hidden text-xl">
            ☰
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
         class="max-h-0 overflow-hidden transition-all duration-300 flex flex-col gap-4 text-sm mt-4">

        <a href="/#services" class="hover:text-pink-500">Services</a>
        <a href="/#about" class="hover:text-pink-500">About</a>
        <a href="{{ url('/track') }}" class="hover:text-pink-500">Track Order</a>

        <a href="{{ url('/login') }}"
           class="px-4 py-1 border rounded-lg text-sm text-center hover:bg-gray-100">
            Sign In
        </a>

        <a href="{{ url('/order') }}"
           class="text-white px-4 py-1 rounded-lg text-sm hover:bg-pink-600 transition text-center"
           style="background-color: #D47497;">
            Order Now
        </a>
    </div>
</nav>

<!-- MOBILE MENU TOGGLE SCRIPT -->
<script>
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
        if (mobileMenu.classList.contains('max-h-0')) {
            mobileMenu.classList.remove('max-h-0');
            mobileMenu.classList.add('max-h-96');
        } else {
            mobileMenu.classList.remove('max-h-96');
            mobileMenu.classList.add('max-h-0');
        }
    });
</script>
<!-- FOOTER -->
<footer class="bg-gray-900 text-gray-300 px-8 py-10">
    <div class="grid md:grid-cols-4 gap-6">

        <div class="flex items-start gap-3">
    <!-- Logo -->
    <img src="{{ asset('images/logo.jpg') }}" 
         class="w-10 h-10 rounded-full object-cover" 
         alt="Sprint PHL Logo">

    <!-- Text -->
    <div>
        <h3 class="font-semibold text-white">Sprint PHL</h3>
        <p class="text-sm mt-2">
            Professional printing services
            <br>made simple and accessible for
            <br>everyone.
        </p>
    </div>
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
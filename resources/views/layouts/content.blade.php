@include('layouts.header')

<body class="bg-gray-50 text-gray-800">

    @include('layouts.navbar')

    <div class="w-full pt-5">
        @yield('content')
    </div>

    @include('layouts.footer')

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // SERVICE
            const serviceCards = document.querySelectorAll('.service-card');
            const serviceInput = document.getElementById('service');

            serviceCards.forEach(card => {
                card.addEventListener('click', function () {
                    serviceCards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    serviceInput.value = this.dataset.value;
                });
            });

            // ✅ PAPER SIZE (NO nested DOMContentLoaded)
            const paperCards = document.querySelectorAll('.paper-size');
            const paperInput = document.getElementById('paper_size');
            const customInput = document.getElementById('custom-size-input');

            paperCards.forEach(card => {
                card.addEventListener('click', () => {

                    paperCards.forEach(c => c.classList.remove('active'));
                    card.classList.add('active');

                    const value = card.dataset.value;
                    paperInput.value = value;

                    if (value === "Custom") {
                        customInput.classList.remove('hidden');
                    } else {
                        customInput.classList.add('hidden');
                    }
                });
            });

            // COLOR
            document.querySelectorAll('.color-option').forEach(card => {
                card.addEventListener('click', () => {
                    document.querySelectorAll('.color-option').forEach(c => c.classList.remove('active'));
                    card.classList.add('active');
                    document.getElementById('color').value = card.dataset.value;
                });
            });

        });
    </script>

    <script>
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('fileElem');
        const fileList = document.getElementById('file-list');

        // click opens file picker
        dropArea.addEventListener('click', () => fileInput.click());

        // handle file select
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        // drag events
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('bg-pink-100');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('bg-pink-100');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();

            const files = e.dataTransfer.files;

            document.getElementById('fileElem').files = files;

            handleFiles(files);
        });

        function handleFiles(files) {
            [...files].forEach(file => {
                const div = document.createElement('div');
                div.className = "flex justify-between items-center bg-gray-100 p-3 rounded-lg";

                div.innerHTML = `
                    <div>
                        <p class="font-medium">${file.name}</p>
                        <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                    </div>
                    <button class="text-red-500 text-sm">Remove</button>
                `;

                div.querySelector('button').onclick = () => div.remove();

                fileList.appendChild(div);
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const productId = "{{ $productId ?? '' }}";

            if (!productId) return;

            document.querySelectorAll('.service-card').forEach(card => {
                card.classList.remove('active');

                if (card.dataset.value === productId) {
                    card.classList.add('active');
                }
            });

            document.getElementById('service').value = productId;
        });
    </script>
</body>
@extends('client.layouts.app')
@section('content')

<style>
    .option-card {
        padding: 16px;
        border: 1px solid #ddd;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.2s;
    }

    .service-card {
        padding: 16px;
        border: 1px solid #ddd;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.2s;

        height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        text-align: center;
    }

    .service-card:hover,
    .option-card:hover {
        border-color: #D47497;
        background: #FFF1F6;
    }

    .option-card.active,
    .service-card.active {
        border: 2px solid #D47497;
        background: #FCE7F3;
    }

    #custom-size-input {
        transition: all 0.3s ease;
    }
</style>

<!-- HEADER -->
@section('header')
<div class="flex items-center justify-between w-full">

    <!-- LEFT SIDE -->
    <div class="flex items-center gap-3">

        <!-- MOBILE MENU -->
        <button onclick="toggleSidebar()" class="md:hidden text-gray-600">
            <i data-feather="menu"></i>
        </button>

        <!-- TITLE -->
        <div class="leading-snug">
            <h1 class="text-xl font-semibold">Order</h1>
            <p class="text-sm text-gray-500">
                Create an Order
            </p>
        </div>

    </div>

</div>
@endsection

<div class="max-w-5xl mx-auto px-6 py-8">

    <h1 class="text-2xl font-semibold mb-6">New Order</h1>

    <form method="POST" action="{{ route('client.order.store') }}" enctype="multipart/form-data">
    @csrf

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            <input type="text" name="customer_name"
                value="{{ session('user_name') }}"
                placeholder="Full Name"
                class="px-4 py-3 rounded-lg bg-gray-100"
                {{ session('user_id') ? 'readonly' : '' }}
                required>

            <input type="email" name="email"
                value="{{ session('user_email') }}"
                placeholder="Email"
                class="px-4 py-3 rounded-lg bg-gray-100"
                {{ session('user_id') ? 'readonly' : '' }}
                required>

            <input type="text" name="phone" id="phone"
                value="{{ old('phone', '+63 9') }}"
                maxlength="16"
                pattern="\+63\s9\d{2}\s\d{3}\s\d{4}"
                title="Enter a valid PH number (e.g. +63 917 123 4567)"
                class="px-4 py-3 rounded-lg bg-gray-100"
                required>

        </div>

        <!-- PRINTING DETAILS -->
        <div class="flex items-center gap-2 mb-6">
            <i class="fa-regular fa-file text-pink-500"></i>
            <h2 class="text-lg font-semibold">Printing Details</h2>
        </div>

        <!-- SERVICE -->
        <h3 class="font-medium mb-4">Select Printing Service <span class="text-red-600">*</span></h3>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            @foreach($products as $product)
                <div class="service-card border rounded-xl p-4 cursor-pointer hover:border-pink-400 transition"
                    data-id="{{ $product->product_id }}"
                    data-price="{{ $product->base_price }}">

                    <p class="font-medium">{{ $product->product_name }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        ₱{{ number_format($product->base_price, 2) }}
                    </p>
                </div>
            @endforeach
        </div>

        <input type="hidden" name="product_id" id="product_id">

        <!-- QUANTITY -->
        <div class="mb-6">
            <label class="font-medium">How Many Do You Need?<span class="text-red-600">*</span></label>
            <input type="number" name="quantity" placeholder="e.g., 100"
                class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100" required>
        </div>

        <!-- PAPER SIZE -->
        <label class="font-medium">Paper Size<span class="text-red-600">*</span></label>
        <div class="grid grid-cols-2 gap-4 mb-4">

            @foreach(['A4','A5','Letter','Custom'] as $size)
                <div class="option-card paper-size border rounded-xl p-4 cursor-pointer"
                    data-value="{{ $size }}">
                    <p class="font-medium">{{ $size }} Size</p>
                </div>
            @endforeach

        </div>

        <div id="custom-size-input" class="hidden mb-4">
            <input type="text" name="custom_size"
                placeholder="Enter custom size (e.g. 5x7 inches)"
                class="w-full px-4 py-3 rounded-lg bg-gray-100">
        </div>

        <input type="hidden" name="paper_size" id="paper_size">

        <!-- COLOR -->
        <div class="mb-6">
            <label class="font-medium mb-2 block">Color Options</label>

            <div class="grid grid-cols-2 gap-4">
                <div class="option-card color-option border rounded-xl p-3 cursor-pointer"
                    data-value="Full Color">🎨 Full Color</div>

                <div class="option-card color-option border rounded-xl p-3 cursor-pointer"
                    data-value="Black & White">⚫ Black & White</div>
            </div>

            <input type="hidden" name="color" id="color">
        </div>

        <!-- PAPER QUALITY -->
        <div class="mb-6">
            <label class="font-medium">Paper Quality</label>
            <select name="paper_quality"
                    class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100">
                <option value="Matte">Matte</option>
                <option value="Glossy">Glossy</option>
                <option value="Premium">Premium</option>
            </select>
        </div>

        <!-- FILE -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mt-6">

            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-upload text-pink-500"></i>
                <h2 class="text-lg font-semibold">Your Files</h2>
            </div>

            <p class="text-sm text-gray-500 mb-6">
                We accept PDF, JPG, PNG, JPEG, PSD files
            </p>

            <!-- DROP AREA -->
            <div id="drop-area"
                class="border-2 border-dashed border-pink-200 rounded-xl p-10 text-center cursor-pointer bg-pink-50">

                <i class="fa-solid fa-arrow-up-from-bracket text-4xl text-blue-400"></i>

                <p class="mt-4 font-medium text-gray-700">
                    Drag & Drop Your Files Here
                </p>
                <p class="text-sm text-gray-500">
                    or click to browse your computer
                </p>

                <input type="file" id="fileElem" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.psd" class="hidden">

                <button type="button"
                    id="chooseFilesBtn"
                    class="mt-4 px-4 py-2 border rounded-lg bg-white">
                    Choose Files
                </button>

                <p class="text-xs text-gray-400 mt-4">
                    Supported: PDF, JPG, PNG • Max size: 50MB
                </p>
            </div>

            <!-- FILE ERROR -->
            <div id="file-error" class="hidden mt-3 px-4 py-2 bg-red-100 text-red-600 text-sm rounded-lg"></div>

            <!-- FILE LIST -->
            <div class="mt-6">
                <h3 class="font-medium mb-3">Uploaded Files</h3>
                <div id="file-list" class="space-y-3"></div>
            </div>

            <!-- TIPS -->
            <div class="mt-6 bg-pink-100 border border-pink-300 rounded-xl p-4 text-sm">
                <p class="font-semibold text-pink-600">📄 File Tips</p>
                <ul class="mt-2 space-y-1">
                    <li>✔ Use high quality images (300 DPI or higher)</li>
                    <li>✔ PDF files work best for print</li>
                    <li>✔ Make sure text is readable</li>
                    <li>✔ Add small border around design</li>
                </ul>
            </div>

        </div>

        <!-- TOTAL -->
        <div class="mt-6 mb-6 bg-gray-50 rounded-xl p-4 space-y-2 text-sm">

            <div class="flex justify-between">
                <span>Base Price:</span>
                <span id="basePrice">₱0.00</span>
            </div>

            <div class="flex justify-between">
                <span>Full Color Fee:</span>
                <span id="colorFee">₱0.00</span>
            </div>

            <div class="flex justify-between">
                <span>Paper Quality:</span>
                <span id="qualityFee">₱0.00</span>
            </div>

            <hr>

            <div class="flex justify-between font-medium">
                <span>Price per unit:</span>
                <span id="pricePerUnit">₱0.00</span>
            </div>

            <div class="flex justify-between">
                <span>Quantity:</span>
                <span id="qtyDisplay">0</span>
            </div>

            <div class="flex justify-between font-semibold bg-gray-200 p-2 rounded">
                <span>Subtotal:</span>
                <span id="subtotal">₱0.00</span>
            </div>

        </div>

        <div class="bg-pink-500 text-white px-6 py-3 rounded-xl flex justify-between items-center">
            <span class="font-semibold">Total:</span>
            <span id="totalPrice" class="font-bold text-lg">₱0.00</span>
        </div>

        <!-- DELIVERY METHOD -->
        <div class="mt-6 mb-6">

            <h3 class="font-medium mb-3">Delivery Method <span class="text-red-600">*</span></h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- PICKUP -->
                <div class="option-card delivery-option active"
                    data-value="pickup">

                    <p class="font-medium">📦 Pick up Order</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Customer will pick up at the store
                    </p>
                </div>

                <!-- DELIVERY -->
                <div class="option-card delivery-option"
                    data-value="delivery">

                    <p class="font-medium">🚚 Delivery</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Deliver via rider (Maxim)
                    </p>
                </div>

            </div>

            <input type="hidden" name="delivery_type" id="delivery_type" value="pickup">

        </div>

        <!-- SUBMIT -->
        <div class="flex justify-end">
            <button type="submit" onclick="console.log('BUTTON CLICKED')"
                class="bg-pink-500 text-white px-6 py-3 rounded-lg">
                Create Order
            </button>
        </div>

    </div>

    </form>
</div>

<div id="preview-modal"
    class="fixed inset-0 bg-black bg-opacity-70 hidden z-50">

    <div class="fixed inset-0 flex items-center justify-center">

        <div class="bg-white p-4 rounded-xl max-w-2xl w-full relative shadow-xl">

            <!-- CLOSE -->
            <button onclick="closePreview()"
                class="absolute top-2 right-3 text-xl font-bold text-gray-600 hover:text-red-500">
                ✖
            </button>

            <!-- IMAGE -->
            <img id="preview-img"
                class="w-full max-h-[80vh] object-contain rounded-lg">

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('DOMContentLoaded', function () {

        const form = document.querySelector('form');

        if (form) {
            form.addEventListener('submit', function(e) {

                const product = document.getElementById('product_id').value;
                const phone = document.getElementById('phone').value.trim();

                const phoneRegex = /^\+63\s9\d{2}\s\d{3}\s\d{4}$/;

                if (!product) {
                    e.preventDefault();
                    alert('Please select a printing service.');
                    return;
                }

                if (!phoneRegex.test(phone)) {
                    e.preventDefault();
                    alert('Please enter a valid Philippine phone number.\nExample: +63 917 123 4567');
                    return;
                }
            });
        }

    });

    console.log("FINAL SCRIPT RUNNING");

    // =========================
    // HELPERS
    // =========================
    const get = id => document.getElementById(id);
    const qs  = sel => document.querySelector(sel);

    function safeListener(el, event, fn) {
        if (el) el.addEventListener(event, fn);
    }

    function setText(id, value) {
        const el = get(id);
        if (el) el.innerText = value;
    }

    let selectedPrice = 0;

    // =========================
    // ELEMENTS
    // =========================
    const qtyInput       = qs('[name="quantity"]');
    const qualityInput   = qs('[name="paper_quality"]');
    const colorInput     = get('color');
    const productInput   = get('product_id');
    const paperSizeInput = get('paper_size');

    // =========================
    // PHONE INPUT
    // =========================
    const phoneInput = document.getElementById('phone');

    if (phoneInput) {
        const PREFIX = '+63 ';

        phoneInput.addEventListener('input', function () {
            let value = phoneInput.value;

            // Always enforce prefix
            if (!value.startsWith(PREFIX)) {
                value = PREFIX + value.replace(/^\+?6?3?\s?/, '');
            }

            // Get digits only after +63
            let digits = value.slice(PREFIX.length).replace(/\D/g, '');

            // 🔥 PH rule: must start with 9
            if (digits.length > 0 && digits[0] !== '9') {
                digits = '9' + digits.replace(/^9*/, '');
            }

            // Limit to 10 digits (9XXXXXXXXX)
            digits = digits.substring(0, 10);

            // Format: 9XX XXX XXXX
            let formatted = PREFIX;
            if (digits.length > 0) formatted += digits.substring(0, 3);
            if (digits.length >= 4) formatted += ' ' + digits.substring(3, 6);
            if (digits.length >= 7) formatted += ' ' + digits.substring(6, 10);

            phoneInput.value = formatted;
        });

        // Prevent deleting +63
        phoneInput.addEventListener('keydown', function (e) {
            if (phoneInput.selectionStart <= PREFIX.length &&
                (e.key === 'Backspace' || e.key === 'Delete')) {
                e.preventDefault();
            }
        });

        // Keep cursor after +63
        phoneInput.addEventListener('focus', function () {
            if (phoneInput.selectionStart < PREFIX.length) {
                phoneInput.setSelectionRange(PREFIX.length, PREFIX.length);
            }
        });
    }

    // =========================
    // SERVICE SELECT
    // =========================
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('click', function () {

            document.querySelectorAll('.service-card')
                .forEach(c => c.classList.remove('active'));

            this.classList.add('active');

            selectedPrice = Number(this.dataset.price) || 0;

            if (productInput) productInput.value = this.dataset.id;

            calculateTotal();
        });
    });

    // =========================
    // COLOR
    // =========================
    document.querySelectorAll('.color-option').forEach(card => {
        card.addEventListener('click', function () {

            document.querySelectorAll('.color-option')
                .forEach(c => c.classList.remove('active'));

            this.classList.add('active');

            if (colorInput) {
                colorInput.value = this.dataset.value;
            }

            calculateTotal();
        });
    });

    // =========================
    // DELIVERY STYLE (VISUAL ONLY)
    // =========================
    document.querySelectorAll('.delivery-option').forEach(card => {
        card.addEventListener('click', function () {

            document.querySelectorAll('.delivery-option')
                .forEach(c => c.classList.remove('active'));

            this.classList.add('active');

            const input = document.getElementById('delivery_type');
            if (input) {
                input.value = this.dataset.value;
            }
        });
    });

    // =========================
    // FILE UPLOAD
    // =========================
    const dropArea  = get('drop-area');
    const fileInput = get('fileElem');
    const fileList  = get('file-list');
    const chooseBtn = get('chooseFilesBtn');

    // "Choose Files" button opens picker
    if (chooseBtn) {
        chooseBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.click();
        });
    }

    // Drop area click (but NOT when clicking the button)
    if (dropArea) {
        dropArea.addEventListener('click', function(e) {
            if (e.target.closest('button')) return; // ignore button clicks
            fileInput.click();
        });

        dropArea.addEventListener('dragover', e => {
            e.preventDefault();
            dropArea.classList.add('bg-pink-100');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('bg-pink-100');
        });

        dropArea.addEventListener('drop', e => {
            e.preventDefault();
            dropArea.classList.remove('bg-pink-100');
            const dt = new DataTransfer();
            [...e.dataTransfer.files].forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
            fileList.innerHTML = ''; // clear old list
            handleFiles(e.dataTransfer.files);
        });

        // In the fileInput change listener:
        // if (fileInput) {
        //     fileInput.addEventListener('change', () => {
        //         fileList.innerHTML = ''; // clear old list before re-rendering
        //         handleFiles(fileInput.files);
        //     });
        // }
    }

    // When files are selected via picker
    if (fileInput) {
        fileInput.addEventListener('change', () => {
            handleFiles(fileInput.files);
        });
    }

    function handleFiles(files) {
        const maxSize = 50 * 1024 * 1024; // 50MB in bytes

        [...files].forEach(file => {
            if (file.size > maxSize) {
                showFilError(`"${file.name}" is too large (${(file.size / 1024 / 1024).toFixed(1)}MB). Max size is 50MB.`);
                return; // skip this file
            }
            renderFile(file);
        });
    }

    function showFilError(message) {
        const errorDiv = document.getElementById('file-error');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
            setTimeout(() => errorDiv.classList.add('hidden'), 5000);
        }
    }

    function renderFile(file) {
        if (!fileList) return;

        const div = document.createElement('div');
        div.className = "flex justify-between items-center bg-gray-100 px-4 py-2 rounded-lg";

        let previewBtn = '';
        if (file.type.startsWith('image/')) {
            const url = URL.createObjectURL(file);
            previewBtn = `<button type="button" onclick="openPreview('${url}')" class="text-blue-500 text-xs underline">View</button>`;
        } else if (file.type === 'application/pdf') {
            const url = URL.createObjectURL(file);
            previewBtn = `<a href="${url}" target="_blank" class="text-blue-500 text-xs underline">Open PDF</a>`;
        } else {
            previewBtn = `<span class="text-gray-400 text-xs">No Preview</span>`;
        }

        div.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-file text-pink-400 text-sm"></i>
                <span class="text-sm">${file.name}</span>
                <span class="text-xs text-gray-400">(${(file.size / 1024).toFixed(1)} KB)</span>
            </div>
            <div class="flex gap-3 items-center">
                ${previewBtn}
                <span class="text-xs text-green-500 font-medium">✔ Ready</span>
            </div>
        `;

        fileList.appendChild(div);
    }

    // =========================
    // PREVIEW MODAL
    // =========================
    window.openPreview = function (src) {
        const modal = get('preview-modal');
        const img   = get('preview-img');

        if (img) img.src = src;
        if (modal) modal.classList.remove('hidden');
    };

    window.closePreview = function () {
        const modal = get('preview-modal');
        if (modal) modal.classList.add('hidden');
    };

    // =========================
    // CUSTOM SIZE (FIXED)
    // =========================

    // Get elements once
    const customSizeField = document.querySelector('[name="custom_size"]');
    const customBox = document.getElementById('custom-size-input');

    // ✅ Run ONLY ONCE (not inside click)
    if (customSizeField) {
        customSizeField.addEventListener('input', function () {
            if (paperSizeInput && this.value.trim() !== '') {
                paperSizeInput.value = 'Custom - ' + this.value.trim();
            } else if (paperSizeInput) {
                paperSizeInput.value = 'Custom';
            }
        });
    }

    // Click selection
    document.querySelectorAll('.paper-size').forEach(card => {
        card.addEventListener('click', function () {

            // Remove active from all
            document.querySelectorAll('.paper-size')
                .forEach(c => c.classList.remove('active'));

            // Add active to selected
            this.classList.add('active');

            let value = this.dataset.value;

            // Set hidden input
            if (paperSizeInput) {
                paperSizeInput.value = value;
            }

            // Show/hide custom input
            if (customBox) {
                if (value === 'Custom') {
                    customBox.classList.remove('hidden');
                } else {
                    customBox.classList.add('hidden');

                    // Clear custom input when switching away
                    if (customSizeField) {
                        customSizeField.value = '';
                    }
                }
            }
        });
    });

    // =========================
    // PRICE CALCULATION
    // =========================
    function calculateTotal() {

        let qty = qtyInput ? Number(qtyInput.value) || 0 : 0;
        let color = colorInput ? colorInput.value : '';
        let quality = qualityInput ? qualityInput.value : 'Matte';

        let discount = 0;
        if (qty >= 500) discount = 0.20;
        else if (qty >= 100) discount = 0.10;

        let discounted = selectedPrice - (selectedPrice * discount);
        let colorFee = (color === 'Full Color') ? 10 : 0;

        let qualityFees = {
            Matte: 0,
            Glossy: -5,
            Premium: 20
        };

        let qualityFee = qualityFees[quality] ?? 0;

        let pricePerUnit = discounted + colorFee + qualityFee;
        let total = pricePerUnit * qty;

        setText('basePrice', "₱" + selectedPrice.toFixed(2));
        setText('colorFee', "₱" + colorFee.toFixed(2));
        setText('qualityFee', "₱" + qualityFee.toFixed(2));
        setText('pricePerUnit', "₱" + pricePerUnit.toFixed(2));
        setText('qtyDisplay', qty);
        setText('subtotal', "₱" + total.toFixed(2));
        setText('totalPrice', "₱" + total.toFixed(2));
    }

    // =========================
    // LISTENERS
    // =========================
    safeListener(qtyInput, 'input', calculateTotal);
    safeListener(qualityInput, 'change', calculateTotal);

    calculateTotal();

});
</script>

@endsection
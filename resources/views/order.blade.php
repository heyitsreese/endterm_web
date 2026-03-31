<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - Sprint PHL</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-gray-800 min-h-screen flex flex-col" x-data="orderWizard()">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm px-6 py-4">
        <div class="flex justify-between items-center max-w-6xl mx-auto w-full">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.jpg') }}" class="w-10 h-10 rounded-full" alt="Sprint PHL Logo">
                    <h1 class="font-semibold text-lg max-sm:hidden">Sprint PHL</h1>
                </a>
            </div>

            <!-- Right Links -->
            <div class="flex items-center gap-4 text-sm font-medium">
                <a href="/track-order" class="text-gray-600 hover:text-gray-900 hidden sm:block">Track Order</a>
                <a href="/login" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Sign In</a>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow flex flex-col w-full max-w-4xl mx-auto px-4 py-8">
        
        <!-- Stepper (Hidden on Step 5) -->
        <div class="mb-10 mt-4" x-show="step < 5" x-cloak>
            <div class="flex items-center justify-between relative">
                <!-- Line Behind -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-200 z-0 hidden md:block"></div>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-pink-500 z-0 transition-all duration-300 hidden md:block" 
                     :style="`width: ${(step - 1) * 33.33}%`"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center w-1/4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white transition-colors duration-300"
                         :class="step >= 1 ? 'bg-pink-500' : 'bg-gray-200 text-gray-500'">
                        <i data-feather="check" x-show="step > 1" class="w-5 h-5"></i>
                        <span x-show="step === 1">1</span>
                    </div>
                    <div class="mt-3 text-center hidden md:block">
                        <div class="text-sm font-semibold" :class="step >= 1 ? 'text-gray-900' : 'text-gray-400'">Your Details</div>
                        <div class="text-xs text-gray-400">Name & Email</div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center w-1/4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white transition-colors duration-300"
                         :class="step >= 2 ? 'bg-pink-500' : 'bg-gray-200 text-gray-500'">
                        <i data-feather="check" x-show="step > 2" class="w-5 h-5"></i>
                        <span x-show="step <= 2">2</span>
                    </div>
                    <div class="mt-3 text-center hidden md:block">
                        <div class="text-sm font-semibold" :class="step >= 2 ? 'text-gray-900' : 'text-gray-400'">What to Print</div>
                        <div class="text-xs text-gray-400">Choose Service</div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center w-1/4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white transition-colors duration-300"
                         :class="step >= 3 ? 'bg-pink-500' : 'bg-gray-200 text-gray-500'">
                        <i data-feather="check" x-show="step > 3" class="w-5 h-5"></i>
                        <span x-show="step <= 3">3</span>
                    </div>
                    <div class="mt-3 text-center hidden md:block">
                        <div class="text-sm font-semibold" :class="step >= 3 ? 'text-gray-900' : 'text-gray-400'">Upload Files</div>
                        <div class="text-xs text-gray-400">Your Design</div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative z-10 flex flex-col items-center w-1/4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-colors duration-300"
                         :class="step >= 4 ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-500'">
                        4
                    </div>
                    <div class="mt-3 text-center hidden md:block">
                        <div class="text-sm font-semibold" :class="step >= 4 ? 'text-gray-900' : 'text-gray-400'">Finalize</div>
                        <div class="text-xs text-gray-400">Complete Order</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== STEP 1: CONTACT INFO ==================== -->
        <div x-show="step === 1" x-cloak>
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold mb-2">Let's Get Started!</h2>
                <p class="text-gray-500">We just need a few details to get your order ready</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
                <div class="flex items-center gap-2 font-semibold text-lg mb-1">
                    <i data-feather="mail" class="text-pink-500 w-5 h-5"></i> Your Contact Information
                </div>
                <p class="text-sm text-gray-500 mb-6">No account needed - we'll email your order details</p>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                            <i data-feather="user" class="w-4 h-4 text-gray-400"></i> Your Full Name
                        </label>
                        <input type="text" x-model="order.name" class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-400" placeholder="John Smith">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                            <i data-feather="mail" class="w-4 h-4 text-gray-400"></i> Email Address
                        </label>
                        <input type="email" x-model="order.email" class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-400" placeholder="john@example.com">
                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1"><i data-feather="help-circle" class="w-3 h-3"></i> We'll send your order ID and updates here</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                            <i data-feather="phone" class="w-4 h-4 text-gray-400"></i> Phone Number <span class="text-gray-400 text-xs">(Optional)</span>
                        </label>
                        <input type="tel" x-model="order.phone" class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-400" placeholder="+1 (555) 000-0000">
                    </div>
                </div>

                <div class="mt-8 bg-pink-50 border border-pink-100 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <div class="bg-white rounded-full p-1 shadow-sm mt-0.5"><i data-feather="check-circle" class="w-5 h-5 text-pink-500 bg-white"></i></div>
                        <div>
                            <h4 class="font-semibold text-pink-700 mb-2">✨ Quick & Easy Ordering</h4>
                            <ul class="space-y-1 text-sm text-pink-600 font-medium">
                                <li class="flex items-center gap-2">✓ No account registration needed</li>
                                <li class="flex items-center gap-2">✓ Get your order ID instantly</li>
                                <li class="flex items-center gap-2">✓ Track anytime with your ID</li>
                                <li class="flex items-center gap-2">✓ Receive email updates automatically</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center text-sm text-gray-500 mb-8">
                💼 Need to manage multiple orders? 
                <button class="px-4 py-2 border rounded-lg bg-white shadow-sm ml-2 font-medium hover:bg-gray-50">Create a Business Account</button>
            </div>
        </div>

        <!-- ==================== STEP 2: PRINTING DETAILS ==================== -->
        <div x-show="step === 2" x-cloak>
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold mb-2">What Would You Like to Print?</h2>
                <p class="text-gray-500">Choose your printing options below</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
                <div class="flex items-center gap-2 font-semibold text-lg mb-6 text-pink-600">
                    <i data-feather="file-text" class="w-5 h-5"></i> Printing Details
                </div>

                <!-- Service -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold mb-3">Select Printing Service</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <template x-for="s in ['Business Cards', 'Flyers', 'Posters', 'Brochures', 'Banners', 'Booklets']">
                            <button @click="order.service = s"
                                    class="border rounded-xl p-4 flex flex-col items-center justify-center gap-2 transition-all"
                                    :class="order.service === s ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300 hover:bg-gray-50'">
                                <div class="w-8 h-8 flex items-center justify-center text-2xl">
                                    <span x-show="s === 'Business Cards'">💼</span>
                                    <span x-show="s === 'Flyers'">📄</span>
                                    <span x-show="s === 'Posters'">🖼️</span>
                                    <span x-show="s === 'Brochures'">📰</span>
                                    <span x-show="s === 'Banners'">🎯</span>
                                    <span x-show="s === 'Booklets'">📖</span>
                                </div>
                                <span class="text-sm font-semibold" x-text="s"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold mb-1 flex items-center gap-2"><i data-feather="layers" class="w-4 h-4 text-gray-500"></i> How Many Do You Need?</label>
                    <input type="number" x-model="order.quantity" class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-400" placeholder="e.g., 100">
                    <p class="text-xs text-gray-500 mt-2">💡 Larger quantities often get better prices!</p>
                </div>

                <!-- Paper Size -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold mb-3 flex items-center gap-2"><i data-feather="maximize" class="w-4 h-4 text-gray-500"></i> Choose Paper Size</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <!-- A4 Size Option -->
                        <button @click="order.paperSize = 'A4'" 
                                class="relative border rounded-xl p-5 text-left transition-all"
                                :class="order.paperSize === 'A4' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300'">
                            <!-- Absolute Checkmark icon when selected -->
                            <i data-feather="check-circle" x-show="order.paperSize === 'A4'" class="absolute right-4 top-1/2 -translate-y-1/2 text-pink-500 w-5 h-5"></i>
                            <div class="font-semibold text-sm">A4 Size</div>
                            <div class="text-xs text-gray-500 mt-1">210 × 297 mm (Standard)</div>
                        </button>
                        
                        <!-- A5 Size Option -->
                        <button @click="order.paperSize = 'A5'" 
                                class="relative border rounded-xl p-5 text-left transition-all"
                                :class="order.paperSize === 'A5' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300'">
                            <i data-feather="check-circle" x-show="order.paperSize === 'A5'" class="absolute right-4 top-1/2 -translate-y-1/2 text-pink-500 w-5 h-5"></i>
                            <div class="font-semibold text-sm">A5 Size</div>
                            <div class="text-xs text-gray-500 mt-1">148 × 210 mm (Smaller)</div>
                        </button>

                        <!-- Letter Size Option -->
                        <button @click="order.paperSize = 'Letter'" 
                                class="relative border rounded-xl p-5 text-left transition-all"
                                :class="order.paperSize === 'Letter' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300'">
                            <i data-feather="check-circle" x-show="order.paperSize === 'Letter'" class="absolute right-4 top-1/2 -translate-y-1/2 text-pink-500 w-5 h-5"></i>
                            <div class="font-semibold text-sm">Letter Size</div>
                            <div class="text-xs text-gray-500 mt-1">8.5 × 11 inches (US)</div>
                        </button>

                         <!-- Custom Size Option -->
                        <button @click="order.paperSize = 'Custom'" 
                                class="relative border rounded-xl p-5 text-left transition-all"
                                :class="order.paperSize === 'Custom' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300'">
                            <i data-feather="check-circle" x-show="order.paperSize === 'Custom'" class="absolute right-4 top-1/2 -translate-y-1/2 text-pink-500 w-5 h-5"></i>
                            <div class="font-semibold text-sm">Custom Size</div>
                            <div class="text-xs text-gray-500 mt-1">Tell us your size</div>
                        </button>

                    </div>
                </div>

                <!-- Color -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold mb-3 flex items-center gap-2"><i data-feather="aperture" class="w-4 h-4 text-gray-500"></i> Color Options</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button @click="order.color = 'Full Color'" class="relative border rounded-xl p-5 text-left transition-all" :class="order.color === 'Full Color' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:bg-gray-50'">
                            <i data-feather="check-circle" x-show="order.color === 'Full Color'" class="absolute right-4 top-1/2 -translate-y-1/2 text-pink-500 w-5 h-5"></i>
                            <div class="font-semibold text-sm flex items-center gap-2">🎨 Full Color</div>
                            <div class="text-xs text-gray-500 mt-1">Vibrant colors (CMYK printing)</div>
                        </button>
                        <button @click="order.color = 'Black & White'" class="relative border rounded-xl p-5 text-left transition-all" :class="order.color === 'Black & White' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:bg-gray-50'">
                            <i data-feather="check-circle" x-show="order.color === 'Black & White'" class="absolute right-4 top-1/2 -translate-y-1/2 text-pink-500 w-5 h-5"></i>
                            <div class="font-semibold text-sm flex items-center gap-2">⚫ Black & White</div>
                            <div class="text-xs text-gray-500 mt-1">Simple and cost-effective</div>
                        </button>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold mb-1">Paper Quality</label>
                    <select class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-400 appearance-none text-gray-600" x-model="order.paperQuality">
                        <option value="">Choose paper quality</option>
                        <option>Standard (80gsm)</option>
                        <option>Premium (100gsm)</option>
                        <option>Cardstock (250gsm)</option>
                        <option>Glossy Finish</option>
                        <option>Matte Finish</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Special Instructions <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <textarea class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-400 min-h-[100px]" placeholder="Tell us anything special about your order..."></textarea>
                </div>
            </div>
        </div>

        <!-- ==================== STEP 3: UPLOAD FILES ==================== -->
        <div x-show="step === 3" x-cloak>
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold mb-2">Upload Your Design Files</h2>
                <p class="text-gray-500">Add the files you want us to print</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
                <div class="flex items-center gap-2 font-semibold text-lg mb-1 text-pink-600">
                    <i data-feather="upload" class="w-5 h-5"></i> Your Files
                </div>
                <p class="text-sm text-gray-500 mb-6">We accept PDF, JPEG, PNG, JPEG, and PSD files</p>

                <!-- Drag & Drop -->
                <div class="border-2 border-dashed border-pink-200 rounded-2xl p-12 text-center bg-pink-50/30 hover:bg-pink-50/50 transition-colors cursor-pointer mb-8" @click="$refs.fileInput.click()">
                    <div class="w-16 h-16 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-feather="upload" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-semibold text-gray-700 text-lg mb-2">Drag & Drop Your Files Here</h3>
                    <p class="text-sm text-gray-500 mb-6">or click to browse your computer</p>
                    <button class="px-6 py-2 border rounded-lg bg-white shadow-sm font-medium text-sm flex items-center justify-center mx-auto gap-2">
                        <i data-feather="folder" class="w-4 h-4 text-gray-500"></i> Choose Files
                    </button>
                    <input type="file" x-ref="fileInput" class="hidden" multiple @change="handleFileUpload">
                    <p class="text-xs text-gray-400 mt-6">Supported: PDF, JPG, PSD, JPEG, PNG • Max size: 50MB per file</p>
                </div>

                <!-- Uploaded Files -->
                <div class="mb-8" x-show="hasFile">
                    <h4 class="font-semibold text-sm mb-3">Uploaded Files</h4>
                    <div class="border border-gray-200 rounded-xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-pink-50 text-pink-500 rounded-lg"><i data-feather="file-text" class="w-6 h-6"></i></div>
                            <div>
                                <div class="font-medium text-sm text-gray-800">design-mockup.pdf</div>
                                <div class="text-xs text-gray-400">2.4 MB</div>
                            </div>
                        </div>
                        <button class="text-red-500 text-sm font-medium hover:text-red-600" @click="hasFile = false">Remove</button>
                    </div>
                </div>

                <div class="bg-pink-50 border border-pink-100 rounded-xl p-6">
                    <h4 class="font-semibold text-pink-300/40 mb-3 flex items-center gap-2"><i data-feather="file" class="w-4 h-4"></i> File Tips for Best Results:</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">✓ Use high quality images (300 DPI or higher)</li>
                        <li class="flex items-center gap-2">✓ PDF files work best for print</li>
                        <li class="flex items-center gap-2">✓ Make sure text is clear and readable</li>
                        <li class="flex items-center gap-2">✓ Include a small border around your design</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- ==================== STEP 4: FINALIZE ==================== -->
        <div x-show="step === 4" x-cloak>
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold mb-2">Almost Done!</h2>
                <p class="text-gray-500">Review your order and complete payment</p>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-2xl shadow-sm border border-pink-100 p-8 mb-6 pb-2">
                <div class="flex items-center gap-2 font-semibold text-lg mb-6 text-pink-600">
                    <i data-feather="shopping-cart" class="w-5 h-5"></i> Your Order Summary
                </div>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between py-3 border-b border-gray-100 text-sm">
                        <span class="text-gray-500">Service:</span>
                        <span class="font-semibold" x-text="order.service || 'Business Cards'"></span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100 text-sm">
                        <span class="text-gray-500">Quantity:</span>
                        <span class="font-semibold"><span x-text="order.quantity || '500'"></span> units</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100 text-sm">
                        <span class="text-gray-500">Paper:</span>
                        <span class="font-semibold" x-text="order.paperQuality || 'Premium Quality'"></span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100 text-sm">
                        <span class="text-gray-500">Color:</span>
                        <span class="font-semibold" x-text="order.color || 'Full Color'"></span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100 text-sm">
                        <span class="text-gray-500">Subtotal:</span>
                        <span class="font-semibold">P 2000.00</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="bg-pink-400/90 text-white rounded-xl p-4 flex justify-between items-center mb-4">
                    <span class="text-lg">Total:</span>
                    <span class="text-2xl font-semibold">P 2000.00</span>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
                <div class="flex items-center gap-2 font-semibold text-lg mb-6 text-gray-800">
                    <i data-feather="credit-card" class="w-5 h-5 text-pink-500"></i> Payment Details
                </div>

                <div class="space-y-5 mb-8">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Name on Card</label>
                        <input type="text" class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-400" placeholder="John Smith">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Card Number</label>
                        <input type="text" class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-400" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Expiry Date</label>
                            <input type="text" class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-400" placeholder="MM/YY">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Security Code (CVC)</label>
                            <input type="text" class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-400" placeholder="123">
                        </div>
                    </div>
                </div>

                <!-- Secure -->
                <div class="bg-green-50 text-green-700 rounded-lg p-4 flex items-center gap-2 text-sm border border-green-100 font-medium">
                    <i data-feather="check-circle" class="w-5 h-5"></i> 🔒 Your payment is 100% secure and encrypted
                </div>
            </div>
        </div>

        <!-- ==================== STEP 5: SUCCESS ==================== -->
        <div x-show="step === 5" x-cloak class="my-auto py-8">
            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-md shadow-green-200">
                    <i data-feather="check" class="w-10 h-10 text-white stroke-[3px]"></i>
                </div>
                <h2 class="text-3xl font-bold mb-2">Order Placed Successfully!</h2>
                <p class="text-gray-500">Thank you for your order. We'll get started on it right away.</p>
            </div>

            <!-- Order ID Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-8 mb-6 text-center shadow-lg shadow-blue-50">
                <div class="bg-blue-50/50 -mx-8 -mt-8 px-8 py-4 border-b border-blue-100 rounded-t-2xl mb-8">
                    <h3 class="font-bold text-gray-900 text-sm mb-1">Your Order ID</h3>
                    <p class="text-xs text-gray-500">Save this ID to track your order anytime</p>
                </div>

                <div class="border-2 border-dashed border-blue-200 rounded-xl p-6 flex justify-center items-center gap-3 mb-6 bg-white hover:border-blue-300 transition-colors cursor-pointer group">
                    <span class="text-3xl font-medium tracking-wide text-pink-400 group-hover:text-pink-500 transition-colors">ORD-893XY2</span>
                    <i data-feather="copy" class="w-5 h-5 text-pink-300 group-hover:text-pink-500"></i>
                </div>
                <p class="text-sm text-gray-500 flex justify-center items-center gap-2">
                    <i data-feather="mail" class="w-4 h-4"></i> Order confirmation sent to: <span class="font-medium text-gray-700">john@example.com</span>
                </p>
            </div>

            <!-- Next Steps -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-10">
                <h3 class="font-bold text-gray-800 mb-6 font-medium">What Happens Next?</h3>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">1</div>
                        <div>
                            <h4 class="font-semibold text-sm">Email Confirmation</h4>
                            <p class="text-xs text-gray-500 mt-1">Check your email for order details and tracking information</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">2</div>
                        <div>
                            <h4 class="font-semibold text-sm">Order Processing</h4>
                            <p class="text-xs text-gray-500 mt-1">Our team will review your files and begin production</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">3</div>
                        <div>
                            <h4 class="font-semibold text-sm">Track Your Order</h4>
                            <p class="text-xs text-gray-500 mt-1">Use your Order ID to check the status anytime</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">4</div>
                        <div>
                            <h4 class="font-semibold text-sm">Delivery</h4>
                            <p class="text-xs text-gray-500 mt-1">Your order will be delivered within 2-3 business days</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="/track-order" class="w-full sm:w-auto bg-pink-500 hover:bg-pink-600 text-white font-medium px-8 py-3 rounded-xl flex items-center justify-center gap-2 transition-colors">
                    <i data-feather="search" class="w-4 h-4"></i> Track Order
                </a>
                <button @click="reset()" class="w-full sm:w-auto bg-white border border-gray-200 hover:bg-gray-50 font-medium px-8 py-3 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm">
                    <i data-feather="printer" class="w-4 h-4"></i> Place Another Order
                </button>
                <a href="/" class="w-full sm:w-auto bg-white border border-gray-200 hover:bg-gray-50 font-medium px-8 py-3 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm">
                    <i data-feather="home" class="w-4 h-4"></i> Back to Home
                </a>
            </div>

            <!-- Footer note -->
            <div class="mt-8 bg-blue-50/50 border border-blue-100 rounded-xl p-4 text-center text-sm text-gray-600">
                Questions about your order? Contact us at <a href="mailto:sprintphl@gmail.com" class="text-pink-500 font-medium hover:underline">sprintphl@gmail.com</a> or call <span class="text-pink-500 font-medium">1-800-SPRINT-01</span>
            </div>
        </div>

        <!-- ==================== NAVIGATION BUTTONS ==================== -->
        <div class="mt-auto flex justify-between items-center bg-white p-4 rounded-xl border border-gray-100 shadow-sm" x-show="step < 5" x-cloak>
            <button @click="back()" 
                    class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition flex items-center gap-2 text-sm"
                    :class="step === 1 ? 'invisible' : ''">
                ← Go Back
            </button>
            <button @click="next()" 
                    class="px-8 py-2 bg-pink-400/90 text-white rounded-lg hover:bg-pink-500 transition font-medium flex items-center gap-2 shadow-sm text-sm border border-pink-500"
                    x-show="step < 4">
                Continue →
            </button>
            <button @click="next()" 
                    class="px-8 py-2 bg-pink-400/90 text-white rounded-lg hover:bg-pink-500 transition font-medium flex items-center gap-2 shadow-sm text-sm border border-pink-500 bg-gradient-to-r from-pink-400 to-pink-500"
                    x-show="step === 4">
                🎉 Complete Order
            </button>
        </div>

    </main>

    <!-- Footer -->
    <footer x-show="step < 4" x-cloak class="bg-gray-900 text-gray-300 px-8 py-10 mt-12 hidden md:block">
        <div class="grid md:grid-cols-4 gap-6 max-w-6xl mx-auto">
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
        // Initialize Feather icons initially and on step changes
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderWizard', () => ({
                step: 1,
                hasFile: true,
                order: {
                    name: '',
                    email: '',
                    phone: '',
                    service: 'Business Cards',
                    quantity: '',
                    paperSize: 'A4',
                    color: 'Full Color',
                    paperQuality: ''
                },
                next() {
                    if (this.step < 5) this.step++;
                    this.$nextTick(() => { feather.replace(); window.scrollTo(0,0); });
                },
                back() {
                    if (this.step > 1) this.step--;
                    this.$nextTick(() => { feather.replace(); window.scrollTo(0,0); });
                },
                reset() {
                    this.step = 1;
                    this.order = {
                        name: '', email: '', phone: '', service: 'Business Cards', quantity: '', paperSize: 'A4', color: 'Full Color', paperQuality: ''
                    };
                    this.$nextTick(() => { feather.replace(); window.scrollTo(0,0); });
                },
                handleFileUpload(e) {
                    if(e.target.files.length > 0) {
                        this.hasFile = true;
                    }
                }
            }))
        });

        // First render
        feather.replace();
    </script>
</body>
</html>

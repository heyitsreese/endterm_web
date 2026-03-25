<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sprint PHL</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-gray-200 min-h-screen flex items-center justify-center">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-8">

        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.jpg') }}" class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center" alt="Hero Image">
        </div>

        <!-- Title -->
        <h2 class="text-center text-2xl font-semibold">Sprint PHL</h2>
        <p class="text-center text-gray-500 text-sm mb-6">
            Sign in to your printing management account
        </p>

        <!-- Form -->
        <form>
            <!-- Email -->
            <div class="mb-4">
                <label class="text-sm font-medium">Email</label>
                <input type="email"
                       placeholder="name@example.com"
                       class="w-full mt-1 px-4 py-2 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-400">
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="text-sm font-medium">Password</label>
                <div class="relative">
                    <input type="password"
                           placeholder="Enter your password"
                           class="w-full mt-1 px-4 py-2 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-400">
                    
                    <!-- Eye Icon -->
                    <span class="absolute right-3 top-3 text-gray-400 cursor-pointer">
                        👁
                    </span>
                </div>
            </div>

            <!-- Options -->
            <div class="flex justify-between items-center text-sm mb-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox">
                    Remember me
                </label>

                <a href="#" class="text-pink-500 hover:underline">
                    Forgot password?
                </a>
            </div>

            <!-- Button -->
            <button class="w-full bg-pink-500 text-white py-2 rounded-lg hover:bg-pink-600 transition">
                Sign In
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-6">
            <div class="flex-1 h-px bg-gray-300"></div>
            <span class="px-3 text-gray-400 text-sm">Or</span>
            <div class="flex-1 h-px bg-gray-300"></div>
        </div>

        <!-- Signup -->
        <p class="text-center text-sm text-gray-500">
            Don't have an account?
            <a href="#" class="text-pink-500 font-medium hover:underline">
                Sign up
            </a>
        </p>

    </div>

</body>
</html>
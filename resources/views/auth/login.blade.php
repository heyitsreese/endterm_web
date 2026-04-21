@extends('layouts.content')

@section('content')

<div class="flex justify-center items-center min-h-[80vh]">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-xl">

        <!-- LOGO -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.jpg') }}"
                 class="w-14 h-14 rounded-full object-cover">
        </div>

        <!-- TITLE -->
        <h2 class="text-xl font-semibold text-center">Sprint PHL</h2>
        <p class="text-sm text-gray-500 text-center mb-6">
            Sign in to your printing management account
        </p>

        {{-- SESSION ERROR --}}
        @if(session('error'))
            <div class="bg-red-100 text-red-600 text-sm p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 text-sm p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div class="mb-4">
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" required
                    value="{{ old('email') }}"
                    placeholder="name@example.com"
                    class="w-full mt-1 px-3 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400">
            </div>

            <!-- PASSWORD -->
            <div class="mb-4">
                <label class="text-sm font-medium">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        placeholder="Enter your password"
                        class="w-full mt-1 px-3 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400">

                    <i id="togglePassword"
                       data-feather="eye"
                       class="absolute right-3 top-3 w-4 h-4 text-gray-400 cursor-pointer"></i>
                </div>
            </div>

            <!-- OPTIONS -->
            <div class="flex justify-between items-center text-sm mb-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <a href="#" class="text-pink-500 hover:underline">
                    Forgot password?
                </a>
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full py-2 rounded-lg text-white transition"
                style="background-color: #D47497;">
                Sign In
            </button>

        </form>

        <!-- DIVIDER -->
        <div class="flex items-center my-6">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="px-3 text-sm text-gray-400">Or</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        <!-- SIGN UP -->
        <p class="text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-pink-500 hover:underline">
                Sign up
            </a>
        </p>

    </div>

</div>

<!-- PASSWORD TOGGLE SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function () {
            const type = password.type === 'password' ? 'text' : 'password';
            password.type = type;
        });
    }

});
</script>

@endsection
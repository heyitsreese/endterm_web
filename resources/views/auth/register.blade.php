@extends('layouts.content')

@section('content')

<div class="flex justify-center items-center min-h-[80vh] mb-6">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-xl">

        <!-- LOGO -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.jpg') }}"
                 class="w-14 h-14 rounded-full object-cover">
        </div>

        <!-- TITLE -->
        <h2 class="text-xl font-semibold text-center">Sprint PHL</h2>
        <p class="text-sm text-gray-500 text-center mb-6">
            Create your printing management account
        </p>

        {{-- ERRORS --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 text-sm p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- NAME -->
            <div class="mb-4">
                <label class="text-sm font-medium">Full Name</label>
                <input type="text" name="name" required
                    value="{{ old('name') }}"
                    placeholder="Juan Dela Cruz"
                    class="w-full mt-1 px-3 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400">
            </div>

            <!-- EMAIL -->
            <div class="mb-4">
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" required
                    value="{{ old('email') }}"
                    placeholder="name@example.com"
                    class="w-full mt-1 px-3 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400">
            </div>

            <!-- ACCOUNT TYPE -->
            <div class="mb-4">
                <label class="text-sm font-medium block mb-2">Account Type</label>

                <div class="flex gap-2">
                    <button type="button"
                        id="btnOrganization"
                        class="w-full py-2 rounded-lg border bg-pink-500 text-white">
                        Organization
                    </button>

                    <button type="button"
                        id="btnCompany"
                        class="w-full py-2 rounded-lg border text-gray-600">
                        Company
                    </button>
                </div>

                <!-- Hidden input (this is what Laravel reads) -->
                <input type="hidden" name="account_type" id="account_type" value="organization">
            </div>

            <!-- COMPANY NAME -->
            <div class="mb-4">
                <label class="text-sm font-medium">Organization / Company Name</label>
                <input type="text" name="company_name" required
                    value="{{ old('company_name') }}"
                    placeholder="Enter name"
                    class="w-full mt-1 px-3 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400">
            </div>

            <!-- PASSWORD -->
            <div class="mb-4">
                <label class="text-sm font-medium">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        placeholder="Enter password"
                        class="w-full mt-1 px-3 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400">

                    <i id="togglePassword"
                       data-feather="eye"
                       class="absolute right-3 top-3 w-4 h-4 text-gray-400 cursor-pointer"></i>
                </div>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="mb-4">
                <label class="text-sm font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                    placeholder="Confirm password"
                    class="w-full mt-1 px-3 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400">
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full py-2 rounded-lg text-white transition"
                style="background-color: #D47497;">
                Sign Up
            </button>

        </form>

        <!-- DIVIDER -->
        <div class="flex items-center my-6">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="px-3 text-sm text-gray-400">Or</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        <!-- LOGIN -->
        <p class="text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-pink-500 hover:underline">
                Sign in
            </a>
        </p>

    </div>

</div>

<!-- SCRIPTS -->
<script>
    const btnOrg = document.getElementById('btnOrganization');
    const btnCom = document.getElementById('btnCompany');
    const accountInput = document.getElementById('account_type');

    function setActive(type) {
        if (type === 'organization') {
            btnOrg.classList.add('bg-pink-500', 'text-white');
            btnCom.classList.remove('bg-pink-500', 'text-white');
        } else {
            btnCom.classList.add('bg-pink-500', 'text-white');
            btnOrg.classList.remove('bg-pink-500', 'text-white');
        }
    }

    // initial load
    setActive(accountInput.value);

    btnOrg.addEventListener('click', () => {
        accountInput.value = 'organization';
        setActive('organization');
    });

    btnCom.addEventListener('click', () => {
        accountInput.value = 'company';
        setActive('company');
    });
</script>

<script>
    // Show/hide company field
    const accountType = document.getElementById('account_type');
    const companyField = document.getElementById('company_field');

    accountType.addEventListener('change', function () {
        if (this.value === 'organization') {
            companyField.classList.remove('hidden');
        } else {
            companyField.classList.add('hidden');
        }
    });

    // Password toggle
    document.addEventListener('DOMContentLoaded', function () {

    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function () {

            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

        });
    }
});
</script>

@endsection
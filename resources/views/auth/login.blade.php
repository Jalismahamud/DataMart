<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="text-center mb-6">
            <h2 class="text-2xl font-black text-[#2b1d12]">Admin Login</h2>
            <p class="text-sm text-slate-500 mt-1">আপনার প্রশাসক অ্যাকাউন্টে প্রবেশ করুন</p>
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address')" class="text-[#2b1d12] font-semibold text-sm" />
            <x-text-input id="email" 
                class="block mt-2 w-full px-4 py-2 border border-[#dcc6ac] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d97706] focus:border-transparent" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="admin@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" class="text-[#2b1d12] font-semibold text-sm" />

            <x-text-input id="password" 
                class="block mt-2 w-full px-4 py-2 border border-[#dcc6ac] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d97706] focus:border-transparent"
                type="password"
                name="password"
                required 
                autocomplete="current-password" 
                placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Remember Me -->
        <div class="block mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-[#dcc6ac] text-[#d97706] focus:ring-[#d97706]" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('আমাকে মনে রাখুন') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-[#d97706] hover:text-[#b85e00] font-medium" href="{{ route('password.request') }}">
                    {{ __('পাসওয়ার্ড ভুলে গেছেন?') }}
                </a>
            @endif

            <button type="submit" class="px-6 py-2 bg-[#d97706] text-white font-semibold rounded-lg hover:bg-[#b85e00] transition-colors focus:outline-none focus:ring-2 focus:ring-[#d97706] focus:ring-offset-2">
                {{ __('লগইন করুন') }}
            </button>
        </div>
    </form>
</x-guest-layout>

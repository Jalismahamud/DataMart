<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-black text-[#2b1d12]">পাসওয়ার্ড রিসেট</h2>
        <p class="text-sm text-slate-500 mt-1">আপনার ইমেইল ঠিকানা দিয়ে আমরা রিসেট লিঙ্ক পাঠাব</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

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
                placeholder="admin@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" class="text-sm text-[#d97706] hover:text-[#b85e00] font-medium">
                {{ __('ফিরে যান') }}
            </a>
            <button type="submit" class="px-6 py-2 bg-[#d97706] text-white font-semibold rounded-lg hover:bg-[#b85e00] transition-colors focus:outline-none focus:ring-2 focus:ring-[#d97706] focus:ring-offset-2">
                {{ __('রিসেট লিঙ্ক পাঠান') }}
            </button>
        </div>
    </form>
</x-guest-layout>

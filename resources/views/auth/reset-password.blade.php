<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-black text-[#2b1d12]">নতুন পাসওয়ার্ড সেট করুন</h2>
        <p class="text-sm text-slate-500 mt-1">একটি শক্তিশালী পাসওয়ার্ড তৈরি করুন</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address')" class="text-[#2b1d12] font-semibold text-sm" />
            <x-text-input id="email" 
                class="block mt-2 w-full px-4 py-2 border border-[#dcc6ac] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d97706] focus:border-transparent" 
                type="email" 
                name="email" 
                :value="old('email', $request->email)" 
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
                autocomplete="new-password" 
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-[#2b1d12] font-semibold text-sm" />

            <x-text-input id="password_confirmation" 
                class="block mt-2 w-full px-4 py-2 border border-[#dcc6ac] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#d97706] focus:border-transparent"
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password" 
                placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
        </div>

        <div class="flex items-center justify-center mt-6">
            <button type="submit" class="px-6 py-2 bg-[#d97706] text-white font-semibold rounded-lg hover:bg-[#b85e00] transition-colors focus:outline-none focus:ring-2 focus:ring-[#d97706] focus:ring-offset-2">
                {{ __('পাসওয়ার্ড রিসেট করুন') }}
            </button>
        </div>
    </form>
</x-guest-layout>

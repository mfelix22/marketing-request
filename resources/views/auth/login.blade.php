<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Welcome back</h2>
        <p class="text-gray-500 text-sm mt-1">Sign in to your account to continue</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div class="mb-4">
            <label for="username" class="block text-xs font-medium text-gray-600 mb-1.5">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                autocomplete="username"
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('username') border-red-400 @enderror">
            @error('username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-xs font-medium text-gray-600 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('password') border-red-400 @enderror">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mb-5">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                <span class="text-sm text-gray-600">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 transition-colors">Forgot password?</a>
            @endif
        </div>

        <button type="submit"
            class="w-full py-2.5 bg-[#1D3557] text-white text-sm font-semibold rounded-lg hover:bg-[#162840] transition-colors">
            Sign In
        </button>

        <p class="text-center text-xs text-gray-500 mt-5">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Register here</a>
        </p>
    </form>
</x-guest-layout>

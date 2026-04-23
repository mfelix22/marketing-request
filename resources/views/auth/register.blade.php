<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Create Account</h2>
        <p class="text-gray-500 text-sm mt-1">Register to submit marketing requests</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Full Name <span
                    class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('name') border-red-400 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Username <span
                    class="text-red-500">*</span></label>
            <input type="text" name="username" value="{{ old('username') }}" required
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('username') border-red-400 @enderror">
            @error('username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Email Address <span
                    class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('email') border-red-400 @enderror">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Department <span
                    class="text-red-500">*</span></label>
            @php $departments = \App\Models\Department::orderBy('name')->get(); @endphp
            <select name="department_id" required
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('department_id') border-red-400 @enderror">
                <option value="">Select your department</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}</option>
                @endforeach
            </select>
            @error('department_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Password <span
                    class="text-red-500">*</span></label>
            <input type="password" name="password" required autocomplete="new-password"
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition @error('password') border-red-400 @enderror">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Confirm Password <span
                    class="text-red-500">*</span></label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition">
        </div>

        <button type="submit"
            class="w-full py-2.5 bg-[#1D3557] text-white text-sm font-semibold rounded-lg hover:bg-[#162840] transition-colors">
            Create Account
        </button>

        <p class="text-center text-xs text-gray-500 mt-5">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Sign in</a>
        </p>
    </form>
</x-guest-layout>

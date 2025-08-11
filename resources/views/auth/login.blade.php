@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 p-6">
    <div class="bg-white shadow-lg rounded-2xl w-full max-w-md p-8 border border-gray-100 animate-card">

        {{-- Logo/Icon --}}
        <div class="flex justify-center mb-6 animate-stagger" style="animation-delay: 0.1s">
            <div class="bg-blue-500 p-4 rounded-xl shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </div>
        </div>

        {{-- Title --}}
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-8 animate-stagger" style="animation-delay: 0.2s">
            Masuk ke Akun Anda
        </h2>

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div class="animate-stagger" style="animation-delay: 0.3s">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="email" autofocus
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all duration-300 hover:shadow-sm @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="animate-stagger" style="animation-delay: 0.4s">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all duration-300 hover:shadow-sm @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember & Forgot --}}
            <div class="flex items-center justify-between text-sm animate-stagger" style="animation-delay: 0.5s">
                <label class="flex items-center gap-2 text-gray-600">
                    <input id="remember" name="remember" type="checkbox" class="rounded text-blue-500 focus:ring-blue-400">
                    Ingat saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-blue-500 hover:text-blue-600 transition-colors">Lupa password?</a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-blue-500 text-white py-3 px-4 rounded-lg font-medium shadow-sm hover:bg-blue-600 hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300 animate-stagger"
                style="animation-delay: 0.6s">
                Masuk
            </button>
        </form>

        {{-- Divider --}}
        <div class="flex items-center my-6 animate-stagger" style="animation-delay: 0.7s">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="px-3 text-gray-500 text-sm">atau</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        {{-- Register Link --}}
        <p class="mt-6 text-center text-sm text-gray-600 animate-stagger" style="animation-delay: 0.8s">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-medium text-blue-500 hover:text-blue-600 transition-colors">
                Daftar di sini
            </a>
        </p>
    </div>
</div>

{{-- Animations --}}
<style>
@keyframes fade-up {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes pop-in {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}
.animate-card {
    animation: fade-up 0.5s ease-out forwards;
}
.animate-stagger {
    opacity: 0;
    animation: fade-up 0.5s ease-out forwards;
}
.animate-stagger[style*="animation-delay"] {
    animation-delay: var(--delay, 0.1s);
}
</style>
@endsection

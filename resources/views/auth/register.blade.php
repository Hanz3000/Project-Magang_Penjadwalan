@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 p-6">
    <div class="bg-white shadow-lg rounded-2xl w-full max-w-md p-8 border border-gray-100 animate-card">

        {{-- Logo/Icon --}}
        <div class="flex justify-center mb-6 animate-stagger" style="animation-delay: 0.1s">
            <div class="bg-blue-500 p-4 rounded-xl shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
        </div>

        {{-- Title --}}
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-8 animate-stagger" style="animation-delay: 0.2s">
            Buat Akun Baru
        </h2>

        {{-- Form --}}
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            {{-- Nama Lengkap --}}
            <div class="animate-stagger" style="animation-delay: 0.3s">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all duration-300 hover:shadow-sm @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="animate-stagger" style="animation-delay: 0.4s">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all duration-300 hover:shadow-sm @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="animate-stagger" style="animation-delay: 0.5s">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all duration-300 hover:shadow-sm @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="animate-stagger" style="animation-delay: 0.6s">
                <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all duration-300 hover:shadow-sm">
            </div>

            {{-- Syarat & Ketentuan --}}
            <div class="flex items-center text-sm animate-stagger" style="animation-delay: 0.7s">
                <input id="terms" name="terms" type="checkbox" required
                    class="rounded text-blue-500 focus:ring-blue-400 border-gray-300">
                <label for="terms" class="ml-2 text-gray-600">
                    Saya menyetujui <a href="#" class="text-blue-500 hover:text-blue-600 transition-colors">syarat dan ketentuan</a>
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-blue-500 text-white py-3 px-4 rounded-lg font-medium shadow-sm hover:bg-blue-600 hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300 animate-stagger"
                style="animation-delay: 0.8s">
                Daftar Sekarang
            </button>
        </form>

        {{-- Divider --}}
        <div class="flex items-center my-6 animate-stagger" style="animation-delay: 0.9s">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="px-3 text-gray-500 text-sm">atau</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        {{-- Login Link --}}
        <p class="mt-6 text-center text-sm text-gray-600 animate-stagger" style="animation-delay: 1s">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-blue-500 hover:text-blue-600 transition-colors">
                Masuk disini
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
.animate-card {
    animation: fade-up 0.5s ease-out forwards;
}
.animate-stagger {
    opacity: 0;
    animation: fade-up 0.5s ease-out forwards;
}
</style>
@endsection

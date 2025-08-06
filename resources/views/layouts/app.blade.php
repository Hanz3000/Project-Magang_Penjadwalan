<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>To-Do List</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8fafc;
        }
        
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background-color: #4f46e5;
            transition: background-color 0.2s ease;
        }
        
        .btn-primary:hover {
            background-color: #4338ca;
        }
        
        .btn-danger {
            background-color: #ef4444;
            transition: background-color 0.2s ease;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        .nav-link {
            transition: color 0.2s ease;
        }
        
        .nav-link:hover {
            color: #4f46e5;
        }
    </style>
</head>

<body class="min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('tasks.index') }}" class="flex items-center text-xl font-semibold text-gray-900">
                        <i class="fas fa-tasks text-indigo-600 mr-2"></i>
                        TaskFlow
                    </a>
                    
                </div>
                
                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                    @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-danger text-white px-4 py-2 rounded text-sm font-medium">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="flex space-x-3">
                        <a href="{{ route('login') }}" class="nav-link px-4 py-2 text-gray-700">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary text-white px-4 py-2 rounded text-sm font-medium">Register</a>
                        @endif
                    </div>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="sm:hidden flex items-center">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 focus:outline-none" aria-controls="mobile-menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="sm:hidden hidden bg-white shadow-md" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                @auth
                <a href="{{ route('tasks.index') }}" class="block px-3 py-2 text-gray-700">
                    <i class="fas fa-list-check mr-2"></i> My Tasks
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-gray-700">Register</a>
                @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white card rounded-lg p-6">
                @yield('content')
            </div>
        </div>
    </main>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.querySelector('button[aria-controls="mobile-menu"]');
            const mobileMenu = document.getElementById('mobile-menu');
            
            menuButton.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !expanded);
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
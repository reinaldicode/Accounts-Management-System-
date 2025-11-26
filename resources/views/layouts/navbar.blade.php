<!-- resources/views/layouts/navbar.blade.php -->

<nav class="bg-indigo-600 text-white fixed w-full z-30 top-0 shadow-lg">
    <div class="px-6 py-3 flex justify-between items-center">
        <!-- Logo & Title -->
        <div class="flex items-center space-x-3">
            <i class="fas fa-shield-alt text-2xl"></i>
            <div>
                <h1 class="text-xl font-bold">AMS</h1>
                <p class="text-xs text-indigo-200">Accounts Management System</p>
            </div>
        </div>
        
        <!-- User Menu -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 hover:bg-indigo-700 px-3 py-2 rounded">
                <div class="w-8 h-8 bg-indigo-800 rounded-full flex items-center justify-center">
                    <i class="fas fa-user"></i>
                </div>
                <div class="text-left">
                    <p class="text-sm font-medium">{{ session('ams_fullname', 'User') }}</p>
                    <p class="text-xs text-indigo-200">{{ session('ams_username') }}</p>
                </div>
                <i class="fas fa-chevron-down text-sm"></i>
            </button>
            
            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" x-cloak
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 text-gray-700">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <hr class="my-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
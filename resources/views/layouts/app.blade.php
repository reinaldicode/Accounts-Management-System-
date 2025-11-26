<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AMS') - Accounts Management System</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    
    @include('layouts.navbar')
    
    <div class="flex">
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <main class="flex-1 p-6 ml-64 mt-16">
            
            <!-- Flash Messages -->
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
            @endif
            
            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
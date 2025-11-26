<!-- resources/views/auth/login.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - AMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-block p-4 bg-indigo-100 rounded-full mb-4">
                    <i class="fas fa-shield-alt text-4xl text-indigo-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">AMS Login</h1>
                <p class="text-gray-600 mt-2">Accounts Management System</p>
                
                @if($systemCode)
                <div class="mt-4 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Logging in to: <strong>{{ strtoupper($systemCode) }}</strong>
                </div>
                @endif
            </div>
            
            <!-- Error Messages -->
            @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
            </div>
            @endif
            
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf
                
                <!-- Hidden fields -->
                <input type="hidden" name="return_url" value="{{ $returnUrl }}">
                
                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Username
                    </label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Enter your username">
                    @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Enter your password">
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 transition duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </button>
            </form>
            
            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <i class="fas fa-shield-alt mr-1"></i>
                Secure Login - AMS {{ date('Y') }}
            </div>
        </div>
    </div>
    
</body>
</html>
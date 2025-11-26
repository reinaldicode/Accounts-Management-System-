<!-- resources/views/errors/404.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <div class="mb-8">
            <i class="fas fa-exclamation-triangle text-indigo-600 text-8xl mb-4"></i>
            <h1 class="text-6xl font-bold text-gray-800 mb-2">404</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Page Not Found</h2>
            <p class="text-gray-600 mb-8">The page you're looking for doesn't exist.</p>
        </div>
        <div class="space-x-4">
            <a href="{{ route('dashboard') }}" 
               class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-home mr-2"></i>Go to Dashboard
            </a>
            <a href="javascript:history.back()" 
               class="inline-block px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>Go Back
            </a>
        </div>
    </div>
</body>
</html>
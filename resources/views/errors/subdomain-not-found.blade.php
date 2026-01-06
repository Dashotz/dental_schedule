<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subdomain Not Found</title>
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Heroicons are loaded via blade-heroicons package -->
</head>
<body class="bg-gradient-to-br from-dental-teal-lighter to-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full text-center">
        <div class="card-dental p-8 md:p-12">
            <x-dental-icon name="exclamation-triangle" class="w-24 h-24 text-red-500 mb-4" />
            <h1 class="text-3xl font-bold text-red-600 mb-4">Subdomain Not Found</h1>
            <p class="text-lg text-gray-700 mb-2">The subdomain <strong class="text-gray-900">{{ $subdomain }}</strong> does not exist.</p>
            <p class="text-gray-500">Please check the URL and try again.</p>
        </div>
    </div>
</body>
</html>

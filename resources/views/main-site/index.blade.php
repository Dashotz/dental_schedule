<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Management System - Admin Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-dental-teal-lighter via-white to-dental-teal-lighter min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <x-dental-icon name="tooth" class="w-8 h-8 text-dental-teal" />
                    <span class="ml-2 text-xl font-bold text-gray-800">Dental Admin Portal</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.login') }}" class="btn-dental">
                        Admin Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-5xl font-bold text-gray-800 mb-6">
                Dental Management System
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Comprehensive administrative platform for managing dental clinics, subdomains, subscriptions, and system operations.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('admin.login') }}" class="btn-dental text-lg px-8 py-4">
                    Access Admin Dashboard
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Administrative Features</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-dental p-6 text-center">
                    <div class="w-16 h-16 bg-dental-teal/10 rounded-full flex items-center justify-center text-dental-teal text-3xl mx-auto mb-4">
                        <x-dental-icon name="globe" class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Subdomain Management</h3>
                    <p class="text-gray-600">
                        Create, manage, and monitor dental clinic subdomains with full control over their status and configuration.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-dental p-6 text-center">
                    <div class="w-16 h-16 bg-dental-teal/10 rounded-full flex items-center justify-center text-dental-teal text-3xl mx-auto mb-4">
                        <x-dental-icon name="credit-card" class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Subscription Management</h3>
                    <p class="text-gray-600">
                        Track and manage subscriptions, billing cycles, and payment status for all dental clinic subdomains.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-dental p-6 text-center">
                    <div class="w-16 h-16 bg-dental-teal/10 rounded-full flex items-center justify-center text-dental-teal text-3xl mx-auto mb-4">
                        <x-dental-icon name="graph-up" class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Reports & Analytics</h3>
                    <p class="text-gray-600">
                        Comprehensive reports and insights on revenue, subscriptions, and system-wide analytics.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-dental-teal to-dental-teal-dark text-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">Multi-Tenant</div>
                    <div class="text-xl opacity-90">Subdomain Support</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">Secure</div>
                    <div class="text-xl opacity-90">Admin Authentication</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">Comprehensive</div>
                    <div class="text-xl opacity-90">Management Tools</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Ready to Manage Your System?</h2>
            <p class="text-lg text-gray-600 mb-8">
                Access the admin dashboard to manage all aspects of your dental management system.
            </p>
            <a href="{{ route('admin.login') }}" class="btn-dental text-lg px-8 py-4 inline-block">
                Login to Admin Dashboard
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-gray-400">
                &copy; {{ date('Y') }} Dental Management System. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>


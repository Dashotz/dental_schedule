<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Required - {{ $subdomain->name ?? 'Dental Clinic' }}</title>

    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Heroicons are loaded via blade-heroicons package -->
</head>

<body class="bg-gradient-to-br from-dental-teal-lighter to-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="card-dental text-center p-8 md:p-12">
            <div
                class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-dental-teal to-dental-teal-dark rounded-full flex items-center justify-center animate-pulse">
                <x-dental-icon name="{{ $reason === 'subscription_expired' ? 'credit-card' : 'exclamation-triangle' }}"
                    class="w-16 h-16 text-white" />
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-dental-teal-dark mb-4">
                @if($reason === 'subscription_expired')
                    Subscription Expired
                @else
                    Service Temporarily Unavailable
                @endif
            </h1>

            <p class="text-lg text-gray-600 mb-8">
                @if($reason === 'subscription_expired')
                    Your subscription has expired. Please renew to continue using our services.
                @else
                    This service is currently disabled. Please contact support for assistance.
                @endif
            </p>

            <div
                class="p-6 rounded-xl mb-6 {{ $reason === 'subscription_expired' ? 'bg-yellow-50 border-2 border-yellow-300' : 'bg-red-50 border-2 border-red-300' }}">
                <h5 class="font-semibold text-lg mb-3 flex items-center justify-center gap-2">
                    <x-dental-icon name="{{ $reason === 'subscription_expired' ? 'info-circle' : 'x-circle' }}"
                        class="w-5 h-5" />
                    {{ $subdomain->name }}
                </h5>
                <p class="text-gray-700">
                    @if($reason === 'subscription_expired')
                        Your subscription period has ended. To restore access to your dental clinic management system,
                        please renew your subscription.
                    @else
                        This subdomain has been temporarily disabled. Please contact the administrator to reactivate your
                        service.
                    @endif
                </p>
            </div>

            @if($subdomain->email || $subdomain->phone)
                <div class="bg-dental-teal-lighter rounded-xl p-6 mb-6">
                    <h5 class="font-semibold text-dental-teal-dark mb-4 flex items-center justify-center gap-2">
                        <x-dental-icon name="telephone" class="w-5 h-5" /> Contact Support
                    </h5>
                    @if($subdomain->email)
                        <p class="mb-2 text-gray-700">
                            <x-dental-icon name="envelope" class="w-5 h-5 text-dental-teal" />
                            <strong>Email:</strong>
                            <a href="mailto:{{ $subdomain->email }}"
                                class="text-dental-teal hover:text-dental-teal-dark underline">
                                {{ $subdomain->email }}
                            </a>
                        </p>
                    @endif
                    @if($subdomain->phone)
                        <p class="text-gray-700">
                            <x-dental-icon name="telephone" class="w-5 h-5 text-dental-teal" />
                            <strong>Phone:</strong>
                            <a href="tel:{{ $subdomain->phone }}"
                                class="text-dental-teal hover:text-dental-teal-dark underline">
                                {{ $subdomain->phone }}
                            </a>
                        </p>
                    @endif
                </div>
            @endif

            <!-- Admin Panel button removed as per request -->
        </div>
    </div>
</body>

</html>
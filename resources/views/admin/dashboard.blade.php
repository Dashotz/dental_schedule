@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <i class="bi bi-speedometer2"></i> Admin Dashboard
    </h2>
    <p class="text-gray-600 mt-2">Welcome back, {{ auth()->user()->name }}!</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card-dental bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Total Subdomains</h6>
                    <h3 class="text-3xl font-bold mb-0">{{ $totalSubdomains }}</h3>
                </div>
                <i class="bi bi-globe text-5xl opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Active Subdomains</h6>
                    <h3 class="text-3xl font-bold mb-0">{{ $activeSubdomains }}</h3>
                </div>
                <i class="bi bi-check-circle text-5xl opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-yellow-500 to-yellow-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Active Subscriptions</h6>
                    <h3 class="text-3xl font-bold mb-0">{{ $activeSubscriptions }}</h3>
                </div>
                <i class="bi bi-credit-card text-5xl opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-red-500 to-red-600 text-white">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="text-sm font-medium opacity-90 mb-2">Expiring Soon</h6>
                    <h3 class="text-3xl font-bold mb-0">{{ $expiringSubscriptions }}</h3>
                </div>
                <i class="bi bi-exclamation-triangle text-5xl opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="card-dental bg-gradient-to-br from-cyan-500 to-cyan-600 text-white">
        <div class="p-6">
            <h6 class="text-sm font-medium opacity-90 mb-2">Total Revenue</h6>
            <h3 class="text-3xl font-bold mb-0">${{ number_format($totalRevenue, 2) }}</h3>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-gray-600 to-gray-700 text-white">
        <div class="p-6">
            <h6 class="text-sm font-medium opacity-90 mb-2">Monthly Revenue</h6>
            <h3 class="text-3xl font-bold mb-0">${{ number_format($monthlyRevenue, 2) }}</h3>
        </div>
    </div>
</div>

<!-- Recent Subdomains -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card-dental">
        <div class="card-dental-header">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <i class="bi bi-globe"></i> Recent Subdomains
            </h5>
        </div>
        <div class="p-6">
            @if($recentSubdomains->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subdomain</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentSubdomains as $subdomain)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $subdomain->subdomain }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $subdomain->name }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded text-xs font-medium {{ $subdomain->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.subdomains.show', $subdomain) }}" class="btn-dental text-sm py-1.5 px-3">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No subdomains yet.</p>
            @endif
        </div>
    </div>

    <!-- Expiring Subscriptions -->
    <div class="card-dental">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <i class="bi bi-exclamation-triangle"></i> Subscriptions Expiring Soon
            </h5>
        </div>
        <div class="p-6">
            @if($expiringSoon->count() > 0)
                <div class="space-y-2">
                    @foreach($expiringSoon as $subdomain)
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h6 class="font-semibold text-gray-800 mb-1">{{ $subdomain->name }}</h6>
                                    <small class="text-gray-500">{{ $subdomain->subdomain }}</small>
                                </div>
                                <a href="{{ route('admin.subdomains.show', $subdomain) }}" class="btn-dental-outline text-sm py-1.5 px-3">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No subscriptions expiring soon.</p>
            @endif
        </div>
    </div>
</div>
@endsection

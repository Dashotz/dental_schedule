@extends('layouts.app')

@section('title', 'Insights')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <x-dental-icon name="pie-chart" class="w-5 h-5" /> Insights & Analytics
    </h2>
    <a href="{{ route('admin.reports.index') }}" class="btn-dental">
        <x-dental-icon name="bar-chart" class="w-5 h-5" /> View Reports
    </a>
</div>

<!-- Key Metrics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="card-dental bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="p-6">
            <h6 class="text-sm font-medium opacity-90 mb-2">Renewal Rate</h6>
            <h3 class="text-3xl font-bold mb-0">{{ number_format($renewalRate, 1) }}%</h3>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="p-6">
            <h6 class="text-sm font-medium opacity-90 mb-2">Average Subscription Value</h6>
            <h3 class="text-3xl font-bold mb-0">${{ number_format($avgSubscriptionValue, 2) }}</h3>
        </div>
    </div>
    <div class="card-dental bg-gradient-to-br from-cyan-500 to-cyan-600 text-white">
        <div class="p-6">
            <h6 class="text-sm font-medium opacity-90 mb-2">Total Subscriptions</h6>
            <h3 class="text-3xl font-bold mb-0">{{ $subscriptionStatus->sum('count') }}</h3>
        </div>
    </div>
</div>

<!-- Subscription Status Distribution -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card-dental">
        <div class="card-dental-header">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="pie-chart" class="w-5 h-5" /> Subscription Status Distribution
            </h5>
        </div>
        <div class="p-6">
            <canvas id="subscriptionStatusChart" class="max-h-[250px]"></canvas>
        </div>
    </div>

    <!-- Plan Distribution -->
    <div class="card-dental">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="diagram-3" class="w-5 h-5" /> Plan Distribution
            </h5>
        </div>
        <div class="p-6">
            <canvas id="planDistributionChart" class="max-h-[250px]"></canvas>
        </div>
    </div>
</div>

<!-- Billing Cycle & Subdomain Status -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card-dental">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="credit-card" class="w-5 h-5" /> Billing Cycle Distribution
            </h5>
        </div>
        <div class="p-6">
            <canvas id="billingCycleChart" class="max-h-[250px]"></canvas>
        </div>
    </div>

    <!-- Subdomain Status -->
    <div class="card-dental">
        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="globe" class="w-5 h-5" /> Subdomain Status
            </h5>
        </div>
        <div class="p-6">
            <canvas id="subdomainStatusChart" class="max-h-[250px]"></canvas>
        </div>
    </div>
</div>

<!-- Revenue Trends -->
<div class="card-dental mb-6">
    <div class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-6 py-4 rounded-t-2xl">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <x-dental-icon name="graph-up" class="w-5 h-5" /> Revenue Trends (Last 6 Months)
        </h5>
    </div>
    <div class="p-6">
        <canvas id="revenueTrendsChart" class="max-h-[300px]"></canvas>
    </div>
</div>

<!-- Top Revenue Generating Subdomains -->
<div class="card-dental">
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white px-6 py-4 rounded-t-2xl">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <x-dental-icon name="trophy" class="w-5 h-5" /> Top Revenue Generating Subdomains
        </h5>
    </div>
    <div class="p-6">
        @if($topRevenueSubdomains->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subdomain</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topRevenueSubdomains as $index => $subdomain)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded text-xs font-medium {{ $index < 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                        #{{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subdomain->subdomain }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subdomain->name }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-green-600">${{ number_format($subdomain->subscriptions_sum_amount ?? 0, 2) }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded text-xs font-medium {{ $subdomain->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.subdomains.edit', $subdomain) }}" class="btn-dental text-sm py-1.5 px-3">
                                        <x-dental-icon name="pencil" class="w-5 h-5" /> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No revenue data available.</p>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function initCharts() {
        if (typeof Chart === 'undefined') {
            setTimeout(initCharts, 100);
            return;
        }
        
        // Subscription Status Chart
        const subscriptionStatusCtx = document.getElementById('subscriptionStatusChart').getContext('2d');
        new Chart(subscriptionStatusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($subscriptionStatus->pluck('status')->map(fn($s) => ucfirst($s))) !!},
                datasets: [{
                    data: {!! json_encode($subscriptionStatus->pluck('count')) !!},
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(108, 117, 125, 0.8)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Plan Distribution Chart
        const planDistributionCtx = document.getElementById('planDistributionChart').getContext('2d');
        new Chart(planDistributionCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($planDistribution->pluck('plan_name')->map(fn($p) => ucfirst($p))) !!},
                datasets: [{
                    data: {!! json_encode($planDistribution->pluck('count')) !!},
                    backgroundColor: [
                        'rgba(32, 178, 170, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(111, 66, 193, 0.8)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Billing Cycle Chart
        const billingCycleCtx = document.getElementById('billingCycleChart').getContext('2d');
        new Chart(billingCycleCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($billingCycleDistribution->pluck('billing_cycle')->map(fn($b) => ucfirst($b))) !!},
                datasets: [{
                    label: 'Subscriptions',
                    data: {!! json_encode($billingCycleDistribution->pluck('count')) !!},
                    backgroundColor: 'rgba(255, 193, 7, 0.8)',
                    borderColor: 'rgb(255, 193, 7)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Subdomain Status Chart
        const subdomainStatusCtx = document.getElementById('subdomainStatusChart').getContext('2d');
        new Chart(subdomainStatusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($subdomainStatus->map(fn($s) => $s->is_active ? 'Active' : 'Inactive')) !!},
                datasets: [{
                    data: {!! json_encode($subdomainStatus->pluck('count')) !!},
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Revenue Trends Chart
        const revenueTrendsCtx = document.getElementById('revenueTrendsChart').getContext('2d');
        new Chart(revenueTrendsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(collect($revenueTrends)->pluck('month')) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode(collect($revenueTrends)->pluck('revenue')) !!},
                    borderColor: '#20b2aa',
                    backgroundColor: 'rgba(32, 178, 170, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        initCharts();
    }
</script>
@endpush
@endsection

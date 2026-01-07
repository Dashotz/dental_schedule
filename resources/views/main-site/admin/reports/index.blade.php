@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
        <x-dental-icon name="bar-chart" class="w-5 h-5" /> Reports
    </h2>
</div>

<!-- Revenue Over Time Chart -->
<div class="card-dental mb-6">
    <div class="card-dental-header">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <x-dental-icon name="graph-up" class="w-5 h-5" /> Revenue Over Time (Last 12 Months)
        </h5>
    </div>
    <div class="p-6">
        <canvas id="revenueChart" class="max-h-[300px]"></canvas>
    </div>
</div>

<!-- Monthly Revenue Breakdown -->
<div class="card-dental mb-6">
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-t-2xl">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <x-dental-icon name="calendar-month" class="w-5 h-5" /> Monthly Revenue Breakdown
        </h5>
    </div>
    <div class="p-6">
        <canvas id="monthlyRevenueChart" class="max-h-[300px]"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Subscription Growth -->
    <div class="card-dental">
        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="arrow-up-circle" class="w-5 h-5" /> Subscription Growth
            </h5>
        </div>
        <div class="p-6">
            <canvas id="subscriptionGrowthChart" class="max-h-[250px]"></canvas>
        </div>
    </div>

    <!-- Subdomain Growth -->
    <div class="card-dental">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="globe" class="w-5 h-5" /> Subdomain Growth
            </h5>
        </div>
        <div class="p-6">
            <canvas id="subdomainGrowthChart" class="max-h-[250px]"></canvas>
        </div>
    </div>
</div>

<!-- Revenue by Plan -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card-dental">
        <div class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="pie-chart" class="w-5 h-5" /> Revenue by Plan
            </h5>
        </div>
        <div class="p-6">
            <canvas id="revenueByPlanChart" class="max-h-[250px]"></canvas>
        </div>
    </div>

    <!-- Revenue by Billing Cycle -->
    <div class="card-dental">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="text-lg font-semibold flex items-center gap-2">
                <x-dental-icon name="credit-card" class="w-5 h-5" /> Revenue by Billing Cycle
            </h5>
        </div>
        <div class="p-6">
            <canvas id="revenueByBillingChart" class="max-h-[250px]"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function initCharts() {
        if (typeof Chart === 'undefined') {
            setTimeout(initCharts, 100);
            return;
        }
        
        // Revenue Over Time Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueData->pluck('month')) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($revenueData->pluck('total')) !!},
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

        // Monthly Revenue Chart
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevenueCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(collect($monthlyRevenue)->pluck('month')) !!},
                datasets: [{
                    label: 'Monthly Revenue ($)',
                    data: {!! json_encode(collect($monthlyRevenue)->pluck('revenue')) !!},
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: 'rgb(40, 167, 69)',
                    borderWidth: 1
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

        // Subscription Growth Chart
        const subscriptionGrowthCtx = document.getElementById('subscriptionGrowthChart').getContext('2d');
        new Chart(subscriptionGrowthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($subscriptionGrowth->pluck('month')) !!},
                datasets: [{
                    label: 'New Subscriptions',
                    data: {!! json_encode($subscriptionGrowth->pluck('count')) !!},
                    borderColor: 'rgb(23, 162, 184)',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
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
                        beginAtZero: true
                    }
                }
            }
        });

        // Subdomain Growth Chart
        const subdomainGrowthCtx = document.getElementById('subdomainGrowthChart').getContext('2d');
        new Chart(subdomainGrowthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($subdomainGrowth->pluck('month')) !!},
                datasets: [{
                    label: 'New Subdomains',
                    data: {!! json_encode($subdomainGrowth->pluck('count')) !!},
                    borderColor: 'rgb(255, 193, 7)',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
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
                        beginAtZero: true
                    }
                }
            }
        });

        // Revenue by Plan Chart
        const revenueByPlanCtx = document.getElementById('revenueByPlanChart').getContext('2d');
        new Chart(revenueByPlanCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($revenueByPlan->pluck('plan_name')->map(fn($p) => ucfirst($p))) !!},
                datasets: [{
                    data: {!! json_encode($revenueByPlan->pluck('total')) !!},
                    backgroundColor: [
                        'rgba(32, 178, 170, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(111, 66, 193, 0.8)'
                    ],
                    borderColor: [
                        '#20b2aa',
                        'rgb(40, 167, 69)',
                        'rgb(111, 66, 193)'
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
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': $' + context.parsed.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Revenue by Billing Cycle Chart
        const revenueByBillingCtx = document.getElementById('revenueByBillingChart').getContext('2d');
        new Chart(revenueByBillingCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($revenueByBillingCycle->pluck('billing_cycle')->map(fn($b) => ucfirst($b))) !!},
                datasets: [{
                    data: {!! json_encode($revenueByBillingCycle->pluck('total')) !!},
                    backgroundColor: [
                        'rgba(108, 117, 125, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgb(108, 117, 125)',
                        'rgb(255, 193, 7)',
                        'rgb(220, 53, 69)'
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
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': $' + context.parsed.toLocaleString();
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

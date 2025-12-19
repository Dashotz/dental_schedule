@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-bar-chart"></i> Reports</h2>
    <a href="{{ route('admin.insights.index') }}" class="btn btn-primary">
        <i class="bi bi-pie-chart"></i> View Insights
    </a>
</div>

<!-- Revenue Over Time Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Revenue Over Time (Last 12 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="60"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Revenue Breakdown -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-month"></i> Monthly Revenue Breakdown</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyRevenueChart" height="60"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Subscription Growth -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-arrow-up-circle"></i> Subscription Growth</h5>
            </div>
            <div class="card-body">
                <canvas id="subscriptionGrowthChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Subdomain Growth -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-globe"></i> Subdomain Growth</h5>
            </div>
            <div class="card-body">
                <canvas id="subdomainGrowthChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Revenue by Plan -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Revenue by Plan</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueByPlanChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Revenue by Billing Cycle -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-credit-card"></i> Revenue by Billing Cycle</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueByBillingChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-body canvas {
        max-height: 300px !important;
    }
    
    #revenueChart,
    #monthlyRevenueChart,
    #revenueTrendsChart {
        max-height: 200px !important;
    }
    
    #subscriptionGrowthChart,
    #subdomainGrowthChart,
    #revenueByPlanChart,
    #revenueByBillingChart,
    #subscriptionStatusChart,
    #planDistributionChart,
    #billingCycleChart,
    #subdomainStatusChart {
        max-height: 250px !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Wait for Chart.js to load
    function initCharts() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            setTimeout(initCharts, 100);
            return;
        }
        
        $(document).ready(function() {
        // Revenue Over Time Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueData->pluck('month')) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($revenueData->pluck('total')) !!},
                    borderColor: 'rgb(13, 110, 253)',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
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
                        'rgba(13, 110, 253, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(111, 66, 193, 0.8)'
                    ],
                    borderColor: [
                        'rgb(13, 110, 253)',
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
        });
    }
    
    // Initialize charts when page loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        initCharts();
    }
</script>
@endpush
@endsection


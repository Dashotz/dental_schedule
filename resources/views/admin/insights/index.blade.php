@extends('layouts.app')

@section('title', 'Insights')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-pie-chart"></i> Insights & Analytics</h2>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-primary">
        <i class="bi bi-bar-chart"></i> View Reports
    </a>
</div>

<!-- Key Metrics -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Renewal Rate</h6>
                <h3 class="mb-0">{{ number_format($renewalRate, 1) }}%</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Average Subscription Value</h6>
                <h3 class="mb-0">${{ number_format($avgSubscriptionValue, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Total Subscriptions</h6>
                <h3 class="mb-0">{{ $subscriptionStatus->sum('count') }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Subscription Status Distribution -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Subscription Status Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="subscriptionStatusChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Plan Distribution -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Plan Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="planDistributionChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Billing Cycle & Subdomain Status -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-credit-card"></i> Billing Cycle Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="billingCycleChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Subdomain Status -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-globe"></i> Subdomain Status</h5>
            </div>
            <div class="card-body">
                <canvas id="subdomainStatusChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Trends -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Revenue Trends (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueTrendsChart" height="60"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Revenue Generating Subdomains -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Top Revenue Generating Subdomains</h5>
            </div>
            <div class="card-body">
                @if($topRevenueSubdomains->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Subdomain</th>
                                    <th>Name</th>
                                    <th>Total Revenue</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topRevenueSubdomains as $index => $subdomain)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                                #{{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td>{{ $subdomain->subdomain }}</td>
                                        <td>{{ $subdomain->name }}</td>
                                        <td><strong>${{ number_format($subdomain->subscriptions_sum_amount ?? 0, 2) }}</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $subdomain->is_active ? 'success' : 'danger' }}">
                                                {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.subdomains.show', $subdomain) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No revenue data available.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-body canvas {
        max-height: 300px !important;
    }
    
    #revenueTrendsChart {
        max-height: 200px !important;
    }
    
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
                        'rgba(13, 110, 253, 0.8)',
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
                    borderColor: 'rgb(108, 117, 125)',
                    backgroundColor: 'rgba(108, 117, 125, 0.1)',
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


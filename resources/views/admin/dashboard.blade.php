@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
        <p class="text-muted">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Subdomains</h6>
                        <h3 class="mb-0">{{ $totalSubdomains }}</h3>
                    </div>
                    <i class="bi bi-globe fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Active Subdomains</h6>
                        <h3 class="mb-0">{{ $activeSubdomains }}</h3>
                    </div>
                    <i class="bi bi-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Active Subscriptions</h6>
                        <h3 class="mb-0">{{ $activeSubscriptions }}</h3>
                    </div>
                    <i class="bi bi-credit-card fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-white bg-danger h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Expiring Soon</h6>
                        <h3 class="mb-0">{{ $expiringSubscriptions }}</h3>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Cards -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Total Revenue</h6>
                <h3 class="mb-0">${{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card bg-secondary text-white h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Monthly Revenue</h6>
                <h3 class="mb-0">${{ number_format($monthlyRevenue, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Recent Subdomains -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-globe"></i> Recent Subdomains</h5>
            </div>
            <div class="card-body">
                @if($recentSubdomains->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Subdomain</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSubdomains as $subdomain)
                                    <tr>
                                        <td>{{ $subdomain->subdomain }}</td>
                                        <td>{{ $subdomain->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $subdomain->is_active ? 'success' : 'danger' }}">
                                                {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.subdomains.show', $subdomain) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No subdomains yet.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Expiring Subscriptions -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Subscriptions Expiring Soon</h5>
            </div>
            <div class="card-body">
                @if($expiringSoon->count() > 0)
                    <div class="list-group">
                        @foreach($expiringSoon as $subdomain)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $subdomain->name }}</h6>
                                        <small class="text-muted">{{ $subdomain->subdomain }}</small>
                                    </div>
                                    <a href="{{ route('admin.subdomains.show', $subdomain) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No subscriptions expiring soon.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


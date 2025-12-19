@extends('layouts.app')

@section('title', 'Subscriptions Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-credit-card text-primary"></i> Subscriptions Management</h2>
        <p class="text-muted mb-0">Manage all subscription plans and billing cycles</p>
    </div>
    <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary btn-lg shadow-sm">
        <i class="bi bi-plus-circle"></i> Add Subscription
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0"><i class="bi bi-list-ul text-primary"></i> All Subscriptions</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4"><i class="bi bi-globe me-2"></i>Subdomain</th>
                        <th><i class="bi bi-star me-2"></i>Plan</th>
                        <th class="text-end"><i class="bi bi-currency-dollar me-2"></i>Amount</th>
                        <th class="text-center"><i class="bi bi-calendar-range me-2"></i>Billing Cycle</th>
                        <th><i class="bi bi-calendar-check me-2"></i>Start Date</th>
                        <th><i class="bi bi-calendar-x me-2"></i>End Date</th>
                        <th class="text-center"><i class="bi bi-info-circle me-2"></i>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-globe text-primary"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.subdomains.show', $subscription->subdomain) }}" 
                                           class="text-decoration-none fw-semibold text-dark">
                                            {{ $subscription->subdomain->name }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $subscription->subdomain->subdomain }}.yourdomain.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info px-3 py-2">
                                    <i class="bi bi-star-fill me-1"></i>
                                    {{ ucfirst($subscription->plan_name) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <strong class="text-success">${{ number_format($subscription->amount, 2) }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary px-3 py-2">
                                    {{ ucfirst($subscription->billing_cycle) }}
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-calendar-check text-muted me-1"></i>
                                {{ $subscription->start_date->format('M d, Y') }}
                            </td>
                            <td>
                                <i class="bi bi-calendar-x text-muted me-1"></i>
                                <span class="{{ $subscription->end_date->isPast() ? 'text-danger' : '' }}">
                                    {{ $subscription->end_date->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'expired' ? 'danger' : 'warning') }} px-3 py-2">
                                    <i class="bi bi-{{ $subscription->status === 'active' ? 'check-circle' : ($subscription->status === 'expired' ? 'x-circle' : 'clock') }}"></i>
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary update-status-btn" 
                                            data-id="{{ $subscription->id }}"
                                            data-status="{{ $subscription->status }}"
                                            title="Update Status">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @if($subscription->status === 'active' && $subscription->end_date <= now()->addDays(7))
                                        <button class="btn btn-sm btn-outline-warning send-reminder" 
                                                data-id="{{ $subscription->id }}"
                                                title="Send Payment Reminder">
                                            <i class="bi bi-envelope"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                    <p class="text-muted mt-3 mb-0">No subscriptions found.</p>
                                    <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary mt-3">
                                        <i class="bi bi-plus-circle"></i> Create Your First Subscription
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-center">
                    {{ $subscriptions->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Update Status
        $('.update-status-btn').on('click', function() {
            const subscriptionId = $(this).data('id');
            const currentStatus = $(this).data('status');
            
            Swal.fire({
                title: 'Update Subscription Status',
                html: `
                    <div class="mb-3">
                        <label class="form-label">Select New Status:</label>
                        <select class="form-select" id="statusSelect">
                            <option value="active" ${currentStatus === 'active' ? 'selected' : ''}>Active</option>
                            <option value="expired" ${currentStatus === 'expired' ? 'selected' : ''}>Expired</option>
                            <option value="cancelled" ${currentStatus === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>Pending</option>
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Update Status',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                preConfirm: () => {
                    return document.getElementById('statusSelect').value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const newStatus = result.value;
                    
                    $.ajax({
                        url: `/admin/subscriptions/${subscriptionId}/update-status`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: newStatus
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to update status.'
                            });
                        }
                    });
                }
            });
        });

        // Send Payment Reminder
        $('.send-reminder').on('click', function() {
            const subscriptionId = $(this).data('id');
            
            Swal.fire({
                title: 'Send Payment Reminder?',
                text: 'A payment reminder will be sent to the subdomain owner.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, send reminder',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/subscriptions/${subscriptionId}/send-reminder`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message || 'Payment reminder sent successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to send reminder.'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush


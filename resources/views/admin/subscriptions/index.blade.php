@extends('layouts.app')

@section('title', 'Subscriptions Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/skeleton-loading.css') }}">
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-credit-card text-primary"></i> Subscriptions Management</h2>
        <p class="text-muted mb-0">Manage all subscription plans and billing cycles</p>
    </div>
    <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#createSubscriptionModal" id="openCreateSubscriptionModal">
        <i class="bi bi-plus-circle"></i> Add Subscription
    </button>
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
                                        <button type="button" 
                                                class="btn btn-link text-decoration-none fw-semibold text-dark p-0 view-subdomain-from-subscription" 
                                                data-subdomain-id="{{ $subscription->subdomain->id }}">
                                            {{ $subscription->subdomain->name }}
                                        </button>
                                        <br>
                                        <small class="text-muted">{{ $subscription->subdomain->subdomain }}.helioho.st</small>
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
                                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createSubscriptionModal" id="openCreateSubscriptionModalEmpty">
                                        <i class="bi bi-plus-circle"></i> Create Your First Subscription
                                    </button>
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

<!-- Modals -->
@include('admin.subscriptions.modals.create')
<div id="viewSubdomainModalContainer"></div>
@endsection

@push('scripts')
<script src="{{ asset('js/skeleton-loading.js') }}"></script>
<script>
    $(document).ready(function() {
        // Load create subscription modal content
        $('#openCreateSubscriptionModal, #openCreateSubscriptionModalEmpty').on('click', function() {
            if ($('#createSubscriptionModal .modal-body form').length === 0 || $('#modal_subdomain_id').length === 0) {
                $.ajax({
                    url: '{{ route("admin.subscriptions.create") }}',
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        $('#createSubscriptionModal .modal-body').html($(response.html).find('.modal-body').html());
                        $('#createSubscriptionModal .modal-footer').html($(response.html).find('.modal-footer').html());
                    }
                });
            }
        });

        // Auto-calculate end date for subscription
        $(document).on('change', '#modal_billing_cycle, #modal_start_date', function() {
            calculateSubscriptionEndDate();
        });

        function calculateSubscriptionEndDate() {
            const billingCycle = $('#modal_billing_cycle').val();
            const startDate = $('#modal_start_date').val();
            
            if (!startDate || !billingCycle) {
                $('#modal_end_date').val('');
                return;
            }
            
            const start = new Date(startDate);
            let endDate = new Date(start);
            
            switch(billingCycle) {
                case 'monthly':
                    endDate.setMonth(endDate.getMonth() + 1);
                    endDate.setDate(endDate.getDate() - 1);
                    break;
                case 'quarterly':
                    endDate.setMonth(endDate.getMonth() + 3);
                    endDate.setDate(endDate.getDate() - 1);
                    break;
                case 'yearly':
                    endDate.setFullYear(endDate.getFullYear() + 1);
                    endDate.setDate(endDate.getDate() - 1);
                    break;
            }
            
            $('#modal_end_date').val(endDate.toISOString().split('T')[0]);
        }

        // Create subscription form submission
        $(document).on('submit', '#createSubscriptionForm', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Creating...');

            $.ajax({
                url: '{{ route("admin.subscriptions.store") }}',
                method: 'POST',
                data: form.serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#createSubscriptionModal').modal('hide');
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
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            const input = form.find(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            $(`#${key}_error`).text(errors[key][0]).removeClass('d-none');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to create subscription.'
                        });
                    }
                    submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-2"></i>Create Subscription');
                }
            });
        });

        // Reset form on modal close
        $('#createSubscriptionModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0]?.reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').addClass('d-none');
            $('#modal_end_date').val('');
        });

        // View subdomain from subscriptions page
        $(document).on('click', '.view-subdomain-from-subscription', function() {
            const subdomainId = $(this).data('subdomain-id');
            
            // Create a temporary container if it doesn't exist
            if ($('#viewSubdomainModalContainer').length === 0) {
                $('body').append('<div id="viewSubdomainModalContainer"></div>');
            }
            
            // Show modal immediately with skeleton loading
            const loadingHtml = SkeletonLoading.getViewSubdomainModal();
            $('#viewSubdomainModalContainer').html(loadingHtml);
            $('#viewSubdomainModal').modal('show');
            
            // Load content asynchronously
            $.ajax({
                url: `/admin/subdomains/${subdomainId}`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#viewSubdomainModalContainer').html(response.html);
                    // Re-initialize modal if needed
                    if (!$('#viewSubdomainModal').hasClass('show')) {
                        $('#viewSubdomainModal').modal('show');
                    }
                },
                error: function(xhr) {
                    $('#viewSubdomainModalContainer').html(`
                        <div class="modal fade" id="viewSubdomainModal" tabindex="-1" aria-labelledby="viewSubdomainModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Error</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-danger">Failed to load subdomain details. Please try again.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    $('#viewSubdomainModal').modal('show');
                }
            });
        });

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


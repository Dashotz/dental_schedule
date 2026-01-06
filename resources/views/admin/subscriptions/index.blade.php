@extends('layouts.app')

@section('title', 'Subscriptions Management')

{{-- Skeleton loading styles migrated to Tailwind in app.css --}}

@section('content')
<div class="flex justify-between items-center mb-6 flex-wrap gap-4">
    <div>
        <h2 class="text-3xl font-bold text-gray-800 mb-1 flex items-center gap-2">
            <i class="bi bi-credit-card text-dental-teal"></i> Subscriptions Management
        </h2>
        <p class="text-gray-600">Manage all subscription plans and billing cycles</p>
    </div>
    <button type="button" class="btn-dental shadow-lg" onclick="openModal('createSubscriptionModal')" id="openCreateSubscriptionModal">
        <i class="bi bi-plus-circle"></i> Add Subscription
    </button>
</div>

<div class="card-dental">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <i class="bi bi-list-ul text-dental-teal"></i> All Subscriptions
        </h5>
    </div>
    <div class="p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-globe mr-2"></i>Subdomain
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-star mr-2"></i>Plan
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-currency-dollar mr-2"></i>Amount
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-calendar-range mr-2"></i>Billing Cycle
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-calendar-check mr-2"></i>Start Date
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-calendar-x mr-2"></i>End Date
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-info-circle mr-2"></i>Status
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-dental-teal/10 rounded-full p-2 mr-3">
                                        <i class="bi bi-globe text-dental-teal"></i>
                                    </div>
                                    <div>
                                        <button type="button" 
                                                class="text-left font-semibold text-gray-900 hover:text-dental-teal transition-colors view-subdomain-from-subscription" 
                                                data-subdomain-id="{{ $subscription->subdomain->id }}">
                                            {{ $subscription->subdomain->name }}
                                        </button>
                                        <br>
                                        <small class="text-gray-500">{{ $subscription->subdomain->subdomain }}.helioho.st</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-3 py-1.5 rounded text-xs font-medium bg-cyan-100 text-cyan-800">
                                    <i class="bi bi-star-fill mr-1"></i>
                                    {{ ucfirst($subscription->plan_name) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                <strong class="text-green-600">${{ number_format($subscription->amount, 2) }}</strong>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($subscription->billing_cycle) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="bi bi-calendar-check text-gray-400 mr-1"></i>
                                {{ $subscription->start_date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <i class="bi bi-calendar-x text-gray-400 mr-1"></i>
                                <span class="{{ $subscription->end_date->isPast() ? 'text-red-600' : 'text-gray-500' }}">
                                    {{ $subscription->end_date->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'expired' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800'
                                    ];
                                    $statusColor = $statusColors[$subscription->status] ?? 'bg-gray-100 text-gray-800';
                                    $statusIcons = [
                                        'active' => 'check-circle',
                                        'expired' => 'x-circle',
                                        'cancelled' => 'x-circle',
                                        'pending' => 'clock'
                                    ];
                                    $statusIcon = $statusIcons[$subscription->status] ?? 'circle';
                                @endphp
                                <span class="px-3 py-1.5 rounded text-xs font-medium {{ $statusColor }}">
                                    <i class="bi bi-{{ $statusIcon }}"></i>
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                <button class="btn-dental text-sm py-1.5 px-3 update-status-btn" 
                                        data-id="{{ $subscription->id }}"
                                        data-status="{{ $subscription->status }}"
                                        title="Update Status">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($subscription->status === 'active' && $subscription->end_date <= now()->addDays(7))
                                    <button class="btn-dental-outline text-sm py-1.5 px-3 border-yellow-500 text-yellow-600 hover:bg-yellow-50 send-reminder" 
                                            data-id="{{ $subscription->id }}"
                                            title="Send Payment Reminder">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="py-4">
                                    <i class="bi bi-inbox text-6xl text-gray-300"></i>
                                    <p class="text-gray-500 mt-3 mb-0">No subscriptions found.</p>
                                    <button type="button" class="btn-dental mt-3" onclick="openModal('createSubscriptionModal')" id="openCreateSubscriptionModalEmpty">
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
            <div class="px-6 py-4 border-t border-gray-200 bg-white">
                <div class="flex justify-center">
                    {{ $subscriptions->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modals -->
@include('admin.subscriptions.partials.create-modal')
<div id="viewSubdomainModalContainer"></div>
@endsection

@push('scripts')
<script src="{{ asset('js/skeleton-loading.js') }}"></script>
<script>
    $(document).ready(function() {
        // Load create subscription modal content
        $('#openCreateSubscriptionModal, #openCreateSubscriptionModalEmpty').on('click', function() {
            if ($('#createSubscriptionModal .p-6 form').length === 0 || $('#modal_subdomain_id').length === 0) {
                $.ajax({
                    url: '{{ route("admin.subscriptions.create") }}',
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        $('#createSubscriptionModal .p-6').html($(response.html).find('.p-6, .modal-body').html());
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
            submitBtn.prop('disabled', true).html('<span class="inline-block animate-spin mr-2">⟳</span>Creating...');

            $.ajax({
                url: '{{ route("admin.subscriptions.store") }}',
                method: 'POST',
                data: form.serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    closeModal('createSubscriptionModal');
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
                            input.addClass('border-red-500');
                            $(`#${key}_error`).text(errors[key][0]).removeClass('hidden');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to create subscription.'
                        });
                    }
                    submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle mr-2"></i>Create Subscription');
                }
            });
        });

        // Reset form on modal close
        $('#createSubscriptionModal').on('hidden', function() {
            $(this).find('form')[0]?.reset();
            $(this).find('.border-red-500').removeClass('border-red-500');
            $(this).find('.text-red-500').addClass('hidden');
            $('#modal_end_date').val('');
        });

        // View subdomain from subscriptions page
        $(document).on('click', '.view-subdomain-from-subscription', function() {
            const subdomainId = $(this).data('subdomain-id');
            
            if ($('#viewSubdomainModalContainer').length === 0) {
                $('body').append('<div id="viewSubdomainModalContainer"></div>');
            }
            
            const loadingHtml = SkeletonLoading.getViewSubdomainModal();
            $('#viewSubdomainModalContainer').html(loadingHtml);
            openModal('viewSubdomainModal');
            
            $.ajax({
                url: `/admin/subdomains/${subdomainId}`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#viewSubdomainModalContainer').html(response.html);
                },
                error: function(xhr) {
                    $('#viewSubdomainModalContainer').html(`
                        <x-modal id="viewSubdomainModal" title="Error">
                            <p class="text-red-600">Failed to load subdomain details. Please try again.</p>
                        </x-modal>
                    `);
                    openModal('viewSubdomainModal');
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select New Status:</label>
                        <select class="input-dental" id="statusSelect">
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
                confirmButtonColor: '#20b2aa',
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

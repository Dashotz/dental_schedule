@extends('layouts.app')

@section('title', 'Subscriptions Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-credit-card"></i> Subscriptions Management</h2>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Subdomain</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Billing Cycle</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr>
                            <td>
                                <a href="{{ route('admin.subdomains.show', $subscription->subdomain) }}">
                                    {{ $subscription->subdomain->name }}
                                </a>
                            </td>
                            <td>{{ ucfirst($subscription->plan_name) }}</td>
                            <td>${{ number_format($subscription->amount, 2) }}</td>
                            <td>{{ ucfirst($subscription->billing_cycle) }}</td>
                            <td>{{ $subscription->start_date->format('M d, Y') }}</td>
                            <td>{{ $subscription->end_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'expired' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td>
                                @if($subscription->end_date <= now()->addDays(7) && $subscription->status === 'active')
                                    <button class="btn btn-sm btn-warning send-reminder" data-id="{{ $subscription->id }}">
                                        <i class="bi bi-envelope"></i> Remind
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No subscriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.send-reminder').on('click', function() {
            const subscriptionId = $(this).data('id');
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
                        text: response.message || 'Payment reminder sent successfully.'
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
        });
    });
</script>
@endpush


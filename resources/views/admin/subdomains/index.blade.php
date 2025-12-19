@extends('layouts.app')

@section('title', 'Subdomains Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-globe text-primary"></i> Subdomains Management</h2>
        <p class="text-muted mb-0">Manage all dental clinic subdomains and their settings</p>
    </div>
    <a href="{{ route('admin.subdomains.create') }}" class="btn btn-primary btn-lg shadow-sm">
        <i class="bi bi-plus-circle"></i> Add New Subdomain
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0"><i class="bi bi-list-ul text-primary"></i> All Subdomains</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4"><i class="bi bi-globe me-2"></i>Subdomain</th>
                        <th><i class="bi bi-building me-2"></i>Name</th>
                        <th><i class="bi bi-envelope me-2"></i>Email</th>
                        <th class="text-center"><i class="bi bi-toggle-on me-2"></i>Status</th>
                        <th class="text-center"><i class="bi bi-credit-card me-2"></i>Subscription</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subdomains as $subdomain)
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-globe text-primary"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $subdomain->subdomain }}</strong>
                                        <small class="text-muted">.yourdomain.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $subdomain->name }}</span>
                            </td>
                            <td>
                                @if($subdomain->email)
                                    <i class="bi bi-envelope text-muted me-1"></i>
                                    <span>{{ $subdomain->email }}</span>
                                @else
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-status" 
                                               type="checkbox" 
                                               data-id="{{ $subdomain->id }}"
                                               {{ $subdomain->is_active ? 'checked' : '' }}
                                               style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                    </div>
                                    <span class="badge bg-{{ $subdomain->is_active ? 'success' : 'danger' }} px-3 py-2">
                                        <i class="bi bi-{{ $subdomain->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                        {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $activeSub = $subdomain->subscriptions
                                        ->where('status', 'active')
                                        ->where('end_date', '>=', now())
                                        ->sortByDesc('end_date')
                                        ->first();
                                @endphp
                                @if($activeSub)
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        {{ ucfirst($activeSub->plan_name) }}
                                    </span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>
                                        No Subscription
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.subdomains.show', $subdomain) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="View Details">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                    <p class="text-muted mt-3 mb-0">No subdomains found.</p>
                                    <a href="{{ route('admin.subdomains.create') }}" class="btn btn-primary mt-3">
                                        <i class="bi bi-plus-circle"></i> Create Your First Subdomain
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subdomains->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-center">
                    {{ $subdomains->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.toggle-status').on('change', function() {
            const toggle = $(this);
            const subdomainId = toggle.data('id');
            const isChecked = toggle.is(':checked');
            const newStatus = isChecked ? 'activate' : 'deactivate';
            const subdomainName = toggle.closest('tr').find('td:eq(1)').text().trim();

            // Show confirmation dialog
            Swal.fire({
                title: `Are you sure?`,
                html: `Do you want to <strong>${newStatus}</strong> the subdomain <strong>"${subdomainName}"</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: isChecked ? '#198754' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${newStatus} it!`,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, proceed with the toggle
                    $.ajax({
                        url: `/admin/subdomains/${subdomainId}/toggle-status`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function() {
                            // Revert the toggle on error
                            toggle.prop('checked', !isChecked);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to update status. Please try again.'
                            });
                        }
                    });
                } else {
                    // User cancelled, revert the toggle
                    toggle.prop('checked', !isChecked);
                }
            });
        });
    });
</script>
@endpush


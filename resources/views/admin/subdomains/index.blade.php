@extends('layouts.app')

@section('title', 'Subdomains Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-globe"></i> Subdomains Management</h2>
    <a href="{{ route('admin.subdomains.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Subdomain
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Subdomain</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Subscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subdomains as $subdomain)
                        <tr>
                            <td><strong>{{ $subdomain->subdomain }}</strong></td>
                            <td>{{ $subdomain->name }}</td>
                            <td>{{ $subdomain->email ?? 'N/A' }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" 
                                           type="checkbox" 
                                           data-id="{{ $subdomain->id }}"
                                           {{ $subdomain->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        <span class="badge bg-{{ $subdomain->is_active ? 'success' : 'danger' }}">
                                            {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                @php
                                    $activeSub = $subdomain->subscriptions
                                        ->where('status', 'active')
                                        ->where('end_date', '>=', now())
                                        ->sortByDesc('end_date')
                                        ->first();
                                @endphp
                                @if($activeSub)
                                    <span class="badge bg-success">{{ ucfirst($activeSub->plan_name) }}</span>
                                @else
                                    <span class="badge bg-danger">No Subscription</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.subdomains.show', $subdomain) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.subdomains.edit', $subdomain) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No subdomains found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $subdomains->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.toggle-status').on('change', function() {
            const subdomainId = $(this).data('id');
            const isChecked = $(this).is(':checked');

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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update status.'
                    });
                }
            });
        });
    });
</script>
@endpush


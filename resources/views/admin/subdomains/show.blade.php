@extends('layouts.app')

@section('title', 'Subdomain Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-globe"></i> {{ $subdomain->name }}</h2>
    <div>
        <a href="{{ route('admin.subdomains.edit', $subdomain) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.subdomains.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Subdomain Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Subdomain:</th>
                        <td><strong>{{ $subdomain->subdomain }}</strong></td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td>{{ $subdomain->name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $subdomain->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td>{{ $subdomain->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td>{{ $subdomain->address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
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
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-primary w-100 mb-2" id="generateLinkBtn">
                    <i class="bi bi-link-45deg"></i> Generate Registration Link
                </button>
            </div>
        </div>

        <!-- Registration Links -->
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-link-45deg"></i> Registration Links</h6>
            </div>
            <div class="card-body">
                @php
                    $activeLink = $subdomain->registrationLinks->where('is_active', true)->first();
                @endphp
                @if($activeLink)
                    <div class="list-group mb-3">
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control form-control-sm registration-link-input" 
                                               value="{{ $activeLink->link }}" readonly>
                                        <button class="btn btn-outline-secondary btn-sm copy-link-btn" 
                                                type="button" 
                                                data-link="{{ $activeLink->link }}">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i> 
                                        Uses: {{ $activeLink->used_count }}/∞ (Unlimited)
                                        @if($activeLink->expires_at)
                                            | Expires: {{ $activeLink->expires_at->format('M d, Y') }} (when subscription ends)
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-muted mb-0">No active registration link. Generate one to get started.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle status
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
                }
            });
        });

        // Generate registration link
        $('#generateLinkBtn').on('click', function() {
            Swal.fire({
                title: 'Generate Registration Link',
                html: `
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        The link will have unlimited uses and will expire when the subscription ends.
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Generate',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/subdomains/{{ $subdomain->id }}/generate-link`,
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
                            }).then(() => {
                                // Reload page to show the new link in the list
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to generate link.'
                            });
                        }
                    });
                }
            });
        });

        // Copy link (for existing links)
        $(document).on('click', '.copy-link-btn', function() {
            const link = $(this).data('link');
            navigator.clipboard.writeText(link).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Registration link copied to clipboard.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }).catch(function() {
                // Fallback for older browsers
                const input = $(this).closest('.input-group').find('input');
                input.select();
                document.execCommand('copy');
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Registration link copied to clipboard.',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
    });
</script>
@endpush


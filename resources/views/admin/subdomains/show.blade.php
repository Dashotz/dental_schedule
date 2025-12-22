@extends('layouts.app')

@section('title', 'Subdomain Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="bi bi-globe text-primary"></i> {{ $subdomain->name }}
        </h2>
        <p class="text-muted mb-0">
            <i class="bi bi-link-45deg"></i> {{ $subdomain->subdomain }}.helioho.st
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.subdomains.edit', $subdomain) }}" class="btn btn-warning shadow-sm">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.subdomains.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Subdomain Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-globe me-1"></i>Subdomain
                            </label>
                            <strong class="d-block">{{ $subdomain->subdomain }}.helioho.st</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-building me-1"></i>Clinic Name
                            </label>
                            <strong class="d-block">{{ $subdomain->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-envelope me-1"></i>Email
                            </label>
                            <span class="d-block">
                                @if($subdomain->email)
                                    {{ $subdomain->email }}
                                @else
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-telephone me-1"></i>Phone
                            </label>
                            <span class="d-block">
                                @if($subdomain->phone)
                                    {{ $subdomain->phone }}
                                @else
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    @if($subdomain->address)
                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-geo-alt me-1"></i>Address
                            </label>
                            <span class="d-block">{{ $subdomain->address }}</span>
                        </div>
                    </div>
                    @endif
                    @if($subdomain->description)
                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-file-text me-1"></i>Description
                            </label>
                            <span class="d-block">{{ $subdomain->description }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-2 d-block">
                                <i class="bi bi-toggle-on me-1"></i>Status
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" 
                                           type="checkbox" 
                                           data-id="{{ $subdomain->id }}"
                                           {{ $subdomain->is_active ? 'checked' : '' }}
                                           style="width: 3.5rem; height: 1.75rem; cursor: pointer;">
                                </div>
                                <span class="badge bg-{{ $subdomain->is_active ? 'success' : 'danger' }} px-3 py-2">
                                    <i class="bi bi-{{ $subdomain->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                    {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-lightning-charge"></i> Quick Actions</h6>
            </div>
            <div class="card-body">
                <button class="btn btn-primary w-100" id="generateLinkBtn">
                    <i class="bi bi-link-45deg"></i> Generate Registration Link
                </button>
            </div>
        </div>

        <!-- Registration Links -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-link-45deg"></i> Registration Links</h6>
            </div>
            <div class="card-body">
                @php
                    $activeLink = $subdomain->registrationLinks->where('is_active', true)->first();
                @endphp
                @if($activeLink)
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-2">Active Registration Link</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control registration-link-input" 
                                   value="{{ $activeLink->link }}" 
                                   readonly
                                   id="registrationLinkInput">
                            <button class="btn btn-outline-primary copy-link-btn" 
                                    type="button" 
                                    data-link="{{ $activeLink->link }}"
                                    title="Copy to clipboard">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block">
                                <i class="bi bi-info-circle"></i> 
                                Uses: <strong>{{ $activeLink->used_count }}</strong>/∞ (Unlimited)
                            </small>
                            @if($activeLink->expires_at)
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-calendar-x"></i> 
                                    Expires: <strong>{{ $activeLink->expires_at->format('M d, Y') }}</strong>
                                    <br><span class="text-muted small">(when subscription ends)</span>
                                </small>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-link-45deg display-6 text-muted"></i>
                        <p class="text-muted mb-0 mt-2">No active registration link</p>
                        <small class="text-muted">Generate one to get started</small>
                    </div>
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
            const toggle = $(this);
            const subdomainId = toggle.data('id');
            const isChecked = toggle.is(':checked');
            const newStatus = isChecked ? 'activate' : 'deactivate';
            const subdomainName = '{{ $subdomain->name }}';

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
                            }).then(() => {
                                // Reload page to update badge
                                window.location.reload();
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


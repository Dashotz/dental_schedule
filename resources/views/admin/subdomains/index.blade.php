@extends('layouts.app')

@section('title', 'Subdomains Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/skeleton-loading.css') }}">
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-globe text-primary"></i> Subdomains Management</h2>
        <p class="text-muted mb-0">Manage all dental clinic subdomains and their settings</p>
    </div>
    <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#createSubdomainModal" id="openCreateModal">
        <i class="bi bi-plus-circle"></i> Add New Subdomain
    </button>
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
                                        <small class="text-muted">.helioho.st</small>
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
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary view-subdomain-btn" 
                                        data-subdomain-id="{{ $subdomain->id }}"
                                        title="View Details">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-warning edit-subdomain-btn ms-1" 
                                        data-subdomain-id="{{ $subdomain->id }}"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger delete-subdomain-btn ms-1" 
                                        data-subdomain-id="{{ $subdomain->id }}"
                                        data-subdomain-name="{{ $subdomain->name }}"
                                        title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                    <p class="text-muted mt-3 mb-0">No subdomains found.</p>
                                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createSubdomainModal" id="openCreateModalEmpty">
                                        <i class="bi bi-plus-circle"></i> Create Your First Subdomain
                                    </button>
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

<!-- Modals -->
@include('admin.subdomains.modals.create')
@include('admin.subdomains.modals.edit')
<div id="viewSubdomainModalContainer"></div>
@endsection

@push('scripts')
<script src="{{ asset('js/skeleton-loading.js') }}"></script>
<script>
    $(document).ready(function() {
        // Load create modal content
        $('#openCreateModal, #openCreateModalEmpty').on('click', function(e) {
            e.preventDefault();
            const modal = $('#createSubdomainModal');
            
            // Show modal immediately if form not loaded
            if ($('#createSubdomainModal .modal-body form').length === 0) {
                // Show skeleton loading
                modal.find('.modal-body').html(SkeletonLoading.getCreateSubdomainModalBody());
                modal.modal('show');
                
                // Load content asynchronously
                $.ajax({
                    url: '{{ route("admin.subdomains.create") }}',
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        modal.find('.modal-body').html($(response.html).find('.modal-body').html());
                    },
                    error: function(xhr) {
                        modal.find('.modal-body').html(`
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i> Failed to load form. Please try again.
                            </div>
                        `);
                    }
                });
            } else {
                modal.modal('show');
            }
        });

        // Create subdomain form submission
        $(document).on('submit', '#createSubdomainForm', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Creating...');

            $.ajax({
                url: '{{ route("admin.subdomains.store") }}',
                method: 'POST',
                data: form.serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#createSubdomainModal').modal('hide');
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
                            text: xhr.responseJSON?.message || 'Failed to create subdomain.'
                        });
                    }
                    submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-2"></i>Create Subdomain');
                }
            });
        });

        // View subdomain modal
        $(document).on('click', '.view-subdomain-btn', function() {
            const subdomainId = $(this).data('subdomain-id');
            const btn = $(this);
            
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

        // Edit subdomain modal
        $(document).on('click', '.edit-subdomain-btn', function() {
            const subdomainId = $(this).data('subdomain-id');
            window.currentEditSubdomainId = subdomainId;
            $('#edit_subdomain_id').val(subdomainId);
            
            // Show modal immediately with skeleton loading
            $('#editSubdomainModalBody').html(SkeletonLoading.getEditSubdomainModalBody());
            $('#editSubdomainModal').modal('show');
            
            // Load content asynchronously
            $.ajax({
                url: `/admin/subdomains/${subdomainId}/edit`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#editSubdomainModalBody').html(response.html);
                },
                error: function(xhr) {
                    $('#editSubdomainModalBody').html(`
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> Failed to load edit form. Please try again.
                        </div>
                    `);
                }
            });
        });

        // Edit subdomain form submission
        $(document).on('submit', '#editSubdomainForm', function(e) {
            e.preventDefault();
            const form = $(this);
            const subdomainId = window.currentEditSubdomainId;
            const submitBtn = form.find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');

            $.ajax({
                url: `/admin/subdomains/${subdomainId}`,
                method: 'POST',
                data: form.serialize() + '&_method=PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#editSubdomainModal').modal('hide');
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
                            const errorDiv = input.closest('.mb-3, .mb-4').find('.invalid-feedback');
                            if (errorDiv.length) {
                                errorDiv.text(errors[key][0]).removeClass('d-none');
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to update subdomain.'
                        });
                    }
                    submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle me-2"></i>Update Subdomain');
                }
            });
        });

        // Toggle status
        $('.toggle-status').on('change', function() {
            const toggle = $(this);
            const subdomainId = toggle.data('id');
            const isChecked = toggle.is(':checked');
            const newStatus = isChecked ? 'activate' : 'deactivate';
            const subdomainName = toggle.closest('tr').find('td:eq(1)').text().trim();

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
                            toggle.prop('checked', !isChecked);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to update status. Please try again.'
                            });
                        }
                    });
                } else {
                    toggle.prop('checked', !isChecked);
                }
            });
        });

        // Handle actions inside view modal
        $(document).on('click', '#generateLinkBtnModal', function() {
            const subdomainId = $(this).data('subdomain-id');
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
                        url: `/admin/subdomains/${subdomainId}/generate-link`,
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
                                $('#viewSubdomainModal').modal('hide');
                                // Reload the view modal
                                $.ajax({
                                    url: `/admin/subdomains/${subdomainId}`,
                                    method: 'GET',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    success: function(response) {
                                        $('#viewSubdomainModalContainer').html(response.html);
                                        $('#viewSubdomainModal').modal('show');
                                    }
                                });
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

        $(document).on('click', '#editSubdomainBtnModal', function() {
            const subdomainId = $(this).data('subdomain-id');
            $('#viewSubdomainModal').modal('hide');
            window.currentEditSubdomainId = subdomainId;
            $('#edit_subdomain_id').val(subdomainId);
            
            // Show modal immediately with skeleton loading
            $('#editSubdomainModalBody').html(SkeletonLoading.getEditSubdomainModalBody());
            $('#editSubdomainModal').modal('show');
            
            // Load content asynchronously
            $.ajax({
                url: `/admin/subdomains/${subdomainId}/edit`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#editSubdomainModalBody').html(response.html);
                },
                error: function(xhr) {
                    $('#editSubdomainModalBody').html(`
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> Failed to load edit form. Please try again.
                        </div>
                    `);
                }
            });
        });

        // Copy link functionality in view modal
        $(document).on('click', '#viewSubdomainModal .copy-link-btn', function() {
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
                const input = $('#registrationLinkInputModal');
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

        // Toggle status in view modal
        $(document).on('change', '#viewSubdomainModal .toggle-status', function() {
            const toggle = $(this);
            const subdomainId = toggle.data('id');
            const isChecked = toggle.is(':checked');
            const newStatus = isChecked ? 'activate' : 'deactivate';
            const subdomainName = $('#viewSubdomainModalLabel').text().trim();

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
                                $('#viewSubdomainModal').modal('hide');
                                window.location.reload();
                            });
                        },
                        error: function() {
                            toggle.prop('checked', !isChecked);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to update status. Please try again.'
                            });
                        }
                    });
                } else {
                    toggle.prop('checked', !isChecked);
                }
            });
        });

        // Delete subdomain
        $(document).on('click', '.delete-subdomain-btn', function() {
            const subdomainId = $(this).data('subdomain-id');
            const subdomainName = $(this).data('subdomain-name');
            const deleteBtn = $(this);

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete "${subdomainName}". This action cannot be undone and will also delete all related registration links and subscriptions.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                    $.ajax({
                        url: `/admin/subdomains/${subdomainId}`,
                        method: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
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
                                text: xhr.responseJSON?.message || 'Failed to delete subdomain.'
                            });
                            deleteBtn.prop('disabled', false).html('<i class="bi bi-trash"></i>');
                        }
                    });
                }
            });
        });

        // Reset form on modal close
        $('#createSubdomainModal, #editSubdomainModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0]?.reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').addClass('d-none');
        });
    });
</script>
@endpush


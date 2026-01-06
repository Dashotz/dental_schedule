@extends('layouts.app')

@section('title', 'Subdomains Management')

{{-- Skeleton loading styles migrated to Tailwind in app.css --}}

@section('content')
<div class="flex justify-between items-center mb-6 flex-wrap gap-4">
    <div>
        <h2 class="text-3xl font-bold text-gray-800 mb-1 flex items-center gap-2">
            <i class="bi bi-globe text-dental-teal"></i> Subdomains Management
        </h2>
        <p class="text-gray-600">Manage all dental clinic subdomains and their settings</p>
    </div>
    <button type="button" class="btn-dental shadow-lg" onclick="openModal('createSubdomainModal')" id="openCreateModal">
        <i class="bi bi-plus-circle"></i> Add New Subdomain
    </button>
</div>

<div class="card-dental">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <i class="bi bi-list-ul text-dental-teal"></i> All Subdomains
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
                            <i class="bi bi-building mr-2"></i>Name
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-envelope mr-2"></i>Email
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-toggle-on mr-2"></i>Status
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="bi bi-credit-card mr-2"></i>Subscription
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subdomains as $subdomain)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-dental-teal/10 rounded-full p-2 mr-3">
                                        <i class="bi bi-globe text-dental-teal"></i>
                                    </div>
                                    <div>
                                        <strong class="block text-gray-900">{{ $subdomain->subdomain }}</strong>
                                        <small class="text-gray-500">.helioho.st</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="font-medium text-gray-900">{{ $subdomain->name }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($subdomain->email)
                                    <i class="bi bi-envelope text-gray-400 mr-1"></i>
                                    {{ $subdomain->email }}
                                @else
                                    <span class="text-gray-400 italic">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-status" 
                                               type="checkbox" 
                                               data-id="{{ $subdomain->id }}"
                                               {{ $subdomain->is_active ? 'checked' : '' }}
                                               style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                    </div>
                                    <span class="px-3 py-1.5 rounded text-xs font-medium {{ $subdomain->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="bi bi-{{ $subdomain->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                        {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                @php
                                    $activeSub = $subdomain->subscriptions
                                        ->where('status', 'active')
                                        ->where('end_date', '>=', now())
                                        ->sortByDesc('end_date')
                                        ->first();
                                @endphp
                                @if($activeSub)
                                    <span class="px-3 py-1.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="bi bi-check-circle mr-1"></i>
                                        {{ ucfirst($activeSub->plan_name) }}
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <i class="bi bi-x-circle mr-1"></i>
                                        No Subscription
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                <button type="button" 
                                        class="btn-dental text-sm py-1.5 px-3 view-subdomain-btn" 
                                        data-subdomain-id="{{ $subdomain->id }}"
                                        title="View Details">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <button type="button" 
                                        class="btn-dental-outline text-sm py-1.5 px-3 border-yellow-500 text-yellow-600 hover:bg-yellow-50 edit-subdomain-btn" 
                                        data-subdomain-id="{{ $subdomain->id }}"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" 
                                        class="btn-dental-outline text-sm py-1.5 px-3 border-red-500 text-red-600 hover:bg-red-50 delete-subdomain-btn" 
                                        data-subdomain-id="{{ $subdomain->id }}"
                                        data-subdomain-name="{{ $subdomain->name }}"
                                        title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="py-4">
                                    <i class="bi bi-inbox text-6xl text-gray-300"></i>
                                    <p class="text-gray-500 mt-3 mb-0">No subdomains found.</p>
                                    <button type="button" class="btn-dental mt-3" onclick="openModal('createSubdomainModal')" id="openCreateModalEmpty">
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
            <div class="px-6 py-4 border-t border-gray-200 bg-white">
                <div class="flex justify-center">
                    {{ $subdomains->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modals -->
@include('admin.subdomains.partials.create-modal')
@include('admin.subdomains.partials.edit-modal')
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
            
            if ($('#createSubdomainModal .p-6 form').length === 0) {
                modal.find('.p-6').html(SkeletonLoading.getCreateSubdomainModalBody());
                openModal('createSubdomainModal');
                
                $.ajax({
                    url: '{{ route("admin.subdomains.create") }}',
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        modal.find('.p-6').html($(response.html).find('.p-6, .modal-body').html());
                    },
                    error: function(xhr) {
                        modal.find('.p-6').html(`
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                                <i class="bi bi-exclamation-triangle"></i> Failed to load form. Please try again.
                            </div>
                        `);
                    }
                });
            } else {
                openModal('createSubdomainModal');
            }
        });

        // Create subdomain form submission
        $(document).on('submit', '#createSubdomainForm', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<span class="inline-block animate-spin mr-2">⟳</span>Creating...');

            $.ajax({
                url: '{{ route("admin.subdomains.store") }}',
                method: 'POST',
                data: form.serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    closeModal('createSubdomainModal');
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
                            text: xhr.responseJSON?.message || 'Failed to create subdomain.'
                        });
                    }
                    submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle mr-2"></i>Create Subdomain');
                }
            });
        });

        // View subdomain modal
        $(document).on('click', '.view-subdomain-btn', function() {
            const subdomainId = $(this).data('subdomain-id');
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

        // Edit subdomain modal
        $(document).on('click', '.edit-subdomain-btn', function() {
            const subdomainId = $(this).data('subdomain-id');
            window.currentEditSubdomainId = subdomainId;
            $('#edit_subdomain_id').val(subdomainId);
            
            $('#editSubdomainModalBody').html(SkeletonLoading.getEditSubdomainModalBody());
            openModal('editSubdomainModal');
            
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
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
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
            submitBtn.prop('disabled', true).html('<span class="inline-block animate-spin mr-2">⟳</span>Updating...');

            $.ajax({
                url: `/admin/subdomains/${subdomainId}`,
                method: 'POST',
                data: form.serialize() + '&_method=PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    closeModal('editSubdomainModal');
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
                            const errorDiv = input.closest('.mb-3, .mb-4').find('.text-red-500');
                            if (errorDiv.length) {
                                errorDiv.text(errors[key][0]).removeClass('hidden');
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to update subdomain.'
                        });
                    }
                    submitBtn.prop('disabled', false).html('<i class="bi bi-check-circle mr-2"></i>Update Subdomain');
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
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
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
                                closeModal('viewSubdomainModal');
                                $.ajax({
                                    url: `/admin/subdomains/${subdomainId}`,
                                    method: 'GET',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    success: function(response) {
                                        $('#viewSubdomainModalContainer').html(response.html);
                                        openModal('viewSubdomainModal');
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
            closeModal('viewSubdomainModal');
            window.currentEditSubdomainId = subdomainId;
            $('#edit_subdomain_id').val(subdomainId);
            
            $('#editSubdomainModalBody').html(SkeletonLoading.getEditSubdomainModalBody());
            openModal('editSubdomainModal');
            
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
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
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
                                closeModal('viewSubdomainModal');
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
                    deleteBtn.prop('disabled', true).html('<span class="inline-block animate-spin">⟳</span>');

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
        $('#createSubdomainModal, #editSubdomainModal').on('hidden', function() {
            $(this).find('form')[0]?.reset();
            $(this).find('.border-red-500').removeClass('border-red-500');
            $(this).find('.text-red-500').addClass('hidden');
        });
    });
</script>
@endpush

@extends('layouts.app')

@section('title', 'Subdomains Management')

{{-- Skeleton loading styles migrated to Tailwind in app.css --}}

@section('content')
<div class="flex justify-between items-center mb-6 flex-wrap gap-4">
    <div>
        <h2 class="text-3xl font-bold text-gray-800 mb-1 flex items-center gap-2">
            <x-dental-icon name="globe" class="w-8 h-8 text-dental-teal" /> Subdomains Management
        </h2>
        <p class="text-gray-600">Manage all dental clinic subdomains and their settings</p>
    </div>
    <button type="button" class="inline-flex items-center gap-2 btn-dental shadow-lg" onclick="openModal('createSubdomainModal')" id="openCreateModal">
        <x-dental-icon name="plus-circle" class="w-5 h-5" /> Add New Subdomain
    </button>
</div>

<div class="card-dental">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold flex items-center gap-2">
            <x-dental-icon name="list-ul" class="w-5 h-5 text-dental-teal" /> All Subdomains
        </h5>
    </div>
    <div class="p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <span class="flex items-center"><x-dental-icon name="globe" class="w-4 h-4 mr-2" />Subdomain</span>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <span class="flex items-center"><x-dental-icon name="building" class="w-4 h-4 mr-2" />Name</span>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <span class="flex items-center"><x-dental-icon name="envelope" class="w-4 h-4 mr-2" />Email</span>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <span class="flex items-center justify-center"><x-dental-icon name="toggle-on" class="w-4 h-4 mr-2" />Status</span>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <span class="flex items-center justify-center"><x-dental-icon name="credit-card" class="w-4 h-4 mr-2" />Subscription</span>
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
                                        <x-dental-icon name="globe" class="w-5 h-5 text-dental-teal" />
                                    </div>
                                    <div>
                                        <strong class="block text-gray-900">{{ $subdomain->subdomain }}</strong>
                                        @if($subdomain->port)
                                            <small class="text-gray-500">:{{ $subdomain->port }}</small>
                                        @else
                                            <small class="text-gray-400">No port</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="font-medium text-gray-900">{{ $subdomain->name }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($subdomain->email)
                                    <x-dental-icon name="envelope" class="w-4 h-4 text-gray-400 mr-1 inline" />
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
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium {{ $subdomain->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <x-dental-icon name="{{ $subdomain->is_active ? 'check-circle' : 'x-circle' }}" class="w-3 h-3" />
                                        {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                @php
                                    // Use already eager-loaded subscriptions (already filtered in controller)
                                    $activeSub = $subdomain->subscriptions->first();
                                @endphp
                                @if($activeSub)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <x-dental-icon name="check-circle" class="w-3 h-3" />
                                        {{ ucfirst($activeSub->plan_name) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <x-dental-icon name="x-circle" class="w-3 h-3" />
                                        No Subscription
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm">
                                <button type="button" 
                                        class="inline-flex items-center gap-2 btn-dental text-sm py-1.5 px-3 view-subdomain-btn" 
                                        data-subdomain-id="{{ $subdomain->id }}"
                                        title="View Details">
                                    <x-dental-icon name="eye" class="w-4 h-4" /> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="py-4">
                                    <x-dental-icon name="inbox" class="w-16 h-16 text-gray-300 mx-auto" />
                                    <p class="text-gray-500 mt-3 mb-0">No subdomains found.</p>
                                    <button type="button" class="inline-flex items-center gap-2 btn-dental mt-3" onclick="openModal('createSubdomainModal')" id="openCreateModalEmpty">
                                        <x-dental-icon name="plus-circle" class="w-5 h-5" /> Create Your First Subdomain
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
@include('main-site.admin.subdomains.partials.create-modal')
@include('main-site.admin.subdomains.partials.edit-modal')
<div id="viewSubdomainModalContainer"></div>
@endsection

@push('scripts')
<script src="{{ asset('js/skeleton-loading.js') }}"></script>
<script>
    // Ensure jQuery is loaded before executing scripts
    (function() {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded');
            return;
        }
        
        var $ = jQuery;
        
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
                            <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                                <x-dental-icon name="exclamation-triangle" class="w-5 h-5" /> Failed to load form. Please try again.
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
                    submitBtn.prop('disabled', false).html('<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Create Subdomain');
                }
            });
        });

        // View subdomain modal
        $(document).on('click', '.view-subdomain-btn', function(e) {
            e.preventDefault();
            const subdomainId = $(this).data('subdomain-id');
            // Store subdomain ID for back button
            window.lastViewedSubdomainId = subdomainId;
            
            // Check if container exists
            if ($('#viewSubdomainModalContainer').length === 0) {
                $('body').append('<div id="viewSubdomainModalContainer"></div>');
            }
            
            const loadingHtml = SkeletonLoading.getViewSubdomainModal();
            $('#viewSubdomainModalContainer').html(loadingHtml);
            
            // Open modal
            const modal = document.getElementById('viewSubdomainModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            
            $.ajax({
                url: `/admin/subdomains/${subdomainId}`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#viewSubdomainModalContainer').html(response.html);
                    // Make sure modal is visible after content loads
                    const modal = document.getElementById('viewSubdomainModal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                },
                error: function(xhr) {
                    $('#viewSubdomainModalContainer').html(`
                        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" id="viewSubdomainModal">
                            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto modal-scroll">
                                <div class="card-dental-header flex justify-between items-center">
                                    <h5 class="text-lg font-semibold">Error</h5>
                                    <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors" onclick="closeModal('viewSubdomainModal')">
                                        &times;
                                    </button>
                                </div>
                                <div class="p-6">
                                    <p class="text-red-600">Failed to load subdomain details. Please try again.</p>
                                </div>
                                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                                    <button onclick="closeModal('viewSubdomainModal')" class="btn-dental-outline">Close</button>
                                </div>
                            </div>
                        </div>
                    `);
                    openModal('viewSubdomainModal');
                }
            });
        });

        // Handle actions inside view modal
        $(document).on('click', '#generateLinkBtnModal', function() {
            const subdomainId = $(this).data('subdomain-id');
            Swal.fire({
                title: 'Generate Registration Link',
                html: `
                    <div class="flex items-start gap-2 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>The link will have unlimited uses and will expire when the subscription ends.</span>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Generate',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn-dental',
                    cancelButton: 'btn-dental-outline'
                },
                buttonsStyling: false
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
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: 'btn-dental'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                // Reload the modal content
                                $.ajax({
                                    url: `/admin/subdomains/${subdomainId}`,
                                    method: 'GET',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    success: function(response) {
                                        $('#viewSubdomainModalContainer').html(response.html);
                                        const modal = document.getElementById('viewSubdomainModal');
                                        if (modal) {
                                            modal.classList.remove('hidden');
                                            modal.classList.add('flex');
                                        }
                                    }
                                });
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to generate link.',
                                customClass: {
                                    confirmButton: 'btn-dental'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '#editSubdomainBtnModal', function() {
            const subdomainId = $(this).data('subdomain-id');
            // Store subdomain ID for back button
            window.lastViewedSubdomainId = subdomainId;
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
                        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Failed to load edit form. Please try again.
                        </div>
                    `);
                }
            });
        });

        // Back button from edit modal to view modal
        $(document).on('click', '#backToViewSubdomainBtn', function() {
            const subdomainId = window.lastViewedSubdomainId;
            if (!subdomainId) {
                closeModal('editSubdomainModal');
                return;
            }
            
            closeModal('editSubdomainModal');
            
            // Check if container exists
            if ($('#viewSubdomainModalContainer').length === 0) {
                $('body').append('<div id="viewSubdomainModalContainer"></div>');
            }
            
            const loadingHtml = SkeletonLoading.getViewSubdomainModal();
            $('#viewSubdomainModalContainer').html(loadingHtml);
            
            // Open modal
            const modal = document.getElementById('viewSubdomainModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            
            $.ajax({
                url: `/admin/subdomains/${subdomainId}`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#viewSubdomainModalContainer').html(response.html);
                    const modal = document.getElementById('viewSubdomainModal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                },
                error: function(xhr) {
                    $('#viewSubdomainModalContainer').html(`
                        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" id="viewSubdomainModal">
                            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto modal-scroll">
                                <div class="card-dental-header flex justify-between items-center">
                                    <h5 class="text-lg font-semibold">Error</h5>
                                    <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors" onclick="closeModal('viewSubdomainModal')">
                                        &times;
                                    </button>
                                </div>
                                <div class="p-6">
                                    <p class="text-red-600">Failed to load subdomain details. Please try again.</p>
                                </div>
                                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                                    <button onclick="closeModal('viewSubdomainModal')" class="btn-dental-outline">Close</button>
                                </div>
                            </div>
                        </div>
                    `);
                    openModal('viewSubdomainModal');
                }
            });
        });

        // Delete subdomain from modal
        $(document).on('click', '#deleteSubdomainBtnModal', function() {
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
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn-dental',
                    cancelButton: 'btn-dental-outline'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteBtn.prop('disabled', true).html('<span class="inline-block animate-spin mr-2">⟳</span>Deleting...');

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
                            closeModal('viewSubdomainModal');
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: 'btn-dental'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to delete subdomain.',
                                customClass: {
                                    confirmButton: 'btn-dental'
                                },
                                buttonsStyling: false
                            });
                            deleteBtn.prop('disabled', false).html('<svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Delete Subdomain');
                        }
                    });
                }
            });
        });

        // Copy link functionality in view modal
        $(document).on('click', '#viewSubdomainModal .copy-link-btn', function() {
            const link = $(this).data('link');
            const button = $(this);
            navigator.clipboard.writeText(link).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Registration link copied to clipboard.',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: {
                        confirmButton: 'btn-dental'
                    },
                    buttonsStyling: false
                });
            }).catch(function() {
                const input = button.closest('.flex').find('input');
                input[0].select();
                document.execCommand('copy');
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Registration link copied to clipboard.',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: {
                        confirmButton: 'btn-dental'
                    },
                    buttonsStyling: false
                });
            });
        });

        // Toggle status in view modal
        $(document).on('change', '#viewSubdomainModal .toggle-status', function() {
            const toggle = $(this);
            const subdomainId = toggle.data('id');
            const isChecked = toggle.is(':checked');
            const newStatus = isChecked ? 'activate' : 'deactivate';
            const subdomainName = $('#viewSubdomainModal').find('.card-dental-header h5').text().trim();

            Swal.fire({
                title: `Are you sure?`,
                html: `Do you want to <strong>${newStatus}</strong> the subdomain <strong>"${subdomainName}"</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: isChecked ? '#198754' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${newStatus} it!`,
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn-dental',
                    cancelButton: 'btn-dental-outline'
                },
                buttonsStyling: false
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
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: 'btn-dental'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                // Reload the modal content
                                $.ajax({
                                    url: `/admin/subdomains/${subdomainId}`,
                                    method: 'GET',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    success: function(response) {
                                        $('#viewSubdomainModalContainer').html(response.html);
                                        const modal = document.getElementById('viewSubdomainModal');
                                        if (modal) {
                                            modal.classList.remove('hidden');
                                            modal.classList.add('flex');
                                        }
                                    }
                                });
                            });
                        },
                        error: function() {
                            toggle.prop('checked', !isChecked);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to update status. Please try again.',
                                customClass: {
                                    confirmButton: 'btn-dental'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                } else {
                    toggle.prop('checked', !isChecked);
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
                        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Failed to load edit form. Please try again.
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
                    submitBtn.prop('disabled', false).html('<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Update Subdomain');
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
                            deleteBtn.prop('disabled', false).html('<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>');
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
    })();
</script>
@endpush

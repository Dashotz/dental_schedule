<?php $__env->startSection('title', 'Subdomains Management'); ?>

<?php $__env->startSection('content'); ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $subdomains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdomain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-globe text-primary"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block"><?php echo e($subdomain->subdomain); ?></strong>
                                        <small class="text-muted">.yourdomain.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium"><?php echo e($subdomain->name); ?></span>
                            </td>
                            <td>
                                <?php if($subdomain->email): ?>
                                    <i class="bi bi-envelope text-muted me-1"></i>
                                    <span><?php echo e($subdomain->email); ?></span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-status" 
                                               type="checkbox" 
                                               data-id="<?php echo e($subdomain->id); ?>"
                                               <?php echo e($subdomain->is_active ? 'checked' : ''); ?>

                                               style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                    </div>
                                    <span class="badge bg-<?php echo e($subdomain->is_active ? 'success' : 'danger'); ?> px-3 py-2">
                                        <i class="bi bi-<?php echo e($subdomain->is_active ? 'check-circle' : 'x-circle'); ?>"></i>
                                        <?php echo e($subdomain->is_active ? 'Active' : 'Inactive'); ?>

                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php
                                    $activeSub = $subdomain->subscriptions
                                        ->where('status', 'active')
                                        ->where('end_date', '>=', now())
                                        ->sortByDesc('end_date')
                                        ->first();
                                ?>
                                <?php if($activeSub): ?>
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        <?php echo e(ucfirst($activeSub->plan_name)); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>
                                        No Subscription
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary view-subdomain-btn" 
                                        data-subdomain-id="<?php echo e($subdomain->id); ?>"
                                        title="View Details">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-warning edit-subdomain-btn ms-1" 
                                        data-subdomain-id="<?php echo e($subdomain->id); ?>"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
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
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($subdomains->hasPages()): ?>
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-center">
                    <?php echo e($subdomains->links()); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modals -->
<?php echo $__env->make('admin.subdomains.modals.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('admin.subdomains.modals.edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div id="viewSubdomainModalContainer"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Load create modal content
        $('#openCreateModal, #openCreateModalEmpty').on('click', function() {
            if ($('#createSubdomainModal .modal-body form').length === 0) {
                $.ajax({
                    url: '<?php echo e(route("admin.subdomains.create")); ?>',
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        $('#createSubdomainModal .modal-body').html($(response.html).find('.modal-body').html());
                    }
                });
            }
        });

        // Create subdomain form submission
        $(document).on('submit', '#createSubdomainForm', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Creating...');

            $.ajax({
                url: '<?php echo e(route("admin.subdomains.store")); ?>',
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

        // Edit subdomain modal
        $(document).on('click', '.edit-subdomain-btn', function() {
            const subdomainId = $(this).data('subdomain-id');
            window.currentEditSubdomainId = subdomainId;
            $('#edit_subdomain_id').val(subdomainId);
            
            $.ajax({
                url: `/admin/subdomains/${subdomainId}/edit`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#editSubdomainModalBody').html(response.html);
                    $('#editSubdomainModal').modal('show');
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
                            _token: '<?php echo e(csrf_token()); ?>'
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
                            _token: '<?php echo e(csrf_token()); ?>'
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
            
            $.ajax({
                url: `/admin/subdomains/${subdomainId}/edit`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#editSubdomainModalBody').html(response.html);
                    $('#editSubdomainModal').modal('show');
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
                            _token: '<?php echo e(csrf_token()); ?>'
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

        // Reset form on modal close
        $('#createSubdomainModal, #editSubdomainModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0]?.reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').addClass('d-none');
        });
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH H:\Github\dental_schedule\resources\views/admin/subdomains/index.blade.php ENDPATH**/ ?>
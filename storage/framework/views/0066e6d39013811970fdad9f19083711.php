<div class="modal fade" id="createSubscriptionModal" tabindex="-1" aria-labelledby="createSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createSubscriptionModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Create New Subscription
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createSubscriptionForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="modal_subdomain_id" class="form-label fw-semibold mb-2">
                            <i class="bi bi-globe text-primary me-1"></i>Subdomain <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="modal_subdomain_id" name="subdomain_id" required>
                            <option value="">Select Subdomain...</option>
                            <?php $__currentLoopData = $subdomains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subdomain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subdomain->id); ?>">
                                    <?php echo e($subdomain->name); ?> (<?php echo e($subdomain->subdomain); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="invalid-feedback d-none" id="subdomain_id_error"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modal_plan_name" class="form-label fw-semibold mb-2">
                                <i class="bi bi-star text-primary me-1"></i>Plan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="modal_plan_name" name="plan_name" required>
                                <option value="">Select Plan...</option>
                                <option value="basic">Basic</option>
                                <option value="premium">Premium</option>
                                <option value="enterprise">Enterprise</option>
                            </select>
                            <div class="invalid-feedback d-none" id="plan_name_error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_amount" class="form-label fw-semibold mb-2">
                                <i class="bi bi-currency-dollar text-primary me-1"></i>Amount <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" 
                                       id="modal_amount" name="amount" required>
                            </div>
                            <div class="invalid-feedback d-none" id="amount_error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modal_billing_cycle" class="form-label fw-semibold mb-2">
                                <i class="bi bi-calendar-range text-primary me-1"></i>Billing Cycle <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="modal_billing_cycle" name="billing_cycle" required>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                            <div class="invalid-feedback d-none" id="billing_cycle_error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_start_date" class="form-label fw-semibold mb-2">
                                <i class="bi bi-calendar-check text-primary me-1"></i>Start Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" 
                                   id="modal_start_date" name="start_date" 
                                   value="<?php echo e(date('Y-m-d')); ?>" required>
                            <div class="invalid-feedback d-none" id="start_date_error"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_end_date" class="form-label fw-semibold mb-2">
                            <i class="bi bi-calendar-x text-primary me-1"></i>End Date (Auto-calculated)
                        </label>
                        <input type="date" class="form-control" 
                               id="modal_end_date" name="end_date" readonly 
                               style="background-color: #e9ecef; cursor: not-allowed;">
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> 
                            End date is automatically calculated based on billing cycle and start date.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Create Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php /**PATH H:\Github\dental_schedule\resources\views/admin/subscriptions/modals/create.blade.php ENDPATH**/ ?>
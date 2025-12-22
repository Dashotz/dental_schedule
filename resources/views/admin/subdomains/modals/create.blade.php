<div class="modal fade" id="createSubdomainModal" tabindex="-1" aria-labelledby="createSubdomainModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createSubdomainModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Create New Subdomain
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createSubdomainForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modal_subdomain" class="form-label fw-semibold mb-2">
                                <i class="bi bi-globe text-primary me-1"></i>Subdomain <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="modal_subdomain" 
                                       name="subdomain" 
                                       placeholder="clinic-name" 
                                       required>
                                <span class="input-group-text bg-light border-start-0">.helioho.st</span>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Only lowercase letters, numbers, and hyphens allowed.
                            </small>
                            <div class="invalid-feedback d-none" id="subdomain_error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="modal_name" class="form-label fw-semibold mb-2">
                                <i class="bi bi-building text-primary me-1"></i>Clinic Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="modal_name" 
                                   name="name" 
                                   placeholder="Enter clinic name"
                                   required>
                            <div class="invalid-feedback d-none" id="name_error"></div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="modal_description" class="form-label fw-semibold mb-2">
                                <i class="bi bi-file-text text-primary me-1"></i>Description
                            </label>
                            <textarea class="form-control" 
                                      id="modal_description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Enter a brief description about the clinic"></textarea>
                            <div class="invalid-feedback d-none" id="description_error"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="modal_email" class="form-label fw-semibold mb-2">
                                <i class="bi bi-envelope text-primary me-1"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="modal_email" 
                                   name="email" 
                                   placeholder="clinic@example.com">
                            <div class="invalid-feedback d-none" id="email_error"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="modal_phone" class="form-label fw-semibold mb-2">
                                <i class="bi bi-telephone text-primary me-1"></i>Phone
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="modal_phone" 
                                   name="phone" 
                                   placeholder="+1 (555) 123-4567">
                            <div class="invalid-feedback d-none" id="phone_error"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="modal_address" class="form-label fw-semibold mb-2">
                                <i class="bi bi-geo-alt text-primary me-1"></i>Address
                            </label>
                            <textarea class="form-control" 
                                      id="modal_address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="Enter clinic address"></textarea>
                            <div class="invalid-feedback d-none" id="address_error"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create Subdomain
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


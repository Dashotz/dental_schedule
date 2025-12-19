<div class="row">
    <div class="col-md-6 mb-3">
        <label for="edit_modal_subdomain" class="form-label fw-semibold mb-2">
            <i class="bi bi-globe text-primary me-1"></i>Subdomain <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <input type="text" 
                   class="form-control form-control-lg" 
                   id="edit_modal_subdomain" 
                   name="subdomain" 
                   value="{{ $subdomain->subdomain }}" 
                   placeholder="clinic-name" 
                   required>
            <span class="input-group-text bg-light border-start-0">.yourdomain.com</span>
        </div>
        <small class="text-muted d-block mt-2">
            <i class="bi bi-info-circle"></i> Only lowercase letters, numbers, and hyphens allowed.
        </small>
        <div class="invalid-feedback d-none" id="subdomain_error"></div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="edit_modal_name" class="form-label fw-semibold mb-2">
            <i class="bi bi-building text-primary me-1"></i>Clinic Name <span class="text-danger">*</span>
        </label>
        <input type="text" 
               class="form-control form-control-lg" 
               id="edit_modal_name" 
               name="name" 
               value="{{ $subdomain->name }}" 
               placeholder="Enter clinic name"
               required>
        <div class="invalid-feedback d-none" id="name_error"></div>
    </div>

    <div class="col-12 mb-3">
        <label for="edit_modal_description" class="form-label fw-semibold mb-2">
            <i class="bi bi-file-text text-primary me-1"></i>Description
        </label>
        <textarea class="form-control" 
                  id="edit_modal_description" 
                  name="description" 
                  rows="3"
                  placeholder="Enter a brief description about the clinic">{{ $subdomain->description }}</textarea>
        <div class="invalid-feedback d-none" id="description_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label for="edit_modal_email" class="form-label fw-semibold mb-2">
            <i class="bi bi-envelope text-primary me-1"></i>Email
        </label>
        <input type="email" 
               class="form-control" 
               id="edit_modal_email" 
               name="email" 
               value="{{ $subdomain->email }}"
               placeholder="clinic@example.com">
        <div class="invalid-feedback d-none" id="email_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label for="edit_modal_phone" class="form-label fw-semibold mb-2">
            <i class="bi bi-telephone text-primary me-1"></i>Phone
        </label>
        <input type="text" 
               class="form-control" 
               id="edit_modal_phone" 
               name="phone" 
               value="{{ $subdomain->phone }}"
               placeholder="+1 (555) 123-4567">
        <div class="invalid-feedback d-none" id="phone_error"></div>
    </div>

    <div class="col-md-4 mb-3">
        <label for="edit_modal_address" class="form-label fw-semibold mb-2">
            <i class="bi bi-geo-alt text-primary me-1"></i>Address
        </label>
        <textarea class="form-control" 
                  id="edit_modal_address" 
                  name="address" 
                  rows="3"
                  placeholder="Enter clinic address">{{ $subdomain->address }}</textarea>
        <div class="invalid-feedback d-none" id="address_error"></div>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label for="edit_modal_subdomain" class="form-label flex items-center gap-2">
            <i class="bi bi-globe text-dental-teal"></i>Subdomain <span class="text-red-500">*</span>
        </label>
        <div class="flex">
            <input type="text" 
                   class="input-dental rounded-r-none" 
                   id="edit_modal_subdomain" 
                   name="subdomain" 
                   value="{{ $subdomain->subdomain }}" 
                   placeholder="clinic-name" 
                   required>
            <span class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg px-4 py-2.5 text-gray-600 flex items-center">.helioho.st</span>
        </div>
        <small class="text-gray-500 block mt-2">
            <i class="bi bi-info-circle"></i> Only lowercase letters, numbers, and hyphens allowed.
        </small>
        <div class="text-red-500 text-sm mt-1 hidden" id="subdomain_error"></div>
    </div>

    <div class="md:col-span-2">
        <label for="edit_modal_name" class="form-label flex items-center gap-2">
            <i class="bi bi-building text-dental-teal"></i>Clinic Name <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               class="input-dental" 
               id="edit_modal_name" 
               name="name" 
               value="{{ $subdomain->name }}" 
               placeholder="Enter clinic name"
               required>
        <div class="text-red-500 text-sm mt-1 hidden" id="name_error"></div>
    </div>

    <div class="md:col-span-2">
        <label for="edit_modal_description" class="form-label flex items-center gap-2">
            <i class="bi bi-file-text text-dental-teal"></i>Description
        </label>
        <textarea class="input-dental" 
                  id="edit_modal_description" 
                  name="description" 
                  rows="3"
                  placeholder="Enter a brief description about the clinic">{{ $subdomain->description }}</textarea>
        <div class="text-red-500 text-sm mt-1 hidden" id="description_error"></div>
    </div>

    <div>
        <label for="edit_modal_email" class="form-label flex items-center gap-2">
            <i class="bi bi-envelope text-dental-teal"></i>Email
        </label>
        <input type="email" 
               class="input-dental" 
               id="edit_modal_email" 
               name="email" 
               value="{{ $subdomain->email }}"
               placeholder="clinic@example.com">
        <div class="text-red-500 text-sm mt-1 hidden" id="email_error"></div>
    </div>

    <div>
        <label for="edit_modal_phone" class="form-label flex items-center gap-2">
            <i class="bi bi-telephone text-dental-teal"></i>Phone
        </label>
        <input type="text" 
               class="input-dental" 
               id="edit_modal_phone" 
               name="phone" 
               value="{{ $subdomain->phone }}"
               placeholder="+1 (555) 123-4567">
        <div class="text-red-500 text-sm mt-1 hidden" id="phone_error"></div>
    </div>

    <div class="md:col-span-2">
        <label for="edit_modal_address" class="form-label flex items-center gap-2">
            <i class="bi bi-geo-alt text-dental-teal"></i>Address
        </label>
        <textarea class="input-dental" 
                  id="edit_modal_address" 
                  name="address" 
                  rows="3"
                  placeholder="Enter clinic address">{{ $subdomain->address }}</textarea>
        <div class="text-red-500 text-sm mt-1 hidden" id="address_error"></div>
    </div>
</div>


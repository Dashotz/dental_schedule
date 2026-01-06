<div class="space-y-6">
    <!-- Subdomain Field -->
    <div>
        <label for="edit_modal_subdomain" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
            <x-dental-icon name="globe" class="w-4 h-4 text-dental-teal" />
            <span>Subdomain <span class="text-red-500">*</span></span>
        </label>
        <div class="flex">
            <input type="text" 
                   class="input-dental rounded-r-none flex-1" 
                   id="edit_modal_subdomain" 
                   name="subdomain" 
                   value="{{ $subdomain->subdomain }}" 
                   placeholder="clinic-name" 
                   required>
            <span class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg px-4 py-2.5 text-gray-600 flex items-center whitespace-nowrap">.helioho.st</span>
        </div>
        <small class="text-gray-500 text-xs block mt-2 flex items-center gap-1">
            <x-dental-icon name="info-circle" class="w-4 h-4" />
            <span>Only lowercase letters, numbers, and hyphens allowed.</span>
        </small>
        <div class="text-red-500 text-sm mt-1 hidden" id="subdomain_error"></div>
    </div>

    <!-- Clinic Name Field -->
    <div>
        <label for="edit_modal_name" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
            <x-dental-icon name="building" class="w-4 h-4 text-dental-teal" />
            <span>Clinic Name <span class="text-red-500">*</span></span>
        </label>
        <input type="text" 
               class="input-dental w-full" 
               id="edit_modal_name" 
               name="name" 
               value="{{ $subdomain->name }}" 
               placeholder="Enter clinic name"
               required>
        <div class="text-red-500 text-sm mt-1 hidden" id="name_error"></div>
    </div>

    <!-- Description Field -->
    <div>
        <label for="edit_modal_description" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
            <x-dental-icon name="file-text" class="w-4 h-4 text-dental-teal" />
            <span>Description</span>
        </label>
        <textarea class="input-dental w-full" 
                  id="edit_modal_description" 
                  name="description" 
                  rows="3"
                  placeholder="Enter a brief description about the clinic">{{ $subdomain->description }}</textarea>
        <div class="text-red-500 text-sm mt-1 hidden" id="description_error"></div>
    </div>

    <!-- Email and Phone Fields -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="edit_modal_email" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                <x-dental-icon name="envelope" class="w-4 h-4 text-dental-teal" />
                <span>Email</span>
            </label>
            <input type="email" 
                   class="input-dental w-full" 
                   id="edit_modal_email" 
                   name="email" 
                   value="{{ $subdomain->email }}"
                   placeholder="clinic@example.com">
            <div class="text-red-500 text-sm mt-1 hidden" id="email_error"></div>
        </div>

        <div>
            <label for="edit_modal_phone" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                <x-dental-icon name="telephone" class="w-4 h-4 text-dental-teal" />
                <span>Phone</span>
            </label>
            <input type="text" 
                   class="input-dental w-full" 
                   id="edit_modal_phone" 
                   name="phone" 
                   value="{{ $subdomain->phone }}"
                   placeholder="+1 (555) 123-4567">
            <div class="text-red-500 text-sm mt-1 hidden" id="phone_error"></div>
        </div>
    </div>

    <!-- Address Field -->
    <div>
        <label for="edit_modal_address" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
            <x-dental-icon name="geo-alt" class="w-4 h-4 text-dental-teal" />
            <span>Address</span>
        </label>
        <textarea class="input-dental w-full" 
                  id="edit_modal_address" 
                  name="address" 
                  rows="3"
                  placeholder="Enter clinic address">{{ $subdomain->address }}</textarea>
        <div class="text-red-500 text-sm mt-1 hidden" id="address_error"></div>
    </div>
</div>


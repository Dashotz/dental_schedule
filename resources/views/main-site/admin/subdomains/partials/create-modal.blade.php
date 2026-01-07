<x-modal id="createSubdomainModal" title="Create New Subdomain" size="xl">
    <form id="createSubdomainForm">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label for="modal_subdomain" class="form-label flex items-center gap-2">
                    <x-dental-icon name="globe" class="w-5 h-5 text-dental-teal" />Subdomain <span class="text-red-500">*</span>
                </label>
                <div class="flex">
                    <input type="text" 
                           class="input-dental rounded-r-none" 
                           id="modal_subdomain" 
                           name="subdomain" 
                           placeholder="clinic-name" 
                           required>
                    <span class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg px-4 py-2.5 text-gray-600 flex items-center">
                        <x-dental-icon name="info-circle" class="w-4 h-4 mr-1" />
                        <span class="text-xs">Port auto-assigned</span>
                    </span>
                </div>
                <small class="text-gray-500 block mt-2">
                    <x-dental-icon name="info-circle" class="w-4 h-4 inline" /> Only lowercase letters, numbers, and hyphens allowed.
                </small>
                <div class="text-red-500 text-sm mt-1 hidden" id="subdomain_error"></div>
            </div>

            <div class="md:col-span-2">
                <label for="modal_name" class="form-label flex items-center gap-2">
                    <x-dental-icon name="building" class="w-5 h-5 text-dental-teal" />Clinic Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       class="input-dental" 
                       id="modal_name" 
                       name="name" 
                       placeholder="Enter clinic name"
                       required>
                <div class="text-red-500 text-sm mt-1 hidden" id="name_error"></div>
            </div>

            <div class="md:col-span-2">
                <label for="modal_description" class="form-label flex items-center gap-2">
                    <x-dental-icon name="file-text" class="w-5 h-5 text-dental-teal" />Description
                </label>
                <textarea class="input-dental" 
                          id="modal_description" 
                          name="description" 
                          rows="3"
                          placeholder="Enter a brief description about the clinic"></textarea>
                <div class="text-red-500 text-sm mt-1 hidden" id="description_error"></div>
            </div>

            <div>
                <label for="modal_email" class="form-label flex items-center gap-2">
                    <x-dental-icon name="envelope" class="w-5 h-5 text-dental-teal" />Email
                </label>
                <input type="email" 
                       class="input-dental" 
                       id="modal_email" 
                       name="email" 
                       placeholder="clinic@example.com">
                <div class="text-red-500 text-sm mt-1 hidden" id="email_error"></div>
            </div>

            <div>
                <label for="modal_phone" class="form-label flex items-center gap-2">
                    <x-dental-icon name="telephone" class="w-5 h-5 text-dental-teal" />Phone
                </label>
                <input type="text" 
                       class="input-dental" 
                       id="modal_phone" 
                       name="phone" 
                       placeholder="+1 (555) 123-4567">
                <div class="text-red-500 text-sm mt-1 hidden" id="phone_error"></div>
            </div>

            <div class="md:col-span-2">
                <label for="modal_address" class="form-label flex items-center gap-2">
                    <x-dental-icon name="geo-alt" class="w-5 h-5 text-dental-teal" />Address
                </label>
                <textarea class="input-dental" 
                          id="modal_address" 
                          name="address" 
                          rows="3"
                          placeholder="Enter clinic address"></textarea>
                <div class="text-red-500 text-sm mt-1 hidden" id="address_error"></div>
            </div>
        </div>
    </form>
    
    <x-slot name="footer">
        <button type="button" class="btn-dental-outline" onclick="closeModal('createSubdomainModal')">
            <x-dental-icon name="x-circle" class="w-5 h-5" /> Cancel
        </button>
        <button type="submit" form="createSubdomainForm" class="btn-dental">
            <x-dental-icon name="check-circle" class="w-5 h-5" /> Create Subdomain
        </button>
    </x-slot>
</x-modal>


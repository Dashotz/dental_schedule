<x-modal id="editSubdomainModal" title="Edit Subdomain" size="xl">
    <form id="editSubdomainForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="subdomain_id" id="edit_subdomain_id">
        <div id="editSubdomainModalBody" class="p-6">
            <!-- Content will be loaded via AJAX -->
        </div>
    </form>
    
    <x-slot name="footer">
        <div class="flex items-center justify-between w-full">
            <button type="button" class="btn-dental-outline" id="backToViewSubdomainBtn">
                <x-dental-icon name="arrow-left" class="w-5 h-5" /> Back
            </button>
            <div class="flex gap-2">
                <button type="button" class="btn-dental-outline" onclick="closeModal('editSubdomainModal')">
                    <x-dental-icon name="x-circle" class="w-5 h-5" /> Cancel
                </button>
                <button type="submit" form="editSubdomainForm" class="btn-dental">
                    <x-dental-icon name="check-circle" class="w-5 h-5" /> Update Subdomain
                </button>
            </div>
        </div>
    </x-slot>
</x-modal>


<x-modal id="editSubdomainModal" title="Edit Subdomain" size="xl">
    <form id="editSubdomainForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="subdomain_id" id="edit_subdomain_id">
        <div id="editSubdomainModalBody">
            <!-- Content will be loaded via AJAX -->
        </div>
    </form>
    
    <x-slot name="footer">
        <button type="button" class="btn-dental-outline" onclick="closeModal('editSubdomainModal')">
            <x-dental-icon name="x-circle" class="w-5 h-5" /> Cancel
        </button>
        <button type="submit" form="editSubdomainForm" class="btn-dental">
            <x-dental-icon name="check-circle" class="w-5 h-5" /> Update Subdomain
        </button>
    </x-slot>
</x-modal>


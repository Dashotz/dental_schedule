<div class="modal fade" id="editSubdomainModal" tabindex="-1" aria-labelledby="editSubdomainModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editSubdomainModalLabel">
                    <i class="bi bi-pencil me-2"></i>Edit Subdomain
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSubdomainForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="subdomain_id" id="edit_subdomain_id">
                <div class="modal-body p-4" id="editSubdomainModalBody">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Subdomain
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


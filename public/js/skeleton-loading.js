/**
 * Skeleton Loading Templates
 * Provides skeleton loading HTML templates for modals and forms
 */

const SkeletonLoading = {
    /**
     * Get skeleton HTML for view subdomain modal
     */
    getViewSubdomainModal: function() {
        return `
            <div class="modal fade" id="viewSubdomainModal" tabindex="-1" aria-labelledby="viewSubdomainModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="viewSubdomainModalLabel">
                                <i class="bi bi-globe me-2"></i><span class="skeleton" style="width: 200px; height: 1.5rem; display: inline-block; background: rgba(255,255,255,0.3); border-radius: 4px;"></span>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="skeleton" style="width: 80px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                                        <div class="skeleton" style="width: 70%; height: 1rem; border-radius: 4px;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="skeleton" style="width: 100px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                                        <div class="skeleton" style="width: 80%; height: 1rem; border-radius: 4px;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="skeleton" style="width: 60px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                                        <div class="skeleton" style="width: 75%; height: 1rem; border-radius: 4px;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="skeleton" style="width: 70px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                                        <div class="skeleton" style="width: 65%; height: 1rem; border-radius: 4px;"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded">
                                        <div class="skeleton" style="width: 60px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="skeleton" style="width: 56px; height: 28px; border-radius: 14px;"></div>
                                            <div class="skeleton" style="width: 80px; height: 24px; border-radius: 12px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-success text-white">
                                            <div class="skeleton" style="width: 120px; height: 0.75rem; background: rgba(255,255,255,0.3); border-radius: 4px;"></div>
                                        </div>
                                        <div class="card-body">
                                            <div class="skeleton" style="height: 38px; border-radius: 6px; margin-bottom: 0.5rem;"></div>
                                            <div class="skeleton" style="height: 38px; border-radius: 6px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-info text-white">
                                            <div class="skeleton" style="width: 150px; height: 0.75rem; background: rgba(255,255,255,0.3); border-radius: 4px;"></div>
                                        </div>
                                        <div class="card-body">
                                            <div class="skeleton" style="width: 140px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                                            <div class="skeleton" style="height: 45px; border-radius: 6px; margin-bottom: 0.5rem;"></div>
                                            <div class="skeleton" style="width: 100px; height: 0.75rem; margin-top: 0.5rem; border-radius: 4px;"></div>
                                            <div class="skeleton" style="width: 180px; height: 0.75rem; margin-top: 0.25rem; border-radius: 4px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Get skeleton HTML for edit subdomain modal body
     */
    getEditSubdomainModalBody: function() {
        return `
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="skeleton" style="width: 100px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                    <div class="skeleton" style="height: 45px; border-radius: 6px;"></div>
                    <div class="skeleton" style="width: 80%; height: 0.75rem; margin-top: 0.5rem; border-radius: 4px;"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="skeleton" style="width: 90px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                    <div class="skeleton" style="height: 45px; border-radius: 6px;"></div>
                </div>
                <div class="col-12 mb-3">
                    <div class="skeleton" style="width: 90px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                    <div class="skeleton" style="height: 80px; border-radius: 6px;"></div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="skeleton" style="width: 60px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                    <div class="skeleton" style="height: 45px; border-radius: 6px;"></div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="skeleton" style="width: 60px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                    <div class="skeleton" style="height: 45px; border-radius: 6px;"></div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="skeleton" style="width: 70px; height: 0.75rem; margin-bottom: 0.5rem; border-radius: 4px;"></div>
                    <div class="skeleton" style="height: 80px; border-radius: 6px;"></div>
                </div>
            </div>
        `;
    },

    /**
     * Get skeleton HTML for create subdomain modal body
     */
    getCreateSubdomainModalBody: function() {
        return this.getEditSubdomainModalBody(); // Same structure as edit modal
    }
};


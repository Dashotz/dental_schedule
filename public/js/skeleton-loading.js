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
            <x-modal id="viewSubdomainModal" title="Loading..." size="xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="skeleton skeleton-text-sm"></div>
                        <div class="skeleton skeleton-text"></div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="skeleton skeleton-text-sm"></div>
                        <div class="skeleton skeleton-text"></div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="skeleton skeleton-text-sm"></div>
                        <div class="skeleton skeleton-text"></div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="skeleton skeleton-text-sm"></div>
                        <div class="skeleton skeleton-text"></div>
                    </div>
                    <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                        <div class="skeleton skeleton-text-sm"></div>
                        <div class="flex items-center gap-3">
                            <div class="skeleton" style="width: 56px; height: 28px; border-radius: 14px;"></div>
                            <div class="skeleton skeleton-badge"></div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="card-dental">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-3 rounded-t-2xl">
                            <div class="skeleton" style="width: 120px; height: 0.75rem; background: rgba(255,255,255,0.3); border-radius: 4px;"></div>
                        </div>
                        <div class="p-4 space-y-2">
                            <div class="skeleton skeleton-button"></div>
                            <div class="skeleton skeleton-button"></div>
                        </div>
                    </div>
                    <div class="card-dental">
                        <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-4 py-3 rounded-t-2xl">
                            <div class="skeleton" style="width: 150px; height: 0.75rem; background: rgba(255,255,255,0.3); border-radius: 4px;"></div>
                        </div>
                        <div class="p-4">
                            <div class="skeleton skeleton-text-sm"></div>
                            <div class="skeleton skeleton-input"></div>
                            <div class="skeleton skeleton-text-sm mt-2"></div>
                            <div class="skeleton skeleton-text-sm"></div>
                        </div>
                    </div>
                </div>
            </x-modal>
        `;
    },

    /**
     * Get skeleton HTML for edit subdomain modal body
     */
    getEditSubdomainModalBody: function() {
        return `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <div class="skeleton skeleton-text-sm"></div>
                    <div class="skeleton skeleton-input"></div>
                    <div class="skeleton skeleton-text-sm mt-2"></div>
                </div>
                <div class="md:col-span-2">
                    <div class="skeleton skeleton-text-sm"></div>
                    <div class="skeleton skeleton-input"></div>
                </div>
                <div class="md:col-span-2">
                    <div class="skeleton skeleton-text-sm"></div>
                    <div class="skeleton" style="height: 80px; border-radius: 6px;"></div>
                </div>
                <div>
                    <div class="skeleton skeleton-text-sm"></div>
                    <div class="skeleton skeleton-input"></div>
                </div>
                <div>
                    <div class="skeleton skeleton-text-sm"></div>
                    <div class="skeleton skeleton-input"></div>
                </div>
                <div class="md:col-span-2">
                    <div class="skeleton skeleton-text-sm"></div>
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

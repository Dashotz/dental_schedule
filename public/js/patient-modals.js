/**
 * Patient modals functionality
 * Handles view and edit patient modals
 */

(function() {
    'use strict';

    // Wait for jQuery to be available
    function init() {
        if (typeof $ === 'undefined') {
            setTimeout(init, 100);
            return;
        }

        $(document).ready(function() {
            initViewPatientModal();
            initEditPatientModal();
            initEditFormSubmission();
        });
    }

    /**
     * Initialize view patient modal
     */
    function initViewPatientModal() {
        $(document).on('click', '.view-patient-btn', function(e) {
            e.preventDefault();
            const patientId = $(this).data('patient-id');
            
            // Check if container exists
            if ($('#viewPatientModalContainer').length === 0) {
                $('body').append('<div id="viewPatientModalContainer"></div>');
            }
            
            // Show loading state
            $('#viewPatientModalContainer').html(getLoadingModal('viewPatientModal', 'Loading...'));
            
            // Open modal
            if (typeof openModal === 'function') {
                openModal('viewPatientModal');
            }
            
            // Load patient data
            $.ajax({
                url: `/patients/${patientId}`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#viewPatientModalContainer').html(response.html);
                    // Make sure modal is visible after content loads
                    const modal = document.getElementById('viewPatientModal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                },
                error: function(xhr) {
                    $('#viewPatientModalContainer').html(getErrorModal('viewPatientModal', 'Failed to load patient details. Please try again.'));
                    if (typeof openModal === 'function') {
                        openModal('viewPatientModal');
                    }
                }
            });
        });
    }

    /**
     * Initialize edit patient modal
     */
    function initEditPatientModal() {
        $(document).on('click', '.edit-patient-btn', function(e) {
            e.preventDefault();
            const patientId = $(this).data('patient-id');
            
            // Close view modal if open
            if (typeof closeModal === 'function') {
                closeModal('viewPatientModal');
            }
            
            // Check if container exists
            if ($('#editPatientModalContainer').length === 0) {
                $('body').append('<div id="editPatientModalContainer"></div>');
            }
            
            // Show loading state
            $('#editPatientModalContainer').html(getLoadingModal('editPatientModal', 'Loading...', 'max-w-6xl'));
            
            // Open modal
            if (typeof openModal === 'function') {
                openModal('editPatientModal');
            }
            
            // Load edit form
            $.ajax({
                url: `/patients/${patientId}/edit`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#editPatientModalContainer').html(response.html);
                    // Make sure modal is visible after content loads
                    const modal = document.getElementById('editPatientModal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                },
                error: function(xhr) {
                    $('#editPatientModalContainer').html(getErrorModal('editPatientModal', 'Failed to load edit form. Please try again.'));
                    if (typeof openModal === 'function') {
                        openModal('editPatientModal');
                    }
                }
            });
        });
    }

    /**
     * Initialize edit form submission
     */
    function initEditFormSubmission() {
        $(document).on('submit', '#editPatientForm', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span class="inline-block animate-spin mr-2">⟳</span>Updating...');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize() + '&_method=PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (typeof closeModal === 'function') {
                        closeModal('editPatientModal');
                    }
                    
                    // Show success message
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Patient information updated successfully.',
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                confirmButton: 'btn-dental'
                            },
                            buttonsStyling: false
                        });
                    }
                    
                    // Reload the page to refresh the table
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        // Remove previous error styling
                        form.find('.border-red-500').removeClass('border-red-500');
                        form.find('.invalid-feedback').remove();
                        // Add error styling and messages
                        Object.keys(errors).forEach(function(key) {
                            const input = form.find(`[name="${key}"]`);
                            input.addClass('border-red-500');
                            const errorDiv = $('<div class="invalid-feedback">' + errors[key][0] + '</div>');
                            input.after(errorDiv);
                        });
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to update patient.',
                                customClass: {
                                    confirmButton: 'btn-dental'
                                },
                                buttonsStyling: false
                            });
                        }
                    }
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
    }

    /**
     * Get loading modal HTML
     */
    function getLoadingModal(modalId, title, maxWidth = 'max-w-4xl') {
        return `
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" id="${modalId}">
                <div class="bg-white rounded-2xl shadow-2xl ${maxWidth} w-full mx-4 max-h-[90vh] overflow-y-auto modal-scroll">
                    <div class="card-dental-header flex justify-between items-center">
                        <h5 class="text-lg font-semibold">${title}</h5>
                        <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors" onclick="closeModal('${modalId}')">
                            &times;
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-dental-teal"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Get error modal HTML
     */
    function getErrorModal(modalId, message) {
        return `
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" id="${modalId}">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto modal-scroll">
                    <div class="card-dental-header flex justify-between items-center">
                        <h5 class="text-lg font-semibold">Error</h5>
                        <button type="button" class="text-white hover:text-gray-200 text-2xl transition-colors" onclick="closeModal('${modalId}')">
                            &times;
                        </button>
                    </div>
                    <div class="p-6">
                        <p class="text-red-600">${message}</p>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                        <button onclick="closeModal('${modalId}')" class="btn-dental-outline">Close</button>
                    </div>
                </div>
            </div>
        `;
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();


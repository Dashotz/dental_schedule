/**
 * Core application JavaScript
 * Handles sidebar, modals, and global SweetAlert configuration
 */

// Wait for DOM and dependencies to be ready
(function() {
    'use strict';

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        // Initialize SweetAlert configuration (if available)
        if (typeof Swal !== 'undefined') {
            configureSweetAlert();
        }

        // Initialize sidebar
        if (typeof $ !== 'undefined') {
            initSidebar();
        }

        // Initialize modals
        initModals();

        // Show session messages
        showSessionMessages();
    }

    /**
     * Configure SweetAlert2 with Tailwind-compatible dental theme
     */
    function configureSweetAlert() {
        Swal.mixin({
            customClass: {
                confirmButton: 'btn-dental',
                cancelButton: 'btn-dental-outline',
                popup: 'rounded-2xl',
                title: 'text-gray-800',
                htmlContainer: 'text-gray-600'
            },
            buttonsStyling: false,
            confirmButtonColor: '#20b2aa',
            cancelButtonColor: '#6c757d'
        });
    }

    /**
     * Show session flash messages
     */
    function showSessionMessages() {
        if (typeof Swal === 'undefined') return;

        // Success message
        const successMessage = document.querySelector('[data-session-success]');
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: successMessage.textContent,
                confirmButtonColor: '#20b2aa',
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    confirmButton: 'btn-dental'
                },
                buttonsStyling: false
            });
        }

        // Error message
        const errorMessage = document.querySelector('[data-session-error]');
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage.textContent,
                confirmButtonColor: '#dc3545',
                customClass: {
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg'
                },
                buttonsStyling: false
            });
        }

        // Validation errors
        const validationErrors = document.querySelector('[data-validation-errors]');
        if (validationErrors) {
            const errors = JSON.parse(validationErrors.textContent);
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul class="text-left list-disc pl-5 text-gray-700">' + 
                      errors.map(error => '<li>' + error + '</li>').join('') + 
                      '</ul>',
                confirmButtonColor: '#dc3545',
                customClass: {
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg'
                },
                buttonsStyling: false
            });
        }
    }

    /**
     * Initialize sidebar functionality
     */
    function initSidebar() {
        $(document).ready(function() {
            const sidebar = $('#sidebar');
            const mainContent = $('#mainContent');
            const toggleBtn = $('#toggleSidebar');
            const toggleIcon = $('#toggleIcon');
            const mobileToggle = $('#mobileToggleSidebar');
            const sidebarOverlay = $('#sidebarOverlay');
            const analyticsMenu = $('#analyticsMenu');
            const analyticsChevron = $('#analyticsChevron');
            let isCollapsed = false;

            // Desktop toggle
            if (toggleBtn.length) {
                toggleBtn.on('click', function() {
                    isCollapsed = !isCollapsed;
                    if (isCollapsed) {
                        sidebar.addClass('w-16').removeClass('w-64');
                        mainContent.addClass('lg:ml-16').removeClass('lg:ml-64');
                        toggleIcon.css('transform', 'rotate(180deg)');
                        $('.sidebar-title').hide();
                        $('.nav-link-dental span').hide();
                        $('.user-info-text').hide();
                        $('.logout-text').hide();
                        $('.sidebar-tooth-icon').hide();
                        toggleBtn.addClass('mx-auto').removeClass('flex-shrink-0');
                    } else {
                        sidebar.addClass('w-64').removeClass('w-16');
                        mainContent.addClass('lg:ml-64').removeClass('lg:ml-16');
                        toggleIcon.css('transform', 'rotate(0deg)');
                        $('.sidebar-title').show();
                        $('.nav-link-dental span').show();
                        $('.user-info-text').show();
                        $('.logout-text').show();
                        $('.sidebar-tooth-icon').show();
                        toggleBtn.removeClass('mx-auto').addClass('flex-shrink-0');
                    }
                });
            }

            // Mobile toggle
            if (mobileToggle.length) {
                mobileToggle.on('click', function() {
                    sidebar.toggleClass('-translate-x-full');
                    sidebarOverlay.toggleClass('hidden');
                });
            }

            // Close sidebar when overlay is clicked (mobile)
            if (sidebarOverlay.length) {
                sidebarOverlay.on('click', function() {
                    sidebar.addClass('-translate-x-full');
                    sidebarOverlay.addClass('hidden');
                });
            }

            // Analytics menu toggle
            $('.analytics-toggle').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                analyticsMenu.toggleClass('hidden');
                analyticsChevron.toggleClass('rotate-180');
            });

            // Handle window resize
            $(window).on('resize', function() {
                const isMobileNow = window.innerWidth < 1024;
                if (isMobileNow) {
                    sidebar.addClass('-translate-x-full');
                    sidebarOverlay.addClass('hidden');
                    mainContent.removeClass('lg:ml-16 lg:ml-64').addClass('ml-0');
                } else {
                    sidebar.removeClass('-translate-x-full');
                    sidebarOverlay.addClass('hidden');
                    if (isCollapsed) {
                        mainContent.addClass('lg:ml-16').removeClass('lg:ml-64');
                    } else {
                        mainContent.addClass('lg:ml-64').removeClass('lg:ml-16');
                    }
                }
            });
        });
    }

    /**
     * Initialize modal functionality
     */
    function initModals() {
        // Global modal functions
        window.openModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        };

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed') && e.target.id && e.target.id.includes('modal')) {
                window.closeModal(e.target.id);
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.fixed.flex[id*="modal"]');
                openModals.forEach(modal => {
                    window.closeModal(modal.id);
                });
            }
        });
    }
})();


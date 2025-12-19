/**
 * Form Validation with Guard Clauses
 * Uses early returns instead of nested if-else statements
 */

const FormValidator = {
    /**
     * Validate registration form
     */
    validateRegistrationForm: function() {
        // Guard: Check if form exists
        const form = $('#registrationForm');
        if (!form.length) {
            this.showError('Form not found');
            return false;
        }

        // Guard: Validate required fields
        if (!this.validateRequiredFields()) {
            return false;
        }

        // Guard: Validate name fields
        if (!this.validateNames()) {
            return false;
        }

        // Guard: Validate email
        if (!this.validateEmail()) {
            return false;
        }

        // Guard: Validate phone numbers
        if (!this.validatePhones()) {
            return false;
        }

        // Guard: Validate date of birth
        if (!this.validateDateOfBirth()) {
            return false;
        }

        // Guard: Validate appointment date
        if (!this.validateAppointmentDate()) {
            return false;
        }

        // Guard: Validate appointment time
        if (!this.validateAppointmentTime()) {
            return false;
        }

        // Guard: Validate CAPTCHA
        if (!this.validateCaptcha()) {
            return false;
        }

        // All validations passed
        return true;
    },

    /**
     * Validate required fields
     */
    validateRequiredFields: function() {
        const requiredFields = [
            { id: '#first_name', name: 'First Name' },
            { id: '#last_name', name: 'Last Name' },
            { id: '#phone', name: 'Phone' },
            { id: '#appointment_date', name: 'Appointment Date' },
            { id: '#appointment_time', name: 'Appointment Time' },
            { id: '#appointment_type', name: 'Appointment Type' },
            { id: '#captcha', name: 'CAPTCHA' }
        ];

        for (const field of requiredFields) {
            const value = $(field.id).val()?.trim();
            
            if (!value) {
                this.showError(`${field.name} is required`);
                $(field.id).focus();
                return false;
            }
        }

        return true;
    },

    /**
     * Validate name fields (first name, last name)
     */
    validateNames: function() {
        const nameRegex = /^[a-zA-Z\s\-\']+$/;
        
        const firstName = $('#first_name').val()?.trim();
        if (!nameRegex.test(firstName)) {
            this.showError('First name can only contain letters, spaces, hyphens, and apostrophes');
            $('#first_name').focus();
            return false;
        }

        const lastName = $('#last_name').val()?.trim();
        if (!nameRegex.test(lastName)) {
            this.showError('Last name can only contain letters, spaces, hyphens, and apostrophes');
            $('#last_name').focus();
            return false;
        }

        return true;
    },

    /**
     * Validate email format
     */
    validateEmail: function() {
        const email = $('#email').val()?.trim();
        
        // Guard: Email is optional, skip if empty
        if (!email) {
            return true;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            this.showError('Please enter a valid email address');
            $('#email').focus();
            return false;
        }

        return true;
    },

    /**
     * Validate phone numbers
     */
    validatePhones: function() {
        const phoneRegex = /^[\d\s\-\+\(\)]+$/;
        
        const phone = $('#phone').val()?.trim();
        if (!phoneRegex.test(phone)) {
            this.showError('Phone number format is invalid');
            $('#phone').focus();
            return false;
        }

        const phoneAlt = $('#phone_alt').val()?.trim();
        if (phoneAlt && !phoneRegex.test(phoneAlt)) {
            this.showError('Alternate phone number format is invalid');
            $('#phone_alt').focus();
            return false;
        }

        return true;
    },

    /**
     * Validate date of birth
     */
    validateDateOfBirth: function() {
        const dob = $('#date_of_birth').val();
        
        // Guard: Date of birth is optional
        if (!dob) {
            return true;
        }

        const dobDate = new Date(dob);
        const today = new Date();
        
        if (dobDate >= today) {
            this.showError('Date of birth must be in the past');
            $('#date_of_birth').focus();
            return false;
        }

        return true;
    },

    /**
     * Validate appointment date
     */
    validateAppointmentDate: function() {
        const appointmentDate = $('#appointment_date').val();
        if (!appointmentDate) {
            return true; // Already checked in required fields
        }

        const appointmentDateObj = new Date(appointmentDate);
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(0, 0, 0, 0);

        if (appointmentDateObj < tomorrow) {
            this.showError('Appointment date must be at least tomorrow');
            $('#appointment_date').focus();
            return false;
        }

        return true;
    },

    /**
     * Validate appointment time
     */
    validateAppointmentTime: function() {
        const time = $('#appointment_time').val();
        if (!time) {
            return true; // Already checked in required fields
        }

        const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
        if (!timeRegex.test(time)) {
            this.showError('Invalid time format. Please use HH:MM format');
            $('#appointment_time').focus();
            return false;
        }

        return true;
    },

    /**
     * Validate CAPTCHA
     */
    validateCaptcha: function() {
        const captcha = $('#captcha').val()?.trim();
        
        if (!captcha) {
            this.showError('Please complete the CAPTCHA verification');
            $('#captcha').focus();
            return false;
        }

        if (captcha.length < 4) {
            this.showError('CAPTCHA code is too short');
            $('#captcha').focus();
            return false;
        }

        return true;
    },

    /**
     * Show error message using SweetAlert
     */
    showError: function(message) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: message,
            confirmButtonColor: '#dc3545'
        });
    },

    /**
     * Show success message using SweetAlert
     */
    showSuccess: function(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: message,
            confirmButtonColor: '#0d6efd',
            timer: 3000,
            timerProgressBar: true
        });
    },

    /**
     * Sanitize input value
     */
    sanitizeInput: function(value) {
        if (!value) return '';
        
        // Remove HTML tags
        let sanitized = $('<div>').text(value).html();
        
        // Remove null bytes
        sanitized = sanitized.replace(/\0/g, '');
        
        // Trim whitespace
        sanitized = sanitized.trim();
        
        return sanitized;
    }
};


/**
 * Validates an email address
 * @param {string} email - The email to validate
 * @returns {boolean} - Whether the email is valid
 */
export const isValidEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

/**
 * Validates a password
 * @param {string} password - The password to validate
 * @returns {object} - Object containing validation results
 */
export const validatePassword = (password) => {
    const results = {
        isValid: false,
        hasMinLength: password.length >= 8,
        hasUpperCase: /[A-Z]/.test(password),
        hasNumber: /[0-9]/.test(password),
        hasSpecialChar: /[^A-Za-z0-9]/.test(password),
        strength: 0
    };

    // Calculate strength
    if (results.hasMinLength) results.strength += 1;
    if (results.hasUpperCase) results.strength += 1;
    if (results.hasNumber) results.strength += 1;
    if (results.hasSpecialChar) results.strength += 1;

    // Password is valid if it meets minimum requirements
    results.isValid = results.hasMinLength &&
        (results.hasUpperCase || results.hasNumber || results.hasSpecialChar);

    return results;
};

/**
 * Validates a phone number
 * @param {string} phone - The phone number to validate
 * @returns {boolean} - Whether the phone number is valid
 */
export const isValidPhone = (phone) => {
    // Basic validation for international phone numbers
    const phoneRegex = /^\+?[0-9\s\-()]{8,20}$/;
    return phoneRegex.test(phone);
};

/**
 * Validates form fields
 * @param {object} fields - Object containing form fields
 * @param {object} rules - Validation rules for each field
 * @returns {object} - Object containing validation errors
 */
export const validateForm = (fields, rules) => {
    const errors = {};

    Object.keys(rules).forEach(field => {
        const value = fields[field];
        const fieldRules = rules[field];

        // Required validation
        if (fieldRules.required && (!value || value.trim() === '')) {
            errors[field] = fieldRules.message || 'Ce champ est requis';
            return;
        }

        // Skip other validations if field is empty and not required
        if (!value && !fieldRules.required) return;

        // Email validation
        if (fieldRules.email && !isValidEmail(value)) {
            errors[field] = fieldRules.message || 'Email invalide';
            return;
        }

        // Password validation
        if (fieldRules.password) {
            const passwordValidation = validatePassword(value);
            if (!passwordValidation.isValid) {
                errors[field] = fieldRules.message || 'Mot de passe invalide';
                return;
            }
        }

        // Phone validation
        if (fieldRules.phone && !isValidPhone(value)) {
            errors[field] = fieldRules.message || 'Numéro de téléphone invalide';
            return;
        }

        // Min length validation
        if (fieldRules.minLength && value.length < fieldRules.minLength) {
            errors[field] = fieldRules.message || `Minimum ${fieldRules.minLength} caractères requis`;
            return;
        }

        // Max length validation
        if (fieldRules.maxLength && value.length > fieldRules.maxLength) {
            errors[field] = fieldRules.message || `Maximum ${fieldRules.maxLength} caractères autorisés`;
            return;
        }

        // Match validation (for password confirmation)
        if (fieldRules.match && value !== fields[fieldRules.match]) {
            errors[field] = fieldRules.message || 'Les valeurs ne correspondent pas';
            return;
        }

        // Custom validation
        if (fieldRules.validate && typeof fieldRules.validate === 'function') {
            const customError = fieldRules.validate(value, fields);
            if (customError) {
                errors[field] = customError;
                return;
            }
        }
    });

    return errors;
};
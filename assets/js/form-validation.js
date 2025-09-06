/**
 * Form Validation JavaScript
 * Xử lý validation cho các form trong hệ thống
 */

$(document).ready(function() {
    'use strict';
    
    // Initialize form validation
    initializeFormValidation();
    
    // Initialize real-time validation
    initializeRealTimeValidation();
    
    // Initialize custom validators
    initializeCustomValidators();
});

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    // Add validation classes to forms
    $('form').addClass('needs-validation');
    
    // Handle form submission
    $('form').on('submit', function(e) {
        var $form = $(this);
        
        if (!validateForm($form)) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
        
        // Show loading state
        showFormLoading($form);
    });
}

/**
 * Initialize real-time validation
 */
function initializeRealTimeValidation() {
    // Validate on blur
    $('input, select, textarea').on('blur', function() {
        validateField($(this));
    });
    
    // Validate on input for specific fields
    $('input[type="email"], input[type="password"], input[type="text"]').on('input', debounce(function() {
        validateField($(this));
    }, 500));
}

/**
 * Initialize custom validators
 */
function initializeCustomValidators() {
    // Add custom validation methods
    $.validator.addMethod('vietnamesePhone', function(value, element) {
        var phoneRegex = /^(\+84|84|0)[1-9][0-9]{8,9}$/;
        return this.optional(element) || phoneRegex.test(value);
    }, 'Số điện thoại không hợp lệ');
    
    $.validator.addMethod('vietnameseIdCard', function(value, element) {
        var idCardRegex = /^[0-9]{9,12}$/;
        return this.optional(element) || idCardRegex.test(value);
    }, 'Số CMND/CCCD không hợp lệ');
    
    $.validator.addMethod('futureDate', function(value, element) {
        var date = new Date(value);
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        return this.optional(element) || date >= today;
    }, 'Ngày phải là tương lai');
    
    $.validator.addMethod('pastDate', function(value, element) {
        var date = new Date(value);
        var today = new Date();
        today.setHours(23, 59, 59, 999);
        return this.optional(element) || date <= today;
    }, 'Ngày phải là quá khứ');
    
    $.validator.addMethod('dateRange', function(value, element, params) {
        var date = new Date(value);
        var startDate = new Date(params[0]);
        var endDate = new Date(params[1]);
        return this.optional(element) || (date >= startDate && date <= endDate);
    }, 'Ngày không nằm trong khoảng cho phép');
}

/**
 * Validate entire form
 */
function validateForm($form) {
    var isValid = true;
    var $fields = $form.find('input, select, textarea');
    
    // Clear previous validation
    clearFormValidation($form);
    
    // Validate each field
    $fields.each(function() {
        if (!validateField($(this))) {
            isValid = false;
        }
    });
    
    // Add validation class
    $form.addClass('was-validated');
    
    return isValid;
}

/**
 * Validate single field
 */
function validateField($field) {
    var value = $field.val();
    var rules = $field.data('rules');
    var isValid = true;
    var errorMessage = '';
    
    // Skip validation for disabled or readonly fields
    if ($field.prop('disabled') || $field.prop('readonly')) {
        return true;
    }
    
    // Required validation
    if (rules && rules.includes('required') && !value.trim()) {
        isValid = false;
        errorMessage = 'Trường này là bắt buộc';
    }
    
    // Email validation
    if (rules && rules.includes('email') && value && !isValidEmail(value)) {
        isValid = false;
        errorMessage = 'Email không hợp lệ';
    }
    
    // Phone validation
    if (rules && rules.includes('phone') && value && !isValidPhone(value)) {
        isValid = false;
        errorMessage = 'Số điện thoại không hợp lệ';
    }
    
    // Min length validation
    var minLength = $field.data('min-length');
    if (minLength && value.length < minLength) {
        isValid = false;
        errorMessage = 'Tối thiểu ' + minLength + ' ký tự';
    }
    
    // Max length validation
    var maxLength = $field.data('max-length');
    if (maxLength && value.length > maxLength) {
        isValid = false;
        errorMessage = 'Tối đa ' + maxLength + ' ký tự';
    }
    
    // Min value validation
    var minValue = $field.data('min-value');
    if (minValue && parseFloat(value) < minValue) {
        isValid = false;
        errorMessage = 'Giá trị tối thiểu là ' + minValue;
    }
    
    // Max value validation
    var maxValue = $field.data('max-value');
    if (maxValue && parseFloat(value) > maxValue) {
        isValid = false;
        errorMessage = 'Giá trị tối đa là ' + maxValue;
    }
    
    // Pattern validation
    var pattern = $field.data('pattern');
    if (pattern && value && !new RegExp(pattern).test(value)) {
        isValid = false;
        errorMessage = 'Định dạng không đúng';
    }
    
    // Custom validation
    var customValidator = $field.data('validator');
    if (customValidator && typeof window[customValidator] === 'function') {
        var customResult = window[customValidator](value, $field);
        if (customResult !== true) {
            isValid = false;
            errorMessage = customResult || 'Giá trị không hợp lệ';
        }
    }
    
    // Show validation result
    showFieldValidation($field, isValid, errorMessage);
    
    return isValid;
}

/**
 * Show field validation result
 */
function showFieldValidation($field, isValid, message) {
    $field.removeClass('is-valid is-invalid');
    $field.siblings('.invalid-feedback, .valid-feedback').remove();
    
    if (isValid) {
        $field.addClass('is-valid');
        if (message) {
            $field.after('<div class="valid-feedback">' + message + '</div>');
        }
    } else {
        $field.addClass('is-invalid');
        if (message) {
            $field.after('<div class="invalid-feedback">' + message + '</div>');
        }
    }
}

/**
 * Clear form validation
 */
function clearFormValidation($form) {
    $form.removeClass('was-validated');
    $form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    $form.find('.invalid-feedback, .valid-feedback').remove();
}

/**
 * Show form loading state
 */
function showFormLoading($form) {
    var $submitBtn = $form.find('button[type="submit"]');
    var originalText = $submitBtn.html();
    
    $submitBtn.prop('disabled', true);
    $submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...');
    
    // Store original text for restoration
    $submitBtn.data('original-text', originalText);
}

/**
 * Hide form loading state
 */
function hideFormLoading($form) {
    var $submitBtn = $form.find('button[type="submit"]');
    var originalText = $submitBtn.data('original-text');
    
    $submitBtn.prop('disabled', false);
    if (originalText) {
        $submitBtn.html(originalText);
    }
}

/**
 * Validation helper functions
 */

// Email validation
function isValidEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Phone validation
function isValidPhone(phone) {
    var re = /^(\+84|84|0)[1-9][0-9]{8,9}$/;
    return re.test(phone);
}

// Vietnamese ID card validation
function isValidIdCard(idCard) {
    var re = /^[0-9]{9,12}$/;
    return re.test(idCard);
}

// Password strength validation
function validatePasswordStrength(password) {
    var strength = 0;
    var messages = [];
    
    if (password.length < 6) {
        messages.push('Mật khẩu phải có ít nhất 6 ký tự');
        return { valid: false, messages: messages };
    }
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    if (strength < 3) {
        messages.push('Mật khẩu quá yếu');
        return { valid: false, messages: messages };
    }
    
    return { valid: true, strength: strength };
}

// Date validation
function isValidDate(dateString) {
    var date = new Date(dateString);
    return date instanceof Date && !isNaN(date);
}

// Future date validation
function isFutureDate(dateString) {
    var date = new Date(dateString);
    var today = new Date();
    today.setHours(0, 0, 0, 0);
    return date >= today;
}

// Past date validation
function isPastDate(dateString) {
    var date = new Date(dateString);
    var today = new Date();
    today.setHours(23, 59, 59, 999);
    return date <= today;
}

// Date range validation
function isDateInRange(dateString, startDate, endDate) {
    var date = new Date(dateString);
    var start = new Date(startDate);
    var end = new Date(endDate);
    return date >= start && date <= end;
}

/**
 * Custom validators
 */

// Password confirmation validator
function validatePasswordConfirmation(value, $field) {
    var password = $field.closest('form').find('input[name="password"]').val();
    return value === password || 'Mật khẩu xác nhận không khớp';
}

// Username availability validator
function validateUsernameAvailability(value, $field) {
    if (!value) return true;
    
    // This would typically make an AJAX call to check availability
    // For now, we'll just check if it's not a common reserved word
    var reservedWords = ['admin', 'administrator', 'root', 'user', 'test'];
    if (reservedWords.includes(value.toLowerCase())) {
        return 'Tên đăng nhập không được sử dụng';
    }
    
    return true;
}

// Email availability validator
function validateEmailAvailability(value, $field) {
    if (!value) return true;
    
    // This would typically make an AJAX call to check availability
    // For now, we'll just return true
    return true;
}

// File size validator
function validateFileSize(value, $field) {
    var file = $field[0].files[0];
    if (!file) return true;
    
    var maxSize = $field.data('max-size');
    if (maxSize && file.size > maxSize) {
        return 'File quá lớn. Kích thước tối đa: ' + formatFileSize(maxSize);
    }
    
    return true;
}

// File type validator
function validateFileType(value, $field) {
    var file = $field[0].files[0];
    if (!file) return true;
    
    var allowedTypes = $field.data('allowed-types');
    if (allowedTypes && !allowedTypes.includes(file.type)) {
        return 'Loại file không được phép';
    }
    
    return true;
}

/**
 * Utility functions
 */

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    var k = 1024;
    var sizes = ['Bytes', 'KB', 'MB', 'GB'];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Debounce function
function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Export functions for global use
window.FormValidation = {
    validateForm: validateForm,
    validateField: validateField,
    clearFormValidation: clearFormValidation,
    showFormLoading: showFormLoading,
    hideFormLoading: hideFormLoading,
    isValidEmail: isValidEmail,
    isValidPhone: isValidPhone,
    isValidIdCard: isValidIdCard,
    validatePasswordStrength: validatePasswordStrength,
    isValidDate: isValidDate,
    isFutureDate: isFutureDate,
    isPastDate: isPastDate,
    isDateInRange: isDateInRange,
    formatFileSize: formatFileSize
};

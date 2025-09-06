/**
 * Office Management System - Main JavaScript
 * Xử lý các chức năng JavaScript chung
 */

$(document).ready(function() {
    'use strict';
    
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize popovers
    initializePopovers();
    
    // Initialize form validation
    initializeFormValidation();
    
    // Initialize AJAX setup
    initializeAjax();
    
    // Initialize notifications
    initializeNotifications();
    
    // Initialize sidebar toggle
    initializeSidebar();
    
    // Initialize data tables
    initializeDataTables();
    
    // Initialize modals
    initializeModals();
    
    // Initialize date pickers
    initializeDatePickers();
    
    // Initialize file uploads
    initializeFileUploads();
});

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize Bootstrap popovers
 */
function initializePopovers() {
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    // Real-time validation
    $('input, select, textarea').on('blur', function() {
        validateField($(this));
    });
    
    // Form submission validation
    $('form[data-validate]').on('submit', function(e) {
        if (!validateForm($(this))) {
            e.preventDefault();
            return false;
        }
    });
}

/**
 * Validate a single field
 */
function validateField($field) {
    var value = $field.val().trim();
    var rules = $field.data('rules');
    var isValid = true;
    var errorMessage = '';
    
    if (!rules) return true;
    
    // Required validation
    if (rules.includes('required') && !value) {
        isValid = false;
        errorMessage = 'Trường này là bắt buộc';
    }
    
    // Email validation
    if (rules.includes('email') && value && !isValidEmail(value)) {
        isValid = false;
        errorMessage = 'Email không hợp lệ';
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
    
    // Show/hide error
    showFieldError($field, isValid, errorMessage);
    
    return isValid;
}

/**
 * Validate entire form
 */
function validateForm($form) {
    var isValid = true;
    var $fields = $form.find('input, select, textarea');
    
    $fields.each(function() {
        if (!validateField($(this))) {
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Show field error
 */
function showFieldError($field, isValid, message) {
    $field.removeClass('is-valid is-invalid');
    $field.siblings('.invalid-feedback').remove();
    
    if (isValid) {
        $field.addClass('is-valid');
    } else {
        $field.addClass('is-invalid');
        $field.after('<div class="invalid-feedback">' + message + '</div>');
    }
}

/**
 * Check if email is valid
 */
function isValidEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Initialize AJAX setup
 */
function initializeAjax() {
    // Set CSRF token for all AJAX requests
    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
                var token = $('meta[name="csrf-token"]').attr('content');
                if (token) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            }
        }
    });
    
    // Global AJAX error handler
    $(document).ajaxError(function(event, xhr, settings, thrownError) {
        if (xhr.status === 401) {
            showNotification('Phiên đăng nhập đã hết hạn', 'warning');
            setTimeout(function() {
                window.location.href = '/office_management/public/login';
            }, 2000);
        } else if (xhr.status === 403) {
            showNotification('Bạn không có quyền thực hiện hành động này', 'error');
        } else if (xhr.status === 422) {
            var errors = xhr.responseJSON.errors;
            if (errors) {
                displayValidationErrors(errors);
            }
        } else if (xhr.status >= 500) {
            showNotification('Lỗi máy chủ. Vui lòng thử lại sau', 'error');
        }
    });
}

/**
 * Display validation errors
 */
function displayValidationErrors(errors) {
    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    // Display new errors
    $.each(errors, function(field, messages) {
        var $field = $('[name="' + field + '"]');
        if ($field.length) {
            $field.addClass('is-invalid');
            $field.after('<div class="invalid-feedback">' + messages.join('<br>') + '</div>');
        }
    });
}

/**
 * Initialize notifications
 */
function initializeNotifications() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
    
    // Close alert on click
    $('.alert .btn-close').on('click', function() {
        $(this).closest('.alert').fadeOut();
    });
}

/**
 * Show notification
 */
function showNotification(message, type, duration) {
    type = type || 'info';
    duration = duration || 5000;
    
    var alertClass = 'alert-' + type;
    var iconClass = getNotificationIcon(type);
    
    var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
        '<i class="' + iconClass + ' me-2"></i>' +
        message +
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
        '</div>';
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert
    $('main').prepend(alertHtml);
    
    // Auto-hide
    setTimeout(function() {
        $('.alert').fadeOut();
    }, duration);
}

/**
 * Get notification icon
 */
function getNotificationIcon(type) {
    var icons = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-circle',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    };
    return icons[type] || icons['info'];
}

/**
 * Initialize sidebar toggle
 */
function initializeSidebar() {
    $('#sidebarToggle').on('click', function() {
        $('.sidebar').toggleClass('show');
        $('.main-content-with-sidebar').toggleClass('sidebar-open');
    });
    
    // Close sidebar when clicking outside on mobile
    $(document).on('click', function(e) {
        if ($(window).width() < 768) {
            if (!$(e.target).closest('.sidebar, #sidebarToggle').length) {
                $('.sidebar').removeClass('show');
            }
        }
    });
}

/**
 * Initialize data tables
 */
function initializeDataTables() {
    if ($.fn.DataTable) {
        $('.data-table').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
            },
            pageLength: 25,
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });
    }
}

/**
 * Initialize modals
 */
function initializeModals() {
    // Confirm delete modal
    $('[data-confirm-delete]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var title = $(this).data('confirm-delete') || 'Xác nhận xóa';
        
        showConfirmModal(title, 'Bạn có chắc chắn muốn xóa mục này?', function() {
            window.location.href = url;
        });
    });
    
    // Confirm action modal
    $('[data-confirm-action]').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var title = $(this).data('confirm-action') || 'Xác nhận hành động';
        var message = $(this).data('confirm-message') || 'Bạn có chắc chắn muốn thực hiện hành động này?';
        
        showConfirmModal(title, message, function() {
            window.location.href = url;
        });
    });
}

/**
 * Show confirm modal
 */
function showConfirmModal(title, message, callback) {
    var modalHtml = '<div class="modal fade" id="confirmModal" tabindex="-1">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<h5 class="modal-title">' + title + '</h5>' +
        '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>' +
        '</div>' +
        '<div class="modal-body">' +
        '<p>' + message + '</p>' +
        '</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>' +
        '<button type="button" class="btn btn-danger" id="confirmButton">Xác nhận</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
    
    // Remove existing modal
    $('#confirmModal').remove();
    
    // Add new modal
    $('body').append(modalHtml);
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
    
    // Handle confirm button click
    $('#confirmButton').on('click', function() {
        modal.hide();
        if (callback) callback();
    });
}

/**
 * Initialize date pickers
 */
function initializeDatePickers() {
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'vi'
    });
    
    $('.datetimepicker').datetimepicker({
        format: 'dd/mm/yyyy hh:ii',
        autoclose: true,
        todayHighlight: true,
        language: 'vi'
    });
}

/**
 * Initialize file uploads
 */
function initializeFileUploads() {
    $('.file-upload').on('change', function() {
        var file = this.files[0];
        var $input = $(this);
        var $preview = $input.siblings('.file-preview');
        
        if (file) {
            // Validate file type
            var allowedTypes = $input.data('allowed-types');
            if (allowedTypes && !allowedTypes.includes(file.type)) {
                showNotification('Loại file không được phép', 'error');
                $input.val('');
                return;
            }
            
            // Validate file size
            var maxSize = $input.data('max-size');
            if (maxSize && file.size > maxSize) {
                showNotification('File quá lớn', 'error');
                $input.val('');
                return;
            }
            
            // Show preview
            if (file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $preview.html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">');
                };
                reader.readAsDataURL(file);
            } else {
                $preview.html('<div class="text-muted"><i class="fas fa-file me-2"></i>' + file.name + '</div>');
            }
        } else {
            $preview.empty();
        }
    });
}

/**
 * Utility functions
 */

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

// Format date
function formatDate(date, format) {
    format = format || 'dd/mm/yyyy';
    var d = new Date(date);
    var day = ('0' + d.getDate()).slice(-2);
    var month = ('0' + (d.getMonth() + 1)).slice(-2);
    var year = d.getFullYear();
    
    return format
        .replace('dd', day)
        .replace('mm', month)
        .replace('yyyy', year);
}

// Format datetime
function formatDateTime(date, format) {
    format = format || 'dd/mm/yyyy hh:ii';
    var d = new Date(date);
    var day = ('0' + d.getDate()).slice(-2);
    var month = ('0' + (d.getMonth() + 1)).slice(-2);
    var year = d.getFullYear();
    var hours = ('0' + d.getHours()).slice(-2);
    var minutes = ('0' + d.getMinutes()).slice(-2);
    
    return format
        .replace('dd', day)
        .replace('mm', month)
        .replace('yyyy', year)
        .replace('hh', hours)
        .replace('ii', minutes);
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

// Throttle function
function throttle(func, limit) {
    var inThrottle;
    return function() {
        var args = arguments;
        var context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(function() {
                inThrottle = false;
            }, limit);
        }
    };
}

// Export functions for global use
window.OfficeManagement = {
    showNotification: showNotification,
    showConfirmModal: showConfirmModal,
    formatCurrency: formatCurrency,
    formatDate: formatDate,
    formatDateTime: formatDateTime,
    debounce: debounce,
    throttle: throttle
};

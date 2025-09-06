/**
 * Calendar JavaScript
 * Xử lý các chức năng calendar trong hệ thống
 */

$(document).ready(function() {
    'use strict';
    
    // Initialize calendar if element exists
    if ($('#calendar').length) {
        initializeCalendar();
    }
    
    // Initialize date pickers
    initializeDatePickers();
    
    // Initialize time pickers
    initializeTimePickers();
});

/**
 * Initialize FullCalendar
 */
function initializeCalendar() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        locale: 'vi',
        buttonText: {
            today: 'Hôm nay',
            month: 'Tháng',
            week: 'Tuần',
            day: 'Ngày',
            list: 'Danh sách'
        },
        events: {
            url: '/office_management/public/api/calendar/events',
            method: 'GET',
            failure: function() {
                showNotification('Không thể tải dữ liệu lịch', 'error');
            }
        },
        eventClick: function(info) {
            showEventDetails(info.event);
        },
        dateClick: function(info) {
            if (info.view.type === 'dayGridMonth') {
                calendar.changeView('timeGridDay', info.dateStr);
            }
        },
        eventDidMount: function(info) {
            // Add tooltip to events
            $(info.el).tooltip({
                title: info.event.title,
                placement: 'top',
                trigger: 'hover'
            });
        },
        height: 'auto',
        contentHeight: 600,
        nowIndicator: true,
        selectable: true,
        selectMirror: true,
        select: function(info) {
            showCreateEventModal(info.start, info.end);
        },
        editable: true,
        eventDrop: function(info) {
            updateEvent(info.event);
        },
        eventResize: function(info) {
            updateEvent(info.event);
        },
        eventChange: function(info) {
            updateEvent(info.event);
        }
    });
    
    calendar.render();
    
    // Store calendar instance globally
    window.calendar = calendar;
}

/**
 * Initialize date pickers
 */
function initializeDatePickers() {
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'vi',
        weekStart: 1,
        daysOfWeekHighlighted: [0, 6],
        datesDisabled: getDisabledDates()
    });
    
    $('.datepicker-range').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'vi',
        weekStart: 1,
        datesDisabled: getDisabledDates()
    });
}

/**
 * Initialize time pickers
 */
function initializeTimePickers() {
    $('.timepicker').timepicker({
        timeFormat: 'HH:mm',
        interval: 15,
        minTime: '08:00',
        maxTime: '18:00',
        defaultTime: '09:00',
        startTime: '08:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
}

/**
 * Get disabled dates for datepicker
 */
function getDisabledDates() {
    // This would typically fetch from server
    // For now, return empty array
    return [];
}

/**
 * Show event details modal
 */
function showEventDetails(event) {
    var modalHtml = '<div class="modal fade" id="eventDetailsModal" tabindex="-1">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<h5 class="modal-title">Chi tiết sự kiện</h5>' +
        '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>' +
        '</div>' +
        '<div class="modal-body">' +
        '<div class="row">' +
        '<div class="col-md-6">' +
        '<strong>Tiêu đề:</strong><br>' +
        '<span>' + event.title + '</span>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<strong>Loại:</strong><br>' +
        '<span class="badge bg-' + getEventTypeColor(event.extendedProps.type) + '">' +
        getEventTypeName(event.extendedProps.type) +
        '</span>' +
        '</div>' +
        '</div>' +
        '<div class="row mt-3">' +
        '<div class="col-md-6">' +
        '<strong>Bắt đầu:</strong><br>' +
        '<span>' + formatDateTime(event.start) + '</span>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<strong>Kết thúc:</strong><br>' +
        '<span>' + formatDateTime(event.end) + '</span>' +
        '</div>' +
        '</div>';
    
    if (event.extendedProps.description) {
        modalHtml += '<div class="row mt-3">' +
            '<div class="col-12">' +
            '<strong>Mô tả:</strong><br>' +
            '<span>' + event.extendedProps.description + '</span>' +
            '</div>' +
            '</div>';
    }
    
    modalHtml += '</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>' +
        '<button type="button" class="btn btn-primary" onclick="editEvent(\'' + event.id + '\')">Chỉnh sửa</button>' +
        '<button type="button" class="btn btn-danger" onclick="deleteEvent(\'' + event.id + '\')">Xóa</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
    
    // Remove existing modal
    $('#eventDetailsModal').remove();
    
    // Add new modal
    $('body').append(modalHtml);
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
    modal.show();
}

/**
 * Show create event modal
 */
function showCreateEventModal(start, end) {
    var modalHtml = '<div class="modal fade" id="createEventModal" tabindex="-1">' +
        '<div class="modal-dialog modal-lg">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<h5 class="modal-title">Tạo sự kiện mới</h5>' +
        '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>' +
        '</div>' +
        '<form id="createEventForm">' +
        '<div class="modal-body">' +
        '<div class="row">' +
        '<div class="col-md-6">' +
        '<div class="mb-3">' +
        '<label for="eventTitle" class="form-label">Tiêu đề *</label>' +
        '<input type="text" class="form-control" id="eventTitle" name="title" required>' +
        '</div>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<div class="mb-3">' +
        '<label for="eventType" class="form-label">Loại sự kiện *</label>' +
        '<select class="form-select" id="eventType" name="type" required>' +
        '<option value="">Chọn loại sự kiện</option>' +
        '<option value="leave">Nghỉ phép</option>' +
        '<option value="meeting">Họp</option>' +
        '<option value="training">Đào tạo</option>' +
        '<option value="event">Sự kiện khác</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-md-6">' +
        '<div class="mb-3">' +
        '<label for="eventStartDate" class="form-label">Ngày bắt đầu *</label>' +
        '<input type="date" class="form-control" id="eventStartDate" name="start_date" required>' +
        '</div>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<div class="mb-3">' +
        '<label for="eventEndDate" class="form-label">Ngày kết thúc *</label>' +
        '<input type="date" class="form-control" id="eventEndDate" name="end_date" required>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-md-6">' +
        '<div class="mb-3">' +
        '<label for="eventStartTime" class="form-label">Giờ bắt đầu</label>' +
        '<input type="time" class="form-control" id="eventStartTime" name="start_time">' +
        '</div>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<div class="mb-3">' +
        '<label for="eventEndTime" class="form-label">Giờ kết thúc</label>' +
        '<input type="time" class="form-control" id="eventEndTime" name="end_time">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="mb-3">' +
        '<label for="eventDescription" class="form-label">Mô tả</label>' +
        '<textarea class="form-control" id="eventDescription" name="description" rows="3"></textarea>' +
        '</div>' +
        '</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>' +
        '<button type="submit" class="btn btn-primary">Tạo sự kiện</button>' +
        '</div>' +
        '</form>' +
        '</div>' +
        '</div>' +
        '</div>';
    
    // Remove existing modal
    $('#createEventModal').remove();
    
    // Add new modal
    $('body').append(modalHtml);
    
    // Set default values
    if (start) {
        $('#eventStartDate').val(formatDateForInput(start));
        $('#eventStartTime').val(formatTimeForInput(start));
    }
    if (end) {
        $('#eventEndDate').val(formatDateForInput(end));
        $('#eventEndTime').val(formatTimeForInput(end));
    }
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('createEventModal'));
    modal.show();
    
    // Handle form submission
    $('#createEventForm').on('submit', function(e) {
        e.preventDefault();
        createEvent($(this));
    });
}

/**
 * Create new event
 */
function createEvent($form) {
    var formData = $form.serialize();
    
    $.ajax({
        url: '/office_management/public/api/calendar/events',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                showNotification('Tạo sự kiện thành công', 'success');
                $('#createEventModal').modal('hide');
                window.calendar.refetchEvents();
            } else {
                showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        },
        error: function(xhr) {
            showNotification('Có lỗi xảy ra khi tạo sự kiện', 'error');
        }
    });
}

/**
 * Update event
 */
function updateEvent(event) {
    var eventData = {
        id: event.id,
        title: event.title,
        start: event.start.toISOString(),
        end: event.end.toISOString(),
        allDay: event.allDay
    };
    
    $.ajax({
        url: '/office_management/public/api/calendar/events/' + event.id,
        method: 'PUT',
        data: eventData,
        success: function(response) {
            if (response.success) {
                showNotification('Cập nhật sự kiện thành công', 'success');
            } else {
                showNotification(response.message || 'Có lỗi xảy ra', 'error');
                window.calendar.refetchEvents();
            }
        },
        error: function(xhr) {
            showNotification('Có lỗi xảy ra khi cập nhật sự kiện', 'error');
            window.calendar.refetchEvents();
        }
    });
}

/**
 * Delete event
 */
function deleteEvent(eventId) {
    if (confirm('Bạn có chắc chắn muốn xóa sự kiện này?')) {
        $.ajax({
            url: '/office_management/public/api/calendar/events/' + eventId,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    showNotification('Xóa sự kiện thành công', 'success');
                    $('#eventDetailsModal').modal('hide');
                    window.calendar.refetchEvents();
                } else {
                    showNotification(response.message || 'Có lỗi xảy ra', 'error');
                }
            },
            error: function(xhr) {
                showNotification('Có lỗi xảy ra khi xóa sự kiện', 'error');
            }
        });
    }
}

/**
 * Edit event
 */
function editEvent(eventId) {
    // This would open an edit modal similar to create modal
    // For now, just close the details modal
    $('#eventDetailsModal').modal('hide');
    showNotification('Chức năng chỉnh sửa đang được phát triển', 'info');
}

/**
 * Utility functions
 */

// Get event type color
function getEventTypeColor(type) {
    var colors = {
        'leave': 'warning',
        'meeting': 'primary',
        'training': 'info',
        'event': 'secondary'
    };
    return colors[type] || 'secondary';
}

// Get event type name
function getEventTypeName(type) {
    var names = {
        'leave': 'Nghỉ phép',
        'meeting': 'Họp',
        'training': 'Đào tạo',
        'event': 'Sự kiện khác'
    };
    return names[type] || 'Khác';
}

// Format date for input
function formatDateForInput(date) {
    var d = new Date(date);
    var year = d.getFullYear();
    var month = ('0' + (d.getMonth() + 1)).slice(-2);
    var day = ('0' + d.getDate()).slice(-2);
    return year + '-' + month + '-' + day;
}

// Format time for input
function formatTimeForInput(date) {
    var d = new Date(date);
    var hours = ('0' + d.getHours()).slice(-2);
    var minutes = ('0' + d.getMinutes()).slice(-2);
    return hours + ':' + minutes;
}

// Format date time for display
function formatDateTime(date) {
    var d = new Date(date);
    var day = ('0' + d.getDate()).slice(-2);
    var month = ('0' + (d.getMonth() + 1)).slice(-2);
    var year = d.getFullYear();
    var hours = ('0' + d.getHours()).slice(-2);
    var minutes = ('0' + d.getMinutes()).slice(-2);
    
    return day + '/' + month + '/' + year + ' ' + hours + ':' + minutes;
}

// Export functions for global use
window.Calendar = {
    showEventDetails: showEventDetails,
    showCreateEventModal: showCreateEventModal,
    createEvent: createEvent,
    updateEvent: updateEvent,
    deleteEvent: deleteEvent,
    editEvent: editEvent,
    formatDateTime: formatDateTime
};

<?php
/**
 * Room Management - Show View
 * Xem chi tiết phòng họp
 */

$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Chi tiết phòng họp</h1>
                    <p class="text-muted">Thông tin chi tiết và lịch sử đặt phòng</p>
                </div>
                <div>
                    <a href="/rooms" class="btn btn-outline-primary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <a href="/rooms/booking" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Đặt phòng
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Details -->
    <div class="row">
        <!-- Room Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-door-open me-2"></i>Thông tin phòng họp
                    </h5>
                </div>
                <div class="card-body">
                    ' . (isset($room) ? '
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên phòng:</label>
                                <p class="form-control-plaintext">' . htmlspecialchars($room['room_name'] ?? 'N/A') . '</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Loại phòng:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-' . (($room['type'] ?? 'normal') === 'special' ? 'warning' : 'primary') . '">
                                        ' . (($room['type'] ?? 'normal') === 'special' ? 'Đặc biệt' : 'Thường') . '
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Sức chứa:</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-users me-1"></i>' . ($room['capacity'] ?? 'N/A') . ' người
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Vị trí:</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-map-marker-alt me-1"></i>' . htmlspecialchars($room['location'] ?? 'N/A') . '
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-' . (($room['status'] ?? 'available') === 'available' ? 'success' : 'danger') . '">
                                        ' . (($room['status'] ?? 'available') === 'available' ? 'Có sẵn' : 'Không khả dụng') . '
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">ID phòng:</label>
                                <p class="form-control-plaintext text-muted">#' . ($room['room_id'] ?? 'N/A') . '</p>
                            </div>
                        </div>
                    </div>
                    ' : '
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Không tìm thấy thông tin phòng họp
                    </div>
                    ') . '
                </div>
            </div>
        </div>

        <!-- Room Statistics -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Thống kê
                    </h5>
                </div>
                <div class="card-body">
                    ' . (isset($roomStats) ? '
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-primary mb-1">' . ($roomStats['total_bookings'] ?? 0) . '</h3>
                                <small class="text-muted">Tổng đặt phòng</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-success mb-1">' . ($roomStats['approved_bookings'] ?? 0) . '</h3>
                                <small class="text-muted">Đã duyệt</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-warning mb-1">' . ($roomStats['pending_bookings'] ?? 0) . '</h3>
                                <small class="text-muted">Chờ duyệt</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-danger mb-1">' . ($roomStats['rejected_bookings'] ?? 0) . '</h3>
                                <small class="text-muted">Bị từ chối</small>
                            </div>
                        </div>
                    </div>
                    ' : '
                    <div class="text-center text-muted">
                        <i class="fas fa-chart-bar fa-2x mb-2"></i>
                        <p>Chưa có dữ liệu thống kê</p>
                    </div>
                    ') . '
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Lịch sử đặt phòng gần đây
                    </h5>
                </div>
                <div class="card-body">
                    ' . (isset($recentBookings) && !empty($recentBookings) ? '
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Người đặt</th>
                                    <th>Ngày đặt</th>
                                    <th>Thời gian</th>
                                    <th>Mục đích</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($booking) {
                                    $statusClass = '';
                                    $statusText = '';
                                    switch($booking['status']) {
                                        case 'approved':
                                            $statusClass = 'bg-success';
                                            $statusText = 'Đã duyệt';
                                            break;
                                        case 'pending':
                                            $statusClass = 'bg-warning';
                                            $statusText = 'Chờ duyệt';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'bg-danger';
                                            $statusText = 'Bị từ chối';
                                            break;
                                        default:
                                            $statusClass = 'bg-secondary';
                                            $statusText = 'Không xác định';
                                    }
                                    
                                    return '
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user text-info me-2"></i>
                                                <div>
                                                    <strong>' . htmlspecialchars($booking['fullname'] ?? 'N/A') . '</strong>
                                                    <br><small class="text-muted">' . htmlspecialchars($booking['department'] ?? 'N/A') . '</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">' . date('d/m/Y', strtotime($booking['booking_date'])) . '</span>
                                        </td>
                                        <td>
                                            <span class="text-primary">' . date('H:i', strtotime($booking['start_time'])) . '</span>
                                            <span class="text-muted">-</span>
                                            <span class="text-primary">' . date('H:i', strtotime($booking['end_time'])) . '</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">' . htmlspecialchars($booking['purpose'] ?? 'N/A') . '</span>
                                        </td>
                                        <td>
                                            <span class="badge ' . $statusClass . '">' . $statusText . '</span>
                                        </td>
                                        <td>
                                            <a href="/rooms/booking/' . $booking['booking_id'] . '" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    ';
                                }, $recentBookings)) . '
                            </tbody>
                        </table>
                    </div>
                    ' : '
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có lịch sử đặt phòng</h5>
                        <p class="text-muted">Phòng họp này chưa được đặt lần nào</p>
                    </div>
                    ') . '
                </div>
            </div>
        </div>
    </div>

    <!-- Room Calendar Preview -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar me-2"></i>Lịch phòng họp (7 ngày tới)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 120px;">Thời gian</th>
                                    <th>Thứ 2</th>
                                    <th>Thứ 3</th>
                                    <th>Thứ 4</th>
                                    <th>Thứ 5</th>
                                    <th>Thứ 6</th>
                                    <th>Thứ 7</th>
                                    <th>Chủ nhật</th>
                                </tr>
                            </thead>
                            <tbody id="roomCalendarBody">
                                <!-- Calendar content will be generated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.room-calendar-cell {
    height: 40px;
    vertical-align: top;
    padding: 2px;
    position: relative;
}

.room-booking-item {
    font-size: 0.7rem;
    padding: 1px 3px;
    margin: 1px 0;
    border-radius: 2px;
    cursor: pointer;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.room-booking-item:hover {
    opacity: 0.8;
}

.room-time-slot {
    font-size: 0.8rem;
    font-weight: 500;
    color: #6c757d;
    text-align: center;
    padding: 4px 2px;
}

#roomCalendarBody th {
    text-align: center;
    font-weight: 600;
    background-color: #f8f9fa;
}

.room-calendar-cell:hover {
    background-color: #f8f9fa;
}
</style>

<script>
$(document).ready(function() {
    // Generate room calendar for next 7 days
    generateRoomCalendar();
    
    function generateRoomCalendar() {
        const today = new Date();
        const next7Days = [];
        
        // Get next 7 days
        for (let i = 0; i < 7; i++) {
            const date = new Date(today);
            date.setDate(today.getDate() + i);
            next7Days.push(date);
        }
        
        // Generate time slots (8:00 - 18:00)
        let timeSlots = [];
        for (let hour = 8; hour <= 18; hour++) {
            timeSlots.push(`${hour.toString().padStart(2, "0")}:00`);
        }
        
        // Generate calendar body
        let calendarHTML = "";
        
        timeSlots.forEach(time => {
            calendarHTML += "<tr>";
            calendarHTML += `<td class="room-time-slot">${time}</td>`;
            
            // Generate cells for each day
            next7Days.forEach(date => {
                const dateStr = date.toISOString().split("T")[0];
                calendarHTML += `<td class="room-calendar-cell" data-date="${dateStr}" data-time="${time}">`;
                
                // Add booking items for this time slot
                const bookings = getRoomBookingsForTimeSlot(date, time);
                bookings.forEach(booking => {
                    const statusClass = getBookingStatusClass(booking.status);
                    calendarHTML += `
                        <div class="room-booking-item ${statusClass}" 
                             title="${booking.fullname} - ${booking.purpose}"
                             data-booking-id="${booking.booking_id}">
                            ${booking.fullname}
                        </div>
                    `;
                });
                
                calendarHTML += "</td>";
            });
            
            calendarHTML += "</tr>";
        });
        
        $("#roomCalendarBody").html(calendarHTML);
    }
    
    function getRoomBookingsForTimeSlot(date, time) {
        const bookings = ' . json_encode($roomBookings ?? []) . ';
        
        return bookings.filter(booking => {
            const bookingDate = new Date(booking.booking_date);
            const bookingTime = booking.start_time.substring(0, 5);
            
            const dateStr = date.toISOString().split("T")[0];
            const bookingDateStr = bookingDate.toISOString().split("T")[0];
            
            return dateStr === bookingDateStr && bookingTime === time;
        });
    }
    
    function getBookingStatusClass(status) {
        switch(status) {
            case "approved": return "bg-success text-white";
            case "pending": return "bg-warning text-dark";
            case "rejected": return "bg-danger text-white";
            default: return "bg-secondary text-white";
        }
    }
});
</script>
';

// Include main layout
include VIEWS_PATH . '/layouts/main.php';
?>

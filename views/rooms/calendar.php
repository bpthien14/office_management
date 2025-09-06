<?php
/**
 * Room Management - Calendar View
 * Lịch phòng họp
 */

$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Lịch phòng họp</h1>
                    <p class="text-muted">Xem lịch đặt phòng họp theo tuần</p>
                </div>
                <div>
                    <a href="/rooms/booking" class="btn btn-primary me-2">
                        <i class="fas fa-plus me-2"></i>Đặt phòng họp
                    </a>
                    <a href="/rooms" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Calendar Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-outline-secondary me-2" id="prevWeek">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <h4 class="mb-0 me-3" id="currentWeek">Tuần hiện tại</h4>
                            <button class="btn btn-outline-secondary" id="nextWeek">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary" id="todayBtn">
                                <i class="fas fa-calendar-day me-1"></i>Hôm nay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Calendar Grid -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" id="calendarTable">
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
                            <tbody id="calendarBody">
                                <tr>
                                    <td class="time-slot">08:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="08:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="08:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="08:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="08:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="08:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="08:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="08:00"></td>
                                </tr>
                                <tr>
                                    <td class="time-slot">09:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="09:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="09:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="09:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="09:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="09:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="09:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="09:00"></td>
                                </tr>
                                <tr>
                                    <td class="time-slot">10:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="10:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="10:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="10:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="10:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="10:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="10:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="10:00"></td>
                                </tr>
                                <tr>
                                    <td class="time-slot">11:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="11:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="11:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="11:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="11:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="11:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="11:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="11:00"></td>
                                </tr>
                                <tr>
                                    <td class="time-slot">12:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="12:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="12:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="12:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="12:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="12:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="12:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="12:00"></td>
                                </tr>
                                <tr>
                                    <td class="time-slot">13:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="13:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="13:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="13:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="13:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="13:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="13:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="13:00"></td>
                                </tr>
                                <tr>
                                    <td class="time-slot">14:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="14:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="14:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="14:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="14:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="14:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="14:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="14:00"></td>
                                </tr>
                                <tr>
                                    <td class="time-slot">15:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="15:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="15:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="15:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="15:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="15:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="15:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="15:00"></td>
                                </tr>
                                <tr>
                                    <td class="time-slot">16:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="16:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="16:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="16:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="16:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="16:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="16:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="16:00"></td>
                                </tr>
                                <tr>
                                    <td class="time-slot">17:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="17:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="17:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="17:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="17:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="17:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="17:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="17:00"></td>
                                </tr>
                                <tr>
                                    <td class="time-slot">18:00</td>
                                    <td class="calendar-cell" data-date="2025-09-02" data-time="18:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-03" data-time="18:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-04" data-time="18:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-05" data-time="18:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-06" data-time="18:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-07" data-time="18:00"></td>
                                    <td class="calendar-cell" data-date="2025-09-08" data-time="18:00"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Legend -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Chú thích:</h6>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <div class="badge bg-success me-2" style="width: 20px; height: 20px;"></div>
                            <span>Đã duyệt</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-warning me-2" style="width: 20px; height: 20px;"></div>
                            <span>Chờ duyệt</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-danger me-2" style="width: 20px; height: 20px;"></div>
                            <span>Bị từ chối</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-secondary me-2" style="width: 20px; height: 20px;"></div>
                            <span>Trống</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.calendar-cell {
    height: 60px;
    vertical-align: top;
    padding: 2px;
    position: relative;
}

.booking-item {
    font-size: 0.75rem;
    padding: 2px 4px;
    margin: 1px 0;
    border-radius: 3px;
    cursor: pointer;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.booking-item:hover {
    opacity: 0.8;
}

.time-slot {
    font-size: 0.8rem;
    font-weight: 500;
    color: #6c757d;
    text-align: center;
    padding: 8px 4px;
}

.room-name {
    font-weight: 600;
    color: #495057;
}

.booking-purpose {
    font-size: 0.7rem;
    color: #6c757d;
    margin-top: 2px;
}

#calendarTable th {
    text-align: center;
    font-weight: 600;
    background-color: #f8f9fa;
}

.calendar-cell:hover {
    background-color: #f8f9fa;
}
</style>

<script>
$(document).ready(function() {
    let currentWeek = new Date();
    
    // Initialize calendar
    console.log("Document ready, initializing calendar");
    updateCalendar();
    
    // Test with sample data
    setTimeout(populateSampleData, 90000);
    
    // Event handlers
    $("#prevWeek").click(function() {
        currentWeek.setDate(currentWeek.getDate() - 7);
        updateCalendar();
    });
    
    $("#nextWeek").click(function() {
        currentWeek.setDate(currentWeek.getDate() + 7);
        updateCalendar();
    });
    
    $("#todayBtn").click(function() {
        currentWeek = new Date();
        updateCalendar();
    });
    
    function updateCalendar() {
        console.log("updateCalendar called");
        const weekStart = getWeekStart(currentWeek);
        const weekEnd = getWeekEnd(currentWeek);
        
        $("#currentWeek").text(`Tuần ${formatDate(weekStart)} - ${formatDate(weekEnd)}`);
        
        // Clear existing calendar content
        $("#calendarBody").empty();
        
        // Generate time slots (8:00 - 18:00)
        let timeSlots = [];
        for (let hour = 8; hour <= 18; hour++) {
            timeSlots.push(`${hour.toString().padStart(2, "0")}:00`);
        }
        
        // Generate calendar body
        timeSlots.forEach(time => {
            let rowHTML = "<tr>";
            rowHTML += `<td class="time-slot">${time}</td>`;
            
            // Generate cells for each day of the week
            for (let day = 0; day < 7; day++) {
                const cellDate = new Date(weekStart);
                cellDate.setDate(weekStart.getDate() + day);
                
                rowHTML += `<td class="calendar-cell" data-date="${formatDate(cellDate)}" data-time="${time}">`;
                
                // Add booking items for this time slot
                const bookings = getBookingsForTimeSlot(cellDate, time);
                console.log(`Time: ${time}, Date: ${formatDate(cellDate)}, Bookings:`, bookings);
                bookings.forEach(booking => {
                    const statusClass = getStatusClass(booking.status);
                    rowHTML += `
                        <div class="booking-item ${statusClass}" 
                             title="${booking.room_name} - ${booking.fullname} (${booking.purpose})"
                             data-booking-id="${booking.booking_id}">
                            <div class="room-name">${booking.room_name}</div>
                            <div class="booking-purpose">${booking.fullname}</div>
                        </div>
                    `;
                });
                
                rowHTML += "</td>";
            }
            
            rowHTML += "</tr>";
            $("#calendarBody").append(rowHTML);
        });
        
        console.log("Calendar updated");
    }
    
    function getWeekStart(date) {
        const d = new Date(date);
        const day = d.getDay();
        const diff = d.getDate() - day + (day === 0 ? -6 : 1); // Adjust when day is Sunday
        return new Date(d.setDate(diff));
    }
    
    function getWeekEnd(date) {
        const weekStart = getWeekStart(date);
        const weekEnd = new Date(weekStart);
        weekEnd.setDate(weekStart.getDate() + 6);
        return weekEnd;
    }
    
    function formatDate(date) {
        return date.toLocaleDateString("vi-VN", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric"
        });
    }
    
    function getStatusClass(status) {
        switch(status) {
            case "approved": return "bg-success text-white";
            case "pending": return "bg-warning text-dark";
            case "rejected": return "bg-danger text-white";
            default: return "bg-secondary text-white";
        }
    }
    
    function getBookingsForTimeSlot(date, time) {
        // Get bookings from PHP data
        const bookings = ' . json_encode($bookings ?? []) . ';
        
        return bookings.filter(booking => {
            const bookingDate = new Date(booking.booking_date);
            const bookingTime = booking.start_time.substring(0, 5);
            
            // Compare dates by YYYY-MM-DD format
            const dateStr = date.toISOString().split("T")[0];
            const bookingDateStr = bookingDate.toISOString().split("T")[0];
            
            console.log(`Comparing: ${dateStr} vs ${bookingDateStr}, ${time} vs ${bookingTime}`);
            
            return dateStr === bookingDateStr && bookingTime === time;
        });
    }
    
    // Simple function to add booking to specific cell
    function addBookingToCell(dateStr, timeStr, booking) {
        const cell = $(`[data-date="${dateStr}"][data-time="${timeStr}"]`);
        if (cell.length > 0) {
            const statusClass = getStatusClass(booking.status);
            const bookingHTML = `
                <div class="booking-item ${statusClass}" 
                     title="${booking.room_name} - ${booking.fullname} (${booking.purpose})"
                     data-booking-id="${booking.booking_id}">
                    <div class="room-name">${booking.room_name}</div>
                    <div class="booking-purpose">${booking.fullname}</div>
                </div>
            `;
            cell.html(bookingHTML);
            console.log(`Added booking to ${dateStr} ${timeStr}`);
        }
    }
    
    // Test function to populate calendar with sample data
    function populateSampleData() {
        console.log("Populating sample data");
        const bookings = ' . json_encode($bookings ?? []) . ';
        console.log("Available bookings:", bookings);
        
        // Add bookings to their respective time slots
        bookings.forEach(booking => {
            const bookingDate = new Date(booking.booking_date);
            const dateStr = bookingDate.toISOString().split("T")[0];
            const timeStr = booking.start_time.substring(0, 5);
            
            console.log(`Adding booking to ${dateStr} ${timeStr}`);
            addBookingToCell(dateStr, timeStr, booking);
        });
    }
    
});
</script>
';

// Include main layout
include VIEWS_PATH . '/layouts/main.php';
?>

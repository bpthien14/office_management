<?php
/**
 * Room Management - Booking View
 * Đặt phòng họp
 */

$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Đặt phòng họp</h1>
                    <p class="text-muted">Đăng ký sử dụng phòng họp</p>
                </div>
                <div>
                    <a href="/rooms/approve" class="btn btn-warning me-2">
                        <i class="fas fa-clipboard-check me-2"></i>Duyệt đặt phòng họp
                    </a>
                    <a href="/rooms" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Booking Form -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Đăng ký đặt phòng
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/rooms/1/book">
                        <input type="hidden" name="_token" value="' . Session::get('csrf_token') . '">
                        
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Người đặt</label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                <option value="">Chọn nhân viên</option>
                                ' . implode('', array_map(function($employee) {
                                    return '<option value="' . $employee['employee_id'] . '">' . htmlspecialchars($employee['fullname']) . ' - ' . htmlspecialchars($employee['department']) . '</option>';
                                }, $employees)) . '
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="room_id" class="form-label">Phòng họp</label>
                            <select name="room_id" id="room_id" class="form-select" required>
                                <option value="">Chọn phòng họp</option>
                                ' . implode('', array_map(function($room) {
                                    return '<option value="' . $room['room_id'] . '">' . htmlspecialchars($room['room_name']) . ' (' . $room['capacity'] . ' người) - ' . htmlspecialchars($room['location']) . '</option>';
                                }, $rooms)) . '
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="booking_date" class="form-label">Ngày đặt</label>
                            <input type="date" name="booking_date" id="booking_date" class="form-control" value="' . date('Y-m-d') . '" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Giờ bắt đầu</label>
                                    <input type="time" name="start_time" id="start_time" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">Giờ kết thúc</label>
                                    <input type="time" name="end_time" id="end_time" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Mục đích sử dụng</label>
                            <textarea name="purpose" id="purpose" class="form-control" rows="3" placeholder="Mô tả mục đích sử dụng phòng họp..." required></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Đặt phòng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Booking List -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Danh sách đặt phòng
                    </h5>
                </div>
                <div class="card-body">
                    ' . (empty($bookings) ? '
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có đặt phòng nào</p>
                    </div>
                    ' : '
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Phòng</th>
                                    <th>Người đặt</th>
                                    <th>Ngày</th>
                                    <th>Giờ</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($booking) {
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'confirmed' => 'success',
                                        'cancelled' => 'danger'
                                    ][$booking['status']] ?? 'secondary';
                                    
                                    $statusText = [
                                        'pending' => 'Chờ duyệt',
                                        'confirmed' => 'Đã xác nhận',
                                        'cancelled' => 'Đã hủy'
                                    ][$booking['status']] ?? $booking['status'];
                                    
                                    return '
                                    <tr>
                                        <td>' . htmlspecialchars($booking['room_name']) . '</td>
                                        <td>' . htmlspecialchars($booking['fullname']) . '</td>
                                        <td>' . date('d/m/Y', strtotime($booking['booking_date'])) . '</td>
                                        <td>' . $booking['start_time'] . ' - ' . $booking['end_time'] . '</td>
                                        <td><span class="badge bg-' . $statusClass . '">' . $statusText . '</span></td>
                                    </tr>
                                    ';
                                }, $bookings)) . '
                            </tbody>
                        </table>
                    </div>
                    ') . '
                </div>
            </div>
        </div>
    </div>
</div>
';

// Include main layout
include VIEWS_PATH . '/layouts/main.php';
?>

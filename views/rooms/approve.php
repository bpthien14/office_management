<?php
/**
 * Room Management - Approve View
 * Duyệt đặt phòng họp
 */

$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Duyệt đặt phòng họp</h1>
                    <p class="text-muted">Quản lý các yêu cầu đặt phòng họp chờ duyệt</p>
                </div>
                <div>
                    <a href="/rooms/booking" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Bookings -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Đơn đặt phòng chờ duyệt
                    </h5>
                </div>
                <div class="card-body">
                    ' . (empty($pending_bookings) ? '
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-muted">Không có đơn đặt phòng nào chờ duyệt</h5>
                        <p class="text-muted">Tất cả đơn đặt phòng đã được xử lý</p>
                    </div>
                    ' : '
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Phòng họp</th>
                                    <th>Người đặt</th>
                                    <th>Ngày đặt</th>
                                    <th>Thời gian</th>
                                    <th>Mục đích</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($booking) {
                                    return '
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-door-open text-primary me-2"></i>
                                                <div>
                                                    <strong>' . htmlspecialchars($booking['room_name'] ?? 'N/A') . '</strong>
                                                    <br><small class="text-muted">' . htmlspecialchars($booking['department'] ?? 'N/A') . '</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user text-info me-2"></i>
                                                <div>
                                                    <strong>' . htmlspecialchars($booking['fullname'] ?? 'N/A') . '</strong>
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
                                            <div class="btn-group" role="group">
                                                <form method="POST" action="/rooms/' . $booking['booking_id'] . '/approve" class="d-inline">
                                                    <input type="hidden" name="_token" value="' . Session::get('csrf_token') . '">
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                            onclick="return confirm(\'Bạn có chắc chắn muốn duyệt đặt phòng này?\')">
                                                        <i class="fas fa-check me-1"></i>Duyệt
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal' . $booking['booking_id'] . '">
                                                    <i class="fas fa-times me-1"></i>Từ chối
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal' . $booking['booking_id'] . '" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Từ chối đặt phòng họp</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="/rooms/' . $booking['booking_id'] . '/reject">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="_token" value="' . Session::get('csrf_token') . '">
                                                        <div class="mb-3">
                                                            <label for="rejection_reason" class="form-label">Lý do từ chối</label>
                                                            <textarea name="rejection_reason" id="rejection_reason" 
                                                                      class="form-control" rows="3" 
                                                                      placeholder="Nhập lý do từ chối đặt phòng họp..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-danger">Từ chối</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    ';
                                }, $pending_bookings)) . '
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

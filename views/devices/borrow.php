<?php
/**
 * Device Management - Borrow View
 * Mượn thiết bị
 */

$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Mượn thiết bị</h1>
                    <p class="text-muted">Đăng ký mượn thiết bị văn phòng</p>
                </div>
                <div>
                    <a href="/devices/approve" class="btn btn-warning me-2">
                        <i class="fas fa-clipboard-check me-2"></i>Duyệt mượn thiết bị
                    </a>
                    <a href="/devices" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Borrow Form -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-hand-holding me-2"></i>Đăng ký mượn thiết bị
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/devices/1/borrow">
                        <input type="hidden" name="_token" value="' . Session::get('csrf_token') . '">
                        
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Nhân viên</label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                <option value="">Chọn nhân viên</option>
                                ' . implode('', array_map(function($employee) {
                                    return '<option value="' . $employee['employee_id'] . '">' . htmlspecialchars($employee['fullname']) . ' - ' . htmlspecialchars($employee['department']) . '</option>';
                                }, $employees)) . '
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="device_id" class="form-label">Thiết bị</label>
                            <select name="device_id" id="device_id" class="form-select" required>
                                <option value="">Chọn thiết bị</option>
                                ' . implode('', array_map(function($device) {
                                    return '<option value="' . $device['device_id'] . '">' . htmlspecialchars($device['device_name']) . ' (Còn: ' . $device['quantity'] . ')</option>';
                                }, $devices)) . '
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="borrow_date" class="form-label">Ngày mượn</label>
                            <input type="date" name="borrow_date" id="borrow_date" class="form-control" value="' . date('Y-m-d') . '" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="expected_return_date" class="form-label">Ngày trả dự kiến</label>
                            <input type="date" name="expected_return_date" id="expected_return_date" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Mục đích sử dụng</label>
                            <textarea name="purpose" id="purpose" class="form-control" rows="3" placeholder="Mô tả mục đích sử dụng thiết bị..." required></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-hand-holding me-2"></i>Đăng ký mượn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Borrow History -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Lịch sử mượn thiết bị
                    </h5>
                </div>
                <div class="card-body">
                    ' . (empty($borrow_history) ? '
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có lịch sử mượn thiết bị</p>
                    </div>
                    ' : '
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Thiết bị</th>
                                    <th>Nhân viên</th>
                                    <th>Ngày mượn</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($borrow) {
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'borrowed' => 'info',
                                        'returned' => 'success',
                                        'overdue' => 'danger'
                                    ][$borrow['status']] ?? 'secondary';
                                    
                                    $statusText = [
                                        'pending' => 'Chờ duyệt',
                                        'approved' => 'Đã duyệt',
                                        'rejected' => 'Bị từ chối',
                                        'borrowed' => 'Đang mượn',
                                        'returned' => 'Đã trả',
                                        'overdue' => 'Quá hạn'
                                    ][$borrow['status']] ?? $borrow['status'];
                                    
                                    return '
                                    <tr>
                                        <td>' . htmlspecialchars($borrow['device_name'] ?? 'N/A') . '</td>
                                        <td>' . htmlspecialchars($borrow['fullname'] ?? 'N/A') . '</td>
                                        <td>' . date('d/m/Y', strtotime($borrow['borrow_date'])) . '</td>
                                        <td><span class="badge bg-' . $statusClass . '">' . $statusText . '</span></td>
                                    </tr>
                                    ';
                                }, $borrow_history)) . '
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

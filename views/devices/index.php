<?php
/**
 * Device Management - Index View
 * Danh sách thiết bị
 */

$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Quản lý thiết bị</h1>
                    <p class="text-muted">Danh sách thiết bị văn phòng</p>
                </div>
                <div>
                    <a href="/devices/borrow" class="btn btn-primary me-2">
                        <i class="fas fa-hand-holding me-2"></i>Mượn thiết bị
                    </a>
                    <a href="/devices/return" class="btn btn-success">
                        <i class="fas fa-undo me-2"></i>Trả thiết bị
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thiết bị..." value="' . htmlspecialchars($search_term ?? '') . '">
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-1"></i>Tìm kiếm
                                </button>
                                <a href="/devices" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Xóa bộ lọc
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Devices Grid -->
    <div class="row">
        ' . (empty($devices) ? '
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-laptop fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có thiết bị nào</h5>
                <p class="text-muted">Thiết bị sẽ được hiển thị ở đây</p>
            </div>
        </div>
        ' : implode('', array_map(function($device) {
            return '
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-laptop fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title mb-0">' . htmlspecialchars($device['device_name']) . '</h6>
                                <small class="text-muted">Tổng: ' . $device['quantity'] . ' | Còn lại: ' . $device['available_count'] . '</small>
                            </div>
                        </div>
                        <p class="card-text text-muted small">' . htmlspecialchars($device['description']) . '</p>
                        <div class="d-flex justify-content-between align-items-center">
                            ' . ($device['is_available'] ? 
                                '<span class="badge bg-success">Có sẵn</span>' : 
                                '<span class="badge bg-warning">Đang mượn (' . $device['borrowed_count'] . ')</span>'
                            ) . '
                            <a href="/devices/' . $device['device_id'] . '" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Xem
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }, $devices))) . '
    </div>
</div>
';

// Include main layout
include VIEWS_PATH . '/layouts/main.php';
?>
<?php
/**
 * Room Management - Index View
 * Danh sách phòng họp
 */

$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Quản lý phòng họp</h1>
                    <p class="text-muted">Danh sách phòng họp và đặt phòng</p>
                </div>
                <div>
                    <a href="/rooms/booking" class="btn btn-primary me-2">
                        <i class="fas fa-calendar-plus me-2"></i>Đặt phòng
                    </a>
                    <a href="/rooms/calendar" class="btn btn-success">
                        <i class="fas fa-calendar me-2"></i>Lịch phòng
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
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm phòng họp..." value="' . htmlspecialchars($search_term) . '">
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-1"></i>Tìm kiếm
                                </button>
                                <a href="/rooms" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Xóa bộ lọc
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Rooms Grid -->
    <div class="row">
        ' . (empty($rooms) ? '
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có phòng họp nào</h5>
                <p class="text-muted">Phòng họp sẽ được hiển thị ở đây</p>
            </div>
        </div>
        ' : implode('', array_map(function($room) {
            $statusClass = [
                'available' => 'success',
                'unavailable' => 'danger'
            ][$room['status']] ?? 'secondary';
            
            $statusText = [
                'available' => 'Có sẵn',
                'unavailable' => 'Không có sẵn'
            ][$room['status']] ?? $room['status'];
            
            return '
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-door-open fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title mb-0">' . htmlspecialchars($room['room_name']) . '</h6>
                                <small class="text-muted">' . htmlspecialchars($room['location']) . '</small>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Sức chứa</small>
                                <div class="fw-bold">' . $room['capacity'] . ' người</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Loại</small>
                                <div class="fw-bold">' . ucfirst($room['type']) . '</div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-' . $statusClass . '">' . $statusText . '</span>
                            <a href="/rooms/' . $room['room_id'] . '" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Xem
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }, $rooms))) . '
    </div>
</div>
';

// Include main layout
include VIEWS_PATH . '/layouts/main.php';
?>

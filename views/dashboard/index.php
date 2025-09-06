<?php
$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Dashboard</h1>
                    <p class="text-muted">Tổng quan hệ thống quản lý nhân sự</p>
                </div>
                <div>
                    <span class="badge bg-primary fs-6">
                        <i class="fas fa-calendar me-1"></i>
                        ' . date('d/m/Y') . '
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng nhân viên
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ' . number_format($stats["total_employees"] ?? 0) . '
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đơn nghỉ phép
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ' . number_format($stats["total_requests"] ?? 0) . '
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Thiết bị
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ' . number_format($stats["total_devices"] ?? 0) . '
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-laptop fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Phòng họp
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ' . number_format($stats["total_rooms"] ?? 0) . '
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>
                        Hoạt động gần đây
                    </h6>
                </div>
                <div class="card-body">
                    ' . (empty($recent_activities) ? 
                        '<div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Chưa có hoạt động nào</p>
                        </div>' : 
                        '<div class="timeline">' . 
                        implode("", array_map(function($activity) {
                            $iconClass = [
                                "new_employee" => "fas fa-user-plus text-success",
                                "leave_request" => "fas fa-calendar text-info",
                                "device_borrow" => "fas fa-laptop text-warning",
                                "room_booking" => "fas fa-door-open text-primary"
                            ][$activity["type"]] ?? "fas fa-circle text-secondary";
                            
                            $statusBadge = isset($activity["status"]) ? 
                                '<span class="badge bg-' . ($activity["status"] === "approved" ? "success" : ($activity["status"] === "rejected" ? "danger" : "warning")) . ' ms-2">' . 
                                ucfirst($activity["status"]) . '</span>' : "";
                            
                            return '
                            <div class="timeline-item d-flex mb-3">
                                <div class="timeline-marker me-3">
                                    <i class="' . $iconClass . '"></i>
                                </div>
                                <div class="timeline-content flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="mb-1">' . htmlspecialchars($activity["message"]) . $statusBadge . '</p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                ' . date("d/m/Y H:i", strtotime($activity["date"])) . '
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        }, $recent_activities)) . 
                        '</div>') . '
                </div>
            </div>
        </div>
        
        <!-- Upcoming Events -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-check me-2"></i>
                        Sự kiện sắp tới
                    </h6>
                </div>
                <div class="card-body">
                    ' . (empty($upcoming_events) ? 
                        '<div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p>Không có sự kiện nào</p>
                        </div>' : 
                        implode("", array_map(function($event) {
                            $iconClass = [
                                "room_booking" => "fas fa-door-open text-primary",
                                "warranty_expiry" => "fas fa-exclamation-triangle text-warning",
                                "leave_request" => "fas fa-calendar text-info"
                            ][$event["type"]] ?? "fas fa-circle text-secondary";
                            
                            return '
                            <div class="event-item d-flex mb-3 p-2 border rounded">
                                <div class="event-icon me-3">
                                    <i class="' . $iconClass . '"></i>
                                </div>
                                <div class="event-content flex-grow-1">
                                    <h6 class="mb-1">' . htmlspecialchars($event["title"]) . '</h6>
                                    <p class="mb-1 text-muted small">' . htmlspecialchars($event["description"]) . '</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        ' . date("H:i", strtotime($event["time"])) . '
                                    </small>
                                </div>
                            </div>';
                        }, $upcoming_events))) . '
                </div>
            </div>
        </div>
    </div>
    
    <!-- Admin Section -->
    ' . (in_array($user["role"], ["admin", "hr"]) ? '
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>
                        Quản trị hệ thống
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Yêu cầu chờ duyệt</h6>
                            <div class="list-group">
                                <a href="/office_management/public/leaves/approve" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Đơn xin nghỉ phép
                                    </span>
                                    <span class="badge bg-warning rounded-pill">
                                        ' . count($pending_requests["leave_requests"] ?? []) . '
                                    </span>
                                </a>
                                <a href="/office_management/public/rooms/approve" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-door-open me-2"></i>
                                        Đặt phòng họp
                                    </span>
                                    <span class="badge bg-info rounded-pill">
                                        ' . count($pending_requests["room_bookings"] ?? []) . '
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Thống kê nhanh</h6>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <h4 class="text-success">' . ($admin_stats["employee_stats"]["active"] ?? 0) . '</h4>
                                        <small class="text-muted">Nhân viên hoạt động</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <h4 class="text-info">' . ($admin_stats["device_stats"]["available"] ?? 0) . '</h4>
                                        <small class="text-muted">Thiết bị có sẵn</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ' : '') . '
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.timeline-item {
    position: relative;
}

.timeline-marker {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fc;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.event-item:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s;
}
</style>
';

// Include main layout
include VIEWS_PATH . '/layouts/main.php';
?>

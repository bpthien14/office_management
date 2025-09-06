<?php
$content = '
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/devices">Thiết bị</a></li>
                        <li class="breadcrumb-item active">Chi tiết thiết bị</li>
                    </ol>
                </div>
                <h4 class="page-title">Chi tiết thiết bị</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-laptop me-2"></i>
                        Thông tin thiết bị
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tên thiết bị</label>
                            <p class="form-control-plaintext">' . htmlspecialchars($device['device_name'] ?? 'N/A') . '</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Số lượng</label>
                            <p class="form-control-plaintext">' . ($device['quantity'] ?? '0') . ' thiết bị</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả</label>
                        <p class="form-control-plaintext">' . (!empty($device['description'] ?? '') ? htmlspecialchars($device['description']) : 'Không có mô tả') . '</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Thao tác
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/devices" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>
                            Danh sách thiết bị
                        </a>
                        <a href="/devices/borrow" class="btn btn-primary">
                            <i class="fas fa-hand-holding me-2"></i>
                            Mượn thiết bị
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Lịch sử mượn thiết bị
                    </h5>
                </div>
                <div class="card-body">
                    ' . (empty($borrow_history) ? '
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có lịch sử mượn thiết bị</p>
                    </div>
                    ' : '
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nhân viên</th>
                                    <th>Ngày mượn</th>
                                    <th>Ngày trả dự kiến</th>
                                    <th>Ngày trả thực tế</th>
                                    <th>Trạng thái</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($item) {
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'approved' => 'success', 
                                        'rejected' => 'danger',
                                        'returned' => 'info'
                                    ][$item['status']] ?? 'secondary';
                                    
                                    $statusText = [
                                        'pending' => 'Chờ duyệt',
                                        'approved' => 'Đã duyệt',
                                        'rejected' => 'Từ chối',
                                        'returned' => 'Đã trả'
                                    ][$item['status']] ?? 'Không xác định';
                                    
                                    return '
                                    <tr>
                                        <td>' . htmlspecialchars($item['fullname'] ?? 'N/A') . '</td>
                                        <td>' . ($item['borrow_date'] ? date('d/m/Y', strtotime($item['borrow_date'])) : 'N/A') . '</td>
                                        <td>' . ($item['expected_return_date'] ? date('d/m/Y', strtotime($item['expected_return_date'])) : 'N/A') . '</td>
                                        <td>' . ($item['return_date'] ? date('d/m/Y', strtotime($item['return_date'])) : 'Chưa trả') . '</td>
                                        <td><span class="badge bg-' . $statusClass . '">' . $statusText . '</span></td>
                                        <td>' . (!empty($item['note'] ?? '') ? htmlspecialchars($item['note']) : 'Không có ghi chú') . '</td>
                                    </tr>';
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

$additionalJS = '
<script>
$(document).ready(function() {
    // Initialize tooltips
    $(\'[data-bs-toggle="tooltip"]\').tooltip();
});
</script>
';

include VIEWS_PATH . '/layouts/main.php';
?>

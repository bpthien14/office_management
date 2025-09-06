<?php
/**
 * Leave Management - Index View
 * Danh sách đơn nghỉ phép
 */

$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Quản lý nghỉ phép</h1>
                    <p class="text-muted">Danh sách đơn xin nghỉ phép</p>
                </div>
                <div>
                    <a href="/leaves/create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tạo đơn nghỉ
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="pending"' . ($status_filter === 'pending' ? ' selected' : '') . '>Chờ duyệt</option>
                                <option value="approved"' . ($status_filter === 'approved' ? ' selected' : '') . '>Đã duyệt</option>
                                <option value="rejected"' . ($status_filter === 'rejected' ? ' selected' : '') . '>Từ chối</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tìm kiếm</label>
                            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên nhân viên..." value="' . htmlspecialchars($search_term) . '">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-1"></i>Tìm kiếm
                                </button>
                                <a href="/leaves" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Xóa bộ lọc
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Leave Requests Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Danh sách đơn nghỉ phép
                    </h5>
                </div>
                <div class="card-body">
                    ' . (empty($leaves) ? '
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có đơn nghỉ phép nào</h5>
                        <p class="text-muted">Bắt đầu tạo đơn nghỉ phép đầu tiên</p>
                        <a href="/leaves/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tạo đơn nghỉ
                        </a>
                    </div>
                    ' : '
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nhân viên</th>
                                    <th>Loại nghỉ</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Số ngày</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($leave) {
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    ][$leave['status']] ?? 'secondary';
                                    
                                    $statusText = [
                                        'pending' => 'Chờ duyệt',
                                        'approved' => 'Đã duyệt',
                                        'rejected' => 'Từ chối'
                                    ][$leave['status']] ?? $leave['status'];
                                    
                                    return '
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    ' . substr($leave['fullname'], 0, 1) . '
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">' . htmlspecialchars($leave['fullname']) . '</h6>
                                                    <small class="text-muted">' . htmlspecialchars($leave['department']) . '</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>' . htmlspecialchars($leave['leave_type']) . '</td>
                                        <td>' . date('d/m/Y', strtotime($leave['start_date'])) . '</td>
                                        <td>' . date('d/m/Y', strtotime($leave['end_date'])) . '</td>
                                        <td>' . $leave['total'] . ' ngày</td>
                                        <td>
                                            <span class="badge bg-' . $statusClass . '">' . $statusText . '</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="/leaves/' . $leave['leave_id'] . '" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="/leaves/' . $leave['leave_id'] . '/edit" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    ';
                                }, $leaves)) . '
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

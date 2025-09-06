<?php
$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Chi tiết đơn nghỉ phép</h1>
                    <p class="text-muted">Thông tin chi tiết đơn nghỉ phép #' . $leave['leave_id'] . '</p>
                </div>
                <div>
                    <a href="/leaves" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Leave Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Thông tin đơn nghỉ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Mã đơn nghỉ</label>
                            <p class="form-control-plaintext">#' . $leave['leave_id'] . '</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <p>
                                <span class="badge bg-' . (['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$leave['status']] ?? 'secondary') . '">' . (['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối'][$leave['status']] ?? $leave['status']) . '</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nhân viên</label>
                            <p class="form-control-plaintext">' . htmlspecialchars($leave['fullname'] ?? 'N/A') . '</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phòng ban</label>
                            <p class="form-control-plaintext">' . htmlspecialchars($leave['department'] ?? 'N/A') . '</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Loại nghỉ</label>
                            <p class="form-control-plaintext">' . htmlspecialchars($leave['leave_type'] ?? 'N/A') . '</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Lý do nghỉ</label>
                            <p class="form-control-plaintext">' . htmlspecialchars($leave['reason_type'] ?? 'N/A') . '</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ngày bắt đầu</label>
                            <p class="form-control-plaintext">' . ($leave['start_date'] ? date('d/m/Y', strtotime($leave['start_date'])) : 'N/A') . '</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ngày kết thúc</label>
                            <p class="form-control-plaintext">' . ($leave['end_date'] ? date('d/m/Y', strtotime($leave['end_date'])) : 'N/A') . '</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Số ngày nghỉ</label>
                            <p class="form-control-plaintext">' . ($leave['total'] ?? '0') . ' ngày</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Người duyệt</label>
                            <p class="form-control-plaintext">' . ($leave['approved_by_email'] ? htmlspecialchars($leave['approved_by_email']) : 'Chưa có') . '</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả chi tiết</label>
                        <p class="form-control-plaintext">' . (!empty($leave['description'] ?? '') ? htmlspecialchars($leave['description']) : 'Không có mô tả') . '</p>
                    </div>
                    
                    ' . ($leave['status'] == 'rejected' && !empty($leave['rejection_reason'] ?? '') ? '
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Lý do từ chối</label>
                        <p class="form-control-plaintext text-danger">' . htmlspecialchars($leave['rejection_reason'] ?? '') . '</p>
                    </div>
                    ' : '') . '
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Thao tác
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/leaves/' . $leave['leave_id'] . '/edit" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                        </a>
                        
                        ' . ($leave['status'] == 'pending' ? '
                        <button type="button" class="btn btn-success" onclick="approveLeave(' . $leave['leave_id'] . ')">
                            <i class="fas fa-check me-2"></i>Duyệt đơn
                        </button>
                        
                        <button type="button" class="btn btn-danger" onclick="rejectLeave(' . $leave['leave_id'] . ')">
                            <i class="fas fa-times me-2"></i>Từ chối
                        </button>
                        ' : '') . '
                        
                        <a href="/leaves" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>Danh sách đơn nghỉ
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Lịch sử
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Đơn nghỉ được tạo</h6>
                                <p class="timeline-text text-muted">Đơn nghỉ được tạo bởi ' . htmlspecialchars($leave['fullname']) . '</p>
                            </div>
                        </div>
                        
                        ' . ($leave['status'] == 'approved' ? '
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Đơn nghỉ được duyệt</h6>
                                <p class="timeline-text text-muted">Được duyệt bởi ' . htmlspecialchars($leave['approved_by_email'] ?? 'Chưa xác định') . '</p>
                            </div>
                        </div>
                        ' : '') . '
                        
                        ' . ($leave['status'] == 'rejected' ? '
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Đơn nghỉ bị từ chối</h6>
                                <p class="timeline-text text-muted">Bị từ chối bởi ' . htmlspecialchars($leave['approved_by_email'] ?? 'Chưa xác định') . '</p>
                            </div>
                        </div>
                        ' : '') . '
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Duyệt đơn nghỉ phép</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="' . Session::getCSRFToken() . '">
                    <input type="hidden" name="leave_id" id="approveLeaveId">
                    
                    <p>Bạn có chắc chắn muốn duyệt đơn nghỉ phép này không?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Duyệt đơn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Từ chối đơn nghỉ phép</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="' . Session::getCSRFToken() . '">
                    <input type="hidden" name="leave_id" id="rejectLeaveId">
                    
                    <div class="mb-3">
                        <label class="form-label">Lý do từ chối</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Nhập lý do từ chối..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
';

$additionalJS = '
<script>
function approveLeave(leaveId) {
    $("#approveLeaveId").val(leaveId);
    $("#approveModal").modal("show");
}

function rejectLeave(leaveId) {
    $("#rejectLeaveId").val(leaveId);
    $("#rejectModal").modal("show");
}

$("#approveForm").on("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const leaveId = $("#approveLeaveId").val();
    
    $.ajax({
        url: "/leaves/" + leaveId + "/approve",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $("#approveModal").modal("hide");
            location.reload();
        },
        error: function(xhr) {
            alert("Có lỗi xảy ra khi duyệt đơn nghỉ!");
        }
    });
});

$("#rejectForm").on("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const leaveId = $("#rejectLeaveId").val();
    
    $.ajax({
        url: "/leaves/" + leaveId + "/reject",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $("#rejectModal").modal("hide");
            location.reload();
        },
        error: function(xhr) {
            alert("Có lỗi xảy ra khi từ chối đơn nghỉ!");
        }
    });
});
</script>
';

include VIEWS_PATH . '/layouts/main.php';
?>

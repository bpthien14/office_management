<?php
$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Chỉnh sửa đơn nghỉ phép</h1>
                    <p class="text-muted">Chỉnh sửa thông tin đơn nghỉ phép #' . $leave['leave_id'] . '</p>
                </div>
                <div>
                    <a href="/leaves/' . $leave['leave_id'] . '" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Thông tin đơn nghỉ phép
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/leaves/' . $leave['leave_id'] . '" id="editLeaveForm">
                        <input type="hidden" name="_token" value="' . Session::getCSRFToken() . '">
                        <input type="hidden" name="_method" value="PUT">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">Nhân viên <span class="text-danger">*</span></label>
                                <select name="employee_id" id="employee_id" class="form-select" required>
                                    <option value="">Chọn nhân viên</option>
                                    ' . implode('', array_map(function($employee) use ($leave) {
                                        $selected = ($employee['employee_id'] == $leave['employee_id']) ? 'selected' : '';
                                        return '<option value="' . $employee['employee_id'] . '" ' . $selected . '>' . htmlspecialchars($employee['fullname']) . ' - ' . htmlspecialchars($employee['department']) . '</option>';
                                    }, $employees)) . '
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="leave_type" class="form-label">Loại nghỉ <span class="text-danger">*</span></label>
                                <select name="leave_type" id="leave_type" class="form-select" required>
                                    <option value="">Chọn loại nghỉ</option>
                                    <option value="Nghỉ phép năm"' . ($leave['leave_type'] == 'Nghỉ phép năm' ? ' selected' : '') . '>Nghỉ phép năm</option>
                                    <option value="Nghỉ không lương"' . ($leave['leave_type'] == 'Nghỉ không lương' ? ' selected' : '') . '>Nghỉ không lương</option>
                                    <option value="Nghỉ ốm"' . ($leave['leave_type'] == 'Nghỉ ốm' ? ' selected' : '') . '>Nghỉ ốm</option>
                                    <option value="Nghỉ việc riêng"' . ($leave['leave_type'] == 'Nghỉ việc riêng' ? ' selected' : '') . '>Nghỉ việc riêng</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="' . $leave['start_date'] . '" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="' . $leave['end_date'] . '" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reason_type" class="form-label">Lý do nghỉ <span class="text-danger">*</span></label>
                                <select name="reason_type" id="reason_type" class="form-select" required>
                                    <option value="">Chọn lý do</option>
                                    <option value="Nghỉ phép"' . ($leave['reason_type'] == 'Nghỉ phép' ? ' selected' : '') . '>Nghỉ phép</option>
                                    <option value="Nghỉ ốm"' . ($leave['reason_type'] == 'Nghỉ ốm' ? ' selected' : '') . '>Nghỉ ốm</option>
                                    <option value="Việc gia đình"' . ($leave['reason_type'] == 'Việc gia đình' ? ' selected' : '') . '>Việc gia đình</option>
                                    <option value="Khác"' . ($leave['reason_type'] == 'Khác' ? ' selected' : '') . '>Khác</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="total_days" class="form-label">Số ngày nghỉ</label>
                                <input type="number" name="total_days" id="total_days" class="form-control" value="' . $leave['total'] . '" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả chi tiết</label>
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Mô tả chi tiết lý do nghỉ...">' . htmlspecialchars($leave['description']) . '</textarea>
                        </div>
                        
                        ' . ($leave['status'] != 'pending' ? '
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Đơn nghỉ đã được ' . ($leave['status'] == 'approved' ? 'duyệt' : 'từ chối') . ', không thể chỉnh sửa.
                        </div>
                        ' : '') . '
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" ' . ($leave['status'] != 'pending' ? 'disabled' : '') . '>
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                            <a href="/leaves/' . $leave['leave_id'] . '" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Thông tin hiện tại
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <p>
                            <span class="badge bg-' . (['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$leave['status']] ?? 'secondary') . '">' . (['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối'][$leave['status']] ?? $leave['status']) . '</span>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Người tạo</label>
                        <p class="form-control-plaintext">' . htmlspecialchars($leave['fullname'] ?? 'N/A') . '</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phòng ban</label>
                        <p class="form-control-plaintext">' . htmlspecialchars($leave['department'] ?? 'N/A') . '</p>
                    </div>
                    
                    ' . ($leave['approved_by_email'] ? '
                    <div class="mb-3">
                        <label class="form-label fw-bold">Người duyệt</label>
                        <p class="form-control-plaintext">' . htmlspecialchars($leave['approved_by_email']) . '</p>
                    </div>
                    ' : '') . '
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Hướng dẫn
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Chỉ có thể chỉnh sửa đơn nghỉ đang chờ duyệt
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Số ngày nghỉ sẽ được tính tự động
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Thay đổi sẽ được lưu ngay lập tức
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            Đơn nghỉ sẽ chuyển về trạng thái chờ duyệt
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
';

$additionalJS = '
<script>
$(document).ready(function() {
    // Calculate days between start and end date
    function calculateDays() {
        const startDate = new Date($("#start_date").val());
        const endDate = new Date($("#end_date").val());
        
        if (startDate && endDate && endDate >= startDate) {
            const timeDiff = endDate.getTime() - startDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
            $("#total_days").val(daysDiff);
        } else {
            $("#total_days").val("");
        }
    }
    
    $("#start_date, #end_date").on("change", calculateDays);
    
    // Form validation
    $("#editLeaveForm").on("submit", function(e) {
        const startDate = new Date($("#start_date").val());
        const endDate = new Date($("#end_date").val());
        
        if (endDate < startDate) {
            e.preventDefault();
            alert("Ngày kết thúc phải sau ngày bắt đầu!");
            return false;
        }
    });
});
</script>
';

include VIEWS_PATH . '/layouts/main.php';
?>

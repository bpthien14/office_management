<?php
$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Tạo đơn nghỉ phép</h1>
                    <p class="text-muted">Điền thông tin để tạo đơn nghỉ phép mới</p>
                </div>
                <div>
                    <a href="/leaves" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>Thông tin đơn nghỉ phép
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/leaves" id="createLeaveForm">
                        <input type="hidden" name="_token" value="' . Session::getCSRFToken() . '">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">Nhân viên <span class="text-danger">*</span></label>
                                <select name="employee_id" id="employee_id" class="form-select" required>
                                    <option value="">Chọn nhân viên</option>';
                                    
foreach ($employees as $employee) {
    $content .= '<option value="' . $employee['employee_id'] . '">' . htmlspecialchars($employee['fullname']) . ' - ' . htmlspecialchars($employee['department']) . '</option>';
}

$content .= '
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="leave_type" class="form-label">Loại nghỉ <span class="text-danger">*</span></label>
                                <select name="leave_type" id="leave_type" class="form-select" required>
                                    <option value="">Chọn loại nghỉ</option>
                                    <option value="Nghỉ phép năm">Nghỉ phép năm</option>
                                    <option value="Nghỉ không lương">Nghỉ không lương</option>
                                    <option value="Nghỉ ốm">Nghỉ ốm</option>
                                    <option value="Nghỉ việc riêng">Nghỉ việc riêng</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reason_type" class="form-label">Lý do nghỉ <span class="text-danger">*</span></label>
                                <select name="reason_type" id="reason_type" class="form-select" required>
                                    <option value="">Chọn lý do</option>
                                    <option value="Nghỉ phép">Nghỉ phép</option>
                                    <option value="Nghỉ ốm">Nghỉ ốm</option>
                                    <option value="Việc gia đình">Việc gia đình</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="total_days" class="form-label">Số ngày nghỉ</label>
                                <input type="number" name="total_days" id="total_days" class="form-control" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả chi tiết</label>
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Mô tả chi tiết lý do nghỉ..."></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Tạo đơn nghỉ
                            </button>
                            <a href="/leaves" class="btn btn-outline-secondary">
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
                        <i class="fas fa-info-circle me-2"></i>Hướng dẫn
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Điền đầy đủ thông tin bắt buộc
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Chọn ngày bắt đầu và kết thúc chính xác
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Mô tả rõ ràng lý do nghỉ
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            Đơn sẽ được gửi để duyệt
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
    $("#createLeaveForm").on("submit", function(e) {
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

$this->view('layouts.main', [
    'title' => $title,
    'content' => $content,
    'additionalJS' => [$additionalJS]
]);
?>

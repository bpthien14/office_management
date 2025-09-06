<?php
$content = '
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Trả thiết bị</h1>
                    <p class="text-muted">Quản lý việc trả thiết bị đã mượn</p>
                </div>
                <div>
                    <a href="/devices" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tìm kiếm</label>
                            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên nhân viên hoặc thiết bị..." value="">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="borrowed">Đang mượn</option>
                                <option value="overdue">Quá hạn</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nhân viên</label>
                            <select name="employee_id" class="form-select">
                                <option value="">Tất cả nhân viên</option>';
                                
foreach ($employees as $employee) {
    $content .= '<option value="' . $employee['employee_id'] . '">' . htmlspecialchars($employee['fullname']) . '</option>';
}

$content .= '
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Tìm
                                </button>
                                <a href="/devices/return" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Borrowed Devices Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Danh sách thiết bị đang mượn
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Thiết bị</th>
                                    <th>Người mượn</th>
                                    <th>Ngày mượn</th>
                                    <th>Ngày trả dự kiến</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>';

// Sample data - replace with actual data from controller
$sampleBorrows = [
    [
        'borrow_id' => 1,
        'device_name' => 'Laptop Dell XPS 13',
        'fullname' => 'Nguyễn Văn A',
        'department' => 'Kỹ thuật',
        'borrow_date' => '2025-08-01',
        'expected_return_date' => '2025-08-15',
        'status' => 'borrowed'
    ],
    [
        'borrow_id' => 2,
        'device_name' => 'Máy chiếu Epson',
        'fullname' => 'Trần Thị B',
        'department' => 'Marketing',
        'borrow_date' => '2025-07-20',
        'expected_return_date' => '2025-08-05',
        'status' => 'overdue'
    ]
];

foreach ($sampleBorrows as $borrow) {
    $statusBadge = $borrow['status'] === 'overdue' ? 'bg-danger' : 'bg-warning';
    $statusText = $borrow['status'] === 'overdue' ? 'Quá hạn' : 'Đang mượn';
    
    $content .= '
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-laptop text-primary me-3"></i>
                                            <div>
                                                <h6 class="mb-0">' . htmlspecialchars($borrow['device_name']) . '</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                ' . substr($borrow['fullname'], 0, 1) . '
                                            </div>
                                            <div>
                                                <h6 class="mb-0">' . htmlspecialchars($borrow['fullname']) . '</h6>
                                                <small class="text-muted">' . htmlspecialchars($borrow['department']) . '</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>' . date('d/m/Y', strtotime($borrow['borrow_date'])) . '</td>
                                    <td>' . date('d/m/Y', strtotime($borrow['expected_return_date'])) . '</td>
                                    <td>
                                        <span class="badge ' . $statusBadge . '">' . $statusText . '</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" onclick="returnDevice(' . $borrow['borrow_id'] . ')">
                                            <i class="fas fa-undo me-1"></i>Trả thiết bị
                                        </button>
                                    </td>
                                </tr>';
}

$content .= '
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Return Device Modal -->
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trả thiết bị</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="returnForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="' . Session::getCSRFToken() . '">
                    <input type="hidden" name="borrow_id" id="returnBorrowId">
                    
                    <div class="mb-3">
                        <label class="form-label">Ngày trả thực tế</label>
                        <input type="date" name="return_date" id="returnDate" class="form-control" value="' . date('Y-m-d') . '" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Ghi chú về tình trạng thiết bị khi trả..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Xác nhận trả
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
';

$additionalJS = '
<script>
function returnDevice(borrowId) {
    $("#returnBorrowId").val(borrowId);
    $("#returnModal").modal("show");
}

$("#returnForm").on("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const borrowId = $("#returnBorrowId").val();
    
    $.ajax({
        url: "/devices/" + borrowId + "/return",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $("#returnModal").modal("hide");
            location.reload();
        },
        error: function(xhr) {
            alert("Có lỗi xảy ra khi trả thiết bị!");
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

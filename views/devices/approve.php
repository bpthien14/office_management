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
                        <li class="breadcrumb-item active">Duyệt mượn thiết bị</li>
                    </ol>
                </div>
                <h4 class="page-title">Duyệt mượn thiết bị</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Danh sách đơn mượn thiết bị chờ duyệt
                    </h5>
                </div>
                <div class="card-body">
                    ' . (empty($pending_borrows) ? '
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Không có đơn mượn thiết bị nào chờ duyệt</p>
                    </div>
                    ' : '
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Thiết bị</th>
                                    <th>Nhân viên</th>
                                    <th>Ngày mượn</th>
                                    <th>Ngày trả dự kiến</th>
                                    <th>Ghi chú</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($borrow) {
                                    return '
                                    <tr>
                                        <td>#' . $borrow['borrow_id'] . '</td>
                                        <td>' . htmlspecialchars($borrow['device_name'] ?? 'N/A') . '</td>
                                        <td>
                                            <div>
                                                <strong>' . htmlspecialchars($borrow['fullname'] ?? 'N/A') . '</strong>
                                                <br>
                                                <small class="text-muted">' . htmlspecialchars($borrow['department'] ?? 'N/A') . '</small>
                                            </div>
                                        </td>
                                        <td>' . ($borrow['borrow_date'] ? date('d/m/Y', strtotime($borrow['borrow_date'])) : 'N/A') . '</td>
                                        <td>' . ($borrow['expected_return_date'] ? date('d/m/Y', strtotime($borrow['expected_return_date'])) : 'N/A') . '</td>
                                        <td>' . (!empty($borrow['note'] ?? '') ? htmlspecialchars($borrow['note']) : 'Không có ghi chú') . '</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-success" onclick="approveBorrow(' . $borrow['borrow_id'] . ')">
                                                    <i class="fas fa-check me-1"></i>Duyệt
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="rejectBorrow(' . $borrow['borrow_id'] . ')">
                                                    <i class="fas fa-times me-1"></i>Từ chối
                                                </button>
                                            </div>
                                        </td>
                                    </tr>';
                                }, $pending_borrows)) . '
                            </tbody>
                        </table>
                    </div>
                    ') . '
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận duyệt -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận duyệt mượn thiết bị</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn duyệt đơn mượn thiết bị này?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" id="confirmApprove">
                    <i class="fas fa-check me-1"></i>Xác nhận duyệt
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận từ chối -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận từ chối mượn thiết bị</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn từ chối đơn mượn thiết bị này?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmReject">
                    <i class="fas fa-times me-1"></i>Xác nhận từ chối
                </button>
            </div>
        </div>
    </div>
</div>
';

$additionalJS = '
<script>
let currentBorrowId = null;

function approveBorrow(borrowId) {
    currentBorrowId = borrowId;
    $("#approveModal").modal("show");
}

function rejectBorrow(borrowId) {
    currentBorrowId = borrowId;
    $("#rejectModal").modal("show");
}

$("#confirmApprove").on("click", function() {
    if (currentBorrowId) {
        const form = $("<form>", {
            method: "POST",
            action: "/devices/" + currentBorrowId + "/approve"
        });
        
        const token = $("<input>", {
            type: "hidden",
            name: "_token",
            value: "' . Session::getCSRFToken() . '"
        });
        
        form.append(token);
        $("body").append(form);
        form.submit();
    }
});

$("#confirmReject").on("click", function() {
    if (currentBorrowId) {
        const form = $("<form>", {
            method: "POST",
            action: "/devices/" + currentBorrowId + "/reject"
        });
        
        const token = $("<input>", {
            type: "hidden",
            name: "_token",
            value: "' . Session::getCSRFToken() . '"
        });
        
        form.append(token);
        $("body").append(form);
        form.submit();
    }
});
</script>
';

include VIEWS_PATH . '/layouts/main.php';
?>

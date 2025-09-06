<?php
/**
 * LeaveController Class
 * Xử lý quản lý nghỉ phép
 */

require_once MODELS_PATH . '/LeaveRequest.php';
require_once MODELS_PATH . '/Employee.php';

class LeaveController extends BaseController
{
    private $leaveModel;
    private $employeeModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->leaveModel = new LeaveRequest();
        $this->employeeModel = new Employee();
    }
    
    /**
     * Hiển thị danh sách đơn nghỉ phép
     */
    public function index()
    {
        try {
            $page = $_GET['page'] ?? 1;
            $status = $_GET['status'] ?? null;
            $search = $_GET['search'] ?? '';
            
            $data = [
                'title' => 'Quản lý nghỉ phép',
                'leaves' => $this->leaveModel->getWithEmployee(),
                'status_filter' => $status,
                'search_term' => $search,
                'current_page' => $page
            ];
            
            $this->view('leaves.index', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/dashboard');
        }
    }
    
    /**
     * Hiển thị form tạo đơn nghỉ phép
     */
    public function create()
    {
        try {
            $data = [
                'title' => 'Tạo đơn nghỉ phép',
                'employees' => $this->employeeModel->getAll()
            ];
            
            $this->view('leaves/create', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/leaves');
        }
    }
    
    /**
     * Xử lý tạo đơn nghỉ phép
     */
    public function store()
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'employee_id' => $_POST['employee_id'] ?? null,
                'leave_type' => $_POST['leave_type'] ?? '',
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'reason_type' => $_POST['reason_type'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => 'pending'
            ];
            
            $validator = $this->validate($data, [
                'employee_id' => 'required',
                'leave_type' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'reason_type' => 'required'
            ]);
            
            if ($validator->fails()) {
                $this->flash('error', 'Dữ liệu không hợp lệ');
                $this->redirect('/leaves/create');
            }
            
            $leaveId = $this->leaveModel->createLeaveRequest($data);
            
            if ($leaveId) {
                $this->flash('success', 'Tạo đơn nghỉ phép thành công!');
                $this->redirect('/leaves');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi tạo đơn nghỉ phép');
                $this->redirect('/leaves/create');
            }
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/leaves/create');
        }
    }
    
    /**
     * Hiển thị chi tiết đơn nghỉ phép
     */
    public function show($id)
    {
        try {
            $leave = $this->leaveModel->getWithEmployee($id);
            
            if (!$leave) {
                $this->flash('error', 'Không tìm thấy đơn nghỉ phép');
                $this->redirect('/leaves');
            }
            
            $data = [
                'title' => 'Chi tiết đơn nghỉ phép',
                'leave' => $leave
            ];
            
            $this->view('leaves/show', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/leaves');
        }
    }
    
    /**
     * Hiển thị form chỉnh sửa đơn nghỉ phép
     */
    public function edit($id)
    {
        try {
            $leave = $this->leaveModel->getWithEmployee($id);
            
            if (!$leave) {
                $this->flash('error', 'Không tìm thấy đơn nghỉ phép');
                $this->redirect('/leaves');
            }
            
            $data = [
                'title' => 'Chỉnh sửa đơn nghỉ phép',
                'leave' => $leave,
                'employees' => $this->employeeModel->getAll()
            ];
            
            $this->view('leaves/edit', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/leaves');
        }
    }
    
    /**
     * Xử lý cập nhật đơn nghỉ phép
     */
    public function update($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'leave_type' => $_POST['leave_type'] ?? '',
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'reason_type' => $_POST['reason_type'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            
            $validator = $this->validate($data, [
                'leave_type' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'reason_type' => 'required'
            ]);
            
            if ($validator->fails()) {
                $this->flash('error', 'Dữ liệu không hợp lệ');
                $this->redirect('/leaves/' . $id . '/edit');
            }
            
            $result = $this->leaveModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Cập nhật đơn nghỉ phép thành công!');
                $this->redirect('/leaves');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi cập nhật đơn nghỉ phép');
                $this->redirect('/leaves/' . $id . '/edit');
            }
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/leaves/' . $id . '/edit');
        }
    }
    
    /**
     * Hiển thị lịch nghỉ phép
     */
    public function calendar()
    {
        try {
            $data = [
                'title' => 'Lịch nghỉ phép',
                'leaves' => $this->leaveModel->getWithEmployee()
            ];
            
            
            $this->view('leaves/calendar', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/leaves');
        }
    }
    
    /**
     * Hiển thị trang duyệt đơn nghỉ phép
     */
    public function approve()
    {
        try {
            $data = [
                'title' => 'Duyệt đơn nghỉ phép',
                'pending_leaves' => $this->leaveModel->getPendingRequests()
            ];
            
            $this->view('leaves.approve', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/leaves');
        }
    }
    
    /**
     * Xử lý duyệt đơn nghỉ phép
     */
    public function approveLeave($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'status' => 'approved',
                'approver_id' => $this->user['user_id'] ?? null
            ];
            
            $result = $this->leaveModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Duyệt đơn nghỉ phép thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi duyệt đơn nghỉ phép');
            }
            
            $this->redirect('/leaves/approve');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/leaves/approve');
        }
    }
    
    /**
     * Xử lý từ chối đơn nghỉ phép
     */
    public function rejectLeave($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'status' => 'rejected',
                'approver_id' => $this->user['user_id'] ?? null,
                'rejection_reason' => $_POST['rejection_reason'] ?? ''
            ];
            
            $result = $this->leaveModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Từ chối đơn nghỉ phép thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi từ chối đơn nghỉ phép');
            }
            
            $this->redirect('/leaves/approve');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/leaves/approve');
        }
    }
}

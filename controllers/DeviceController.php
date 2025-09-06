<?php
/**
 * DeviceController Class
 * Xử lý quản lý thiết bị
 */

class DeviceController extends BaseController
{
    private $deviceModel;
    private $deviceBorrowModel;
    private $employeeModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->deviceModel = new Device();
        $this->deviceBorrowModel = new DeviceBorrow();
        $this->employeeModel = new Employee();
    }
    
    /**
     * Hiển thị danh sách thiết bị
     */
    public function index()
    {
        try {
            $page = $_GET['page'] ?? 1;
            $search = $_GET['search'] ?? '';
            
            $devices = $this->deviceModel->getAll();
            $borrowedDevices = $this->deviceBorrowModel->getBorrowedDevices();
            
            // Tạo map thiết bị đang được mượn
            $borrowedMap = [];
            foreach ($borrowedDevices as $borrow) {
                if (!isset($borrowedMap[$borrow['device_id']])) {
                    $borrowedMap[$borrow['device_id']] = 0;
                }
                $borrowedMap[$borrow['device_id']]++;
            }
            
            // Cập nhật thông tin thiết bị
            foreach ($devices as &$device) {
                $device['borrowed_count'] = $borrowedMap[$device['device_id']] ?? 0;
                $device['available_count'] = $device['quantity'] - $device['borrowed_count'];
                $device['is_available'] = $device['available_count'] > 0;
            }
            
            $data = [
                'title' => 'Quản lý thiết bị',
                'devices' => $devices,
                'search_term' => $search,
                'current_page' => $page
            ];
            
            $this->view('devices/index', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/dashboard');
        }
    }
    
    /**
     * Hiển thị trang mượn thiết bị
     */
    public function borrow()
    {
        try {
            $data = [
                'title' => 'Mượn thiết bị',
                'devices' => $this->deviceModel->getAll(),
                'employees' => $this->employeeModel->getAll(),
                'borrow_history' => $this->deviceBorrowModel->getAllBorrowHistory()
            ];
            
            $this->view('devices/borrow', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/devices');
        }
    }
    
    /**
     * Hiển thị trang trả thiết bị
     */
    public function return()
    {
        try {
            $data = [
                'title' => 'Trả thiết bị',
                'borrowed_devices' => $this->deviceBorrowModel->getBorrowedDevices(),
                'employees' => $this->employeeModel->getAll()
            ];
            
            $this->view('devices/return', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/devices');
        }
    }
    
    /**
     * Hiển thị chi tiết thiết bị
     */
    public function show($id)
    {
        try {
            $device = $this->deviceModel->getById($id);
            
            if (!$device) {
                $this->flash('error', 'Không tìm thấy thiết bị');
                $this->redirect('/devices');
            }
            
            $data = [
                'title' => 'Chi tiết thiết bị',
                'device' => $device,
                'borrow_history' => $this->deviceBorrowModel->getByDevice($id)
            ];
            
            $this->view('devices/show', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/devices');
        }
    }
    
    /**
     * Xử lý tạo thiết bị mới
     */
    public function store()
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'device_name' => $_POST['device_name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'quantity' => $_POST['quantity'] ?? 1
            ];
            
            $validator = $this->validate($data, [
                'device_name' => 'required|min:3',
                'quantity' => 'required|integer|min:1'
            ]);
            
            if ($validator->fails()) {
                $this->flash('error', 'Dữ liệu không hợp lệ');
                $this->redirect('/devices');
            }
            
            $deviceId = $this->deviceModel->create($data);
            
            if ($deviceId) {
                $this->flash('success', 'Tạo thiết bị thành công!');
                $this->redirect('/devices');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi tạo thiết bị');
                $this->redirect('/devices');
            }
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/devices');
        }
    }
    
    /**
     * Xử lý cập nhật thiết bị
     */
    public function update($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'device_name' => $_POST['device_name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'quantity' => $_POST['quantity'] ?? 1
            ];
            
            $validator = $this->validate($data, [
                'device_name' => 'required|min:3',
                'quantity' => 'required|integer|min:1'
            ]);
            
            if ($validator->fails()) {
                $this->flash('error', 'Dữ liệu không hợp lệ');
                $this->redirect('/devices/' . $id);
            }
            
            $result = $this->deviceModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Cập nhật thiết bị thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi cập nhật thiết bị');
            }
            
            $this->redirect('/devices');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/devices');
        }
    }
    
    /**
     * Xử lý mượn thiết bị
     */
    public function borrowDevice($id = null)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'employee_id' => $_POST['employee_id'] ?? null,
                'borrow_date' => $_POST['borrow_date'] ?? date('Y-m-d'),
                'expected_return_date' => $_POST['expected_return_date'] ?? '',
                'status' => 'pending'
            ];
            
            $validator = $this->validate($data, [
                'employee_id' => 'required',
                'borrow_date' => 'required|date',
                'expected_return_date' => 'required|date'
            ]);
            
            if ($validator->fails()) {
                $this->flash('error', 'Dữ liệu không hợp lệ');
                $this->redirect('/devices/borrow');
            }
            
            // Tạo record mượn thiết bị
            $borrowId = $this->deviceBorrowModel->create($data);
            
            if ($borrowId) {
                // Tạo chi tiết mượn thiết bị
                $deviceId = $_POST['device_id'] ?? null;
                $note = $_POST['purpose'] ?? '';
                
                if ($deviceId) {
                    $this->deviceBorrowModel->createBorrowDetail($borrowId, $deviceId, $note);
                }
                
                $this->flash('success', 'Đăng ký mượn thiết bị thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi đăng ký mượn thiết bị');
            }
            
            $this->redirect('/devices/borrow');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/devices/borrow');
        }
    }
    
    /**
     * Xử lý trả thiết bị
     */
    public function returnDevice($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'return_date' => $_POST['return_date'] ?? date('Y-m-d'),
                'status' => 'returned'
            ];
            
            $result = $this->deviceBorrowModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Trả thiết bị thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi trả thiết bị');
            }
            
            $this->redirect('/devices/return');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/devices/return');
        }
    }
    
    /**
     * Hiển thị trang duyệt mượn thiết bị
     */
    public function approve()
    {
        try {
            $data = [
                'title' => 'Duyệt mượn thiết bị',
                'pending_borrows' => $this->deviceBorrowModel->getPendingBorrows(),
                'employees' => $this->employeeModel->getAll()
            ];
            
            $this->view('devices/approve', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/devices');
        }
    }
    
    /**
     * Xử lý duyệt mượn thiết bị
     */
    public function approveBorrow($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'status' => 'approved',
                'approver_id' => $this->user['user_id'] ?? null
            ];
            
            $result = $this->deviceBorrowModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Duyệt mượn thiết bị thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi duyệt mượn thiết bị');
            }
            
            $this->redirect('/devices/approve');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/devices/approve');
        }
    }
    
    /**
     * Xử lý từ chối mượn thiết bị
     */
    public function rejectBorrow($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'status' => 'rejected',
                'approver_id' => $this->user['user_id'] ?? null
            ];
            
            $result = $this->deviceBorrowModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Từ chối mượn thiết bị thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi từ chối mượn thiết bị');
            }
            
            $this->redirect('/devices/approve');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/devices/approve');
        }
    }
}

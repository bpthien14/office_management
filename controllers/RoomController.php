<?php
/**
 * RoomController Class
 * Xử lý quản lý phòng họp
 */

class RoomController extends BaseController
{
    private $roomModel;
    private $roomBookingModel;
    private $employeeModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->roomModel = new Room();
        $this->roomBookingModel = new RoomBooking();
        $this->employeeModel = new Employee();
    }
    
    /**
     * Hiển thị danh sách phòng họp
     */
    public function index()
    {
        try {
            $page = $_GET['page'] ?? 1;
            $search = $_GET['search'] ?? '';
            
            $data = [
                'title' => 'Quản lý phòng họp',
                'rooms' => $this->roomModel->getAll(),
                'search_term' => $search,
                'current_page' => $page
            ];
            
            $this->view('rooms/index', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/dashboard');
        }
    }
    
    /**
     * Hiển thị trang đặt phòng họp
     */
    public function booking()
    {
        try {
            $data = [
                'title' => 'Đặt phòng họp',
                'rooms' => $this->roomModel->getAll(),
                'employees' => $this->employeeModel->getAll(),
                'bookings' => $this->roomBookingModel->getAll()
            ];
            
            $this->view('rooms/booking', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/rooms');
        }
    }
    
    /**
     * Hiển thị lịch phòng họp
     */
    public function calendar()
    {
        try {
            $startDate = $_GET['start_date'] ?? null;
            $endDate = $_GET['end_date'] ?? null;
            
            $data = [
                'title' => 'Lịch phòng họp',
                'rooms' => $this->roomModel->getAll(),
                'bookings' => $this->roomBookingModel->getCalendarData($startDate, $endDate)
            ];
            
            $this->view('rooms/calendar', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/rooms');
        }
    }
    
    /**
     * Hiển thị chi tiết phòng họp
     */
    public function show($id)
    {
        try {
            $room = $this->roomModel->getById($id);
            
            if (!$room) {
                $this->flash('error', 'Không tìm thấy phòng họp');
                $this->redirect('/rooms');
            }
            
            $data = [
                'title' => 'Chi tiết phòng họp',
                'room' => $room,
                'bookings' => $this->roomBookingModel->getByRoom($id)
            ];
            
            $this->view('rooms/show', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/rooms');
        }
    }
    
    /**
     * Xử lý tạo phòng họp mới
     */
    public function store()
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'room_name' => $_POST['room_name'] ?? '',
                'type' => $_POST['type'] ?? 'normal',
                'capacity' => $_POST['capacity'] ?? 10,
                'location' => $_POST['location'] ?? '',
                'status' => $_POST['status'] ?? 'available'
            ];
            
            $validator = $this->validate($data, [
                'room_name' => 'required|min:3',
                'capacity' => 'required|integer|min:1'
            ]);
            
            if ($validator->fails()) {
                $this->flash('error', 'Dữ liệu không hợp lệ');
                $this->redirect('/rooms');
            }
            
            $roomId = $this->roomModel->create($data);
            
            if ($roomId) {
                $this->flash('success', 'Tạo phòng họp thành công!');
                $this->redirect('/rooms');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi tạo phòng họp');
                $this->redirect('/rooms');
            }
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/rooms');
        }
    }
    
    /**
     * Xử lý cập nhật phòng họp
     */
    public function update($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'room_name' => $_POST['room_name'] ?? '',
                'type' => $_POST['type'] ?? 'normal',
                'capacity' => $_POST['capacity'] ?? 10,
                'location' => $_POST['location'] ?? '',
                'status' => $_POST['status'] ?? 'available'
            ];
            
            $validator = $this->validate($data, [
                'room_name' => 'required|min:3',
                'capacity' => 'required|integer|min:1'
            ]);
            
            if ($validator->fails()) {
                $this->flash('error', 'Dữ liệu không hợp lệ');
                $this->redirect('/rooms/' . $id);
            }
            
            $result = $this->roomModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Cập nhật phòng họp thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi cập nhật phòng họp');
            }
            
            $this->redirect('/rooms');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/rooms');
        }
    }
    
    /**
     * Xử lý đặt phòng họp
     */
    public function bookRoom($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'room_id' => $id,
                'employee_id' => $_POST['employee_id'] ?? null,
                'booking_date' => $_POST['booking_date'] ?? '',
                'start_time' => $_POST['start_time'] ?? '',
                'end_time' => $_POST['end_time'] ?? '',
                'purpose' => $_POST['purpose'] ?? '',
                'status' => 'pending'
            ];
            
            $validator = $this->validate($data, [
                'employee_id' => 'required',
                'booking_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'purpose' => 'required|min:5'
            ]);
            
            if ($validator->fails()) {
                $this->flash('error', 'Dữ liệu không hợp lệ');
                $this->redirect('/rooms/booking');
            }
            
            $bookingId = $this->roomBookingModel->create($data);
            
            if ($bookingId) {
                $this->flash('success', 'Đặt phòng họp thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi đặt phòng họp');
            }
            
            $this->redirect('/rooms/booking');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/rooms/booking');
        }
    }
    
    /**
     * Xử lý hủy đặt phòng họp
     */
    public function cancelBooking($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'status' => 'cancelled'
            ];
            
            $result = $this->roomBookingModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Hủy đặt phòng họp thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi hủy đặt phòng họp');
            }
            
            $this->redirect('/rooms/booking');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/rooms/booking');
        }
    }
    
    /**
     * Hiển thị trang duyệt đặt phòng họp
     */
    public function approve()
    {
        try {
            $data = [
                'title' => 'Duyệt đặt phòng họp',
                'pending_bookings' => $this->roomBookingModel->getPendingBookings(),
                'employees' => $this->employeeModel->getAll()
            ];
            
            $this->view('rooms/approve', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/rooms');
        }
    }
    
    /**
     * Xử lý duyệt đặt phòng họp
     */
    public function approveBooking($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'status' => 'approved',
                'approver_id' => $this->user['user_id'] ?? null,
                'approved_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->roomBookingModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Duyệt đặt phòng họp thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi duyệt đặt phòng họp');
            }
            
            $this->redirect('/rooms/approve');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/rooms/approve');
        }
    }
    
    /**
     * Xử lý từ chối đặt phòng họp
     */
    public function rejectBooking($id)
    {
        try {
            $this->validateCSRF($_POST['_token'] ?? '');
            
            $data = [
                'status' => 'rejected',
                'approver_id' => $this->user['user_id'] ?? null,
                'approved_at' => date('Y-m-d H:i:s'),
                'rejection_reason' => $_POST['rejection_reason'] ?? 'Không có lý do cụ thể'
            ];
            
            $result = $this->roomBookingModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Từ chối đặt phòng họp thành công!');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi từ chối đặt phòng họp');
            }
            
            $this->redirect('/rooms/approve');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/rooms/approve');
        }
    }
}

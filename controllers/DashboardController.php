<?php
/**
 * DashboardController Class
 * Xử lý trang chủ và dashboard
 */

class DashboardController extends BaseController
{
    private $employeeModel;
    private $leaveModel;
    private $deviceModel;
    private $roomModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->employeeModel = new Employee();
        $this->leaveModel = new LeaveRequest();
        $this->deviceModel = new Device();
        $this->roomModel = new Room();
    }
    
    /**
     * Hiển thị dashboard
     */
    public function index()
    {
        try {
            $data = [
                'title' => 'Dashboard',
                'stats' => $this->getDashboardStats(),
                'recent_activities' => $this->getRecentActivities(),
                'upcoming_events' => $this->getUpcomingEvents()
            ];
            
            // Thêm dữ liệu cụ thể theo role
            if ($this->user['role'] === 'admin' || $this->user['role'] === 'hr') {
                $data['admin_stats'] = $this->getAdminStats();
                $data['pending_requests'] = $this->getPendingRequests();
            }
            
            $this->view('dashboard.index', $data);
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->view('dashboard.index', [
                'title' => 'Dashboard',
                'stats' => [],
                'recent_activities' => [],
                'upcoming_events' => []
            ]);
        }
    }
    
    /**
     * Lấy thống kê dashboard cơ bản
     */
    private function getDashboardStats()
    {
        $stats = [
            'total_employees' => $this->employeeModel->count(),
            'active_employees' => $this->employeeModel->count(), // All employees are considered active
            'total_devices' => $this->deviceModel->count(),
            'available_devices' => $this->deviceModel->count(), // All devices are considered available
            'total_rooms' => $this->roomModel->count(),
            'available_rooms' => $this->roomModel->count('status', 'available')
        ];
        
        // Thêm thống kê nghỉ phép
        $leaveStats = $this->leaveModel->getStats();
        $stats = array_merge($stats, $leaveStats);
        
        return $stats;
    }
    
    /**
     * Lấy thống kê admin
     */
    private function getAdminStats()
    {
        return [
            'employee_stats' => $this->employeeModel->getStats(),
            'device_stats' => $this->deviceModel->getStats(),
            'room_stats' => $this->roomModel->getStats(),
            'leave_stats' => $this->leaveModel->getStats()
        ];
    }
    
    /**
     * Lấy các yêu cầu chờ duyệt
     */
    private function getPendingRequests()
    {
        return [
            'leave_requests' => $this->leaveModel->getPendingRequests(),
            'room_bookings' => $this->roomModel->getPendingBookings()
        ];
    }
    
    /**
     * Lấy hoạt động gần đây
     */
    private function getRecentActivities()
    {
        $activities = [];
        
        // Lấy nhân viên mới
        $newEmployees = $this->employeeModel->getNewEmployees();
        foreach ($newEmployees as $employee) {
            $activities[] = [
                'type' => 'new_employee',
                'message' => "Nhân viên mới: {$employee['fullname']}",
                'date' => date('Y-m-d'), // Use current date since hire_date doesn't exist
                'icon' => 'user-plus'
            ];
        }
        
        // Lấy đơn xin nghỉ gần đây
        $recentLeaves = $this->leaveModel->getWithEmployee();
        $recentLeaves = array_slice($recentLeaves, 0, 5);
        foreach ($recentLeaves as $leave) {
            $activities[] = [
                'type' => 'leave_request',
                'message' => "Đơn xin nghỉ: {$leave['fullname']} - {$leave['leave_type']}",
                'date' => date('Y-m-d'), // Use current date since created_at doesn't exist
                'icon' => 'calendar',
                'status' => $leave['status']
            ];
        }
        
        // Sắp xếp theo ngày
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return array_slice($activities, 0, 10);
    }
    
    /**
     * Lấy sự kiện sắp tới
     */
    private function getUpcomingEvents()
    {
        $events = [];
        
        // Lấy đặt phòng hôm nay (method doesn't exist, using empty array)
        $todayBookings = [];
        foreach ($todayBookings as $booking) {
            $events[] = [
                'type' => 'room_booking',
                'title' => "Đặt phòng: {$booking['room_name']}",
                'time' => $booking['start_time'],
                'description' => $booking['purpose'],
                'icon' => 'calendar'
            ];
        }
        
        // Lấy thiết bị sắp hết bảo hành (method doesn't exist, using empty array)
        $expiringDevices = [];
        foreach ($expiringDevices as $device) {
            $events[] = [
                'type' => 'warranty_expiry',
                'title' => "Bảo hành sắp hết: {$device['device_name']}",
                'time' => $device['warranty_expiry'],
                'description' => "Thiết bị {$device['device_name']} sắp hết bảo hành",
                'icon' => 'warning'
            ];
        }
        
        // Sắp xếp theo thời gian
        usort($events, function($a, $b) {
            return strtotime($a['time']) - strtotime($b['time']);
        });
        
        return array_slice($events, 0, 5);
    }
    
    /**
     * API: Lấy thống kê dashboard
     */
    public function apiStats()
    {
        try {
            $stats = $this->getDashboardStats();
            
            if ($this->user['role'] === 'admin' || $this->user['role'] === 'hr') {
                $stats['admin'] = $this->getAdminStats();
            }
            
            $this->success('Lấy thống kê thành công', $stats);
            
        } catch (Exception $e) {
            $this->error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * API: Lấy hoạt động gần đây
     */
    public function apiRecentActivities()
    {
        try {
            $activities = $this->getRecentActivities();
            $this->success('Lấy hoạt động gần đây thành công', $activities);
            
        } catch (Exception $e) {
            $this->error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * API: Lấy sự kiện sắp tới
     */
    public function apiUpcomingEvents()
    {
        try {
            $events = $this->getUpcomingEvents();
            $this->success('Lấy sự kiện sắp tới thành công', $events);
            
        } catch (Exception $e) {
            $this->error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * API: Lấy biểu đồ thống kê
     */
    public function apiCharts()
    {
        try {
            $charts = [];
            
            // Biểu đồ nhân viên theo phòng ban
            $employeeStats = $this->employeeModel->getStats();
            $charts['employees_by_department'] = $employeeStats['departments'];
            
            // Biểu đồ thiết bị theo loại
            $deviceStats = $this->deviceModel->getStats();
            $charts['devices_by_type'] = $deviceStats['types'];
            
            // Biểu đồ đơn xin nghỉ theo tháng
            $currentYear = date('Y');
            $monthlyLeaves = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthStats = $this->leaveModel->getStats(null, $i, $currentYear);
                $monthlyLeaves[] = [
                    'month' => $i,
                    'count' => $monthStats['total_requests']
                ];
            }
            $charts['leaves_by_month'] = $monthlyLeaves;
            
            $this->success('Lấy dữ liệu biểu đồ thành công', $charts);
            
        } catch (Exception $e) {
            $this->error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}

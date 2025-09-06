<?php
/**
 * RoomBooking Model
 * Quản lý đặt phòng họp
 */

class RoomBooking extends BaseModel
{
    protected $table = 'room_booking';
    protected $fillable = [
        'room_id', 'employee_id', 'booking_date', 'start_time', 'end_time',
        'purpose', 'attendees', 'status', 'approved_by', 'approved_at',
        'rejection_reason', 'notes'
    ];
    
    /**
     * Tạo đặt phòng họp mới
     */
    public function createBooking($data)
    {
        // Set default values
        $data['status'] = $data['status'] ?? 'pending';
        
        return $this->create($data);
    }
    
    /**
     * Lấy đặt phòng của nhân viên
     */
    public function getByEmployee($employeeId, $status = null)
    {
        $sql = "SELECT rb.*, r.room_name, r.room_code, r.capacity, r.location, r.floor
                FROM {$this->table} rb
                LEFT JOIN rooms r ON rb.room_id = r.id
                WHERE rb.employee_id = ?";
        
        $params = [$employeeId];
        
        if ($status) {
            $sql .= " AND rb.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY rb.booking_date DESC, rb.start_time DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Lấy đặt phòng của phòng họp
     */
    public function getByRoom($roomId, $date = null)
    {
        $sql = "SELECT rb.*, e.first_name, e.last_name, e.employee_code, e.department
                FROM {$this->table} rb
                LEFT JOIN employees e ON rb.employee_id = e.id
                WHERE rb.room_id = ?";
        
        $params = [$roomId];
        
        if ($date) {
            $sql .= " AND rb.booking_date = ?";
            $params[] = $date;
        }
        
        $sql .= " ORDER BY rb.booking_date ASC, rb.start_time ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Lấy đặt phòng chờ duyệt
     */
    public function getPendingBookings()
    {
        $sql = "SELECT rb.*, r.room_name, r.room_code, r.capacity, r.location,
                       e.first_name, e.last_name, e.employee_code, e.department
                FROM {$this->table} rb
                LEFT JOIN rooms r ON rb.room_id = r.id
                LEFT JOIN employees e ON rb.employee_id = e.id
                WHERE rb.status = 'pending'
                ORDER BY rb.created_at ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy đặt phòng đã xác nhận
     */
    public function getConfirmedBookings($date = null)
    {
        $sql = "SELECT rb.*, r.room_name, r.room_code, r.capacity, r.location,
                       e.first_name, e.last_name, e.employee_code, e.department
                FROM {$this->table} rb
                LEFT JOIN rooms r ON rb.room_id = r.id
                LEFT JOIN employees e ON rb.employee_id = e.id
                WHERE rb.status = 'confirmed'";
        
        $params = [];
        
        if ($date) {
            $sql .= " AND rb.booking_date = ?";
            $params[] = $date;
        }
        
        $sql .= " ORDER BY rb.booking_date ASC, rb.start_time ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Duyệt đặt phòng
     */
    public function approve($bookingId, $approvedBy)
    {
        return $this->update($bookingId, [
            'status' => 'confirmed',
            'approved_by' => $approvedBy,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Từ chối đặt phòng
     */
    public function reject($bookingId, $approvedBy, $rejectionReason = '')
    {
        return $this->update($bookingId, [
            'status' => 'rejected',
            'approved_by' => $approvedBy,
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $rejectionReason
        ]);
    }
    
    /**
     * Hủy đặt phòng
     */
    public function cancel($bookingId, $reason = '')
    {
        return $this->update($bookingId, [
            'status' => 'cancelled',
            'notes' => $reason
        ]);
    }
    
    /**
     * Kiểm tra xung đột đặt phòng
     */
    public function checkConflict($roomId, $date, $startTime, $endTime, $excludeBookingId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE room_id = ? 
                AND booking_date = ? 
                AND status = 'confirmed'
                AND ((start_time < ? AND end_time > ?) OR (start_time < ? AND end_time > ?))";
        
        $params = [$roomId, $date, $endTime, $startTime, $endTime, $startTime];
        
        if ($excludeBookingId) {
            $sql .= " AND id != ?";
            $params[] = $excludeBookingId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Lấy lịch đặt phòng theo ngày
     */
    public function getDailySchedule($date)
    {
        $sql = "SELECT rb.*, r.room_name, r.room_code, r.capacity, r.location,
                       e.first_name, e.last_name, e.employee_code, e.department
                FROM {$this->table} rb
                LEFT JOIN rooms r ON rb.room_id = r.id
                LEFT JOIN employees e ON rb.employee_id = e.id
                WHERE rb.booking_date = ? 
                AND rb.status = 'confirmed'
                ORDER BY r.room_name ASC, rb.start_time ASC";
        
        return $this->db->fetchAll($sql, [$date]);
    }
    
    /**
     * Lấy lịch đặt phòng theo tuần
     */
    public function getWeeklySchedule($startDate, $endDate)
    {
        $sql = "SELECT rb.*, r.room_name, r.room_code, r.capacity, r.location,
                       e.first_name, e.last_name, e.employee_code, e.department
                FROM {$this->table} rb
                LEFT JOIN rooms r ON rb.room_id = r.id
                LEFT JOIN employees e ON rb.employee_id = e.id
                WHERE rb.booking_date >= ? 
                AND rb.booking_date <= ?
                AND rb.status = 'confirmed'
                ORDER BY rb.booking_date ASC, r.room_name ASC, rb.start_time ASC";
        
        return $this->db->fetchAll($sql, [$startDate, $endDate]);
    }
    
    /**
     * Lấy thống kê đặt phòng
     */
    public function getStats($employeeId = null, $roomId = null, $month = null, $year = null)
    {
        $month = $month ?: date('m');
        $year = $year ?: date('Y');
        
        $sql = "SELECT 
                    COUNT(*) as total_bookings,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_bookings,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings
                FROM {$this->table} 
                WHERE MONTH(booking_date) = ? AND YEAR(booking_date) = ?";
        
        $params = [$month, $year];
        
        if ($employeeId) {
            $sql .= " AND employee_id = ?";
            $params[] = $employeeId;
        }
        
        if ($roomId) {
            $sql .= " AND room_id = ?";
            $params[] = $roomId;
        }
        
        return $this->db->fetch($sql, $params);
    }
    
    /**
     * Lấy đặt phòng trong tương lai
     */
    public function getUpcomingBookings($employeeId = null, $days = 7)
    {
        $sql = "SELECT rb.*, r.room_name, r.room_code, r.capacity, r.location
                FROM {$this->table} rb
                LEFT JOIN rooms r ON rb.room_id = r.id
                WHERE rb.booking_date >= CURDATE() 
                AND rb.booking_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
                AND rb.status = 'confirmed'";
        
        $params = [$days];
        
        if ($employeeId) {
            $sql .= " AND rb.employee_id = ?";
            $params[] = $employeeId;
        }
        
        $sql .= " ORDER BY rb.booking_date ASC, rb.start_time ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Lấy đặt phòng hôm nay
     */
    public function getTodayBookings()
    {
        return $this->getDailySchedule(date('Y-m-d'));
    }
    
    /**
     * Lấy phòng họp được đặt nhiều nhất
     */
    public function getMostBookedRooms($limit = 10, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: date('Y-m-01');
        $endDate = $endDate ?: date('Y-m-t');
        
        $sql = "SELECT r.room_name, r.room_code, r.capacity, r.location,
                       COUNT(rb.id) as booking_count
                FROM {$this->table} rb
                LEFT JOIN rooms r ON rb.room_id = r.id
                WHERE rb.booking_date >= ? 
                AND rb.booking_date <= ?
                AND rb.status = 'confirmed'
                GROUP BY r.id, r.room_name, r.room_code, r.capacity, r.location
                ORDER BY booking_count DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$startDate, $endDate, $limit]);
    }
    
    /**
     * Lấy nhân viên đặt phòng nhiều nhất
     */
    public function getTopBookers($limit = 10, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: date('Y-m-01');
        $endDate = $endDate ?: date('Y-m-t');
        
        $sql = "SELECT e.first_name, e.last_name, e.employee_code, e.department,
                       COUNT(rb.id) as booking_count
                FROM {$this->table} rb
                LEFT JOIN employees e ON rb.employee_id = e.id
                WHERE rb.booking_date >= ? 
                AND rb.booking_date <= ?
                AND rb.status = 'confirmed'
                GROUP BY rb.employee_id, e.first_name, e.last_name, e.employee_code, e.department
                ORDER BY booking_count DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$startDate, $endDate, $limit]);
    }
}

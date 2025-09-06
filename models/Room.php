<?php
/**
 * Room Model
 * Quản lý phòng họp
 */

class Room extends BaseModel
{
    protected $table = 'rooms';
    protected $fillable = [
        'room_name', 'room_code', 'capacity', 'location', 'floor',
        'equipment', 'description', 'status', 'hourly_rate'
    ];
    
    /**
     * Tạo phòng họp mới
     */
    public function createRoom($data)
    {
        // Generate room code nếu chưa có
        if (empty($data['room_code'])) {
            $data['room_code'] = $this->generateRoomCode();
        }
        
        // Set default values
        $data['status'] = $data['status'] ?? 'available';
        
        return $this->create($data);
    }
    
    /**
     * Tạo mã phòng họp tự động
     */
    private function generateRoomCode()
    {
        $prefix = 'ROOM';
        $floor = $this->data['floor'] ?? '1';
        
        // Lấy số thứ tự cuối cùng trên tầng
        $sql = "SELECT room_code FROM {$this->table} 
                WHERE room_code LIKE ? 
                ORDER BY room_code DESC 
                LIMIT 1";
        
        $result = $this->db->fetch($sql, ["{$prefix}{$floor}%"]);
        
        if ($result) {
            $lastNumber = (int) substr($result['room_code'], -2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $floor . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }
    
    /**
     * Lấy phòng họp theo mã phòng
     */
    public function getByRoomCode($roomCode)
    {
        return $this->where('room_code', $roomCode);
    }
    
    /**
     * Lấy phòng họp theo tầng
     */
    public function getByFloor($floor)
    {
        return $this->whereAll('floor', $floor);
    }
    
    /**
     * Lấy phòng họp theo trạng thái
     */
    public function getByStatus($status)
    {
        return $this->whereAll('status', $status);
    }
    
    /**
     * Lấy phòng họp có sẵn
     */
    public function getAvailable()
    {
        return $this->getByStatus('available');
    }
    
    /**
     * Lấy phòng họp đang bảo trì
     */
    public function getMaintenance()
    {
        return $this->getByStatus('maintenance');
    }
    
    /**
     * Tìm kiếm phòng họp
     */
    public function searchRooms($searchTerm, $page = 1, $perPage = 10)
    {
        $searchColumns = ['room_name', 'room_code', 'location', 'description'];
        return $this->search($searchTerm, $searchColumns, $page, $perPage);
    }
    
    /**
     * Lấy phòng họp với thông tin đặt phòng hiện tại
     */
    public function getWithCurrentBooking($roomId = null)
    {
        $sql = "SELECT r.*, rb.id as booking_id, rb.booking_date, rb.start_time, rb.end_time,
                       rb.purpose, e.first_name, e.last_name, e.employee_code, e.department
                FROM {$this->table} r
                LEFT JOIN room_booking rb ON r.id = rb.room_id 
                    AND rb.booking_date = CURDATE() 
                    AND rb.status = 'confirmed'
                    AND rb.start_time <= TIME(NOW()) 
                    AND rb.end_time >= TIME(NOW())
                LEFT JOIN employees e ON rb.employee_id = e.id";
        
        $params = [];
        
        if ($roomId) {
            $sql .= " WHERE r.id = ?";
            $params[] = $roomId;
            return $this->db->fetch($sql, $params);
        } else {
            $sql .= " ORDER BY r.room_name ASC";
            return $this->db->fetchAll($sql, $params);
        }
    }
    
    /**
     * Kiểm tra phòng họp có trống trong khoảng thời gian không
     */
    public function isAvailable($roomId, $date, $startTime, $endTime, $excludeBookingId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM room_booking 
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
        return $result['count'] == 0;
    }
    
    /**
     * Lấy lịch đặt phòng của phòng họp
     */
    public function getBookingSchedule($roomId, $startDate, $endDate)
    {
        $sql = "SELECT rb.*, e.first_name, e.last_name, e.employee_code, e.department
                FROM room_booking rb
                LEFT JOIN employees e ON rb.employee_id = e.id
                WHERE rb.room_id = ? 
                AND rb.booking_date >= ? 
                AND rb.booking_date <= ?
                AND rb.status = 'confirmed'
                ORDER BY rb.booking_date ASC, rb.start_time ASC";
        
        return $this->db->fetchAll($sql, [$roomId, $startDate, $endDate]);
    }
    
    /**
     * Lấy phòng họp phù hợp với yêu cầu
     */
    public function getSuitableRooms($capacity, $date, $startTime, $endTime, $equipment = [])
    {
        $sql = "SELECT r.* FROM {$this->table} r
                WHERE r.capacity >= ? 
                AND r.status = 'available'
                AND r.id NOT IN (
                    SELECT rb.room_id FROM room_booking rb
                    WHERE rb.booking_date = ? 
                    AND rb.status = 'confirmed'
                    AND ((rb.start_time < ? AND rb.end_time > ?) OR (rb.start_time < ? AND rb.end_time > ?))
                )";
        
        $params = [$capacity, $date, $endTime, $startTime, $endTime, $startTime];
        
        // Thêm điều kiện thiết bị nếu có
        if (!empty($equipment)) {
            foreach ($equipment as $eq) {
                $sql .= " AND r.equipment LIKE ?";
                $params[] = "%{$eq}%";
            }
        }
        
        $sql .= " ORDER BY r.capacity ASC, r.room_name ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Lấy thống kê phòng họp
     */
    public function getStats()
    {
        $total = $this->count();
        $available = $this->count('status', 'available');
        $maintenance = $this->count('status', 'maintenance');
        
        $floors = $this->db->fetchAll("
            SELECT floor, COUNT(*) as count 
            FROM {$this->table} 
            GROUP BY floor
        ");
        
        $capacityStats = $this->db->fetch("
            SELECT 
                MIN(capacity) as min_capacity,
                MAX(capacity) as max_capacity,
                AVG(capacity) as avg_capacity
            FROM {$this->table}
        ");
        
        return [
            'total' => $total,
            'available' => $available,
            'maintenance' => $maintenance,
            'floors' => $floors,
            'capacity_stats' => $capacityStats
        ];
    }
    
    /**
     * Lấy phòng họp được đặt nhiều nhất
     */
    public function getMostBooked($limit = 10, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: date('Y-m-01'); // Đầu tháng
        $endDate = $endDate ?: date('Y-m-t'); // Cuối tháng
        
        $sql = "SELECT r.*, COUNT(rb.id) as booking_count
                FROM {$this->table} r
                LEFT JOIN room_booking rb ON r.id = rb.room_id 
                    AND rb.booking_date >= ? 
                    AND rb.booking_date <= ?
                    AND rb.status = 'confirmed'
                GROUP BY r.id, r.room_name, r.room_code, r.capacity, r.location, r.floor, r.equipment, r.description, r.status, r.hourly_rate, r.created_at, r.updated_at
                ORDER BY booking_count DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$startDate, $endDate, $limit]);
    }
    
    /**
     * Lấy phòng họp theo vị trí
     */
    public function getByLocation($location)
    {
        return $this->whereAll('location', $location);
    }
    
    /**
     * Lấy phòng họp theo sức chứa
     */
    public function getByCapacity($minCapacity, $maxCapacity = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE capacity >= ?";
        $params = [$minCapacity];
        
        if ($maxCapacity) {
            $sql .= " AND capacity <= ?";
            $params[] = $maxCapacity;
        }
        
        $sql .= " ORDER BY capacity ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
}

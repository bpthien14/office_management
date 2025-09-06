<?php
/**
 * Device Model
 * Quản lý thiết bị văn phòng
 */

class Device extends BaseModel
{
    protected $table = 'DEVICES';
    protected $primaryKey = 'device_id';
    protected $fillable = [
        'device_name', 'device_type', 'brand', 'model', 'serial_number',
        'purchase_date', 'purchase_price', 'warranty_expiry', 'status',
        'location', 'description', 'assigned_to', 'assigned_date'
    ];
    
    /**
     * Tạo thiết bị mới
     */
    public function createDevice($data)
    {
        // Set default values
        $data['status'] = $data['status'] ?? 'available';
        
        return $this->create($data);
    }
    
    /**
     * Lấy thiết bị theo ID
     */
    public function getById($id)
    {
        return $this->find($id);
    }
    
    /**
     * Lấy thiết bị theo loại
     */
    public function getByType($deviceType)
    {
        return $this->whereAll('device_type', $deviceType);
    }
    
    /**
     * Lấy thiết bị theo trạng thái
     */
    public function getByStatus($status)
    {
        return $this->whereAll('status', $status);
    }
    
    /**
     * Lấy thiết bị có sẵn
     */
    public function getAvailable()
    {
        return $this->getByStatus('available');
    }
    
    /**
     * Lấy thiết bị đang được sử dụng
     */
    public function getInUse()
    {
        return $this->getByStatus('in_use');
    }
    
    /**
     * Lấy thiết bị bị hỏng
     */
    public function getBroken()
    {
        return $this->getByStatus('broken');
    }
    
    /**
     * Lấy thiết bị đang bảo trì
     */
    public function getMaintenance()
    {
        return $this->getByStatus('maintenance');
    }
    
    /**
     * Tìm kiếm thiết bị
     */
    public function searchDevices($searchTerm, $page = 1, $perPage = 10)
    {
        $searchColumns = ['device_name', 'brand', 'model', 'serial_number', 'description'];
        return $this->search($searchTerm, $searchColumns, $page, $perPage);
    }
    
    /**
     * Lấy thiết bị với thông tin người mượn
     */
    public function getWithBorrower($deviceId = null)
    {
        $sql = "SELECT d.*, e.fullname, e.department,
                       db.borrow_date, db.return_date, db.borrow_reason
                FROM {$this->table} d
                LEFT JOIN device_borrow db ON d.id = db.device_id AND db.return_date IS NULL
                LEFT JOIN EMPLOYEES e ON db.employee_id = e.employee_id";
        
        $params = [];
        
        if ($deviceId) {
            $sql .= " WHERE d.id = ?";
            $params[] = $deviceId;
            return $this->db->fetch($sql, $params);
        } else {
            $sql .= " ORDER BY d.created_at DESC";
            return $this->db->fetchAll($sql, $params);
        }
    }
    
    /**
     * Gán thiết bị cho nhân viên
     */
    public function assignToEmployee($deviceId, $employeeId, $reason = '')
    {
        // Cập nhật trạng thái thiết bị
        $this->update($deviceId, [
            'status' => 'in_use',
            'assigned_to' => $employeeId,
            'assigned_date' => date('Y-m-d H:i:s')
        ]);
        
        // Tạo record mượn thiết bị
        $borrowData = [
            'device_id' => $deviceId,
            'employee_id' => $employeeId,
            'borrow_date' => date('Y-m-d H:i:s'),
            'borrow_reason' => $reason,
            'status' => 'borrowed'
        ];
        
        $deviceBorrow = new DeviceBorrow();
        return $deviceBorrow->create($borrowData);
    }
    
    /**
     * Trả thiết bị
     */
    public function returnDevice($deviceId)
    {
        // Cập nhật trạng thái thiết bị
        $this->update($deviceId, [
            'status' => 'available',
            'assigned_to' => null,
            'assigned_date' => null
        ]);
        
        // Cập nhật record mượn thiết bị
        $sql = "UPDATE device_borrow 
                SET return_date = ?, status = 'returned' 
                WHERE device_id = ? AND return_date IS NULL";
        
        return $this->db->execute($sql, [date('Y-m-d H:i:s'), $deviceId]);
    }
    
    /**
     * Lấy lịch sử mượn thiết bị
     */
    public function getBorrowHistory($deviceId)
    {
        $sql = "SELECT db.*, e.fullname, e.department
                FROM DEVICE_BORROW db
                LEFT JOIN EMPLOYEES e ON db.employee_id = e.employee_id
                WHERE db.device_id = ?
                ORDER BY db.borrow_date DESC";
        
        return $this->db->fetchAll($sql, [$deviceId]);
    }
    
    /**
     * Lấy thiết bị sắp hết bảo hành (trong 30 ngày tới)
     */
    public function getExpiringWarranty()
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE warranty_expiry IS NOT NULL 
                AND warranty_expiry <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                AND warranty_expiry >= CURDATE()
                ORDER BY warranty_expiry ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy thiết bị theo vị trí
     */
    public function getByLocation($location)
    {
        return $this->whereAll('location', $location);
    }
    
    /**
     * Lấy thống kê thiết bị
     */
    public function getStats()
    {
        $total = $this->count();
        $available = $total; // All devices are considered available
        $inUse = 0;
        $broken = 0;
        $maintenance = 0;
        
        $types = $this->db->fetchAll("
            SELECT device_name, COUNT(*) as count 
            FROM {$this->table} 
            GROUP BY device_name
        ");
        
        $totalValue = $this->db->fetch("
            SELECT COUNT(*) as total_value 
            FROM {$this->table}
        ");
        
        return [
            'total' => $total,
            'available' => $available,
            'in_use' => $inUse,
            'broken' => $broken,
            'maintenance' => $maintenance,
            'types' => $types,
            'total_value' => $totalValue['total_value'] ?? 0
        ];
    }
    
    /**
     * Lấy thiết bị được mượn nhiều nhất
     */
    public function getMostBorrowed($limit = 10)
    {
        $sql = "SELECT d.*, COUNT(db.id) as borrow_count
                FROM {$this->table} d
                LEFT JOIN device_borrow db ON d.id = db.device_id
                GROUP BY d.id
                ORDER BY borrow_count DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
}

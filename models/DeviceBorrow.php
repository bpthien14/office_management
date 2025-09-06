<?php
/**
 * DeviceBorrow Model
 * Quản lý lịch sử mượn thiết bị
 */

class DeviceBorrow extends BaseModel
{
    protected $table = 'DEVICE_BORROW';
    protected $primaryKey = 'borrow_id';
    protected $fillable = [
        'employee_id', 'borrow_date', 'expected_return_date', 'return_date',
        'status', 'approver_id'
    ];
    protected $timestamps = false;
    
    /**
     * Tạo record mượn thiết bị
     */
    public function createBorrowRecord($data)
    {
        // Set default values
        $data['status'] = $data['status'] ?? 'borrowed';
        $data['borrow_date'] = $data['borrow_date'] ?? date('Y-m-d H:i:s');
        
        return $this->create($data);
    }
    
    /**
     * Tạo chi tiết mượn thiết bị
     */
    public function createBorrowDetail($borrowId, $deviceId, $note = '')
    {
        $sql = "INSERT INTO DEVICE_BORROW_DETAILS (borrow_id, device_id, note) VALUES (?, ?, ?)";
        return $this->db->execute($sql, [$borrowId, $deviceId, $note]);
    }
    
    /**
     * Lấy lịch sử mượn của nhân viên
     */
    public function getByEmployee($employeeId, $status = null)
    {
        $sql = "SELECT db.*, d.device_name, d.description, d.quantity
                FROM {$this->table} db
                LEFT JOIN DEVICES d ON db.device_id = d.device_id
                WHERE db.employee_id = ?";
        
        $params = [$employeeId];
        
        if ($status) {
            $sql .= " AND db.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY db.borrow_date DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Lấy lịch sử mượn của thiết bị
     */
    public function getByDevice($deviceId)
    {
        $sql = "SELECT db.*, bd.device_id, d.device_name, d.description, d.quantity,
                       e.fullname, e.department, bd.note
                FROM {$this->table} db
                LEFT JOIN DEVICE_BORROW_DETAILS bd ON db.borrow_id = bd.borrow_id
                LEFT JOIN DEVICES d ON bd.device_id = d.device_id
                LEFT JOIN EMPLOYEES e ON db.employee_id = e.employee_id
                WHERE bd.device_id = ?
                ORDER BY db.borrow_date DESC";
        
        return $this->db->fetchAll($sql, [$deviceId]);
    }
    
    /**
     * Lấy thiết bị đang được mượn
     */
    public function getCurrentlyBorrowed()
    {
        $sql = "SELECT db.*, d.device_name, d.description, d.quantity,
                       e.fullname, e.department
                FROM {$this->table} db
                LEFT JOIN DEVICES d ON db.device_id = d.device_id
                LEFT JOIN EMPLOYEES e ON db.employee_id = e.employee_id
                WHERE db.return_date IS NULL AND db.status = 'borrowed'
                ORDER BY db.borrow_date ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Trả thiết bị
     */
    public function returnDevice($borrowId, $returnCondition = 'good', $notes = '')
    {
        return $this->update($borrowId, [
            'return_date' => date('Y-m-d H:i:s'),
            'return_condition' => $returnCondition,
            'status' => 'returned',
            'notes' => $notes
        ]);
    }
    
    /**
     * Lấy thống kê mượn thiết bị
     */
    public function getStats($employeeId = null, $deviceId = null, $year = null)
    {
        $year = $year ?: date('Y');
        $sql = "SELECT 
                    COUNT(*) as total_borrows,
                    SUM(CASE WHEN status = 'borrowed' THEN 1 ELSE 0 END) as current_borrows,
                    SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned_borrows,
                    AVG(DATEDIFF(COALESCE(return_date, CURDATE()), borrow_date)) as avg_borrow_days
                FROM {$this->table} 
                WHERE YEAR(borrow_date) = ?";
        
        $params = [$year];
        
        if ($employeeId) {
            $sql .= " AND employee_id = ?";
            $params[] = $employeeId;
        }
        
        if ($deviceId) {
            $sql .= " AND device_id = ?";
            $params[] = $deviceId;
        }
        
        return $this->db->fetch($sql, $params);
    }
    
    /**
     * Lấy thiết bị mượn quá hạn
     */
    public function getOverdueBorrows($daysOverdue = 7)
    {
        $sql = "SELECT db.*, d.device_name, d.description, d.quantity,
                       e.fullname, e.department,
                       DATEDIFF(CURDATE(), db.borrow_date) as days_borrowed
                FROM {$this->table} db
                LEFT JOIN DEVICES d ON db.device_id = d.device_id
                LEFT JOIN EMPLOYEES e ON db.employee_id = e.employee_id
                WHERE db.return_date IS NULL 
                AND db.status = 'borrowed'
                AND DATEDIFF(CURDATE(), db.borrow_date) > ?
                ORDER BY days_borrowed DESC";
        
        return $this->db->fetchAll($sql, [$daysOverdue]);
    }
    
    /**
     * Lấy nhân viên mượn nhiều thiết bị nhất
     */
    public function getTopBorrowers($limit = 10)
    {
        $sql = "SELECT e.fullname, e.department,
                       COUNT(db.id) as borrow_count,
                       SUM(CASE WHEN db.status = 'borrowed' THEN 1 ELSE 0 END) as current_borrows
                FROM {$this->table} db
                LEFT JOIN EMPLOYEES e ON db.employee_id = e.employee_id
                GROUP BY db.employee_id, e.fullname, e.department
                ORDER BY borrow_count DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    /**
     * Lấy thiết bị được mượn nhiều nhất
     */
    public function getMostBorrowedDevices($limit = 10)
    {
        $sql = "SELECT d.device_name, d.description, d.quantity,
                       COUNT(db.borrow_id) as borrow_count,
                       SUM(CASE WHEN db.status = 'borrowed' THEN 1 ELSE 0 END) as current_borrows
                FROM {$this->table} db
                LEFT JOIN DEVICES d ON db.device_id = d.device_id
                GROUP BY db.device_id, d.device_name, d.description, d.quantity
                ORDER BY borrow_count DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    /**
     * Kiểm tra nhân viên có đang mượn thiết bị không
     */
    public function isEmployeeBorrowing($employeeId, $deviceId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE employee_id = ? AND return_date IS NULL AND status = 'borrowed'";
        
        $params = [$employeeId];
        
        if ($deviceId) {
            $sql .= " AND device_id = ?";
            $params[] = $deviceId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Lấy lịch sử mượn theo khoảng thời gian
     */
    public function getByDateRange($startDate, $endDate)
    {
        $sql = "SELECT db.*, d.device_name, d.description, d.quantity,
                       e.fullname, e.department
                FROM {$this->table} db
                LEFT JOIN DEVICES d ON db.device_id = d.device_id
                LEFT JOIN EMPLOYEES e ON db.employee_id = e.employee_id
                WHERE db.borrow_date >= ? AND db.borrow_date <= ?
                ORDER BY db.borrow_date DESC";
        
        return $this->db->fetchAll($sql, [$startDate, $endDate]);
    }
    
    /**
     * Lấy danh sách thiết bị đang được mượn
     */
    public function getBorrowedDevices()
    {
        $sql = "SELECT db.*, bd.device_id, d.device_name, d.description, d.quantity,
                       e.fullname, e.department, bd.note
                FROM {$this->table} db
                INNER JOIN DEVICE_BORROW_DETAILS bd ON db.borrow_id = bd.borrow_id
                INNER JOIN DEVICES d ON bd.device_id = d.device_id
                LEFT JOIN EMPLOYEES e ON db.employee_id = e.employee_id
                WHERE db.return_date IS NULL AND db.status = 'approved'
                ORDER BY db.borrow_date ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy danh sách đơn mượn thiết bị chờ duyệt
     */
    public function getPendingBorrows()
    {
        $sql = "SELECT db.*, bd.device_id, d.device_name, d.description, d.quantity,
                       e.fullname, e.department, bd.note
                FROM {$this->table} db
                LEFT JOIN DEVICE_BORROW_DETAILS bd ON db.borrow_id = bd.borrow_id
                LEFT JOIN DEVICES d ON bd.device_id = d.device_id
                LEFT JOIN EMPLOYEES e ON db.employee_id = e.employee_id
                WHERE db.status = 'pending'
                ORDER BY db.borrow_date ASC";
        
        return $this->db->fetchAll($sql);
    }
}

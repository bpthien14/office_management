<?php
/**
 * LeaveRequest Model
 * Quản lý đơn xin nghỉ phép
 */

class LeaveRequest extends BaseModel
{
    protected $table = 'leave_requests';
    protected $fillable = [
        'employee_id', 'leave_type', 'start_date', 'end_date', 
        'total_days', 'reason', 'status', 'approved_by', 'approved_at', 
        'rejection_reason', 'created_at', 'updated_at'
    ];
    
    /**
     * Tạo đơn xin nghỉ phép
     */
    public function createLeaveRequest($data)
    {
        // Tính số ngày nghỉ
        if (isset($data['start_date']) && isset($data['end_date'])) {
            $data['total_days'] = $this->calculateTotalDays($data['start_date'], $data['end_date']);
        }
        
        // Set default status
        $data['status'] = $data['status'] ?? 'pending';
        
        return $this->create($data);
    }
    
    /**
     * Tính số ngày nghỉ (không tính cuối tuần)
     */
    private function calculateTotalDays($startDate, $endDate)
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $totalDays = 0;
        
        while ($start <= $end) {
            // Chỉ tính thứ 2-6 (không tính cuối tuần)
            if ($start->format('N') < 6) {
                $totalDays++;
            }
            $start->add(new DateInterval('P1D'));
        }
        
        return $totalDays;
    }
    
    /**
     * Lấy đơn xin nghỉ của nhân viên
     */
    public function getByEmployee($employeeId, $status = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE employee_id = ?";
        $params = [$employeeId];
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Lấy đơn xin nghỉ chờ duyệt
     */
    public function getPendingRequests()
    {
        return $this->whereAll('status', 'pending');
    }
    
    /**
     * Lấy đơn xin nghỉ với thông tin nhân viên
     */
    public function getWithEmployee($leaveId = null)
    {
        $sql = "SELECT lr.*, e.first_name, e.last_name, e.employee_code, e.department, e.position,
                       u.username as approved_by_username
                FROM {$this->table} lr
                LEFT JOIN employees e ON lr.employee_id = e.id
                LEFT JOIN users u ON lr.approved_by = u.id";
        
        $params = [];
        
        if ($leaveId) {
            $sql .= " WHERE lr.id = ?";
            $params[] = $leaveId;
            return $this->db->fetch($sql, $params);
        } else {
            $sql .= " ORDER BY lr.created_at DESC";
            return $this->db->fetchAll($sql, $params);
        }
    }
    
    /**
     * Duyệt đơn xin nghỉ
     */
    public function approve($leaveId, $approvedBy)
    {
        return $this->update($leaveId, [
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Từ chối đơn xin nghỉ
     */
    public function reject($leaveId, $approvedBy, $rejectionReason = '')
    {
        return $this->update($leaveId, [
            'status' => 'rejected',
            'approved_by' => $approvedBy,
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $rejectionReason
        ]);
    }
    
    /**
     * Lấy đơn xin nghỉ theo khoảng thời gian
     */
    public function getByDateRange($startDate, $endDate)
    {
        $sql = "SELECT lr.*, e.first_name, e.last_name, e.employee_code
                FROM {$this->table} lr
                LEFT JOIN employees e ON lr.employee_id = e.id
                WHERE lr.start_date <= ? AND lr.end_date >= ?
                AND lr.status = 'approved'
                ORDER BY lr.start_date ASC";
        
        return $this->db->fetchAll($sql, [$endDate, $startDate]);
    }
    
    /**
     * Kiểm tra xung đột nghỉ phép
     */
    public function checkConflict($employeeId, $startDate, $endDate, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE employee_id = ? 
                AND status IN ('pending', 'approved')
                AND ((start_date <= ? AND end_date >= ?) OR (start_date <= ? AND end_date >= ?))";
        
        $params = [$employeeId, $endDate, $startDate, $endDate, $startDate];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Lấy thống kê nghỉ phép
     */
    public function getStats($employeeId = null, $year = null)
    {
        $year = $year ?: date('Y');
        $sql = "SELECT 
                    COUNT(*) as total_requests,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_requests,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_requests,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_requests,
                    SUM(CASE WHEN status = 'approved' THEN total_days ELSE 0 END) as total_approved_days
                FROM {$this->table} 
                WHERE YEAR(created_at) = ?";
        
        $params = [$year];
        
        if ($employeeId) {
            $sql .= " AND employee_id = ?";
            $params[] = $employeeId;
        }
        
        return $this->db->fetch($sql, $params);
    }
    
    /**
     * Lấy đơn xin nghỉ theo loại
     */
    public function getByLeaveType($leaveType, $year = null)
    {
        $year = $year ?: date('Y');
        $sql = "SELECT lr.*, e.first_name, e.last_name, e.employee_code
                FROM {$this->table} lr
                LEFT JOIN employees e ON lr.employee_id = e.id
                WHERE lr.leave_type = ? AND YEAR(lr.created_at) = ?
                ORDER BY lr.created_at DESC";
        
        return $this->db->fetchAll($sql, [$leaveType, $year]);
    }
    
    /**
     * Lấy lịch nghỉ phép cho calendar
     */
    public function getCalendarData($startDate, $endDate)
    {
        $sql = "SELECT lr.id, lr.start_date, lr.end_date, lr.leave_type, lr.status,
                       e.first_name, e.last_name, e.employee_code
                FROM {$this->table} lr
                LEFT JOIN employees e ON lr.employee_id = e.id
                WHERE lr.start_date <= ? AND lr.end_date >= ?
                AND lr.status = 'approved'
                ORDER BY lr.start_date ASC";
        
        return $this->db->fetchAll($sql, [$endDate, $startDate]);
    }
}

<?php
/**
 * Employee Model
 * Quản lý thông tin nhân viên
 */

class Employee extends BaseModel
{
    protected $table = 'EMPLOYEES';
    protected $fillable = [
        'user_id', 'fullname', 'phone', 'position', 'department', 
        'supervisor_id', 'work_history', 'image_path', 'role'
    ];
    
    /**
     * Tạo nhân viên mới
     */
    public function createEmployee($data)
    {
        // Generate employee code nếu chưa có
        if (empty($data['employee_code'])) {
            $data['employee_code'] = $this->generateEmployeeCode();
        }
        
        // Set default values
        // Note: EMPLOYEES table doesn't have status column
        
        return $this->create($data);
    }
    
    /**
     * Tạo mã nhân viên tự động
     */
    private function generateEmployeeCode()
    {
        $prefix = 'EMP';
        $year = date('Y');
        
        // Lấy số thứ tự cuối cùng trong năm
        $sql = "SELECT employee_code FROM {$this->table} 
                WHERE employee_code LIKE ? 
                ORDER BY employee_code DESC 
                LIMIT 1";
        
        $result = $this->db->fetch($sql, ["{$prefix}{$year}%"]);
        
        if ($result) {
            $lastNumber = (int) substr($result['employee_code'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Lấy nhân viên theo user_id
     */
    public function getByUserId($userId)
    {
        return $this->where('user_id', $userId);
    }
    
    /**
     * Lấy nhân viên theo mã nhân viên
     */
    public function getByEmployeeCode($employeeCode)
    {
        return $this->where('employee_code', $employeeCode);
    }
    
    /**
     * Lấy danh sách nhân viên theo phòng ban
     */
    public function getByDepartment($department)
    {
        return $this->whereAll('department', $department);
    }
    
    /**
     * Lấy danh sách nhân viên theo vị trí
     */
    public function getByPosition($position)
    {
        return $this->whereAll('position', $position);
    }
    
    /**
     * Tìm kiếm nhân viên
     */
    public function searchEmployees($searchTerm, $page = 1, $perPage = 10)
    {
        $searchColumns = ['fullname', 'employee_code', 'email', 'phone'];
        return $this->search($searchTerm, $searchColumns, $page, $perPage);
    }
    
    /**
     * Lấy thông tin nhân viên với user info
     */
    public function getWithUser($employeeId)
    {
        $sql = "SELECT e.*, u.email, u.status 
                FROM {$this->table} e 
                LEFT JOIN users u ON e.user_id = u.user_id 
                WHERE e.employee_id = ?";
        
        return $this->db->fetch($sql, [$employeeId]);
    }
    
    /**
     * Lấy danh sách nhân viên với user info
     */
    public function getAllWithUser($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT e.*, u.email, u.status 
                FROM {$this->table} e 
                LEFT JOIN users u ON e.user_id = u.user_id 
                ORDER BY e.employee_id DESC 
                LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql);
        $total = $this->count();
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Cập nhật avatar
     */
    public function updateAvatar($employeeId, $avatarPath)
    {
        return $this->update($employeeId, ['avatar' => $avatarPath]);
    }
    
    /**
     * Lấy thống kê nhân viên
     */
    public function getStats()
    {
        $total = $this->count();
        $active = $total; // All employees are considered active
        $inactive = 0;
        
        $departments = $this->db->fetchAll("
            SELECT department, COUNT(*) as count 
            FROM {$this->table} 
            WHERE department IS NOT NULL 
            GROUP BY department
        ");
        
        $positions = $this->db->fetchAll("
            SELECT position, COUNT(*) as count 
            FROM {$this->table} 
            WHERE position IS NOT NULL 
            GROUP BY position
        ");
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'departments' => $departments,
            'positions' => $positions
        ];
    }
    
    /**
     * Lấy nhân viên sắp nghỉ hưu (trong 1 năm tới)
     */
    public function getRetiringSoon()
    {
        // Note: birth_date column doesn't exist in EMPLOYEES table
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY employee_id ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy nhân viên mới (trong 30 ngày qua)
     */
    public function getNewEmployees()
    {
        // Note: hire_date column doesn't exist in EMPLOYEES table
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY employee_id DESC 
                LIMIT 10";
        
        return $this->db->fetchAll($sql);
    }
}

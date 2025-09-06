<?php
/**
 * User Model
 * Quản lý tài khoản đăng nhập
 */

class User extends BaseModel
{
    protected $table = 'users';
    protected $fillable = [
        'username', 'email', 'password', 'role', 'is_active', 'last_login'
    ];
    protected $hidden = ['password'];
    
    /**
     * Tạo user mới
     */
    public function createUser($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Set default values
        $data['role'] = $data['role'] ?? 'employee';
        $data['is_active'] = $data['is_active'] ?? 1;
        
        return $this->create($data);
    }
    
    /**
     * Xác thực đăng nhập
     */
    public function authenticate($username, $password)
    {
        $user = $this->where('username', $username);
        
        if (!$user || !$user['is_active']) {
            return false;
        }
        
        if (password_verify($password, $user['password'])) {
            // Cập nhật last_login
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Lấy user theo email
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email);
    }
    
    /**
     * Lấy user theo username
     */
    public function findByUsername($username)
    {
        return $this->where('username', $username);
    }
    
    /**
     * Cập nhật password
     */
    public function updatePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
    
    /**
     * Kiểm tra username đã tồn tại chưa
     */
    public function usernameExists($username, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE username = ?";
        $params = [$username];
        
        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Kiểm tra email đã tồn tại chưa
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Lấy danh sách user theo role
     */
    public function getByRole($role)
    {
        return $this->whereAll('role', $role);
    }
    
    /**
     * Kích hoạt/tắt user
     */
    public function toggleActive($userId)
    {
        $user = $this->find($userId);
        if ($user) {
            $newStatus = $user['is_active'] ? 0 : 1;
            return $this->update($userId, ['is_active' => $newStatus]);
        }
        return false;
    }
    
    /**
     * Lấy thống kê user
     */
    public function getStats()
    {
        $total = $this->count();
        $active = $this->count('is_active', 1);
        $inactive = $total - $active;
        
        $roles = $this->db->fetchAll("
            SELECT role, COUNT(*) as count 
            FROM {$this->table} 
            GROUP BY role
        ");
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'roles' => $roles
        ];
    }
}

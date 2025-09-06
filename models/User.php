<?php
/**
 * User Model
 * Quản lý tài khoản đăng nhập
 */

class User extends BaseModel
{
    protected $table = 'USERS';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'email', 'password', 'status'
    ];
    protected $hidden = ['password'];
    protected $timestamps = false;
    
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
        $data['status'] = $data['status'] ?? 'active';
        
        return $this->create($data);
    }
    
    /**
     * Xác thực đăng nhập
     */
    public function authenticate($email, $password)
    {
        $user = $this->where('email', $email);
        
        if (!$user || !$user['status'] || $user['status'] !== 'active') {
            return false;
        }
        
        // Check if password is hashed or plain text
        if (password_get_info($user['password'])['algo'] !== null) {
            // Password is hashed
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        } else {
            // Password is plain text
            if ($user['password'] === $password) {
                return $user;
            }
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
     * Kiểm tra email đã tồn tại chưa (thay thế usernameExists)
     */
    public function usernameExists($email, $excludeId = null)
    {
        return $this->emailExists($email, $excludeId);
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
            $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
            return $this->update($userId, ['status' => $newStatus]);
        }
        return false;
    }
    
    /**
     * Lấy thống kê user
     */
    public function getStats()
    {
        $total = $this->count();
        $active = $this->count('status', 'active');
        $inactive = $total - $active;
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive
        ];
    }
}

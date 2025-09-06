<?php
/**
 * Session Class
 * Quản lý session cho ứng dụng
 */

class Session
{
    private static $started = false;
    
    /**
     * Khởi tạo session
     */
    public static function start()
    {
        if (!self::$started) {
            // Cấu hình session
            ini_set('session.cookie_lifetime', SESSION_LIFETIME);
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            
            session_name(SESSION_NAME);
            session_start();
            self::$started = true;
        }
    }
    
    /**
     * Lấy giá trị session
     */
    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Set giá trị session
     */
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Kiểm tra session có tồn tại không
     */
    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Xóa session
     */
    public static function remove($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Xóa tất cả session
     */
    public static function destroy()
    {
        self::start();
        $_SESSION = [];
        
        // Xóa session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        self::$started = false;
    }
    
    /**
     * Lấy tất cả session data
     */
    public static function all()
    {
        self::start();
        return $_SESSION;
    }
    
    /**
     * Flash message - hiển thị một lần
     */
    public static function flash($key, $value = null)
    {
        self::start();
        
        if ($value === null) {
            // Lấy và xóa flash message
            $message = $_SESSION['_flash'][$key] ?? null;
            unset($_SESSION['_flash'][$key]);
            return $message;
        } else {
            // Set flash message
            $_SESSION['_flash'][$key] = $value;
        }
    }
    
    /**
     * Kiểm tra có flash message không
     */
    public static function hasFlash($key)
    {
        self::start();
        return isset($_SESSION['_flash'][$key]);
    }
    
    /**
     * Regenerate session ID
     */
    public static function regenerate()
    {
        self::start();
        session_regenerate_id(true);
    }
    
    /**
     * Lấy session ID
     */
    public static function getId()
    {
        self::start();
        return session_id();
    }
    
    /**
     * Kiểm tra user đã đăng nhập chưa
     */
    public static function isLoggedIn()
    {
        return self::has('user_id') && self::has('user_role');
    }
    
    /**
     * Lấy thông tin user hiện tại
     */
    public static function user()
    {
        if (self::isLoggedIn()) {
            return [
                'id' => self::get('user_id'),
                'role' => self::get('user_role'),
                'username' => self::get('username'),
                'email' => self::get('email')
            ];
        }
        return null;
    }
    
    /**
     * Login user
     */
    public static function login($userId, $userRole, $username, $email)
    {
        self::regenerate(); // Bảo mật: regenerate session ID
        self::set('user_id', $userId);
        self::set('user_role', $userRole);
        self::set('username', $username);
        self::set('email', $email);
        self::set('login_time', time());
    }
    
    /**
     * Logout user
     */
    public static function logout()
    {
        self::destroy();
    }
}

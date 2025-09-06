<?php
/**
 * BaseController Class
 * Class cơ bản cho tất cả controllers
 */

abstract class BaseController
{
    protected $data = [];
    protected $user = null;
    
    public function __construct()
    {
        // Khởi tạo session
        Session::start();
        
        // Lấy thông tin user hiện tại
        $this->user = Session::user();
        $this->data['user'] = $this->user;
        
        // Kiểm tra authentication nếu cần
        $this->checkAuth();
    }
    
    /**
     * Kiểm tra authentication
     */
    protected function checkAuth()
    {
        // Các route không cần đăng nhập
        $publicRoutes = ['/login', '/register'];
        $currentRoute = $_SERVER['REQUEST_URI'];
        
        // Loại bỏ query string
        $currentRoute = strtok($currentRoute, '?');
        
        if (!in_array($currentRoute, $publicRoutes) && !Session::isLoggedIn()) {
            $this->redirect('/login');
        }
    }
    
    /**
     * Kiểm tra quyền truy cập
     */
    protected function checkPermission($requiredRole)
    {
        if (!$this->user || !in_array($this->user['role'], (array)$requiredRole)) {
            $this->redirect('/dashboard');
        }
    }
    
    /**
     * Render view
     */
    protected function view($view, $data = [])
    {
        // Merge data
        $this->data = array_merge($this->data, $data);
        
        // Extract data to variables
        extract($this->data);
        
        // Include view file
        $viewFile = VIEWS_PATH . '/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View {$view} không tồn tại");
        }
        
        include $viewFile;
    }
    
    /**
     * Redirect
     */
    protected function redirect($url, $statusCode = 302)
    {
        Router::redirect($url, $statusCode);
    }
    
    /**
     * JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Success response
     */
    protected function success($message, $data = [], $statusCode = 200)
    {
        $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
    
    /**
     * Error response
     */
    protected function error($message, $data = [], $statusCode = 400)
    {
        $this->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
    
    /**
     * Validation error response
     */
    protected function validationError($errors, $statusCode = 422)
    {
        $this->json([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ',
            'errors' => $errors
        ], $statusCode);
    }
    
    /**
     * Set flash message
     */
    protected function flash($key, $message)
    {
        Session::flash($key, $message);
    }
    
    /**
     * Get flash message
     */
    protected function getFlash($key)
    {
        return Session::flash($key);
    }
    
    /**
     * Check if has flash message
     */
    protected function hasFlash($key)
    {
        return Session::hasFlash($key);
    }
    
    /**
     * Validate request data
     */
    protected function validate($data, $rules)
    {
        $validator = new Validator($data);
        $validator->setRules($rules);
        
        if (!$validator->validate()) {
            $this->validationError($validator->errors());
        }
        
        return $validator;
    }
    
    /**
     * Sanitize input data
     */
    protected function sanitize($data)
    {
        return Validator::sanitize($data);
    }
    
    /**
     * Get request data
     */
    protected function getRequestData()
    {
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = $_GET;
        }
        
        return $this->sanitize($data);
    }
    
    /**
     * Get JSON request data
     */
    protected function getJsonData()
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?: [];
    }
    
    /**
     * Upload file
     */
    protected function uploadFile($file, $uploadDir, $allowedTypes = [])
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception('File upload không hợp lệ');
        }
        
        // Check file size
        if ($file['size'] > MAX_FILE_SIZE) {
            throw new Exception('File quá lớn. Kích thước tối đa: ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB');
        }
        
        // Check file type
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!empty($allowedTypes) && !in_array($fileExtension, $allowedTypes)) {
            throw new Exception('Loại file không được phép. Các loại được phép: ' . implode(', ', $allowedTypes));
        }
        
        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.' . $fileExtension;
        $uploadPath = $uploadDir . '/' . $filename;
        
        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new Exception('Không thể lưu file');
        }
        
        return $filename;
    }
    
    /**
     * Pagination helper
     */
    protected function paginate($total, $perPage = 10, $currentPage = 1)
    {
        $totalPages = ceil($total / $perPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        
        return [
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_prev' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'prev_page' => $currentPage > 1 ? $currentPage - 1 : null,
            'next_page' => $currentPage < $totalPages ? $currentPage + 1 : null
        ];
    }
    
    /**
     * Generate CSRF token
     */
    protected function csrfToken()
    {
        return Validator::generateCSRF();
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCSRF($token)
    {
        if (!Validator::validateCSRF($token)) {
            $this->error('CSRF token không hợp lệ');
        }
    }
    
    /**
     * Log activity
     */
    protected function logActivity($action, $details = '')
    {
        // Có thể implement logging system ở đây
        $logData = [
            'user_id' => $this->user['id'] ?? null,
            'action' => $action,
            'details' => $details,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Log to file or database
        error_log(json_encode($logData));
    }
}

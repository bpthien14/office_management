<?php
/**
 * AuthController Class
 * Xử lý authentication (đăng nhập, đăng ký, đăng xuất)
 */

class AuthController extends BaseController
{
    private $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }
    
    /**
     * Hiển thị form đăng nhập
     */
    public function showLogin()
    {
        // Nếu đã đăng nhập, redirect về dashboard
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.login', [
            'title' => 'Đăng nhập',
            'csrf_token' => $this->csrfToken()
        ]);
    }
    
    /**
     * Xử lý đăng nhập
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }
        
        try {
            $data = $this->getRequestData();
            
            // Validate CSRF token
            $this->validateCSRF($data['_token'] ?? '');
            
            // Validate input
            $validator = $this->validate($data, [
                'username' => 'required',
                'password' => 'required'
            ]);
            
            if ($validator->hasErrors()) {
                $this->flash('error', 'Vui lòng nhập đầy đủ thông tin');
                $this->redirect('/login');
            }
            
            // Authenticate user
            $user = $this->userModel->authenticate($data['username'], $data['password']);
            
            if (!$user) {
                $this->flash('error', 'Tên đăng nhập hoặc mật khẩu không đúng');
                $this->redirect('/login');
            }
            
            // Login successful
            Session::login(
                $user['id'],
                $user['role'],
                $user['username'],
                $user['email']
            );
            
            // Log activity
            $this->logActivity('login', 'User logged in successfully');
            
            $this->flash('success', 'Đăng nhập thành công!');
            $this->redirect('/dashboard');
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/login');
        }
    }
    
    /**
     * Hiển thị form đăng ký
     */
    public function showRegister()
    {
        // Nếu đã đăng nhập, redirect về dashboard
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.register', [
            'title' => 'Đăng ký',
            'csrf_token' => $this->csrfToken()
        ]);
    }
    
    /**
     * Xử lý đăng ký
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }
        
        try {
            $data = $this->getRequestData();
            
            // Validate CSRF token
            $this->validateCSRF($data['_token'] ?? '');
            
            // Validate input
            $validator = $this->validate($data, [
                'username' => 'required|min:3|max:50',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'password_confirmation' => 'required|confirmed:password'
            ]);
            
            if ($validator->hasErrors()) {
                $errors = $validator->errorsAsString('<br>');
                $this->flash('error', $errors);
                $this->redirect('/register');
            }
            
            // Check if username already exists
            if ($this->userModel->usernameExists($data['username'])) {
                $this->flash('error', 'Tên đăng nhập đã tồn tại');
                $this->redirect('/register');
            }
            
            // Check if email already exists
            if ($this->userModel->emailExists($data['email'])) {
                $this->flash('error', 'Email đã tồn tại');
                $this->redirect('/register');
            }
            
            // Create user
            $userData = [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'employee', // Default role
                'is_active' => 1
            ];
            
            $userId = $this->userModel->createUser($userData);
            
            if ($userId) {
                // Log activity
                $this->logActivity('register', 'New user registered: ' . $data['username']);
                
                $this->flash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
                $this->redirect('/login');
            } else {
                $this->flash('error', 'Có lỗi xảy ra khi tạo tài khoản');
                $this->redirect('/register');
            }
            
        } catch (Exception $e) {
            $this->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            $this->redirect('/register');
        }
    }
    
    /**
     * Đăng xuất
     */
    public function logout()
    {
        // Log activity
        $this->logActivity('logout', 'User logged out');
        
        // Destroy session
        Session::logout();
        
        $this->flash('success', 'Đăng xuất thành công!');
        $this->redirect('/login');
    }
    
    /**
     * API: Đăng nhập
     */
    public function apiLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Method not allowed', [], 405);
        }
        
        try {
            $data = $this->getJsonData();
            
            // Validate input
            if (empty($data['username']) || empty($data['password'])) {
                $this->error('Vui lòng nhập đầy đủ thông tin');
            }
            
            // Authenticate user
            $user = $this->userModel->authenticate($data['username'], $data['password']);
            
            if (!$user) {
                $this->error('Tên đăng nhập hoặc mật khẩu không đúng');
            }
            
            // Login successful
            Session::login(
                $user['id'],
                $user['role'],
                $user['username'],
                $user['email']
            );
            
            // Log activity
            $this->logActivity('api_login', 'User logged in via API');
            
            $this->success('Đăng nhập thành công', [
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);
            
        } catch (Exception $e) {
            $this->error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    /**
     * API: Đăng xuất
     */
    public function apiLogout()
    {
        // Log activity
        $this->logActivity('api_logout', 'User logged out via API');
        
        // Destroy session
        Session::logout();
        
        $this->success('Đăng xuất thành công');
    }
    
    /**
     * API: Kiểm tra trạng thái đăng nhập
     */
    public function apiCheckAuth()
    {
        if (Session::isLoggedIn()) {
            $this->success('Đã đăng nhập', [
                'user' => $this->user
            ]);
        } else {
            $this->error('Chưa đăng nhập', [], 401);
        }
    }
    
    /**
     * API: Đổi mật khẩu
     */
    public function apiChangePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->error('Method not allowed', [], 405);
        }
        
        if (!Session::isLoggedIn()) {
            $this->error('Chưa đăng nhập', [], 401);
        }
        
        try {
            $data = $this->getJsonData();
            
            // Validate input
            $validator = $this->validate($data, [
                'current_password' => 'required',
                'new_password' => 'required|min:6',
                'new_password_confirmation' => 'required|confirmed:new_password'
            ]);
            
            if ($validator->hasErrors()) {
                $this->validationError($validator->errors());
            }
            
            // Verify current password
            $user = $this->userModel->find($this->user['id']);
            if (!password_verify($data['current_password'], $user['password'])) {
                $this->error('Mật khẩu hiện tại không đúng');
            }
            
            // Update password
            $success = $this->userModel->updatePassword($this->user['id'], $data['new_password']);
            
            if ($success) {
                // Log activity
                $this->logActivity('change_password', 'User changed password');
                
                $this->success('Đổi mật khẩu thành công');
            } else {
                $this->error('Có lỗi xảy ra khi đổi mật khẩu');
            }
            
        } catch (Exception $e) {
            $this->error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}

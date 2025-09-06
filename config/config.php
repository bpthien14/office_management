<?php
/**
 * Application Configuration
 * Cấu hình chung của ứng dụng
 */

// Cấu hình cơ bản
define('APP_NAME', 'Office Management System');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/office_management/public');

// Cấu hình đường dẫn
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH);
define('CONFIG_PATH', APP_PATH . '/config');
define('CORE_PATH', APP_PATH . '/core');
define('MODELS_PATH', APP_PATH . '/models');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('VIEWS_PATH', APP_PATH . '/views');
define('ASSETS_PATH', APP_PATH . '/assets');
define('UPLOADS_PATH', APP_PATH . '/uploads');

// Cấu hình bảo mật
define('SECRET_KEY', 'your-secret-key-here-change-in-production');
define('CSRF_TOKEN_NAME', '_token');

// Cấu hình session
define('SESSION_LIFETIME', 3600); // 1 giờ
define('SESSION_NAME', 'office_management_session');

// Cấu hình upload
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Cấu hình phân trang
define('ITEMS_PER_PAGE', 10);

// Cấu hình email (nếu có)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', '');
define('MAIL_PASSWORD', '');
define('MAIL_FROM_EMAIL', 'noreply@office-management.com');
define('MAIL_FROM_NAME', 'Office Management System');

// Cấu hình debug
define('DEBUG_MODE', true);
define('LOG_ERRORS', true);
define('LOG_PATH', ROOT_PATH . '/logs');

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Error reporting
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

<?php
/**
 * Application Configuration
 * Cấu hình chung của ứng dụng
 */

// Cấu hình cơ bản
if (!defined('APP_NAME')) define('APP_NAME', 'Office Management System');
if (!defined('APP_VERSION')) define('APP_VERSION', '1.0.0');
if (!defined('APP_URL')) define('APP_URL', 'http://localhost:8000');

// Cấu hình đường dẫn
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__));
if (!defined('APP_PATH')) define('APP_PATH', ROOT_PATH);
if (!defined('CONFIG_PATH')) define('CONFIG_PATH', APP_PATH . '/config');
if (!defined('CORE_PATH')) define('CORE_PATH', APP_PATH . '/core');
if (!defined('MODELS_PATH')) define('MODELS_PATH', APP_PATH . '/models');
if (!defined('CONTROLLERS_PATH')) define('CONTROLLERS_PATH', APP_PATH . '/controllers');
if (!defined('VIEWS_PATH')) define('VIEWS_PATH', APP_PATH . '/views');
if (!defined('ASSETS_PATH')) define('ASSETS_PATH', APP_PATH . '/assets');
if (!defined('UPLOADS_PATH')) define('UPLOADS_PATH', APP_PATH . '/uploads');

// Cấu hình bảo mật
if (!defined('SECRET_KEY')) define('SECRET_KEY', 'your-secret-key-here-change-in-production');
if (!defined('CSRF_TOKEN_NAME')) define('CSRF_TOKEN_NAME', '_token');

// Cấu hình session
if (!defined('SESSION_LIFETIME')) define('SESSION_LIFETIME', 3600); // 1 giờ
if (!defined('SESSION_NAME')) define('SESSION_NAME', 'office_management_session');

// Cấu hình upload
if (!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
if (!defined('ALLOWED_IMAGE_TYPES')) define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Cấu hình phân trang
if (!defined('ITEMS_PER_PAGE')) define('ITEMS_PER_PAGE', 10);

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

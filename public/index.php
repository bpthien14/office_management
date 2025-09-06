<?php
/**
 * Office Management System - Entry Point
 * Điểm vào chính của hệ thống quản lý nhân sự
 */

// Báo lỗi nếu có lỗi trong quá trình phát triển
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Định nghĩa các hằng số
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH);
define('CONFIG_PATH', APP_PATH . '/config');
define('CORE_PATH', APP_PATH . '/core');
define('MODELS_PATH', APP_PATH . '/models');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('VIEWS_PATH', APP_PATH . '/views');
define('ASSETS_PATH', APP_PATH . '/assets');
define('UPLOADS_PATH', APP_PATH . '/uploads');

// Load cấu hình
require_once CONFIG_PATH . '/config.php';

// Autoloader đơn giản
spl_autoload_register(function ($class) {
    $directories = [
        CORE_PATH,
        MODELS_PATH,
        CONTROLLERS_PATH
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Xử lý lỗi
set_error_handler(function ($severity, $message, $file, $line) {
    if (DEBUG_MODE) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border: 1px solid #f5c6cb; border-radius: 4px;'>";
        echo "<strong>Lỗi PHP:</strong> {$message}<br>";
        echo "<strong>File:</strong> {$file}<br>";
        echo "<strong>Dòng:</strong> {$line}";
        echo "</div>";
    } else {
        error_log("PHP Error: {$message} in {$file} on line {$line}");
    }
});

// Xử lý exception
set_exception_handler(function ($exception) {
    if (DEBUG_MODE) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border: 1px solid #f5c6cb; border-radius: 4px;'>";
        echo "<strong>Exception:</strong> " . $exception->getMessage() . "<br>";
        echo "<strong>File:</strong> " . $exception->getFile() . "<br>";
        echo "<strong>Dòng:</strong> " . $exception->getLine() . "<br>";
        echo "<strong>Stack trace:</strong><br>";
        echo "<pre>" . $exception->getTraceAsString() . "</pre>";
        echo "</div>";
    } else {
        error_log("Uncaught Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
        echo "<h1>500 - Lỗi máy chủ</h1>";
        echo "<p>Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.</p>";
    }
});

// Khởi tạo và chạy router
try {
    $router = new Router();
    $router->handleRequest();
} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border: 1px solid #f5c6cb; border-radius: 4px;'>";
        echo "<strong>Router Error:</strong> " . $e->getMessage() . "<br>";
        echo "<strong>File:</strong> " . $e->getFile() . "<br>";
        echo "<strong>Dòng:</strong> " . $e->getLine();
        echo "</div>";
    } else {
        error_log("Router Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
        echo "<h1>500 - Lỗi máy chủ</h1>";
        echo "<p>Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.</p>";
    }
}

<?php
/**
 * Database Configuration
 * Cấu hình kết nối cơ sở dữ liệu
 */

return [
    'host' => 'localhost',
    'dbname' => 'office_management',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];

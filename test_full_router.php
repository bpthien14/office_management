<?php
require_once 'config/config.php';
require_once 'core/Router.php';
require_once 'core/Database.php';
require_once 'core/Session.php';

// Start session
session_start();

// Set up request
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/leaves/calendar';
$_SERVER['SCRIPT_NAME'] = '/public/index.php';

echo "Testing full router for /leaves/calendar\n";

try {
    $router = new Router();
    $router->handleRequest();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?>

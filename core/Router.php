<?php
/**
 * Router Class
 * Xử lý routing cho ứng dụng MVC
 */

class Router
{
    private $routes = [];
    private $middleware = [];
    
    public function __construct()
    {
        $this->loadRoutes();
    }
    
    /**
     * Load routes từ config
     */
    private function loadRoutes()
    {
        $this->routes = require CONFIG_PATH . '/routes.php';
    }
    
    /**
     * Xử lý request
     */
    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Loại bỏ base path nếu có
        $basePath = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
        if ($basePath !== '/') {
            $uri = str_replace($basePath, '', $uri);
        }
        
        $uri = $uri ?: '/';
        
        // Tìm route phù hợp
        $route = $this->findRoute($method, $uri);
        
        if (!$route) {
            $this->handle404();
            return;
        }
        
        // Xử lý middleware
        $this->handleMiddleware($route);
        
        // Dispatch controller
        $this->dispatch($route);
    }
    
    /**
     * Tìm route phù hợp
     */
    private function findRoute($method, $uri)
    {
        if (!isset($this->routes[$method])) {
            return null;
        }
        
        foreach ($this->routes[$method] as $pattern => $handler) {
            if ($this->matchRoute($pattern, $uri)) {
                return [
                    'pattern' => $pattern,
                    'handler' => $handler,
                    'params' => $this->extractParams($pattern, $uri)
                ];
            }
        }
        
        return null;
    }
    
    /**
     * Kiểm tra route có match không
     */
    private function matchRoute($pattern, $uri)
    {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        return preg_match($pattern, $uri);
    }
    
    /**
     * Extract parameters từ URI
     */
    private function extractParams($pattern, $uri)
    {
        $params = [];
        $patternParts = explode('/', trim($pattern, '/'));
        $uriParts = explode('/', trim($uri, '/'));
        
        foreach ($patternParts as $index => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                $paramName = trim($part, '{}');
                $params[$paramName] = $uriParts[$index] ?? null;
            }
        }
        
        return $params;
    }
    
    /**
     * Xử lý middleware
     */
    private function handleMiddleware($route)
    {
        // Có thể thêm middleware logic ở đây
        // Ví dụ: authentication, authorization, etc.
    }
    
    /**
     * Dispatch controller
     */
    private function dispatch($route)
    {
        $handler = $route['handler'];
        $params = $route['params'];
        
        if (is_string($handler)) {
            list($controllerName, $methodName) = explode('@', $handler);
            
            // Load controller
            $controllerFile = CONTROLLERS_PATH . '/' . $controllerName . '.php';
            
            if (!file_exists($controllerFile)) {
                throw new Exception("Controller {$controllerName} không tồn tại");
            }
            
            require_once $controllerFile;
            
            // Kiểm tra class tồn tại
            if (!class_exists($controllerName)) {
                throw new Exception("Class {$controllerName} không tồn tại");
            }
            
            // Tạo instance controller
            $controller = new $controllerName();
            
            // Kiểm tra method tồn tại
            if (!method_exists($controller, $methodName)) {
                throw new Exception("Method {$methodName} không tồn tại trong {$controllerName}");
            }
            
            // Gọi method với parameters
            call_user_func_array([$controller, $methodName], $params);
        }
    }
    
    /**
     * Xử lý 404
     */
    private function handle404()
    {
        http_response_code(404);
        
        if (file_exists(VIEWS_PATH . '/errors/404.php')) {
            include VIEWS_PATH . '/errors/404.php';
        } else {
            echo '<h1>404 - Trang không tìm thấy</h1>';
        }
    }
    
    /**
     * Redirect
     */
    public static function redirect($url, $statusCode = 302)
    {
        header("Location: $url", true, $statusCode);
        exit;
    }
    
    /**
     * Generate URL
     */
    public function url($path = '')
    {
        $baseUrl = rtrim(APP_URL, '/');
        $path = ltrim($path, '/');
        return $baseUrl . ($path ? '/' . $path : '');
    }
}

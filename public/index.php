<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/container-config.php'; 
$routes = require_once __DIR__ . '/../routes/api.php';

$method = $_SERVER['REQUEST_METHOD'];
$path   = str_replace('/' . basename(__DIR__), '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

header('Content-Type: application/json; charset=utf-8');

$requestData = ($method === 'GET') ? $_GET : (json_decode(file_get_contents('php://input'), true) ?: $_POST);

$matchedHandler = null;
$urlParams = []; 

if (isset($routes[$method])) {
    foreach ($routes[$method] as $routePath => $handler) {
        $pattern = '@^' . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $routePath) . '$@';
        
        if (preg_match($pattern, $path, $matches)) {
            $matchedHandler = $handler;
            array_shift($matches); 
            $urlParams = $matches; 
            break;
        }
    }
}

if ($matchedHandler) {
    [$controllerClass, $action] = explode('@', $matchedHandler);
    
    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass($container); 
        
        $response = $controllerInstance->$action($requestData, ...$urlParams);
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

http_response_code(404);
echo json_encode(['error' => 'Route not found.']);


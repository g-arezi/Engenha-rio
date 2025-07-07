<?php

namespace App\Core;

class Router
{
    private $routes = [];
    private $middlewares = [];
    private $currentMiddleware = [];

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put($path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete($path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    public function group($options, $callback)
    {
        $previousMiddleware = $this->currentMiddleware;
        
        if (isset($options['middleware'])) {
            $this->currentMiddleware[] = $options['middleware'];
        }
        
        $callback($this);
        
        $this->currentMiddleware = $previousMiddleware;
    }

    private function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $this->currentMiddleware
        ];
    }

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Debug logs
        error_log("Router Debug - Method: $requestMethod, URI: $requestUri");
        
        // Remover trailing slash
        $requestUri = rtrim($requestUri, '/');
        if (empty($requestUri)) {
            $requestUri = '/';
        }

        error_log("Router Debug - URI after normalization: $requestUri");
        error_log("Router Debug - Total routes: " . count($this->routes));

        foreach ($this->routes as $route) {
            error_log("Router Debug - Checking route: " . $route['method'] . " " . $route['path']);
            
            if ($route['method'] === $requestMethod && $this->matchRoute($route['path'], $requestUri)) {
                error_log("Router Debug - Route matched: " . $route['path']);
                error_log("Router Debug - Middlewares: " . implode(', ', $route['middleware']));
                
                // Executar middlewares
                foreach ($route['middleware'] as $middleware) {
                    error_log("Router Debug - Executing middleware: $middleware");
                    if (!$this->executeMiddleware($middleware)) {
                        error_log("Router Debug - Middleware $middleware failed");
                        return;
                    }
                    error_log("Router Debug - Middleware $middleware passed");
                }
                
                error_log("Router Debug - Executing handler: " . $route['handler']);
                
                // Executar handler
                $this->executeHandler($route['handler'], $requestUri, $route['path']);
                return;
            }
        }
        
        // Rota não encontrada
        error_log("Router Debug - No route found for: $requestMethod $requestUri");
        http_response_code(404);
        include __DIR__ . '/../../views/errors/404.php';
    }

    private function matchRoute($routePath, $requestUri)
    {
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        
        return preg_match($pattern, $requestUri);
    }

    private function executeMiddleware($middleware)
    {
        error_log("Middleware Debug - Executing: $middleware");
        
        switch ($middleware) {
            case 'auth':
                $isAuthenticated = Auth::check();
                error_log("Middleware Debug - Auth check result: " . ($isAuthenticated ? 'true' : 'false'));
                
                if (!$isAuthenticated) {
                    error_log("Middleware Debug - User not authenticated, redirecting to login");
                    header('Location: /login');
                    exit;
                }
                return $isAuthenticated;
                
            case 'admin':
                $user = Auth::user();
                $isAdmin = Auth::check() && $user && isset($user['role']) && $user['role'] === 'admin';
                error_log("Middleware Debug - Admin check result: " . ($isAdmin ? 'true' : 'false'));
                
                if (!$isAdmin) {
                    error_log("Middleware Debug - User is not admin, access denied");
                    http_response_code(403);
                    echo "Acesso negado. Apenas administradores podem acessar esta página.";
                    exit;
                }
                return $isAdmin;
                
            default:
                error_log("Middleware Debug - Unknown middleware: $middleware");
                return true;
        }
    }

    private function executeHandler($handler, $requestUri, $routePath)
    {
        list($controller, $method) = explode('@', $handler);
        
        $controllerClass = "App\\Controllers\\{$controller}";
        $controllerInstance = new $controllerClass();
        
        // Extrair parâmetros da rota
        $params = $this->extractParams($routePath, $requestUri);
        
        call_user_func_array([$controllerInstance, $method], $params);
    }

    private function extractParams($routePath, $requestUri)
    {
        $routeParts = explode('/', trim($routePath, '/'));
        $uriParts = explode('/', trim($requestUri, '/'));
        
        $params = [];
        
        foreach ($routeParts as $index => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                $params[] = $uriParts[$index] ?? null;
            }
        }
        
        return $params;
    }
}

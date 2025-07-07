<?php
// Carregar configurações de segurança
require_once 'config/security.php';

require_once 'vendor/autoload.php';

use App\Core\Router;
use App\Core\Session;
use App\Core\Auth;
use App\Core\Config;

// Servir arquivos estáticos ANTES de qualquer outro processamento
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Log da requisição
error_log("INDEX.PHP - Requisição: " . $uri);

// Tratamento especial para a nova logo
if ($uri === '/assets/images/engenhario-logo-new.png') {
    error_log("Tentando servir nova logo: " . $uri);
    
    // Tentar diferentes caminhos
    $paths = [
        __DIR__ . '/public/assets/images/engenhario-logo-new.png',
        __DIR__ . '/public/assets/images/logo-new.png'
    ];
    
    foreach ($paths as $logoFile) {
        error_log("Testando arquivo: " . $logoFile . " - Existe: " . (file_exists($logoFile) ? 'SIM' : 'NÃO'));
        
        if (file_exists($logoFile)) {
            header('Content-Type: image/webp');
            header('Content-Length: ' . filesize($logoFile));
            header('Cache-Control: public, max-age=31536000');
            readfile($logoFile);
            exit;
        }
    }
    
    // Se não encontrou, retornar erro
    http_response_code(404);
    echo "Logo não encontrada";
    exit;
}

// Servir outros arquivos estáticos
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|webp|ico|woff|woff2|ttf|eot)$/i', $uri)) {
    $file = __DIR__ . '/public' . $uri;
    
    if (file_exists($file)) {
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}

// Carregar configurações
Config::load();

// Inicializar sessão
Session::start();

// FALLBACK ESPECIAL PARA /documents - TRATAMENTO PRIORITÁRIO
if ($uri === '/documents') {
    error_log("FALLBACK DOCUMENTS: Interceptando /documents no index.php");
    
    // Verificar autenticação
    if (!Auth::check()) {
        error_log("FALLBACK DOCUMENTS: Usuário não autenticado, redirecionando");
        header('Location: /login');
        exit;
    }
    
    error_log("FALLBACK DOCUMENTS: Usuário autenticado, carregando DocumentController");
    
    try {
        // Instanciar o controller diretamente
        $controller = new \App\Controllers\DocumentController();
        $controller->index();
        exit;
    } catch (Exception $e) {
        error_log("FALLBACK DOCUMENTS: Erro no DocumentController: " . $e->getMessage());
        error_log("FALLBACK DOCUMENTS: Stack trace: " . $e->getTraceAsString());
        
        // Carregar página de documentos básica como fallback final
        $user = Auth::user();
        $documents = [];
        $isAdmin = Auth::isAdmin();
        
        // Função auxiliar para formatação de tamanhos
        $formatBytes = function($size) {
            $units = ['B', 'KB', 'MB', 'GB'];
            $unitIndex = 0;
            while ($size >= 1024 && $unitIndex < count($units) - 1) {
                $size /= 1024;
                $unitIndex++;
            }
            return round($size, 2) . ' ' . $units[$unitIndex];
        };
        
        $title = 'Documentos - Engenha Rio';
        $showSidebar = true;
        $activeMenu = 'documents';
        
        error_log("FALLBACK DOCUMENTS: Carregando view básica de documentos");
        
        ob_start();
        include __DIR__ . '/views/documents/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/views/layouts/app.php';
        exit;
    }
}

// Configurar roteamento
$router = new Router();

// Rotas públicas
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/assets/images/engenhario-logo-new.png', 'AssetController@logo');

// Rotas protegidas
$router->group(['middleware' => 'auth'], function($router) {
    $router->get('/dashboard', 'DashboardController@index');
    $router->get('/projects', 'ProjectController@index');
    $router->get('/projects/create', 'ProjectController@create');
    $router->post('/projects', 'ProjectController@store');
    $router->get('/projects/{id}', 'ProjectController@show');
    $router->get('/projects/{id}/edit', 'ProjectController@edit');
    $router->put('/projects/{id}', 'ProjectController@update');
    $router->delete('/projects/{id}', 'ProjectController@destroy');
    $router->put('/projects/{id}/status', 'ProjectController@updateStatus');
    
    $router->get('/documents', 'DocumentController@index');
    $router->post('/documents/upload', 'DocumentController@upload');
    $router->get('/documents/{id}', 'DocumentController@show');
    $router->get('/documents/{id}/edit', 'DocumentController@edit');
    $router->put('/documents/{id}', 'DocumentController@update');
    $router->get('/documents/{id}/preview', 'DocumentController@preview');
    $router->get('/documents/{id}/download', 'DocumentController@download');
    $router->delete('/documents/{id}', 'DocumentController@destroy');
    
    $router->get('/profile', 'ProfileController@index');
    $router->put('/profile', 'ProfileController@update');
    
    // Rotas administrativas
    $router->group(['middleware' => 'admin'], function($router) {
        $router->get('/admin', 'AdminController@index');
        $router->get('/admin/users', 'AdminController@users');
        $router->get('/admin/projects', 'AdminController@projects');
        $router->get('/admin/projects/export', 'AdminController@exportProjects');
        $router->get('/admin/reports', 'AdminController@reports');
        $router->get('/admin/history', 'AdminController@history');
        $router->get('/admin/settings', 'AdminController@settings');
        $router->post('/admin/settings/update', 'AdminController@updateSettings');
        
        // Rotas para gerenciamento de usuários
        $router->post('/admin/users/create', 'AdminController@createUser');
        $router->get('/admin/users/{id}/edit', 'AdminController@editUser');
        $router->get('/admin/edit-user/{id}', 'AdminController@editUser'); // Rota alternativa
        $router->get('/admin/users/{id}/view', 'AdminController@viewUser');
        $router->put('/admin/users/{id}', 'AdminController@updateUser');
        $router->delete('/admin/users/{id}', 'AdminController@deleteUser');
        $router->post('/admin/users/bulk-action', 'AdminController@bulkAction');
        $router->get('/admin/users/export', 'AdminController@exportUsers');
        
        // Rotas AJAX para ações específicas
        $router->post('/admin/users/{id}/approve', 'AdminController@approveUser');
        $router->post('/admin/users/{id}/reject', 'AdminController@rejectUser');
        $router->post('/admin/users/{id}/toggle-status', 'AdminController@toggleUserStatus');
        $router->post('/admin/users/{id}/reset-password', 'AdminController@resetPassword');
        
        // Rotas para funcionalidades de sistema
        $router->post('/admin/cache/clear', 'AdminController@clearCache');
        $router->get('/admin/export/data', 'AdminController@exportData');
        $router->get('/admin/logs', 'AdminController@viewLogs');
        $router->post('/admin/logs/clear', 'AdminController@clearLogs');
        $router->get('/admin/logs/download', 'AdminController@downloadLogs');
    });
});

// Log do estado antes do dispatch
error_log("INDEX.PHP - Antes do dispatch: URI = " . $uri);

// Executar roteamento
try {
    $router->dispatch();
    error_log("INDEX.PHP - Dispatch executado com sucesso");
} catch (Exception $e) {
    error_log("INDEX.PHP - Erro no dispatch: " . $e->getMessage());
    error_log("INDEX.PHP - Stack trace: " . $e->getTraceAsString());
    
    // Fallback final para qualquer erro
    if ($uri === '/documents') {
        error_log("INDEX.PHP - Fallback final para /documents");
        
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        // Página básica de documentos
        $user = Auth::user();
        $documents = [];
        $isAdmin = Auth::isAdmin();
        $formatBytes = function($size) {
            $units = ['B', 'KB', 'MB', 'GB'];
            $unitIndex = 0;
            while ($size >= 1024 && $unitIndex < count($units) - 1) {
                $size /= 1024;
                $unitIndex++;
            }
            return round($size, 2) . ' ' . $units[$unitIndex];
        };
        
        $title = 'Documentos - Engenha Rio';
        $showSidebar = true;
        $activeMenu = 'documents';
        
        ob_start();
        include __DIR__ . '/views/documents/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/views/layouts/app.php';
        exit;
    }
    
    // Para outras rotas, mostrar erro genérico
    http_response_code(500);
    echo "Erro interno do servidor";
}

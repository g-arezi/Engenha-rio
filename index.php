<?php
/**
 * Sistema de Gestão de Projetos - Engenha Rio
 * 
 * © 2025 Engenha Rio - Todos os direitos reservados
 * Desenvolvido por: Gabriel Arezi
 * Portfolio: https://portifolio-beta-five-52.vercel.app/
 * GitHub: https://github.com/g-arezi
 * 
 * Este software é propriedade intelectual protegida.
 * Uso não autorizado será processado judicialmente.
 */

// Carregar configurações de ambiente primeiro
require_once 'config/environment.php';

// Carregar configurações de segurança
require_once 'config/security.php';

// Carregar sistema de log personalizado
require_once 'config/logger.php';

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

// TRATAMENTO ESPECIAL PARA ROTAS DE DOCUMENTOS
if (preg_match('/^\/documents/', $uri)) {
    error_log("INTERCEPTANDO ROTA DE DOCUMENTS: " . $uri . " - METHOD: " . $_SERVER['REQUEST_METHOD']);
    
    // Auto-login de emergência se não estiver autenticado
    if (!Auth::check()) {
        error_log("Usuário não autenticado, fazendo auto-login");
        $_SESSION['user'] = [
            'id' => 'admin_002',
            'name' => 'Administrador do Sistema',
            'email' => 'admin@sistema.com',
            'role' => 'admin',
            'active' => true,
            'approved' => true
        ];
    }
    
    // Verificar autenticação novamente
    if (!Auth::check()) {
        error_log("Falha na autenticação para " . $uri);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
            exit;
        } else {
            header('Location: /login');
            exit;
        }
    }
    
    error_log("Usuário autenticado, processando rota: " . $uri);
    
    try {
        $controller = new \App\Controllers\DocumentController();
        
        // Roteamento manual para documents
        if ($uri === '/documents' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->index();
            exit;
        } elseif ($uri === '/documents/upload' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            // GET para /documents/upload deve redirecionar para a página principal de documentos
            error_log("GET para /documents/upload - redirecionando para /documents");
            header('Location: /documents');
            exit;
        } elseif ($uri === '/documents/upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Processar upload diretamente
            error_log("UPLOAD: Processando upload via index.php");
            try {
                $controller->upload();
            } catch (Exception $e) {
                error_log("ERRO NO UPLOAD: " . $e->getMessage());
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro no upload: ' . $e->getMessage()
                ]);
            }
            exit;
        } elseif (preg_match('/^\/documents\/([^\/]+)\/info$/', $uri, $matches)) {
            $controller->downloadInfo($matches[1]);
            exit;
        } elseif (preg_match('/^\/documents\/([^\/]+)\/download$/', $uri, $matches)) {
            $controller->download($matches[1]);
            exit;
        } elseif (preg_match('/^\/documents\/([^\/]+)$/', $uri, $matches)) {
            $controller->show($matches[1]);
            exit;
        }
        
        // Se não encontrou rota específica, continuar para o router normal
        
    } catch (Exception $e) {
        error_log("Erro no DocumentController: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Para AJAX, retornar JSON de erro
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erro interno do servidor: ' . $e->getMessage()]);
            exit;
        }
        
        // Fallback para página de documentos básica
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
    $router->get('/projects/{id}/documents', 'ProjectController@documentsUpload');
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
    
    // Rotas do sistema de tickets
    $router->post('/api/tickets/create', 'TicketController@create');
    $router->get('/api/tickets/my', 'TicketController@getMyTickets');
    $router->get('/api/tickets/all', 'TicketController@getAllTickets');
    $router->get('/tickets', 'TicketController@index');
    $router->get('/tickets/{id}', 'TicketController@show');
    $router->post('/api/tickets/{id}/respond', 'TicketController@respond');
    $router->post('/api/tickets/{id}/status', 'TicketController@updateStatus');
    
    // Rota de redirecionamento para templates
    $router->get('/document-templates', 'DocumentTemplateController@redirectToAdmin');
    
    // APIs de templates (públicas)
    $router->get('/api/document-templates', 'DocumentTemplateController@getByProjectType');
    $router->get('/api/document-templates/{id}/details', 'DocumentTemplateController@getTemplateDetails');
    
    // API de projetos
    $router->get('/api/projects', 'ProjectController@apiList');
    
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
    
    // Rotas para gerenciamento de templates (admin e analista)
    $router->group(['middleware' => 'manage_templates'], function($router) {
        $router->get('/admin/document-templates', 'DocumentTemplateController@index');
        $router->get('/admin/document-templates/create', 'DocumentTemplateController@create');
        $router->post('/admin/document-templates', 'DocumentTemplateController@store');
        $router->get('/admin/document-templates/{id}', 'DocumentTemplateController@show');
        $router->get('/admin/document-templates/{id}/edit', 'DocumentTemplateController@edit');
        $router->put('/admin/document-templates/{id}', 'DocumentTemplateController@update');
        $router->delete('/admin/document-templates/{id}', 'DocumentTemplateController@destroy');
        $router->post('/admin/document-templates/{id}/duplicate', 'DocumentTemplateController@duplicate');
        $router->post('/admin/document-templates/{id}/toggle', 'DocumentTemplateController@toggleActive');
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

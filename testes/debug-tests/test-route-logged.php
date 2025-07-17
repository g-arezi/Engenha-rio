<?php
// Teste com usuário admin logado

require_once '../vendor/autoload.php';

use App\Core\Router;
use App\Core\Session;
use App\Core\Auth;

// Iniciar sessão primeiro
session_start();

// Simular usuário admin logado
$_SESSION['user_id'] = 1;
$_SESSION['user'] = [
    'id' => 1,
    'name' => 'Admin Test',
    'email' => 'admin@test.com',
    'role' => 'admin'
];

// Simular REQUEST_URI
$_SERVER['REQUEST_URI'] = '/document-templates';
$_SERVER['REQUEST_METHOD'] = 'GET';

echo "Testando rota /document-templates com usuário admin logado\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "Session User ID: " . ($_SESSION['user_id'] ?? 'none') . "\n";
echo "Session User Role: " . ($_SESSION['user']['role'] ?? 'none') . "\n\n";

// Configurar debug mode
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redefinir output buffering para capturar redirecionamentos
ob_start();

try {
    // Criar router
    $router = new Router();
    
    // Simular as rotas do sistema
    $router->group(['middleware' => 'auth'], function($router) {
        $router->get('/document-templates', 'DocumentTemplateController@redirectToAdmin');
    });
    
    echo "Rota adicionada com sucesso\n";
    
    // Tentar despachar
    $router->dispatch();
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

$output = ob_get_contents();
ob_end_clean();

echo "Output capturado:\n";
echo $output;

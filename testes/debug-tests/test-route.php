<?php
// Teste direto da rota document-templates

require_once '../vendor/autoload.php';

use App\Core\Router;
use App\Core\Session;
use App\Core\Auth;

// Simular REQUEST_URI
$_SERVER['REQUEST_URI'] = '/document-templates';
$_SERVER['REQUEST_METHOD'] = 'GET';

// Criar router
$router = new Router();

// Configurar debug mode
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testando rota /document-templates\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n\n";

try {
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

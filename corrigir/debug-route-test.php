<?php
// Debug das rotas e autenticação
require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;
use App\Core\Router;

// Inicializar sessão
Session::start();

echo "<h1>Debug do Sistema de Rotas</h1>";

// Verificar autenticação
echo "<h2>Status da Autenticação:</h2>";
echo "Usuário autenticado: " . (Auth::check() ? 'SIM' : 'NÃO') . "<br>";

if (Auth::check()) {
    $user = Auth::user();
    echo "ID do usuário: " . ($user['id'] ?? 'N/A') . "<br>";
    echo "Nome: " . ($user['name'] ?? 'N/A') . "<br>";
    echo "Email: " . ($user['email'] ?? 'N/A') . "<br>";
    echo "Role: " . ($user['role'] ?? 'N/A') . "<br>";
    echo "É admin: " . (Auth::isAdmin() ? 'SIM' : 'NÃO') . "<br>";
} else {
    echo "Nenhum usuário logado.<br>";
    echo "Dados da sessão: <pre>" . print_r($_SESSION, true) . "</pre>";
}

// Verificar a URI atual
echo "<h2>Informações da Requisição:</h2>";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Parsed URI: " . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "<br>";

// Testar rotas específicas
echo "<h2>Teste de Rotas:</h2>";

$router = new Router();

// Configurar as rotas como no index.php
$router->group(['middleware' => 'auth'], function($router) {
    $router->get('/documents', 'DocumentController@index');
});

echo "Rotas configuradas com sucesso.<br>";

// Verificar se a classe DocumentController existe
echo "<h2>Verificação de Classes:</h2>";
echo "DocumentController existe: " . (class_exists('App\Controllers\DocumentController') ? 'SIM' : 'NÃO') . "<br>";

// Tentar instanciar o controller
try {
    $controller = new \App\Controllers\DocumentController();
    echo "DocumentController instanciado com sucesso.<br>";
    
    if (method_exists($controller, 'index')) {
        echo "Método index() existe no DocumentController.<br>";
    } else {
        echo "Método index() NÃO existe no DocumentController.<br>";
    }
} catch (Exception $e) {
    echo "Erro ao instanciar DocumentController: " . $e->getMessage() . "<br>";
}

// Links para testar
echo "<h2>Links de Teste:</h2>";
echo '<a href="/documents">Testar /documents</a><br>';
echo '<a href="/dashboard">Testar /dashboard</a><br>';
echo '<a href="/login">Testar /login</a><br>';

?>

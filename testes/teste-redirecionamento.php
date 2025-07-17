<?php
// Teste de redirecionamento - Simular acesso não autenticado
session_start();

// Limpar sessão para simular usuário não logado
session_destroy();
session_start();

echo "<h2>Teste de Redirecionamento</h2>";
echo "<p>Sessão limpa. Status: " . (session_status() === PHP_SESSION_ACTIVE ? 'Ativa' : 'Inativa') . "</p>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Dados da sessão: " . print_r($_SESSION, true) . "</p>";

// Testar Auth::check()
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';

$authResult = Auth::check();
echo "<p>Auth::check() resultado: " . ($authResult ? 'true' : 'false') . "</p>";

echo "<hr>";
echo "<h3>Testando Redirecionamento:</h3>";
echo "<a href='/projects/1/edit' style='background: #007bff; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Tentar Editar Projeto (deve redirecionar para login)</a>";
echo "<br><br>";
echo "<a href='/login' style='background: #28a745; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Ir para Login</a>";

echo "<hr>";
echo "<h3>Debug do Router:</h3>";
echo "<p>REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'não definido') . "</p>";
echo "<p>REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'não definido') . "</p>";
echo "<p>HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'não definido') . "</p>";

// Testar se o router está funcionando
if (isset($_GET['test_router'])) {
    echo "<h4>Testando Router Diretamente:</h4>";
    try {
        require_once 'src/Core/Router.php';
        $router = new Router();
        
        // Simular uma requisição para editar projeto
        $_SERVER['REQUEST_URI'] = '/projects/1/edit';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        echo "<p>Executando router para /projects/1/edit...</p>";
        ob_start();
        $router->handleRequest();
        $output = ob_get_clean();
        echo "<p>Output do router: " . htmlspecialchars($output) . "</p>";
        
    } catch (Exception $e) {
        echo "<p>Erro no router: " . $e->getMessage() . "</p>";
    }
}

echo "<br><a href='?test_router=1' style='background: #ffc107; color: black; padding: 10px; text-decoration: none; border-radius: 5px;'>Testar Router Diretamente</a>";
?>

<?php
require_once 'init.php';

try {
    echo "<h1>Teste DocumentController</h1>";
    
    $controller = new \App\Controllers\DocumentController();
    echo "<p>✅ DocumentController criado com sucesso</p>";
    
    // Testar se o método upload existe
    if (method_exists($controller, 'upload')) {
        echo "<p>✅ Método upload existe</p>";
    } else {
        echo "<p>❌ Método upload NÃO existe</p>";
    }
    
    // Testar Auth
    $user = \App\Core\Auth::user();
    if ($user) {
        echo "<p>✅ Usuário autenticado: " . htmlspecialchars($user['name']) . "</p>";
    } else {
        echo "<p>❌ Usuário NÃO autenticado</p>";
    }
    
    echo "<h2>Rotas registradas no Router:</h2>";
    $router = new \App\Core\Router();
    
    // Listar algumas rotas importantes
    echo "<pre>";
    echo "Verificando rota POST /documents/upload...\n";
    
    // Simular uma requisição POST
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['REQUEST_URI'] = '/documents/upload';
    
    echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
    echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
    
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p>❌ Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>

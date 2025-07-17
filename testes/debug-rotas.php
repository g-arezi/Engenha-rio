<?php
/**
 * Teste direto das rotas de documentos
 */

echo "=== TESTE DE ROTAS DE DOCUMENTOS ===\n\n";

// Simular diferentes URIs
$testUris = [
    '/documents',
    '/documents/doc_002',
    '/documents/doc_002/download',
    '/documents/upload'
];

foreach ($testUris as $testUri) {
    echo "Testando URI: $testUri\n";
    
    // Simular request
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = $testUri;
    
    // Verificar se router.php captaria isso
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // Rotas especiais que devem ir direto para o index.php
    $appRoutes = [
        '/documents',
        '/dashboard',
        '/projects',
        '/users',
        '/login',
        '/register',
        '/logout',
        '/api'
    ];
    
    $isAppRoute = false;
    foreach ($appRoutes as $route) {
        if ($uri === $route || strpos($uri, $route . '/') === 0) {
            $isAppRoute = true;
            break;
        }
    }
    
    if ($isAppRoute) {
        echo "  ✅ Seria direcionado para index.php\n";
    } else {
        echo "  ❌ Seria tratado como arquivo estático\n";
    }
    
    echo "\n";
}

echo "\n=== TESTE DO ROTEADOR ===\n\n";

require_once 'vendor/autoload.php';

// Inicializar sessão
session_start();

// Simular usuário logado
$_SESSION['user'] = [
    'id' => 'admin_001',
    'name' => 'Admin Teste',
    'type' => 'administrador'
];

try {
    $router = new \App\Core\Router();
    
    // Adicionar rotas de documentos
    $router->group(['middleware' => 'auth'], function($router) {
        $router->get('/documents', 'DocumentController@index');
        $router->post('/documents/upload', 'DocumentController@upload');
        $router->get('/documents/{id}', 'DocumentController@show');
        $router->get('/documents/{id}/download', 'DocumentController@download');
        $router->delete('/documents/{id}', 'DocumentController@destroy');
    });
    
    echo "✅ Router criado e rotas adicionadas\n\n";
    
    // Testar cada rota
    foreach ($testUris as $testUri) {
        echo "Testando dispatch para: $testUri\n";
        
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = $testUri;
        
        ob_start();
        try {
            $router->dispatch();
            $output = ob_get_clean();
            echo "  ✅ Dispatch executado (output: " . strlen($output) . " bytes)\n";
        } catch (Exception $e) {
            ob_get_clean();
            echo "  ❌ Erro: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro ao criar router: " . $e->getMessage() . "\n";
}

echo "\n=== TESTE DIRETO DO CONTROLLER ===\n\n";

try {
    $controller = new \App\Controllers\DocumentController();
    echo "✅ DocumentController criado\n";
    
    echo "Testando método index()...\n";
    ob_start();
    $controller->index();
    $output = ob_get_clean();
    echo "✅ index() executado (output: " . strlen($output) . " bytes)\n";
    
} catch (Exception $e) {
    echo "❌ Erro no controller: " . $e->getMessage() . "\n";
}

?>

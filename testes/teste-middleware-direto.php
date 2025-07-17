<?php
// Teste específico do middleware de autenticação
session_start();

echo "<h2>Teste do Middleware de Autenticação</h2>";

// Limpar qualquer output anterior
if (ob_get_level()) {
    ob_clean();
}

// Incluir as classes necessárias
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';
require_once 'src/Core/Router.php';

echo "<h3>1. Estado da Sessão:</h3>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session Status: " . (session_status() === PHP_SESSION_ACTIVE ? 'Ativa' : 'Inativa') . "</p>";
echo "<p>Dados da sessão: " . print_r($_SESSION, true) . "</p>";
echo "<p>Auth::check(): " . (Auth::check() ? 'true' : 'false') . "</p>";

echo "<h3>2. Testando Middleware Diretamente:</h3>";

try {
    $router = new Router();
    
    // Usar reflection para acessar método privado
    $reflection = new ReflectionClass($router);
    $method = $reflection->getMethod('executeMiddleware');
    $method->setAccessible(true);
    
    echo "<p>Executando middleware 'auth'...</p>";
    
    // Capturar output do middleware
    ob_start();
    
    try {
        $result = $method->invoke($router, 'auth');
        $output = ob_get_clean();
        echo "<p>✅ Middleware executado. Resultado: " . ($result ? 'true' : 'false') . "</p>";
        if ($output) {
            echo "<p>Output do middleware: " . htmlspecialchars($output) . "</p>";
        }
    } catch (Exception $e) {
        $output = ob_get_clean();
        echo "<p>❌ Middleware falhou: " . $e->getMessage() . "</p>";
        if ($output) {
            echo "<p>Output capturado: " . htmlspecialchars($output) . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erro ao criar router: " . $e->getMessage() . "</p>";
}

echo "<h3>3. Simulando Requisição Completa:</h3>";

// Simular uma requisição GET para /projects/1/edit
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/projects/1/edit';

echo "<p>Simulando requisição: GET /projects/1/edit</p>";

try {
    $router = new Router();
    
    // Definir as rotas necessárias
    $router->get('/projects/{id}/edit', 'ProjectController@edit')->middleware('auth');
    
    echo "<p>Rota definida com middleware auth</p>";
    
    // Capturar output da execução
    ob_start();
    
    try {
        $router->handleRequest();
        $output = ob_get_clean();
        echo "<p>✅ Requisição processada com sucesso!</p>";
        if ($output) {
            echo "<p>Output: " . htmlspecialchars(substr($output, 0, 500)) . "...</p>";
        }
    } catch (Exception $e) {
        $output = ob_get_clean();
        echo "<p>❌ Erro na requisição: " . $e->getMessage() . "</p>";
        if ($output) {
            echo "<p>Output capturado: " . htmlspecialchars($output) . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erro geral: " . $e->getMessage() . "</p>";
}

echo "<h3>4. Verificação de Headers:</h3>";

// Verificar se headers foram enviados
if (headers_sent($file, $line)) {
    echo "<p>⚠️ Headers já enviados em $file:$line</p>";
} else {
    echo "<p>✅ Headers ainda não enviados</p>";
}

// Listar headers que seriam enviados
$headers = headers_list();
if ($headers) {
    echo "<p>Headers preparados:</p>";
    echo "<ul>";
    foreach ($headers as $header) {
        echo "<li>" . htmlspecialchars($header) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Nenhum header preparado</p>";
}

?>

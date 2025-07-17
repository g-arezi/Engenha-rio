<?php
// Teste específico para 404 na edição
session_start();

// Forçar login para teste
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

echo "<h1>🔧 Teste Específico - Edição Projeto</h1>";

try {
    require_once 'vendor/autoload.php';
    require_once 'src/Core/Controller.php';
    require_once 'src/Core/Model.php';
    require_once 'src/Core/Auth.php';
    require_once 'src/Models/Project.php';
    require_once 'src/Models/User.php';
    require_once 'src/Controllers/ProjectController.php';
    
    echo "<h2>1. Testando Acesso Direto ao Controller</h2>";
    
    $controller = new \App\Controllers\ProjectController();
    
    echo "Testando edit com project_001...<br>";
    
    // Capturar output
    ob_start();
    try {
        $controller->edit('project_001');
        echo "✅ Método edit executado sem erro<br>";
    } catch (Exception $e) {
        echo "❌ Erro: " . $e->getMessage() . "<br>";
        echo "Arquivo: " . $e->getFile() . " linha " . $e->getLine() . "<br>";
    }
    $output = ob_get_clean();
    
    if ($output) {
        echo "Output capturado:<br><pre>" . htmlspecialchars($output) . "</pre>";
    }
    
    echo "<h2>2. Verificando Router</h2>";
    
    // Tentar simular uma requisição
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/projects/project_001/edit';
    
    echo "URI simulada: {$_SERVER['REQUEST_URI']}<br>";
    
    // Verificar se o router existe e funciona
    if (file_exists('router.php')) {
        echo "Router encontrado, tentando incluir...<br>";
        try {
            include_once 'router.php';
        } catch (Exception $e) {
            echo "Erro no router: " . $e->getMessage() . "<br>";
        }
    }
    
} catch (Exception $e) {
    echo "Erro geral: " . $e->getMessage() . "<br>";
    echo "Stack: <pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>3. Verificação de URLs</h2>";
echo "<p>Tente estas URLs diretamente:</p>";
echo "<ul>";
echo "<li><a href='/projects/project_001/edit'>/projects/project_001/edit</a></li>";
echo "<li><a href='http://localhost:8000/projects/project_001/edit'>http://localhost:8000/projects/project_001/edit</a></li>";
echo "</ul>";

echo "<h2>4. Debug de Roteamento</h2>";

// Verificar como o sistema processa URLs
if (file_exists('index.php')) {
    $indexContent = file_get_contents('index.php');
    
    // Procurar por padrões de roteamento
    if (strpos($indexContent, '$router->get') !== false) {
        echo "✅ Sistema de roteamento encontrado no index.php<br>";
        
        // Extrair as rotas de projetos
        preg_match_all('/\$router->get\([\'"]([^\'"]*)[\'"],[\'"]([^\'"]*)[\'"]\)/', $indexContent, $matches);
        
        echo "<h3>Rotas GET encontradas:</h3>";
        for ($i = 0; $i < count($matches[0]); $i++) {
            $route = $matches[1][$i];
            $handler = $matches[2][$i];
            if (strpos($route, 'projects') !== false) {
                echo "- <code>$route</code> → <code>$handler</code><br>";
            }
        }
    }
}
?>

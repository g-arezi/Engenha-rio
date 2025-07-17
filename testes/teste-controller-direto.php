<?php
/**
 * Teste de rota direta sem middleware
 */

// Simular ambiente
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/projects/project_001/edit';

require_once 'vendor/autoload.php';
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';
require_once 'src/Models/Project.php';
require_once 'src/Models/User.php';
require_once 'src/Controllers/ProjectController.php';

// Iniciar sessão e fazer login
\App\Core\Session::start();
\App\Core\Session::set('user_id', 'admin_002');
\App\Core\Session::set('role', 'admin');

echo "<h1>🎯 Teste Direto do Controller</h1>";

try {
    echo "<h2>1. Verificando Autenticação</h2>";
    $isLoggedIn = \App\Core\Auth::check();
    echo "Auth::check(): " . ($isLoggedIn ? 'true' : 'false') . "<br>";
    
    if ($isLoggedIn) {
        echo "✅ Usuário autenticado<br>";
        
        echo "<h2>2. Testando Controller Diretamente</h2>";
        $controller = new \App\Controllers\ProjectController();
        
        echo "Chamando controller->edit('project_001')...<br>";
        
        // Capturar qualquer output
        ob_start();
        try {
            $controller->edit('project_001');
            echo "✅ Controller executado sem erros<br>";
        } catch (Exception $e) {
            echo "❌ Erro no controller: " . $e->getMessage() . "<br>";
            echo "Arquivo: " . $e->getFile() . " linha " . $e->getLine() . "<br>";
        }
        $output = ob_get_clean();
        
        if ($output) {
            echo "<h3>Output do Controller:</h3>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
        }
        
    } else {
        echo "❌ Falha na autenticação<br>";
    }
    
    echo "<h2>3. Verificando Projeto</h2>";
    $projectModel = new \App\Models\Project();
    $project = $projectModel->find('project_001');
    
    if ($project) {
        echo "✅ Projeto encontrado: {$project['name']}<br>";
    } else {
        echo "❌ Projeto não encontrado<br>";
    }
    
    echo "<h2>4. Verificando View</h2>";
    $viewPath = __DIR__ . '/views/projects/edit.php';
    if (file_exists($viewPath)) {
        echo "✅ View existe: $viewPath<br>";
    } else {
        echo "❌ View não encontrada: $viewPath<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro geral: " . $e->getMessage() . "<br>";
    echo "Stack: <pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h2>🔧 Solução Definitiva</h2>";
echo "<p>Se o controller funciona diretamente mas a URL retorna 404, o problema está no:</p>";
echo "<ol>";
echo "<li><strong>Sistema de Roteamento</strong> - Não está encontrando a rota</li>";
echo "<li><strong>Middleware de Autenticação</strong> - Bloqueando antes de chegar no controller</li>";
echo "<li><strong>Configuração do Servidor PHP</strong> - router.php não está funcionando</li>";
echo "</ol>";

echo "<h2>📋 Próximos Passos</h2>";
echo "<ol>";
echo "<li>Fazer login normal no sistema: <a href='http://localhost:8000/login' target='_blank'>Login</a></li>";
echo "<li>Ir para projetos: <a href='http://localhost:8000/projects' target='_blank'>Projetos</a></li>";
echo "<li>Clicar em 'Editar' em qualquer projeto</li>";
echo "</ol>";
?>

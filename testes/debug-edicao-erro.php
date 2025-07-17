<?php
/**
 * Debug para capturar erros na edição de projetos
 */

// Configurar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Simular login como admin
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

echo "<h1>🐛 Debug - Edição de Projetos</h1>";
echo "<hr>";

try {
    require_once 'vendor/autoload.php';
    require_once 'src/Core/Controller.php';
    require_once 'src/Core/Model.php';
    require_once 'src/Core/Auth.php';
    require_once 'src/Models/Project.php';
    require_once 'src/Models/User.php';
    require_once 'src/Controllers/ProjectController.php';
    
    echo "<h2>1. Verificando Dependências</h2>";
    echo "✅ Classes carregadas com sucesso<br>";
    
    echo "<h2>2. Verificando Autenticação</h2>";
    $user = \App\Core\Auth::user();
    if ($user) {
        echo "✅ Usuário logado: {$user['name']} ({$user['role']})<br>";
    } else {
        echo "❌ Usuário não logado<br>";
    }
    
    echo "<h2>3. Testando ProjectController::edit()</h2>";
    
    $projectId = 'project_001';
    $controller = new \App\Controllers\ProjectController();
    
    echo "Tentando acessar projeto: {$projectId}<br>";
    
    // Capturar qualquer output ou erro
    ob_start();
    try {
        $controller->edit($projectId);
    } catch (Exception $e) {
        echo "❌ Erro capturado: " . $e->getMessage() . "<br>";
        echo "Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")<br>";
        echo "<pre>Stack trace:\n" . $e->getTraceAsString() . "</pre>";
    } catch (Error $e) {
        echo "❌ Erro fatal: " . $e->getMessage() . "<br>";
        echo "Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")<br>";
        echo "<pre>Stack trace:\n" . $e->getTraceAsString() . "</pre>";
    }
    $output = ob_get_clean();
    
    if (!empty($output)) {
        echo "Output capturado:<br>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }
    
    echo "<h2>4. Verificando Projeto Diretamente</h2>";
    $projectModel = new \App\Models\Project();
    $project = $projectModel->find($projectId);
    
    if ($project) {
        echo "✅ Projeto encontrado:<br>";
        echo "<ul>";
        echo "<li>ID: {$project['id']}</li>";
        echo "<li>Nome: {$project['name']}</li>";
        echo "<li>Status: {$project['status']}</li>";
        echo "<li>Client ID: " . ($project['client_id'] ?? 'N/A') . "</li>";
        echo "<li>User ID: " . ($project['user_id'] ?? 'N/A') . "</li>";
        echo "<li>Analyst ID: " . ($project['analyst_id'] ?? 'N/A') . "</li>";
        echo "</ul>";
    } else {
        echo "❌ Projeto não encontrado<br>";
    }
    
    echo "<h2>5. Testando Método Update</h2>";
    
    // Simular dados POST
    $_POST = [
        '_method' => 'PUT',
        'name' => 'Teste de Edição',
        'description' => 'Descrição de teste para verificar se a edição funciona',
        'status' => 'em_andamento'
    ];
    
    echo "Simulando dados POST:<br>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    ob_start();
    try {
        $controller->update($projectId);
    } catch (Exception $e) {
        echo "❌ Erro no update: " . $e->getMessage() . "<br>";
        echo "Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")<br>";
    } catch (Error $e) {
        echo "❌ Erro fatal no update: " . $e->getMessage() . "<br>";
        echo "Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")<br>";
    }
    $updateOutput = ob_get_clean();
    
    if (!empty($updateOutput)) {
        echo "Output do update:<br>";
        echo "<pre>" . htmlspecialchars($updateOutput) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro geral: " . $e->getMessage() . "<br>";
    echo "Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")<br>";
    echo "<pre>Stack trace:\n" . $e->getTraceAsString() . "</pre>";
} catch (Error $e) {
    echo "❌ Erro fatal geral: " . $e->getMessage() . "<br>";
    echo "Arquivo: " . $e->getFile() . " (linha " . $e->getLine() . ")<br>";
    echo "<pre>Stack trace:\n" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h2>6. Links de Teste</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/projects/project_001/edit' target='_blank'>Tentar Editar Projeto 1</a></li>";
echo "<li><a href='http://localhost:8000/projects' target='_blank'>Ver Todos os Projetos</a></li>";
echo "</ul>";
?>

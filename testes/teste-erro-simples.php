<?php
// Teste simples para verificar erro na edição
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

echo "<h1>Teste Simples - Erro na Edição</h1>";

// Verificar se o arquivo de view existe
$viewPath = __DIR__ . '/views/projects/edit.php';
echo "<h2>1. Verificando View</h2>";
if (file_exists($viewPath)) {
    echo "✅ View encontrada: $viewPath<br>";
} else {
    echo "❌ View NÃO encontrada: $viewPath<br>";
}

// Verificar controller
echo "<h2>2. Verificando Controller</h2>";
$controllerPath = __DIR__ . '/src/Controllers/ProjectController.php';
if (file_exists($controllerPath)) {
    echo "✅ Controller encontrado: $controllerPath<br>";
} else {
    echo "❌ Controller NÃO encontrado: $controllerPath<br>";
}

// Verificar erro específico na view
echo "<h2>3. Verificando Conteúdo da View</h2>";
if (file_exists($viewPath)) {
    $viewContent = file_get_contents($viewPath);
    
    // Verificar se há problemas óbvios
    if (strpos($viewContent, '<?php') === false) {
        echo "❌ View não tem tag PHP de abertura<br>";
    } else {
        echo "✅ View tem tag PHP de abertura<br>";
    }
    
    // Verificar se há variáveis não definidas
    if (strpos($viewContent, '$project') !== false) {
        echo "✅ View usa variável \$project<br>";
    }
    
    if (strpos($viewContent, '$user') !== false) {
        echo "✅ View usa variável \$user<br>";
    }
    
    // Mostrar as primeiras linhas
    $lines = explode("\n", $viewContent);
    echo "<h3>Primeiras 10 linhas da view:</h3>";
    echo "<pre>";
    for ($i = 0; $i < min(10, count($lines)); $i++) {
        echo ($i + 1) . ": " . htmlspecialchars($lines[$i]) . "\n";
    }
    echo "</pre>";
}

// Teste direto da URL
echo "<h2>4. Teste de Acesso Direto</h2>";
echo "<a href='/projects/project_001/edit' target='_blank'>Tentar acessar /projects/project_001/edit</a><br>";

// Verificar dados do projeto
require_once 'vendor/autoload.php';
require_once 'src/Models/Project.php';

$projectModel = new \App\Models\Project();
$project = $projectModel->find('project_001');

echo "<h2>5. Dados do Projeto</h2>";
if ($project) {
    echo "<pre>" . print_r($project, true) . "</pre>";
} else {
    echo "❌ Projeto não encontrado<br>";
}

// Verificar último erro PHP
$lastError = error_get_last();
if ($lastError) {
    echo "<h2>6. Último Erro PHP</h2>";
    echo "<pre>" . print_r($lastError, true) . "</pre>";
}
?>

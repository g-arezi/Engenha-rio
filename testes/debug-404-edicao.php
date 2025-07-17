<?php
/**
 * Debug específico para o erro 404 na edição de projetos
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Debug 404 - Edição de Projetos</h1>";
echo "<hr>";

// Verificar se o servidor está rodando
echo "<h2>1. Verificando Servidor</h2>";
echo "URL atual: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Método: " . $_SERVER['REQUEST_METHOD'] . "<br>";

// Verificar rotas
echo "<h2>2. Verificando Rotas</h2>";
$indexPath = __DIR__ . '/index.php';
if (file_exists($indexPath)) {
    $indexContent = file_get_contents($indexPath);
    if (strpos($indexContent, "projects/{id}/edit") !== false) {
        echo "✅ Rota /projects/{id}/edit encontrada no index.php<br>";
    } else {
        echo "❌ Rota /projects/{id}/edit NÃO encontrada no index.php<br>";
    }
} else {
    echo "❌ Arquivo index.php não encontrado<br>";
}

// Verificar se o controller existe
echo "<h2>3. Verificando Controller</h2>";
$controllerPath = __DIR__ . '/src/Controllers/ProjectController.php';
if (file_exists($controllerPath)) {
    echo "✅ ProjectController encontrado<br>";
    
    // Verificar se o método edit existe
    $controllerContent = file_get_contents($controllerPath);
    if (strpos($controllerContent, "public function edit(") !== false) {
        echo "✅ Método edit() encontrado no controller<br>";
    } else {
        echo "❌ Método edit() NÃO encontrado no controller<br>";
    }
} else {
    echo "❌ ProjectController não encontrado<br>";
}

// Verificar projetos disponíveis
echo "<h2>4. Verificando Projetos Disponíveis</h2>";
$projectsPath = __DIR__ . '/data/projects.json';
if (file_exists($projectsPath)) {
    $projectsData = json_decode(file_get_contents($projectsPath), true);
    if ($projectsData) {
        echo "✅ Projetos encontrados:<br>";
        foreach ($projectsData as $id => $project) {
            $editUrl = "/projects/{$id}/edit";
            echo "- <strong>{$project['name']}</strong> (ID: {$id}) - <a href='http://localhost:8000{$editUrl}' target='_blank'>Testar Edição</a><br>";
        }
    } else {
        echo "❌ Erro ao ler dados dos projetos<br>";
    }
} else {
    echo "❌ Arquivo projects.json não encontrado<br>";
}

// Teste direto da URL
echo "<h2>5. Testes de URL</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/projects' target='_blank'>Lista de Projetos</a></li>";
echo "<li><a href='http://localhost:8000/projects/project_001' target='_blank'>Ver Projeto 1</a></li>";
echo "<li><a href='http://localhost:8000/projects/project_001/edit' target='_blank'>Editar Projeto 1</a></li>";
echo "</ul>";

// Verificar se há middleware ou autenticação bloqueando
echo "<h2>6. Verificando Autenticação</h2>";
session_start();
if (isset($_SESSION['user_id'])) {
    echo "✅ Usuário logado: " . $_SESSION['user_id'] . " (Role: " . ($_SESSION['role'] ?? 'N/A') . ")<br>";
} else {
    echo "❌ Usuário NÃO está logado<br>";
    echo "<p><strong>Isso pode ser a causa do 404!</strong> Muitas rotas requerem autenticação.</p>";
    
    // Fazer login automático para teste
    $_SESSION['user_id'] = 'admin_001';
    $_SESSION['role'] = 'admin';
    echo "✅ Login automático realizado para teste<br>";
}

// Verificar .htaccess ou rewrite rules
echo "<h2>7. Verificando Rewrite Rules</h2>";
$htaccessPath = __DIR__ . '/.htaccess';
if (file_exists($htaccessPath)) {
    echo "✅ Arquivo .htaccess encontrado<br>";
    $htaccessContent = file_get_contents($htaccessPath);
    echo "<pre>" . htmlspecialchars($htaccessContent) . "</pre>";
} else {
    echo "⚠️ Arquivo .htaccess não encontrado - pode estar usando roteamento interno do PHP<br>";
}

// Verificar router class
echo "<h2>8. Verificando Router Class</h2>";
$routerPath = __DIR__ . '/router.php';
if (file_exists($routerPath)) {
    echo "✅ Arquivo router.php encontrado<br>";
} else {
    echo "❌ Arquivo router.php não encontrado<br>";
}

// Verificar vendor/autoload
echo "<h2>9. Verificando Autoload</h2>";
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    echo "✅ Composer autoload encontrado<br>";
} else {
    echo "❌ Composer autoload não encontrado<br>";
}

echo "<hr>";
echo "<h2>💡 Possíveis Causas do 404:</h2>";
echo "<ol>";
echo "<li><strong>Autenticação:</strong> Rota protegida e usuário não logado</li>";
echo "<li><strong>Middleware:</strong> Middleware bloqueando acesso</li>";
echo "<li><strong>Router:</strong> Problema no sistema de roteamento</li>";
echo "<li><strong>Controller:</strong> Método edit() com erro</li>";
echo "<li><strong>Autoload:</strong> Classes não carregadas corretamente</li>";
echo "</ol>";
?>

<style>
.alert { padding: 10px; margin: 10px 0; border-radius: 5px; }
.alert-warning { background: #fff3cd; border: 1px solid #ffeaa7; }
.alert-success { background: #d4edda; border: 1px solid #c3e6cb; }
.alert-danger { background: #f8d7da; border: 1px solid #f5c6cb; }
</style>

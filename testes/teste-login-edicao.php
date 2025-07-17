<?php
/**
 * Teste de Login e Acesso à Edição
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

echo "<h1>🔐 Teste: Login + Edição de Projeto</h1>";
echo "<hr>";

try {
    require_once 'vendor/autoload.php';
    require_once 'src/Core/Auth.php';
    require_once 'src/Models/User.php';
    
    echo "<h2>1. Estado Inicial da Sessão</h2>";
    echo "Sessão antes: <pre>" . print_r($_SESSION, true) . "</pre>";
    
    echo "<h2>2. Verificando Autenticação</h2>";
    $authCheck = \App\Core\Auth::check();
    echo "Auth::check() retorna: " . ($authCheck ? 'true' : 'false') . "<br>";
    
    $user = \App\Core\Auth::user();
    echo "Auth::user() retorna: <pre>" . print_r($user, true) . "</pre>";
    
    if (!$authCheck) {
        echo "<h3>❌ Usuário não autenticado - Fazendo login...</h3>";
        
        // Fazer login manual
        $_SESSION['user_id'] = 'admin_001';
        $_SESSION['role'] = 'admin';
        $_SESSION['name'] = 'Administrador';
        
        echo "Dados de sessão definidos:<br>";
        echo "- user_id: {$_SESSION['user_id']}<br>";
        echo "- role: {$_SESSION['role']}<br>";
        
        // Verificar novamente
        $authCheck = \App\Core\Auth::check();
        echo "Auth::check() após login: " . ($authCheck ? 'true' : 'false') . "<br>";
        
        $user = \App\Core\Auth::user();
        echo "Auth::user() após login: <pre>" . print_r($user, true) . "</pre>";
    } else {
        echo "✅ Usuário já autenticado<br>";
    }
    
    echo "<h2>3. Testando Acesso à Rota de Edição</h2>";
    
    if ($authCheck) {
        echo "✅ Autenticação OK - Pode acessar rotas protegidas<br>";
        echo "<p><strong>Teste estas URLs agora:</strong></p>";
        echo "<ul>";
        echo "<li><a href='http://localhost:8000/projects' target='_blank'>Lista de Projetos</a></li>";
        echo "<li><a href='http://localhost:8000/projects/project_001' target='_blank'>Ver Projeto 1</a></li>";
        echo "<li><a href='http://localhost:8000/projects/project_001/edit' target='_blank'>🎯 EDITAR Projeto 1</a></li>";
        echo "</ul>";
        
        echo "<h3>Cookie de Sessão:</h3>";
        echo "PHPSESSID: " . (isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : 'Não definido') . "<br>";
        
    } else {
        echo "❌ Falha na autenticação - Não pode acessar rotas protegidas<br>";
    }
    
    echo "<h2>4. Debug de Headers</h2>";
    echo "Headers que serão enviados:<br>";
    echo "- User-Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'N/A') . "<br>";
    echo "- Cookie: " . ($_SERVER['HTTP_COOKIE'] ?? 'N/A') . "<br>";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
    echo "Stack: <pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h2>💡 Solução Sugerida</h2>";
echo "<p>Se ainda der 404 após este teste, o problema pode ser:</p>";
echo "<ol>";
echo "<li><strong>Sessão não persistindo</strong> entre requisições</li>";
echo "<li><strong>Cookie de sessão</strong> não sendo enviado</li>";
echo "<li><strong>Middleware</strong> não reconhecendo a autenticação</li>";
echo "</ol>";

echo "<h2>🔧 Ação Recomendada</h2>";
echo "<p>1. Faça login normal no sistema primeiro: <a href='http://localhost:8000/login' target='_blank'>Página de Login</a></p>";
echo "<p>2. Depois acesse: <a href='http://localhost:8000/projects/project_001/edit' target='_blank'>Editar Projeto</a></p>";
?>

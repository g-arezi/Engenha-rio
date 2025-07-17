<?php
/**
 * Login via POST para autenticação real
 */

require_once 'vendor/autoload.php';
require_once 'src/Core/Auth.php';
require_once 'src/Core/Session.php';
require_once 'src/Models/User.php';

\App\Core\Session::start();

echo "<h1>🔑 Login Real via Sistema</h1>";

// Simular POST de login
$_POST['email'] = 'admin@sistema.com';
$_POST['password'] = 'admin123'; // Senha padrão do admin

echo "<h2>1. Tentando Login com Credenciais</h2>";
echo "Email: {$_POST['email']}<br>";
echo "Password: {$_POST['password']}<br>";

try {
    $loginResult = \App\Core\Auth::login($_POST['email'], $_POST['password']);
    
    echo "Resultado do login: " . ($loginResult ? 'SUCESSO' : 'FALHA') . "<br>";
    
    if ($loginResult) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h3>✅ LOGIN REALIZADO COM SUCESSO!</h3>";
        
        $user = \App\Core\Auth::user();
        if ($user) {
            echo "Usuário logado: {$user['name']} ({$user['role']})<br>";
        }
        
        echo "<p><strong>Agora teste a edição:</strong></p>";
        echo "<ul>";
        echo "<li><a href='http://localhost:8000/projects' target='_blank'>📋 Lista de Projetos</a></li>";
        echo "<li><a href='http://localhost:8000/projects/project_001/edit' target='_blank'>✏️ EDITAR Projeto 1</a></li>";
        echo "</ul>";
        echo "</div>";
        
        // Redirecionar automaticamente
        echo "<script>";
        echo "setTimeout(function() {";
        echo "  window.open('http://localhost:8000/projects/project_001/edit', '_blank');";
        echo "}, 2000);";
        echo "</script>";
        echo "<p><em>Abrindo página de edição em 2 segundos...</em></p>";
        
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h3>❌ FALHA NO LOGIN</h3>";
        echo "<p>Credenciais inválidas ou usuário não encontrado.</p>";
        
        // Tentar encontrar um usuário válido
        echo "<h3>Usuários disponíveis:</h3>";
        $userModel = new \App\Models\User();
        $users = $userModel->all();
        
        foreach ($users as $user) {
            if ($user['role'] === 'admin') {
                echo "- {$user['name']} ({$user['email']}) - Role: {$user['role']}<br>";
            }
        }
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro no login: " . $e->getMessage() . "<br>";
}

echo "<h2>2. Estado da Sessão</h2>";
echo "user_id: " . \App\Core\Session::get('user_id', 'não definido') . "<br>";
echo "Auth::check(): " . (\App\Core\Auth::check() ? 'true' : 'false') . "<br>";

echo "<h2>3. Teste Manual</h2>";
echo "<p>Se o login automático não funcionou, tente:</p>";
echo "<ol>";
echo "<li><a href='http://localhost:8000/login' target='_blank'>Ir para a página de login</a></li>";
echo "<li>Usar as credenciais:<br>";
echo "   - <strong>Email:</strong> admin@sistema.com<br>";
echo "   - <strong>Senha:</strong> admin123</li>";
echo "<li>Depois acessar: <a href='http://localhost:8000/projects/project_001/edit' target='_blank'>Editar Projeto</a></li>";
echo "</ol>";
?>

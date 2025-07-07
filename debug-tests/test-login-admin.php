<?php
require_once 'vendor/autoload.php';

use App\Core\Config;
use App\Core\Session;
use App\Core\Auth;
use App\Models\User;

echo "<h2>🔍 Teste de Login - admin@sistema.com</h2>";

// Inicializar
Config::load();
Session::start();

// Dados de teste
$email = 'admin@sistema.com';
$password = 'password';

echo "<h3>📋 Dados de Login:</h3>";
echo "<ul>";
echo "<li><strong>Email:</strong> $email</li>";
echo "<li><strong>Senha:</strong> $password</li>";
echo "</ul>";

// Verificar se o usuário existe
$userModel = new User();
$user = $userModel->findByEmail($email);

echo "<h3>👤 Verificação do Usuário:</h3>";
if ($user) {
    echo "<ul>";
    echo "<li><strong>Usuário encontrado:</strong> ✅ SIM</li>";
    echo "<li><strong>Nome:</strong> " . htmlspecialchars($user['name']) . "</li>";
    echo "<li><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</li>";
    echo "<li><strong>Role:</strong> " . htmlspecialchars($user['role']) . "</li>";
    echo "<li><strong>Active:</strong> " . ($user['active'] ? '✅ SIM' : '❌ NÃO') . "</li>";
    echo "<li><strong>Approved:</strong> " . ($user['approved'] ? '✅ SIM' : '❌ NÃO') . "</li>";
    echo "</ul>";
    
    // Verificar senha
    echo "<h3>🔐 Verificação da Senha:</h3>";
    $passwordValid = password_verify($password, $user['password']);
    echo "<ul>";
    echo "<li><strong>Senha válida:</strong> " . ($passwordValid ? '✅ SIM' : '❌ NÃO') . "</li>";
    echo "<li><strong>Hash armazenado:</strong> " . htmlspecialchars($user['password']) . "</li>";
    echo "</ul>";
    
    // Tentar fazer login
    echo "<h3>🚪 Tentativa de Login:</h3>";
    $loginResult = Auth::login($email, $password);
    echo "<ul>";
    echo "<li><strong>Login bem-sucedido:</strong> " . ($loginResult ? '✅ SIM' : '❌ NÃO') . "</li>";
    echo "</ul>";
    
    if ($loginResult) {
        // Verificar se está logado
        echo "<h3>✅ Verificações Pós-Login:</h3>";
        echo "<ul>";
        echo "<li><strong>Auth::check():</strong> " . (Auth::check() ? '✅ SIM' : '❌ NÃO') . "</li>";
        echo "<li><strong>Auth::isAdmin():</strong> " . (Auth::isAdmin() ? '✅ SIM' : '❌ NÃO') . "</li>";
        echo "<li><strong>Auth::isAnalyst():</strong> " . (Auth::isAnalyst() ? '✅ SIM' : '❌ NÃO') . "</li>";
        echo "<li><strong>Auth::isClient():</strong> " . (Auth::isClient() ? '✅ SIM' : '❌ NÃO') . "</li>";
        echo "</ul>";
        
        $loggedUser = Auth::user();
        if ($loggedUser) {
            echo "<h3>👤 Usuário Logado:</h3>";
            echo "<ul>";
            echo "<li><strong>Nome:</strong> " . htmlspecialchars($loggedUser['name']) . "</li>";
            echo "<li><strong>Email:</strong> " . htmlspecialchars($loggedUser['email']) . "</li>";
            echo "<li><strong>Role:</strong> " . htmlspecialchars($loggedUser['role']) . "</li>";
            echo "</ul>";
        }
        
        echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>🎉 Login Realizado com Sucesso!</h4>";
        echo "<p>Agora você pode acessar o sistema como administrador.</p>";
        echo "<p><a href='/dashboard' class='btn btn-primary'>Ir para Dashboard</a></p>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>❌ Falha no Login</h4>";
        echo "<p>Verifique os dados e tente novamente.</p>";
        echo "</div>";
    }
} else {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>❌ Usuário Não Encontrado</h4>";
    echo "<p>O email '$email' não foi encontrado no sistema.</p>";
    echo "</div>";
}

echo "<hr>";
echo "<h3>📊 Todos os Usuários Administradores:</h3>";
$allUsers = $userModel->all();
echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Nome</th><th>Email</th><th>Role</th><th>Status</th><th>Senha de Teste</th></tr>";

foreach ($allUsers as $u) {
    if ($u['role'] === 'admin') {
        $statusText = ($u['active'] && $u['approved']) ? '✅ Ativo' : '❌ Inativo';
        $testPassword = '';
        
        // Verificar se a senha é '123456' ou 'password'
        if (password_verify('123456', $u['password'])) {
            $testPassword = '123456';
        } elseif (password_verify('password', $u['password'])) {
            $testPassword = 'password';
        } else {
            $testPassword = 'Desconhecida';
        }
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($u['name']) . "</td>";
        echo "<td>" . htmlspecialchars($u['email']) . "</td>";
        echo "<td><span style='background: #007bff; color: white; padding: 3px 8px; border-radius: 3px;'>" . htmlspecialchars($u['role']) . "</span></td>";
        echo "<td>$statusText</td>";
        echo "<td><strong>$testPassword</strong></td>";
        echo "</tr>";
    }
}

echo "</table>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
h2, h3 { color: #333; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
table { margin: 10px 0; }
th { background: #e9ecef; }
.btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
.btn:hover { background: #0056b3; }
</style>

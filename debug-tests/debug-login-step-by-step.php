<?php
require_once 'vendor/autoload.php';

use App\Core\Config;
use App\Core\Session;
use App\Core\Auth;
use App\Models\User;

// Inicializar
Config::load();
Session::start();

echo "<h2>🔍 Debug Completo - Login admin@sistema.com</h2>";

// Limpar sessão anterior
Session::destroy();
Session::start();

$email = 'admin@sistema.com';
$password = 'password';

echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h3>1️⃣ Verificando dados do usuário</h3>";

$userModel = new User();
$user = $userModel->findByEmail($email);

if (!$user) {
    echo "<p style='color: red;'>❌ Usuário não encontrado!</p>";
    exit;
}

echo "<p>✅ Usuário encontrado:</p>";
echo "<ul>";
echo "<li>Nome: " . htmlspecialchars($user['name']) . "</li>";
echo "<li>Email: " . htmlspecialchars($user['email']) . "</li>";
echo "<li>Role: " . htmlspecialchars($user['role']) . "</li>";
echo "<li>Active: " . ($user['active'] ? 'true' : 'false') . "</li>";
echo "<li>Approved: " . ($user['approved'] ? 'true' : 'false') . "</li>";
echo "</ul>";
echo "</div>";

echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h3>2️⃣ Verificando senha</h3>";

$passwordValid = password_verify($password, $user['password']);
echo "<p>Password verify result: " . ($passwordValid ? '✅ VÁLIDA' : '❌ INVÁLIDA') . "</p>";

if (!$passwordValid) {
    echo "<p style='color: red;'>❌ Senha inválida! Parando aqui.</p>";
    exit;
}
echo "</div>";

echo "<div style='background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h3>3️⃣ Verificando aprovação</h3>";

$isApproved = $user['role'] === 'admin' || ($user['approved'] ?? false);
echo "<p>Usuário aprovado: " . ($isApproved ? '✅ SIM' : '❌ NÃO') . "</p>";

if (!$isApproved) {
    echo "<p style='color: red;'>❌ Usuário não aprovado! Parando aqui.</p>";
    exit;
}
echo "</div>";

echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h3>4️⃣ Tentando fazer login</h3>";

$loginResult = Auth::login($email, $password);
echo "<p>Login result: " . ($loginResult ? '✅ SUCESSO' : '❌ FALHA') . "</p>";

if (!$loginResult) {
    echo "<p style='color: red;'>❌ Login falhou! Vamos investigar o método Auth::login()...</p>";
    
    // Vamos fazer o login manualmente para debug
    echo "<h4>🔍 Debug manual do login:</h4>";
    
    // Simular o que o Auth::login faz
    echo "<p>1. Buscar usuário por email...</p>";
    $debugUser = $userModel->findByEmail($email);
    echo "<p>Usuário encontrado: " . ($debugUser ? '✅ SIM' : '❌ NÃO') . "</p>";
    
    if ($debugUser) {
        echo "<p>2. Verificar senha...</p>";
        $debugPasswordValid = password_verify($password, $debugUser['password']);
        echo "<p>Senha válida: " . ($debugPasswordValid ? '✅ SIM' : '❌ NÃO') . "</p>";
        
        if ($debugPasswordValid) {
            echo "<p>3. Verificar aprovação...</p>";
            $debugApproved = $debugUser['role'] === 'admin' || ($debugUser['approved'] ?? false);
            echo "<p>Aprovado: " . ($debugApproved ? '✅ SIM' : '❌ NÃO') . "</p>";
            
            if ($debugApproved) {
                echo "<p>4. Definir sessão...</p>";
                Session::set('user_id', $debugUser['id']);
                Session::regenerate();
                echo "<p>Sessão definida: ✅ SIM</p>";
                echo "<p>User ID na sessão: " . Session::get('user_id') . "</p>";
            }
        }
    }
    
    exit;
}
echo "</div>";

echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h3>5️⃣ Verificando estado pós-login</h3>";

echo "<p>Auth::check(): " . (Auth::check() ? '✅ SIM' : '❌ NÃO') . "</p>";
echo "<p>User ID na sessão: " . Session::get('user_id') . "</p>";

$loggedUser = Auth::user();
if ($loggedUser) {
    echo "<p>✅ Usuário logado:</p>";
    echo "<ul>";
    echo "<li>Nome: " . htmlspecialchars($loggedUser['name']) . "</li>";
    echo "<li>Email: " . htmlspecialchars($loggedUser['email']) . "</li>";
    echo "<li>Role: " . htmlspecialchars($loggedUser['role']) . "</li>";
    echo "</ul>";
    
    echo "<p>Auth::isAdmin(): " . (Auth::isAdmin() ? '✅ SIM' : '❌ NÃO') . "</p>";
    echo "<p>Auth::isAnalyst(): " . (Auth::isAnalyst() ? '✅ SIM' : '❌ NÃO') . "</p>";
    echo "<p>Auth::isClient(): " . (Auth::isClient() ? '✅ SIM' : '❌ NÃO') . "</p>";
} else {
    echo "<p style='color: red;'>❌ Erro: Auth::user() retornou null</p>";
}
echo "</div>";

echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h3>🎉 Resultado Final</h3>";

if (Auth::check() && Auth::isAdmin()) {
    echo "<p style='color: green; font-weight: bold;'>✅ LOGIN REALIZADO COM SUCESSO!</p>";
    echo "<p>Você está logado como administrador e pode acessar:</p>";
    echo "<ul>";
    echo "<li><a href='/dashboard'>Dashboard</a></li>";
    echo "<li><a href='/admin'>Painel Admin</a></li>";
    echo "<li><a href='/admin/users'>Gerenciar Usuários</a></li>";
    echo "</ul>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Ainda há algum problema</p>";
    echo "<p>Verifique os logs acima para identificar o problema.</p>";
}
echo "</div>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
h2, h3, h4 { color: #333; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>

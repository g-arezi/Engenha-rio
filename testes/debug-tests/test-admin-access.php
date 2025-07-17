<?php
require_once 'vendor/autoload.php';

// Fazer login primeiro
session_start();
\App\Core\Session::start();

echo "<h1>Teste de Acesso Admin</h1>";

// Fazer login
$loginResult = \App\Core\Auth::login('admin@sistema.com', 'password');
echo "Login: " . ($loginResult ? 'SUCCESS' : 'FAIL') . "<br>";

// Verificar se está logado
$user = \App\Core\Auth::user();
echo "Usuário logado: " . ($user ? $user['name'] : 'NÃO') . "<br>";

// Verificar se é admin
$isAdmin = \App\Core\Auth::isAdmin();
echo "É admin: " . ($isAdmin ? 'SIM' : 'NÃO') . "<br>";

if ($isAdmin) {
    echo "<br><a href='/admin/users' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Acessar Gerenciar Usuários</a>";
} else {
    echo "<br>❌ Não é possível acessar a área administrativa";
}
?>

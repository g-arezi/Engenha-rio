<?php
// Teste de autenticação e rotas
require_once 'init.php';

use App\Core\Auth;
use App\Models\User;

session_start();

echo "<h1>Debug de Autenticação</h1>";

// Verificar se há sessão ativa
echo "<h2>Sessão Atual:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Tentar fazer login automático se não estiver logado
if (!isset($_SESSION['user_id'])) {
    echo "<h2>Fazendo login automático como admin...</h2>";
    
    $userModel = new User();
    $admin = $userModel->findByEmail('admin@engenhario.com');
    
    if ($admin) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_name'] = $admin['name'];
        $_SESSION['user_email'] = $admin['email'];
        $_SESSION['user_role'] = $admin['role'];
        
        echo "✅ Login realizado com sucesso!<br>";
        echo "Usuário: " . $admin['name'] . " (" . $admin['role'] . ")<br>";
    } else {
        echo "❌ Admin não encontrado!<br>";
    }
}

// Verificar autenticação usando a classe Auth
echo "<h2>Verificação de Autenticação:</h2>";
echo "Auth::check(): " . (Auth::check() ? "✅ SIM" : "❌ NÃO") . "<br>";
echo "Auth::isAdmin(): " . (Auth::isAdmin() ? "✅ SIM" : "❌ NÃO") . "<br>";

if (Auth::check()) {
    $user = Auth::user();
    echo "Usuário logado: " . $user['name'] . " (" . $user['role'] . ")<br>";
}

// Listar usuários pendentes
echo "<h2>Usuários Pendentes:</h2>";
$userModel = new User();
$pendingUsers = $userModel->getPendingUsers();

if (empty($pendingUsers)) {
    echo "Nenhum usuário pendente encontrado.<br>";
    
    // Criar um usuário pendente para teste
    echo "<h3>Criando usuário pendente para teste...</h3>";
    $testUser = [
        'name' => 'Usuário Teste Pendente',
        'email' => 'teste.pendente@example.com',
        'password' => password_hash('123456', PASSWORD_DEFAULT),
        'role' => 'cliente',
        'approved' => false,
        'active' => true,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $userId = $userModel->create($testUser);
    echo "✅ Usuário criado com ID: $userId<br>";
    
    $pendingUsers = $userModel->getPendingUsers();
}

foreach ($pendingUsers as $user) {
    echo "ID: {$user['id']}, Nome: {$user['name']}, Email: {$user['email']}<br>";
}

// Teste direto das funções de aprovação
echo "<h2>Teste das Funções:</h2>";
if (!empty($pendingUsers)) {
    $testUserId = $pendingUsers[0]['id'];
    echo "<a href='#' onclick='testarAprovacao(\"$testUserId\")' class='btn btn-success'>Testar Aprovação</a> ";
    echo "<a href='#' onclick='testarRejeicao(\"$testUserId\")' class='btn btn-danger'>Testar Rejeição</a><br>";
}

echo "<div id='resultado'></div>";
?>

<script>
function testarAprovacao(userId) {
    fetch(`/admin/users/${userId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('resultado').innerHTML = 
            '<div style="background: ' + (data.success ? '#d4edda' : '#f8d7da') + '; padding: 10px; margin: 10px 0;">' +
            (data.success ? '✅ Aprovação: ' + data.message : '❌ Erro: ' + data.error) +
            '</div>';
    })
    .catch(error => {
        document.getElementById('resultado').innerHTML = 
            '<div style="background: #f8d7da; padding: 10px; margin: 10px 0;">❌ Erro: ' + error + '</div>';
    });
}

function testarRejeicao(userId) {
    fetch(`/admin/users/${userId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('resultado').innerHTML = 
            '<div style="background: ' + (data.success ? '#d4edda' : '#f8d7da') + '; padding: 10px; margin: 10px 0;">' +
            (data.success ? '✅ Rejeição: ' + data.message : '❌ Erro: ' + data.error) +
            '</div>';
    })
    .catch(error => {
        document.getElementById('resultado').innerHTML = 
            '<div style="background: #f8d7da; padding: 10px; margin: 10px 0;">❌ Erro: ' + error + '</div>';
    });
}
</script>

<style>
.btn { 
    display: inline-block; 
    padding: 8px 16px; 
    margin: 4px; 
    text-decoration: none; 
    border-radius: 4px; 
    color: white; 
}
.btn-success { background-color: #28a745; }
.btn-danger { background-color: #dc3545; }
</style>

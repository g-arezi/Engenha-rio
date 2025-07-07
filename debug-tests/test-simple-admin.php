<?php
session_start();
require_once 'init.php';

echo "<h1>Teste de Login e Permissões</h1>";

// Simula o login diretamente
$_SESSION['user'] = [
    'id' => 'admin_002',
    'name' => 'Administrador do Sistema',
    'email' => 'admin@sistema.com',
    'role' => 'admin',
    'active' => true,
    'approved' => true
];

echo "<h2>1. Estado da Sessão:</h2>";
echo "Sessão ativa: " . (isset($_SESSION['user']) ? 'SIM' : 'NÃO') . "<br>";
if (isset($_SESSION['user'])) {
    echo "User ID: " . $_SESSION['user']['id'] . "<br>";
    echo "Nome: " . $_SESSION['user']['name'] . "<br>";
    echo "Email: " . $_SESSION['user']['email'] . "<br>";
    echo "Role: " . $_SESSION['user']['role'] . "<br>";
}

echo "<h2>2. Teste de Permissões:</h2>";
$userRole = $_SESSION['user']['role'] ?? null;
$isAdmin = ($userRole === 'admin');
$isAnalyst = ($userRole === 'analista');

echo "Role: $userRole<br>";
echo "É Admin: " . ($isAdmin ? 'SIM' : 'NÃO') . "<br>";
echo "É Analista: " . ($isAnalyst ? 'SIM' : 'NÃO') . "<br>";

echo "<h2>3. Lógica do Dropdown:</h2>";
echo "Condição (\$isAdmin || \$isAnalyst): " . (($isAdmin || $isAnalyst) ? 'TRUE' : 'FALSE') . "<br>";

if ($isAdmin || $isAnalyst) {
    echo "✅ <strong>DROPDOWN DEVE APARECER</strong><br>";
    echo "<div style='background: #e8f5e8; padding: 15px; margin: 10px 0; border-left: 4px solid #4CAF50;'>";
    echo "<strong>Menu Administração:</strong><br><br>";
    
    if ($isAdmin) {
        echo "🔹 Gerenciar Usuários (visível para admin)<br>";
        echo "🔹 Relatórios (visível para admin)<br>";
    }
    
    if ($isAnalyst) {
        echo "🔹 Relatórios (visível para analista)<br>";
    }
    echo "</div>";
} else {
    echo "❌ <strong>DROPDOWN NÃO DEVE APARECER</strong><br>";
}

echo "<h2>4. Simulação do Código da Sidebar:</h2>";
echo "<div style='background: #f5f5f5; padding: 15px; margin: 10px 0; border: 1px solid #ddd;'>";
echo "<code>";
echo "// Código da sidebar<br>";
echo "\$user = \$_SESSION['user'];<br>";
echo "\$isAdmin = (\$user['role'] === 'admin');<br>";
echo "\$isAnalyst = (\$user['role'] === 'analista');<br>";
echo "<br>";
echo "if (\$isAdmin || \$isAnalyst) {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;// Mostrar dropdown<br>";
echo "}<br>";
echo "</code>";
echo "</div>";

echo "<h2>5. Verificação dos Dados do Usuário no JSON:</h2>";
$usersFile = 'data/users.json';
if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
    
    foreach ($users as $key => $userData) {
        if ($userData['email'] === 'admin@sistema.com') {
            echo "<div style='background: #fff3cd; padding: 10px; margin: 10px 0; border-left: 4px solid #ffc107;'>";
            echo "<strong>Dados do admin@sistema.com:</strong><br>";
            echo "Chave: $key<br>";
            echo "Email: " . $userData['email'] . "<br>";
            echo "Role: " . $userData['role'] . "<br>";
            echo "Ativo: " . ($userData['active'] ? 'SIM' : 'NÃO') . "<br>";
            echo "Aprovado: " . ($userData['approved'] ? 'SIM' : 'NÃO') . "<br>";
            echo "</div>";
            break;
        }
    }
} else {
    echo "❌ Arquivo de usuários não encontrado!<br>";
}

echo "<hr>";
echo "<p><a href='/'>Home</a> | <a href='/dashboard'>Dashboard</a> | <a href='/logout'>Logout</a></p>";
?>

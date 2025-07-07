<?php
require_once 'init.php';

// Verificar se o usuário está logado
if (!\App\Core\Auth::check()) {
    echo "<h3>❌ Usuário não está logado</h3>";
    echo "<p>Por favor, faça login primeiro.</p>";
    exit;
}

$user = \App\Core\Auth::user();
echo "<h3>👤 Informações do usuário logado:</h3>";
echo "<ul>";
echo "<li><strong>Nome:</strong> " . htmlspecialchars($user['name'] ?? 'N/A') . "</li>";
echo "<li><strong>Email:</strong> " . htmlspecialchars($user['email'] ?? 'N/A') . "</li>";
echo "<li><strong>Role:</strong> " . htmlspecialchars($user['role'] ?? 'N/A') . "</li>";
echo "<li><strong>Status:</strong> " . htmlspecialchars($user['status'] ?? 'N/A') . "</li>";
echo "</ul>";

echo "<h3>🔐 Verificações de permissão:</h3>";
echo "<ul>";
echo "<li><strong>É Admin?</strong> " . (\App\Core\Auth::isAdmin() ? "✅ Sim" : "❌ Não") . "</li>";
echo "<li><strong>É Analista?</strong> " . (\App\Core\Auth::isAnalyst() ? "✅ Sim" : "❌ Não") . "</li>";
echo "<li><strong>É Cliente?</strong> " . (\App\Core\Auth::isClient() ? "✅ Sim" : "❌ Não") . "</li>";
echo "</ul>";

echo "<h3>📋 Deve mostrar dropdown administrativo?</h3>";
if (\App\Core\Auth::isAdmin() || \App\Core\Auth::isAnalyst()) {
    echo "<p>✅ <strong>SIM</strong> - Dropdown administrativo deve aparecer</p>";
} else {
    echo "<p>❌ <strong>NÃO</strong> - Dropdown administrativo não deve aparecer</p>";
}

echo "<h3>👥 Deve mostrar 'Gerenciar Usuários'?</h3>";
if (\App\Core\Auth::isAdmin()) {
    echo "<p>✅ <strong>SIM</strong> - Opção 'Gerenciar Usuários' deve aparecer</p>";
} else {
    echo "<p>❌ <strong>NÃO</strong> - Opção 'Gerenciar Usuários' não deve aparecer</p>";
}

// Verificar dados do arquivo users.json
echo "<h3>🔍 Dados do arquivo users.json:</h3>";
$usersFile = 'data/users.json';
if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr><th>Nome</th><th>Email</th><th>Role</th><th>Status</th></tr>";
    foreach ($users as $u) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($u['name'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($u['email'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($u['role'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($u['status'] ?? 'N/A') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>❌ Arquivo users.json não encontrado</p>";
}
?>

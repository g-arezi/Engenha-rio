<?php
require_once 'vendor/autoload.php';

use App\Core\Config;
use App\Core\Session;
use App\Core\Auth;

// Inicializar
Config::load();
Session::start();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Debug Completo - Permissões</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
        .card { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { border-left: 5px solid #28a745; background: #d4edda; color: #155724; }
        .danger { border-left: 5px solid #dc3545; background: #f8d7da; color: #721c24; }
        .warning { border-left: 5px solid #ffc107; background: #fff3cd; color: #856404; }
        .info { border-left: 5px solid #17a2b8; background: #d1ecf1; color: #0c5460; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f8f9fa; }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
    </style>
</head>
<body>";

echo "<h1>🔍 DEBUG COMPLETO - PERMISSÕES ADMINISTRATIVAS</h1>";

// Verificar se está logado
if (!Auth::check()) {
    echo "<div class='card danger'>";
    echo "<h2>❌ USUÁRIO NÃO ESTÁ LOGADO</h2>";
    echo "<p>Você precisa fazer login primeiro para ver as permissões.</p>";
    echo "<p><a href='/login' class='btn'>Fazer Login</a></p>";
    echo "</div>";
    echo "</body></html>";
    exit;
}

$user = Auth::user();

// Dados do usuário
echo "<div class='card success'>";
echo "<h2>✅ USUÁRIO LOGADO</h2>";
echo "<table>";
echo "<tr><th>Campo</th><th>Valor</th></tr>";
echo "<tr><td><strong>Nome</strong></td><td>" . htmlspecialchars($user['name'] ?? 'N/A') . "</td></tr>";
echo "<tr><td><strong>Email</strong></td><td>" . htmlspecialchars($user['email'] ?? 'N/A') . "</td></tr>";
echo "<tr><td><strong>Role</strong></td><td>" . htmlspecialchars($user['role'] ?? 'N/A') . "</td></tr>";
echo "<tr><td><strong>ID</strong></td><td>" . htmlspecialchars($user['id'] ?? 'N/A') . "</td></tr>";
echo "<tr><td><strong>Active</strong></td><td>" . ($user['active'] ? '✅ Sim' : '❌ Não') . "</td></tr>";
echo "<tr><td><strong>Approved</strong></td><td>" . ($user['approved'] ? '✅ Sim' : '❌ Não') . "</td></tr>";
echo "</table>";
echo "</div>";

// Verificações de permissão
echo "<div class='card info'>";
echo "<h2>🔐 VERIFICAÇÕES DE PERMISSÃO</h2>";
echo "<table>";
echo "<tr><th>Verificação</th><th>Resultado</th><th>Status</th></tr>";

$checks = [
    'Auth::check()' => Auth::check(),
    'Auth::isAdmin()' => Auth::isAdmin(),
    'Auth::isAnalyst()' => Auth::isAnalyst(),
    'Auth::isClient()' => Auth::isClient(),
    'Role === "admin"' => ($user['role'] ?? '') === 'admin',
    'Role === "analista"' => ($user['role'] ?? '') === 'analista',
    'Role === "cliente"' => ($user['role'] ?? '') === 'cliente',
];

foreach ($checks as $check => $result) {
    $status = $result ? '✅ VERDADEIRO' : '❌ FALSO';
    $color = $result ? '#28a745' : '#dc3545';
    $resultText = $result ? 'true' : 'false';
    echo "<tr><td><strong>$check</strong></td><td>$resultText</td><td style='color: $color;'><strong>$status</strong></td></tr>";
}

echo "</table>";
echo "</div>";

// Condições para o dropdown
$showAdminDropdown = Auth::isAdmin() || Auth::isAnalyst();
$showUserManagement = Auth::isAdmin();

echo "<div class='card warning'>";
echo "<h2>🎯 CONDIÇÕES PARA DROPDOWN ADMINISTRATIVO</h2>";
echo "<table>";
echo "<tr><th>Condição</th><th>Resultado</th><th>Status</th></tr>";
echo "<tr><td><strong>Mostrar Dropdown 'Administração'</strong><br><small>(Auth::isAdmin() || Auth::isAnalyst())</small></td><td>" . ($showAdminDropdown ? 'true' : 'false') . "</td><td style='color: " . ($showAdminDropdown ? '#28a745' : '#dc3545') . ";'><strong>" . ($showAdminDropdown ? '✅ SIM' : '❌ NÃO') . "</strong></td></tr>";
echo "<tr><td><strong>Mostrar 'Gerenciar Usuários'</strong><br><small>(Auth::isAdmin())</small></td><td>" . ($showUserManagement ? 'true' : 'false') . "</td><td style='color: " . ($showUserManagement ? '#28a745' : '#dc3545') . ";'><strong>" . ($showUserManagement ? '✅ SIM' : '❌ NÃO') . "</strong></td></tr>";
echo "</table>";
echo "</div>";

// Simulação do código da sidebar
echo "<div class='card info'>";
echo "<h2>🧪 SIMULAÇÃO DO CÓDIGO DA SIDEBAR</h2>";
echo "<p>Testando as condições exatas do layout:</p>";

echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace;'>";
echo "<h4>Código PHP:</h4>";
echo "<pre>";
echo "// Variáveis no layout:\n";
echo "\$currentUser = Auth::user(); // " . ($user ? "✅ OK" : "❌ NULL") . "\n";
echo "\$isAdmin = Auth::isAdmin(); // " . (Auth::isAdmin() ? "true" : "false") . "\n";
echo "\$isAnalyst = Auth::isAnalyst(); // " . (Auth::isAnalyst() ? "true" : "false") . "\n";
echo "\n";
echo "// Condição do dropdown:\n";
echo "if (\$isAdmin || \$isAnalyst) {\n";
echo "    // Resultado: " . ($showAdminDropdown ? "VERDADEIRO" : "FALSO") . "\n";
echo "    echo 'Dropdown aparece';\n";
echo "    \n";
echo "    if (\$isAdmin) {\n";
echo "        // Resultado: " . ($showUserManagement ? "VERDADEIRO" : "FALSO") . "\n";
echo "        echo 'Gerenciar Usuários aparece';\n";
echo "    }\n";
echo "}\n";
echo "</pre>";
echo "</div>";
echo "</div>";

// Resultado final
if ($showAdminDropdown) {
    echo "<div class='card success'>";
    echo "<h2>🎉 RESULTADO FINAL</h2>";
    echo "<h3>✅ O DROPDOWN DEVERIA APARECER!</h3>";
    echo "<p>Com base nas verificações, o dropdown 'Administração' deveria estar visível na sidebar.</p>";
    if ($showUserManagement) {
        echo "<p>✅ A opção 'Gerenciar Usuários' também deveria estar visível.</p>";
    }
    echo "<h4>📋 Checklist para verificar:</h4>";
    echo "<ul>";
    echo "<li>Olhe na sidebar esquerda do dashboard</li>";
    echo "<li>Procure por um item 'Administração' com uma seta</li>";
    echo "<li>Clique nele para abrir o dropdown</li>";
    echo "<li>Verifique se 'Gerenciar Usuários' está na lista</li>";
    echo "</ul>";
    echo "<p><a href='/dashboard' class='btn'>Ir para Dashboard</a></p>";
    echo "</div>";
} else {
    echo "<div class='card danger'>";
    echo "<h2>❌ PROBLEMA IDENTIFICADO</h2>";
    echo "<h3>O dropdown NÃO deveria aparecer</h3>";
    echo "<p>O usuário atual não tem permissões administrativas.</p>";
    echo "<p><strong>Role atual:</strong> " . ($user['role'] ?? 'N/A') . "</p>";
    echo "<p><strong>Necessário:</strong> 'admin' ou 'analista'</p>";
    echo "</div>";
}

echo "<div class='card'>";
echo "<h3>🔧 Ações Disponíveis</h3>";
echo "<p>";
echo "<a href='/dashboard' class='btn'>Dashboard</a>";
echo "<a href='/logout' class='btn' style='background: #dc3545;'>Logout</a>";
echo "<a href='/login' class='btn' style='background: #28a745;'>Login</a>";
echo "</p>";
echo "</div>";

echo "</body></html>";
?>

<?php
// Teste específico para verificar se Auth está funcionando na view
require_once 'vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;
use App\Core\Config;

// Inicializar
Config::load();
Session::start();

echo "<h2>🔍 Teste de Auth na View</h2>";

// Verificar se está logado
if (!Auth::check()) {
    echo "<p style='color: red;'>❌ Usuário não está logado!</p>";
    exit;
}

$user = Auth::user();

echo "<h3>Simulando o código da sidebar:</h3>";
echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #e9ecef;'>";

// Simular exatamente o código da sidebar
echo "// Teste da condição do dropdown\n";
echo "if (\\App\\Core\\Auth::isAdmin() || \\App\\Core\\Auth::isAnalyst()) {\n";

$showDropdown = \App\Core\Auth::isAdmin() || \App\Core\Auth::isAnalyst();
echo "    // Resultado: " . ($showDropdown ? "VERDADEIRO" : "FALSO") . "\n";

if ($showDropdown) {
    echo "    echo 'DROPDOWN DEVE APARECER';\n";
    echo "    \n";
    echo "    // Dentro do dropdown:\n";
    echo "    if (\\App\\Core\\Auth::isAdmin()) {\n";
    
    $showUserManagement = \App\Core\Auth::isAdmin();
    echo "        // Resultado: " . ($showUserManagement ? "VERDADEIRO" : "FALSO") . "\n";
    
    if ($showUserManagement) {
        echo "        echo 'GERENCIAR USUÁRIOS DEVE APARECER';\n";
    } else {
        echo "        echo 'GERENCIAR USUÁRIOS NÃO DEVE APARECER';\n";
    }
    
    echo "    }\n";
} else {
    echo "    // DROPDOWN NÃO DEVE APARECER\n";
}

echo "}\n";
echo "</pre>";

// Agora vou gerar o HTML da sidebar exatamente como está na view
echo "<h3>HTML gerado pela sidebar:</h3>";
echo "<div style='background: #2c3e50; color: white; padding: 15px; border-radius: 5px;'>";

echo "<ul class='nav flex-column'>";
echo "<li class='nav-item'><a class='nav-link' href='/dashboard'>Dashboard</a></li>";
echo "<li class='nav-item'><a class='nav-link' href='/projects'>Projetos</a></li>";
echo "<li class='nav-item'><a class='nav-link' href='/documents'>Documentos</a></li>";

if (\App\Core\Auth::isAdmin() || \App\Core\Auth::isAnalyst()) {
    echo "<li class='nav-item dropdown'>";
    echo "<a class='nav-link dropdown-toggle' href='#' id='adminDropdown'>";
    echo "<i class='fas fa-cogs'></i> Administração";
    echo "<i class='fas fa-chevron-down ms-auto'></i>";
    echo "</a>";
    echo "<ul class='dropdown-menu dropdown-menu-dark' style='display: none;'>";
    echo "<li><a class='dropdown-item' href='/admin'>Painel Geral</a></li>";
    
    if (\App\Core\Auth::isAdmin()) {
        echo "<li><a class='dropdown-item' href='/admin/users'>Gerenciar Usuários</a></li>";
        echo "<li><a class='dropdown-item' href='/admin/settings'>Configurações</a></li>";
    }
    
    echo "<li><a class='dropdown-item' href='/admin/history'>Histórico</a></li>";
    echo "</ul>";
    echo "</li>";
} else {
    echo "<li style='color: red;'>❌ Dropdown administrativo não deve aparecer</li>";
}

echo "<li class='nav-item'><a class='nav-link' href='/profile'>Perfil</a></li>";
echo "</ul>";

echo "</div>";

echo "<h3>Detalhes do usuário:</h3>";
echo "<ul>";
echo "<li><strong>Nome:</strong> " . htmlspecialchars($user['name'] ?? 'N/A') . "</li>";
echo "<li><strong>Email:</strong> " . htmlspecialchars($user['email'] ?? 'N/A') . "</li>";
echo "<li><strong>Role:</strong> " . htmlspecialchars($user['role'] ?? 'N/A') . "</li>";
echo "</ul>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
h2, h3 { color: #333; }
pre { font-family: 'Courier New', monospace; }
ul { list-style-type: none; padding: 0; }
li { margin: 5px 0; padding: 8px; }
a { color: #007bff; text-decoration: none; }
</style>

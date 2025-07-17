<?php
require_once 'vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;
use App\Core\Config;

// Inicializar
Config::load();
Session::start();

echo "<h2>🔍 Debug - Gerenciar Usuários</h2>";

// Verificar se está logado
if (!Auth::check()) {
    echo "<p style='color: red;'>❌ Usuário não está logado!</p>";
    echo "<p><a href='/login'>Ir para Login</a></p>";
    exit;
}

$user = Auth::user();

echo "<h3>👤 Usuário Atual:</h3>";
echo "<ul>";
echo "<li><strong>Nome:</strong> " . htmlspecialchars($user['name'] ?? 'N/A') . "</li>";
echo "<li><strong>Email:</strong> " . htmlspecialchars($user['email'] ?? 'N/A') . "</li>";
echo "<li><strong>Role:</strong> " . htmlspecialchars($user['role'] ?? 'N/A') . "</li>";
echo "</ul>";

echo "<h3>🔐 Verificações de Permissão:</h3>";
echo "<ul>";
echo "<li><strong>Auth::check():</strong> " . (Auth::check() ? "✅ true" : "❌ false") . "</li>";
echo "<li><strong>Auth::isAdmin():</strong> " . (Auth::isAdmin() ? "✅ true" : "❌ false") . "</li>";
echo "<li><strong>Auth::isAnalyst():</strong> " . (Auth::isAnalyst() ? "✅ true" : "❌ false") . "</li>";
echo "<li><strong>Auth::isClient():</strong> " . (Auth::isClient() ? "✅ true" : "❌ false") . "</li>";
echo "</ul>";

echo "<h3>📋 Condições da Sidebar:</h3>";
echo "<ul>";

// Teste 1: Dropdown administrativo deve aparecer?
$showAdminDropdown = Auth::isAdmin() || Auth::isAnalyst();
echo "<li><strong>Mostrar dropdown 'Administração':</strong> " . ($showAdminDropdown ? "✅ SIM" : "❌ NÃO") . "</li>";

// Teste 2: Gerenciar usuários deve aparecer?
$showUserManagement = Auth::isAdmin();
echo "<li><strong>Mostrar 'Gerenciar Usuários':</strong> " . ($showUserManagement ? "✅ SIM" : "❌ NÃO") . "</li>";
echo "</ul>";

echo "<h3>🧪 Simulação do Código da Sidebar:</h3>";
echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
echo "// Condição do dropdown administrativo\n";
echo "if (Auth::isAdmin() || Auth::isAnalyst()) {\n";
echo "    // Resultado: " . ($showAdminDropdown ? "VERDADEIRO" : "FALSO") . "\n";
echo "    echo 'Dropdown aparece';\n";
echo "    \n";
echo "    // Dentro do dropdown:\n";
echo "    if (Auth::isAdmin()) {\n";
echo "        // Resultado: " . ($showUserManagement ? "VERDADEIRO" : "FALSO") . "\n";
echo "        echo 'Gerenciar Usuários aparece';\n";
echo "    }\n";
echo "}\n";
echo "</pre>";

// Testar acesso direto à rota
echo "<h3>🛤️ Teste de Acesso às Rotas:</h3>";
echo "<ul>";
echo "<li><a href='/admin' target='_blank'>Painel Admin</a> - " . (Auth::isAdmin() ? "✅ Permitido" : "❌ Bloqueado") . "</li>";
echo "<li><a href='/admin/users' target='_blank'>Gerenciar Usuários</a> - " . (Auth::isAdmin() ? "✅ Permitido" : "❌ Bloqueado") . "</li>";
echo "</ul>";

// Verificar dados da sessão
echo "<h3>🔧 Dados da Sessão:</h3>";
echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
echo "Session ID: " . session_id() . "\n";
echo "User ID na sessão: " . (Session::get('user_id') ?? 'N/A') . "\n";
echo "Dados do usuário: " . json_encode($user, JSON_PRETTY_PRINT);
echo "</pre>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #333; }
h3 { color: #666; margin-top: 20px; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>

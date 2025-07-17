<?php
require_once 'init.php';

use App\Core\Auth;
use App\Core\Session;

// Inicia a sessão
Session::start();

echo "<h1>Teste Completo de Permissões Admin</h1>";

// Teste 1: Login com admin@sistema.com
echo "<h2>1. Teste de Login (admin@sistema.com)</h2>";
$auth = new Auth();
$loginResult = $auth->login('admin@sistema.com', 'password');

if ($loginResult) {
    echo "✅ Login realizado com sucesso!<br>";
    
    // Verifica se está logado
    echo "<h3>Estado da Sessão:</h3>";
    echo "Logado: " . ($auth->isLoggedIn() ? 'SIM' : 'NÃO') . "<br>";
    echo "User ID: " . ($auth->user()['id'] ?? 'N/A') . "<br>";
    echo "Nome: " . ($auth->user()['name'] ?? 'N/A') . "<br>";
    echo "Email: " . ($auth->user()['email'] ?? 'N/A') . "<br>";
    echo "Role: " . ($auth->user()['role'] ?? 'N/A') . "<br>";
    
    // Verifica permissões
    echo "<h3>Permissões:</h3>";
    echo "É Admin: " . ($auth->isAdmin() ? 'SIM' : 'NÃO') . "<br>";
    echo "É Analista: " . ($auth->isAnalyst() ? 'SIM' : 'NÃO') . "<br>";
    echo "É Cliente: " . ($auth->isClient() ? 'SIM' : 'NÃO') . "<br>";
    
    // Teste da lógica do dropdown
    echo "<h3>Lógica do Dropdown:</h3>";
    $user = $auth->user();
    $isAdmin = $auth->isAdmin();
    $isAnalyst = $auth->isAnalyst();
    $currentUser = $user;
    
    echo "Variável \$isAdmin: " . ($isAdmin ? 'TRUE' : 'FALSE') . "<br>";
    echo "Variável \$isAnalyst: " . ($isAnalyst ? 'TRUE' : 'FALSE') . "<br>";
    echo "Condição (\$isAdmin || \$isAnalyst): " . (($isAdmin || $isAnalyst) ? 'TRUE' : 'FALSE') . "<br>";
    
    // Simulação do código da sidebar
    echo "<h3>Simulação da Sidebar:</h3>";
    if ($isAdmin || $isAnalyst) {
        echo "✅ DROPDOWN DEVE APARECER<br>";
        echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0;'>";
        echo "<strong>Dropdown Administração:</strong><br>";
        
        if ($isAdmin) {
            echo "- ✅ Gerenciar Usuários (visível para admin)<br>";
            echo "- ✅ Relatórios (visível para admin)<br>";
        }
        
        if ($isAnalyst) {
            echo "- ✅ Relatórios (visível para analista)<br>";
        }
        echo "</div>";
    } else {
        echo "❌ DROPDOWN NÃO DEVE APARECER<br>";
    }
    
} else {
    echo "❌ Erro no login!<br>";
}

// Teste 2: Verificar dados do usuário diretamente
echo "<h2>2. Verificação Direta dos Dados do Usuário</h2>";
$usersFile = 'data/users.json';
if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
    
    echo "<h3>Usuário admin@sistema.com:</h3>";
    foreach ($users as $key => $userData) {
        if ($userData['email'] === 'admin@sistema.com') {
            echo "Chave: $key<br>";
            echo "Email: " . $userData['email'] . "<br>";
            echo "Role: " . $userData['role'] . "<br>";
            echo "Ativo: " . ($userData['active'] ? 'SIM' : 'NÃO') . "<br>";
            echo "Aprovado: " . ($userData['approved'] ? 'SIM' : 'NÃO') . "<br>";
            echo "Password Hash: " . substr($userData['password'], 0, 20) . "...<br>";
            break;
        }
    }
} else {
    echo "❌ Arquivo de usuários não encontrado!<br>";
}

// Teste 3: Verificar hash da senha
echo "<h2>3. Verificação do Hash da Senha</h2>";
$testPassword = 'password';
$storedHash = null;

if (isset($users)) {
    foreach ($users as $userData) {
        if ($userData['email'] === 'admin@sistema.com') {
            $storedHash = $userData['password'];
            break;
        }
    }
}

if ($storedHash) {
    echo "Hash armazenado: " . $storedHash . "<br>";
    echo "Senha testada: '$testPassword'<br>";
    echo "Verificação: " . (password_verify($testPassword, $storedHash) ? 'VÁLIDA' : 'INVÁLIDA') . "<br>";
} else {
    echo "❌ Hash não encontrado!<br>";
}

echo "<hr>";
echo "<p><a href='/logout'>Logout</a> | <a href='/dashboard'>Dashboard</a></p>";
?>

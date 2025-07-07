<?php
require_once 'vendor/autoload.php';

// Inicia a sessão
session_start();
\App\Core\Session::start();

echo "<h1>🔍 DIAGNÓSTICO COMPLETO DO SISTEMA</h1>";

// Teste 1: Verificar dados do usuário
echo "<h2>1. Verificação dos Dados do Usuário</h2>";
$userModel = new \App\Models\User();
$user = $userModel->findByEmail('admin@sistema.com');

if ($user) {
    echo "✅ Usuário encontrado<br>";
    echo "ID: " . $user['id'] . "<br>";
    echo "Nome: " . $user['name'] . "<br>";
    echo "Email: " . $user['email'] . "<br>";
    echo "Role: " . $user['role'] . "<br>";
    echo "Ativo: " . ($user['active'] ? 'SIM' : 'NÃO') . "<br>";
    echo "Aprovado: " . ($user['approved'] ? 'SIM' : 'NÃO') . "<br>";
    
    // Teste da senha
    $passwordTest = password_verify('password', $user['password']);
    echo "Senha 'password' válida: " . ($passwordTest ? 'SIM' : 'NÃO') . "<br>";
} else {
    echo "❌ Usuário não encontrado<br>";
}

// Teste 2: Fazer login
echo "<h2>2. Teste de Login</h2>";
if ($user && password_verify('password', $user['password'])) {
    $loginResult = \App\Core\Auth::login('admin@sistema.com', 'password');
    
    if ($loginResult) {
        echo "✅ Login realizado com sucesso<br>";
        
        // Verificar estado após login
        $loggedUser = \App\Core\Auth::user();
        
        if ($loggedUser) {
            echo "✅ Usuário logado: " . $loggedUser['name'] . "<br>";
            echo "Role: " . $loggedUser['role'] . "<br>";
            
            // Teste de permissões
            $isAdmin = \App\Core\Auth::isAdmin();
            $isAnalyst = \App\Core\Auth::isAnalyst();
            
            echo "É Admin: " . ($isAdmin ? 'SIM' : 'NÃO') . "<br>";
            echo "É Analista: " . ($isAnalyst ? 'SIM' : 'NÃO') . "<br>";
            
            // Teste da condição do dropdown
            echo "<h3>🎯 Resultado da Condição do Dropdown:</h3>";
            if ($isAdmin || $isAnalyst) {
                echo "<div style='background: #d4edda; padding: 20px; border-left: 5px solid #28a745; margin: 15px 0;'>";
                echo "✅ <strong>DROPDOWN ADMINISTRATIVO DEVE APARECER</strong><br>";
                echo "Condição: (\$isAdmin || \$isAnalyst) = TRUE<br>";
                echo "O usuário " . $loggedUser['name'] . " com role '" . $loggedUser['role'] . "' pode ver o menu administrativo.";
                echo "</div>";
            } else {
                echo "<div style='background: #f8d7da; padding: 20px; border-left: 5px solid #dc3545; margin: 15px 0;'>";
                echo "❌ <strong>DROPDOWN ADMINISTRATIVO NÃO DEVE APARECER</strong><br>";
                echo "Condição: (\$isAdmin || \$isAnalyst) = FALSE";
                echo "</div>";
            }
            
        } else {
            echo "❌ Erro: usuário não encontrado após login<br>";
        }
    } else {
        echo "❌ Erro no login<br>";
    }
} else {
    echo "❌ Não foi possível fazer login (dados inválidos)<br>";
}

// Teste 3: Simular a sidebar
echo "<h2>3. Simulação da Sidebar</h2>";
$currentUser = \App\Core\Auth::user();
if ($currentUser) {
    $isAdmin = \App\Core\Auth::isAdmin();
    $isAnalyst = \App\Core\Auth::isAnalyst();
    
    echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin: 10px 0;'>";
    echo "<strong>Variáveis da Sidebar:</strong><br>";
    echo "currentUser: " . ($currentUser ? $currentUser['name'] : 'NULL') . "<br>";
    echo "isAdmin: " . ($isAdmin ? 'TRUE' : 'FALSE') . "<br>";
    echo "isAnalyst: " . ($isAnalyst ? 'TRUE' : 'FALSE') . "<br>";
    echo "Condição do dropdown: " . (($isAdmin || $isAnalyst) ? 'TRUE' : 'FALSE') . "<br>";
    echo "</div>";
    
    // Simular HTML da sidebar
    echo "<h3>HTML que será renderizado na sidebar:</h3>";
    echo "<div style='background: #343a40; color: white; padding: 15px; border-radius: 5px;'>";
    
    if ($isAdmin || $isAnalyst) {
        echo "<strong>⚙️ Administração</strong><br>";
        echo "├── 📊 Painel Geral<br>";
        
        if ($isAdmin) {
            echo "├── 👥 <strong>Gerenciar Usuários</strong><br>";
            echo "├── ⚙️ Configurações<br>";
        }
        
        echo "└── 📚 Histórico<br>";
    } else {
        echo "<em>Dropdown administrativo não visível</em><br>";
    }
    
    echo "</div>";
}

// Resultado final
echo "<h2>4. 📋 RESULTADO FINAL</h2>";
$finalUser = \App\Core\Auth::user();
if ($finalUser && ($finalUser['role'] === 'admin' || $finalUser['role'] === 'analista')) {
    echo "<div style='background: #d1ecf1; padding: 20px; border-left: 5px solid #17a2b8; margin: 15px 0;'>";
    echo "🎉 <strong>SUCESSO!</strong><br>";
    echo "✅ Usuário logado: " . $finalUser['name'] . "<br>";
    echo "✅ Role: " . $finalUser['role'] . "<br>";
    echo "✅ Permissões: " . ($finalUser['role'] === 'admin' ? 'Administrador' : 'Analista') . "<br>";
    echo "✅ Dropdown administrativo: FUNCIONANDO<br>";
    echo "<br><strong>O sistema está funcionando corretamente!</strong>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; padding: 20px; border-left: 5px solid #dc3545; margin: 15px 0;'>";
    echo "❌ <strong>PROBLEMA DETECTADO</strong><br>";
    echo "O sistema não está funcionando conforme esperado.";
    echo "</div>";
}

echo "<hr>";
echo "<div style='text-align: center; margin: 20px 0;'>";
echo "<a href='/dashboard' style='background: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 10px;'>🏠 Ir para Dashboard</a>";
echo "<a href='/logout' style='background: #dc3545; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 10px;'>🚪 Logout</a>";
echo "</div>";
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f8f9fa;
}

h1 {
    color: #2c3e50;
    text-align: center;
    border-bottom: 3px solid #3498db;
    padding-bottom: 10px;
}

h2 {
    color: #34495e;
    margin-top: 30px;
}

h3 {
    color: #7f8c8d;
}
</style>

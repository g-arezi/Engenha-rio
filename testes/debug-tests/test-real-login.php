<?php
require_once 'vendor/autoload.php';

// Inicia a sessão
session_start();

echo "<h1>Teste de Login Real</h1>";

// Limpa a sessão
session_unset();
session_destroy();
session_start();

echo "<h2>1. Fazendo Login com Auth::login()</h2>";
try {
    $result = \App\Core\Auth::login('admin@sistema.com', 'password');
    echo "Login realizado: " . ($result ? 'SUCESSO' : 'FALHA') . "<br>";
    
    if ($result) {
        echo "<h3>Verificando estado após login:</h3>";
        $user = \App\Core\Auth::user();
        echo "Usuário logado: " . ($user ? 'SIM' : 'NÃO') . "<br>";
        
        if ($user) {
            echo "Nome: " . ($user['name'] ?? 'N/A') . "<br>";
            echo "Email: " . ($user['email'] ?? 'N/A') . "<br>";
            echo "Role: " . ($user['role'] ?? 'N/A') . "<br>";
            
            echo "<h3>Permissões:</h3>";
            $isAdmin = \App\Core\Auth::isAdmin();
            $isAnalyst = \App\Core\Auth::isAnalyst();
            
            echo "isAdmin(): " . ($isAdmin ? 'TRUE' : 'FALSE') . "<br>";
            echo "isAnalyst(): " . ($isAnalyst ? 'TRUE' : 'FALSE') . "<br>";
            
            echo "<h3>Teste do Dropdown:</h3>";
            if ($isAdmin || $isAnalyst) {
                echo "✅ <strong>DROPDOWN DEVE APARECER</strong><br>";
                echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
                echo "🎉 O dropdown administrativo funcionará na sidebar!<br>";
                echo "Usuário: " . $user['name'] . " (Role: " . $user['role'] . ")";
                echo "</div>";
            } else {
                echo "❌ <strong>DROPDOWN NÃO DEVE APARECER</strong><br>";
            }
        }
    } else {
        echo "❌ Falha no login. Verificando dados:<br>";
        
        // Verificar se o usuário existe
        $userModel = new \App\Models\User();
        $user = $userModel->findByEmail('admin@sistema.com');
        
        if ($user) {
            echo "Usuário encontrado: SIM<br>";
            echo "Role: " . $user['role'] . "<br>";
            echo "Ativo: " . ($user['active'] ? 'SIM' : 'NÃO') . "<br>";
            echo "Aprovado: " . ($user['approved'] ? 'SIM' : 'NÃO') . "<br>";
            
            // Testar senha
            $passwordTest = password_verify('password', $user['password']);
            echo "Senha válida: " . ($passwordTest ? 'SIM' : 'NÃO') . "<br>";
        } else {
            echo "Usuário não encontrado no banco de dados<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
}

echo "<hr>";
echo "<p><a href='/dashboard'>Ir para Dashboard</a></p>";
?>

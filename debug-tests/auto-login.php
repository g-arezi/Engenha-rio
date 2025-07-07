<?php
require_once 'vendor/autoload.php';

// Inicia a sessão
session_start();

echo "<h1>Login Automático e Redirecionamento</h1>";

// Limpa a sessão atual
session_unset();
session_destroy();
session_start();

echo "<h2>Fazendo login com admin@sistema.com...</h2>";

try {
    $result = \App\Core\Auth::login('admin@sistema.com', 'password');
    
    if ($result) {
        echo "✅ Login realizado com sucesso!<br>";
        echo "Redirecionando para o dashboard...<br>";
        
        // Verificar se realmente logou
        $user = \App\Core\Auth::user();
        if ($user) {
            echo "Usuário logado: " . $user['name'] . " (Role: " . $user['role'] . ")<br>";
            
            // Redirecionar após 2 segundos
            echo "<script>
                setTimeout(function() {
                    window.location.href = '/dashboard';
                }, 2000);
            </script>";
        } else {
            echo "❌ Erro: usuário não encontrado após login<br>";
        }
    } else {
        echo "❌ Erro no login<br>";
        
        // Debug do erro
        $userModel = new \App\Models\User();
        $user = $userModel->findByEmail('admin@sistema.com');
        
        if ($user) {
            echo "Usuário encontrado, verificando senha...<br>";
            $passwordTest = password_verify('password', $user['password']);
            echo "Senha válida: " . ($passwordTest ? 'SIM' : 'NÃO') . "<br>";
            echo "Usuário ativo: " . ($user['active'] ? 'SIM' : 'NÃO') . "<br>";
            echo "Usuário aprovado: " . ($user['approved'] ? 'SIM' : 'NÃO') . "<br>";
        } else {
            echo "Usuário não encontrado<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<p><a href='/dashboard'>Ir para Dashboard</a> | <a href='/login'>Página de Login</a></p>";
?>

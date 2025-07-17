<?php
require_once 'vendor/autoload.php';

// Inicia a sessão
session_start();

// Simula login manual definindo o user_id na sessão
\App\Core\Session::start();
\App\Core\Session::set('user_id', 'admin_002');

echo "<h1>Teste do Dashboard com Sessão Ativa</h1>";

// Verificar se a sessão está ativa
$userId = \App\Core\Session::get('user_id');
echo "user_id na sessão: " . ($userId ?? 'NULL') . "<br>";

if ($userId) {
    $userModel = new \App\Models\User();
    $user = $userModel->find($userId);
    
    if ($user) {
        echo "Usuário: " . $user['name'] . " (Role: " . $user['role'] . ")<br>";
        
        // Simular as variáveis da sidebar
        $isAdmin = ($user['role'] === 'admin');
        $isAnalyst = ($user['role'] === 'analista');
        
        echo "isAdmin: " . ($isAdmin ? 'TRUE' : 'FALSE') . "<br>";
        echo "isAnalyst: " . ($isAnalyst ? 'TRUE' : 'FALSE') . "<br>";
        
        if ($isAdmin || $isAnalyst) {
            echo "✅ <strong>DROPDOWN DEVE APARECER</strong><br>";
        } else {
            echo "❌ <strong>DROPDOWN NÃO DEVE APARECER</strong><br>";
        }
    }
}

// Redirecionar para o dashboard
echo "<br><strong>Redirecionando para o dashboard...</strong><br>";
echo "<script>
setTimeout(function() {
    window.location.href = '/dashboard';
}, 3000);
</script>";

echo "<hr>";
echo "<p><a href='/dashboard'>Ir para Dashboard Agora</a></p>";
?>

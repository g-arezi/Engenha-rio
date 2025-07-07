<?php
// Script para testar login e acesso aos documentos
require_once 'vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;
use App\Models\User;

// Inicializar sessão
Session::start();

echo "<h1>Teste de Login e Documentos</h1>";

// Se não estiver logado, fazer login automático
if (!Auth::check()) {
    echo "<h2>Fazendo login automático...</h2>";
    
    // Tentar fazer login com admin
    $userModel = new User();
    $adminUser = $userModel->findByEmail('admin@sistema.com');
    
    if ($adminUser) {
        echo "Usuário admin encontrado: " . $adminUser['name'] . "<br>";
        
        // Fazer login direto (bypass da senha para teste)
        Session::set('user_id', $adminUser['id']);
        Session::regenerate();
        
        echo "Login realizado com sucesso!<br>";
        echo "ID da sessão: " . $adminUser['id'] . "<br>";
        
    } else {
        echo "Usuário admin não encontrado. Criando usuário de teste...<br>";
        
        // Criar usuário admin de teste
        $userData = [
            'name' => 'Administrador do Sistema',
            'email' => 'admin@sistema.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role' => 'admin',
            'active' => 1,
            'approved' => 1
        ];
        
        $userId = $userModel->create($userData);
        
        if ($userId) {
            echo "Usuário criado com ID: $userId<br>";
            Session::set('user_id', $userId);
            Session::regenerate();
            echo "Login realizado!<br>";
        } else {
            echo "Erro ao criar usuário.<br>";
        }
    }
}

// Verificar status atual
if (Auth::check()) {
    $user = Auth::user();
    echo "<h2>Status Atual:</h2>";
    echo "Logado como: " . $user['name'] . " (" . $user['email'] . ")<br>";
    echo "Role: " . $user['role'] . "<br>";
    echo "É admin: " . (Auth::isAdmin() ? 'SIM' : 'NÃO') . "<br>";
    
    echo "<h2>Testes de Acesso:</h2>";
    echo '<a href="/dashboard" target="_blank">Dashboard</a> | ';
    echo '<a href="/documents" target="_blank">Documentos</a> | ';
    echo '<a href="/projects" target="_blank">Projetos</a><br><br>';
    
    // Tentar acessar documentos diretamente
    echo "<h3>Teste Direto do DocumentController:</h3>";
    
    try {
        $controller = new \App\Controllers\DocumentController();
        
        echo "Controller instanciado com sucesso.<br>";
        
        // Capturar a saída
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        
        if (strlen($output) > 0) {
            echo "Controller executado com sucesso. Saída capturada (" . strlen($output) . " bytes):<br>";
            echo "<div style='border: 1px solid #ccc; padding: 10px; max-height: 300px; overflow: auto;'>";
            echo htmlspecialchars(substr($output, 0, 1000)) . (strlen($output) > 1000 ? '...' : '');
            echo "</div>";
        } else {
            echo "Controller executado mas não gerou saída.<br>";
        }
        
    } catch (Exception $e) {
        echo "Erro ao executar controller: " . $e->getMessage() . "<br>";
        echo "Arquivo: " . $e->getFile() . " Linha: " . $e->getLine() . "<br>";
    }
    
} else {
    echo "Falha no login automático.<br>";
    echo '<a href="/login">Fazer Login Manual</a><br>';
}

// Mostrar logs recentes se existirem
echo "<h2>Logs de Erro Recentes:</h2>";
$logFile = ini_get('error_log');
if ($logFile && file_exists($logFile)) {
    $logs = file($logFile);
    $recentLogs = array_slice($logs, -20); // Últimas 20 linhas
    
    echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 200px; overflow: auto;'>";
    foreach ($recentLogs as $log) {
        if (strpos($log, 'Router Debug') !== false || strpos($log, 'Middleware Debug') !== false) {
            echo htmlspecialchars($log);
        }
    }
    echo "</pre>";
} else {
    echo "Arquivo de log não encontrado.<br>";
}

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1, h2, h3 { color: #333; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>

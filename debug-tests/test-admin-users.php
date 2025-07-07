<?php
require_once 'vendor/autoload.php';

// Testar se a classe User funciona
echo "<h1>Teste da Classe User</h1>";

try {
    $userModel = new \App\Models\User();
    
    echo "<h2>1. Testando all():</h2>";
    $users = $userModel->all();
    echo "Usuários encontrados: " . count($users) . "<br>";
    
    foreach ($users as $user) {
        echo "- " . $user['name'] . " (" . $user['email'] . ") - Role: " . $user['role'] . "<br>";
    }
    
    echo "<h2>2. Testando getPendingUsers():</h2>";
    $pendingUsers = $userModel->getPendingUsers();
    echo "Usuários pendentes: " . count($pendingUsers) . "<br>";
    
    foreach ($pendingUsers as $user) {
        echo "- " . $user['name'] . " (" . $user['email'] . ") - Aprovado: " . ($user['approved'] ? 'SIM' : 'NÃO') . "<br>";
    }
    
    echo "<h2>3. Testando AdminController:</h2>";
    
    // Simular login
    session_start();
    \App\Core\Session::start();
    \App\Core\Session::set('user_id', 'admin_002');
    
    $adminController = new \App\Controllers\AdminController();
    
    echo "AdminController criado com sucesso<br>";
    
    echo "<h2>4. Testando método users():</h2>";
    
    // Capturar saída
    ob_start();
    $adminController->users();
    $output = ob_get_clean();
    
    echo "Método users() executado sem erros<br>";
    echo "Saída capturada: " . strlen($output) . " bytes<br>";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
    echo "Arquivo: " . $e->getFile() . "<br>";
    echo "Linha: " . $e->getLine() . "<br>";
}

echo "<hr>";
echo "<p><a href='/admin/users'>Tentar acessar /admin/users</a></p>";
?>

<?php
require_once 'init.php';

// Simular dados do formulário
$_POST = [
    'name' => 'Teste Usuário',
    'email' => 'teste@exemplo.com',
    'password' => '123456',
    'role' => 'analista',
    'active' => 'on',
    'approved' => 'on'
];

// Simular requisição POST
$_SERVER['REQUEST_METHOD'] = 'POST';

// Testar o AdminController
try {
    $adminController = new App\Controllers\AdminController();
    
    // Capturar output
    ob_start();
    $adminController->createUser();
    $output = ob_get_clean();
    
    echo "Resposta do servidor:\n";
    echo $output . "\n";
    
    // Verificar se o usuário foi criado
    echo "\nVerificando users.json:\n";
    $usersData = json_decode(file_get_contents('data/users.json'), true);
    print_r($usersData);
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>

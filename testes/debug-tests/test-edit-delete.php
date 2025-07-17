<?php
// Teste direto de edição de usuário
require_once 'vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;
use App\Controllers\AdminController;

// Simular ambiente
Session::start();
$_SESSION['user'] = [
    'id' => 'admin_001',
    'name' => 'Administrador',
    'email' => 'admin@engenhario.com',
    'role' => 'admin'
];

$controller = new AdminController();

echo "=== Teste de editUser ===\n";

// Simular requisição GET para editUser
$_SERVER['REQUEST_METHOD'] = 'GET';

// Testar com um usuário existente
$userId = 'analyst_001';

ob_start();
$controller->editUser($userId);
$output = ob_get_clean();

echo "Output: " . $output . "\n";

echo "\n=== Teste de deleteUser ===\n";

// Testar deleteUser
$_SERVER['REQUEST_METHOD'] = 'DELETE';

// Criar um usuário para testar delete
use App\Models\User;
$userModel = new User();

$testUser = [
    'name' => 'Usuário para deletar',
    'email' => 'delete@test.com',
    'password' => password_hash('123456', PASSWORD_DEFAULT),
    'role' => 'user',
    'active' => true,
    'approved' => true
];

$userModel->create($testUser);
$createdUser = $userModel->findByEmail('delete@test.com');

if ($createdUser) {
    echo "Usuário criado com ID: " . $createdUser['id'] . "\n";
    
    ob_start();
    $controller->deleteUser($createdUser['id']);
    $deleteOutput = ob_get_clean();
    
    echo "Output delete: " . $deleteOutput . "\n";
    
    // Verificar se foi deletado
    $deletedUser = $userModel->find($createdUser['id']);
    echo "Usuário após delete: " . ($deletedUser ? "AINDA EXISTE" : "DELETADO") . "\n";
}

echo "\n=== Teste concluído ===\n";
?>

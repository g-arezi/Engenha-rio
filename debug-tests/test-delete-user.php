<?php
// Teste simples de delete de usuário
require_once 'vendor/autoload.php';

use App\Models\User;

echo "=== Testando Delete de Usuário ===\n";

$userModel = new User();

// Listar usuários antes
$users = $userModel->all();
echo "Total de usuários antes: " . count($users) . "\n";

// Tentar deletar um usuário de teste
$userToDelete = 'user_inactive';
$userBefore = $userModel->find($userToDelete);

if ($userBefore) {
    echo "Usuário encontrado: {$userBefore['name']}\n";
    
    $success = $userModel->delete($userToDelete);
    echo "Delete: " . ($success ? "SUCESSO" : "FALHOU") . "\n";
    
    $userAfter = $userModel->find($userToDelete);
    echo "Usuário após delete: " . ($userAfter ? "AINDA EXISTE" : "REMOVIDO") . "\n";
    
    // Listar usuários depois
    $usersAfter = $userModel->all();
    echo "Total de usuários depois: " . count($usersAfter) . "\n";
} else {
    echo "Usuário não encontrado\n";
}

echo "=== Teste concluído ===\n";
?>

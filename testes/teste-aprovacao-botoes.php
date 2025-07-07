<?php
// Teste para criar um usuário pendente e testar aprovação/rejeição
require_once 'init.php';

use App\Models\User;

$userModel = new User();

// Criar um usuário de teste pendente
$testUser = [
    'name' => 'Teste User',
    'email' => 'gabriel.arezi.gsa@gmail.com',
    'password' => password_hash('123456', PASSWORD_DEFAULT),
    'role' => 'cliente',
    'approved' => false,
    'active' => true,
    'created_at' => date('Y-m-d H:i:s')
];

// Verificar se o usuário já existe
$existingUser = $userModel->findByEmail($testUser['email']);

if (!$existingUser) {
    $userId = $userModel->create($testUser);
    echo "Usuário de teste criado com ID: $userId\n";
} else {
    echo "Usuário já existe com ID: " . $existingUser['id'] . "\n";
    // Se já existe, vamos torná-lo pendente para teste
    $userModel->update($existingUser['id'], ['approved' => false]);
    echo "Usuário marcado como pendente para teste\n";
}

// Listar usuários pendentes
$pendingUsers = $userModel->getPendingUsers();
echo "\nUsuários pendentes:\n";
foreach ($pendingUsers as $user) {
    echo "ID: {$user['id']}, Nome: {$user['name']}, Email: {$user['email']}\n";
}

echo "\nTeste o sistema acessando: http://localhost:8000/admin/users\n";
echo "Faça login como admin e teste os botões de aprovar/rejeitar\n";
?>

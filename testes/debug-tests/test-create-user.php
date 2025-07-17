<?php
// Teste de criação de usuário
require_once 'vendor/autoload.php';

use App\Models\User;

echo "=== Testando Criação de Usuário ===\n";

$userModel = new User();

// Dados do novo usuário
$userData = [
    'name' => 'Novo Usuário Teste',
    'email' => 'novo@teste.com',
    'password' => password_hash('123456', PASSWORD_DEFAULT),
    'role' => 'user',
    'active' => true,
    'approved' => false
];

// Verificar se email já existe
$existingUser = $userModel->findByEmail($userData['email']);
if ($existingUser) {
    echo "Email já existe. Deletando usuário anterior...\n";
    $userModel->delete($existingUser['id']);
}

// Criar usuário
$success = $userModel->create($userData);
echo "Criação: " . ($success ? "SUCESSO" : "FALHOU") . "\n";

if ($success) {
    // Buscar usuário criado
    $createdUser = $userModel->findByEmail($userData['email']);
    if ($createdUser) {
        echo "Usuário criado com ID: {$createdUser['id']}\n";
        echo "Nome: {$createdUser['name']}\n";
        echo "Email: {$createdUser['email']}\n";
        echo "Role: {$createdUser['role']}\n";
        echo "Ativo: " . ($createdUser['active'] ? "SIM" : "NÃO") . "\n";
        echo "Aprovado: " . ($createdUser['approved'] ? "SIM" : "NÃO") . "\n";
    }
}

echo "=== Teste concluído ===\n";
?>

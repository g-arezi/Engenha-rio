<?php
// Teste das funcionalidades de gerenciamento de usuários
require_once 'vendor/autoload.php';

use App\Models\User;
use App\Controllers\AdminController;

// Simular autenticação de admin
$_SESSION['user'] = [
    'id' => 'admin_001',
    'name' => 'Administrador',
    'email' => 'admin@engenhario.com',
    'role' => 'admin'
];

// Testar busca de usuários
$userModel = new User();
$users = $userModel->all();
echo "Total de usuários: " . count($users) . "\n";

// Testar usuários pendentes
$pendingUsers = $userModel->getPendingUsers();
echo "Usuários pendentes: " . count($pendingUsers) . "\n";

// Listar usuários pendentes
if (!empty($pendingUsers)) {
    echo "Usuários pendentes:\n";
    foreach ($pendingUsers as $user) {
        echo "- {$user['name']} ({$user['email']})\n";
    }
}

// Testar aprovação de usuário
echo "\n=== Testando aprovação de usuário ===\n";
$userToApprove = 'user_pending';
$success = $userModel->approveUser($userToApprove);
echo "Aprovação do usuário {$userToApprove}: " . ($success ? "SUCESSO" : "FALHOU") . "\n";

// Verificar se foi aprovado
$approvedUser = $userModel->find($userToApprove);
if ($approvedUser) {
    echo "Status de aprovação: " . ($approvedUser['approved'] ? "APROVADO" : "PENDENTE") . "\n";
}

// Testar toggle de status
echo "\n=== Testando toggle de status ===\n";
$userToToggle = 'user_inactive';
$userBefore = $userModel->find($userToToggle);
echo "Status antes: " . ($userBefore['active'] ? "ATIVO" : "INATIVO") . "\n";

$success = $userModel->update($userToToggle, ['active' => !$userBefore['active']]);
echo "Toggle status: " . ($success ? "SUCESSO" : "FALHOU") . "\n";

$userAfter = $userModel->find($userToToggle);
echo "Status depois: " . ($userAfter['active'] ? "ATIVO" : "INATIVO") . "\n";

echo "\n=== Teste concluído ===\n";
?>

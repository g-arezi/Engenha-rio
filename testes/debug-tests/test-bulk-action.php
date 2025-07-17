<?php
// Teste de ação em lote
require_once 'vendor/autoload.php';

use App\Models\User;
use App\Controllers\AdminController;
use App\Core\Session;

echo "=== Testando Ação em Lote ===\n";

// Simular sessão
Session::start();
$_SESSION['user'] = [
    'id' => 'admin_001',
    'name' => 'Administrador',
    'email' => 'admin@engenhario.com',
    'role' => 'admin'
];

$userModel = new User();

// Listar usuários pendentes
$pendingUsers = $userModel->getPendingUsers();
echo "Usuários pendentes: " . count($pendingUsers) . "\n";

if (!empty($pendingUsers)) {
    $userIds = array_column($pendingUsers, 'id');
    echo "IDs dos usuários pendentes: " . implode(', ', $userIds) . "\n";
    
    // Simular dados da requisição
    $bulkData = [
        'action' => 'approve',
        'users' => $userIds
    ];
    
    // Simular entrada JSON
    $jsonInput = json_encode($bulkData);
    
    // Salvar input em arquivo temporário para simular php://input
    file_put_contents('php://temp/maxmemory:1048576', $jsonInput);
    
    echo "Dados para ação em lote: " . $jsonInput . "\n";
    
    // Executar aprovação manual
    foreach ($userIds as $userId) {
        $success = $userModel->approveUser($userId);
        echo "Aprovação de {$userId}: " . ($success ? "SUCESSO" : "FALHOU") . "\n";
    }
    
    // Verificar resultado
    $pendingAfter = $userModel->getPendingUsers();
    echo "Usuários pendentes após aprovação: " . count($pendingAfter) . "\n";
}

echo "=== Teste concluído ===\n";
?>

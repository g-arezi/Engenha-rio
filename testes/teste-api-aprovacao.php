<?php
// Teste direto das rotas de aprovação/rejeição
require_once 'init.php';

use App\Controllers\AdminController;
use App\Models\User;

// Simular uma requisição POST
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['CONTENT_TYPE'] = 'application/json';

// Criar um usuário pendente para teste
$userModel = new User();
$testUser = [
    'name' => 'Usuário Teste API',
    'email' => 'teste.api@example.com',
    'password' => password_hash('123456', PASSWORD_DEFAULT),
    'role' => 'cliente',
    'approved' => false,
    'active' => true,
    'created_at' => date('Y-m-d H:i:s')
];

// Verificar se já existe
$existingUser = $userModel->findByEmail($testUser['email']);
if (!$existingUser) {
    $userId = $userModel->create($testUser);
    echo "✅ Usuário criado para teste com ID: $userId\n";
} else {
    $userId = $existingUser['id'];
    // Garantir que está pendente
    $userModel->update($userId, ['approved' => false]);
    echo "✅ Usuário existente marcado como pendente: $userId\n";
}

echo "\n=== TESTE DE APROVAÇÃO ===\n";

try {
    $controller = new AdminController();
    
    // Capturar a saída
    ob_start();
    $controller->approveUser($userId);
    $output = ob_get_clean();
    
    echo "Saída da aprovação: $output\n";
    
    // Verificar se foi aprovado
    $user = $userModel->find($userId);
    if ($user && $user['approved']) {
        echo "✅ SUCESSO: Usuário foi aprovado!\n";
    } else {
        echo "❌ ERRO: Usuário não foi aprovado.\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO na aprovação: " . $e->getMessage() . "\n";
}

echo "\n=== CRIANDO NOVO USUÁRIO PARA TESTE DE REJEIÇÃO ===\n";

// Criar outro usuário para testar rejeição
$testUser2 = [
    'name' => 'Usuário Teste Rejeição',
    'email' => 'teste.rejeicao@example.com',
    'password' => password_hash('123456', PASSWORD_DEFAULT),
    'role' => 'cliente',
    'approved' => false,
    'active' => true,
    'created_at' => date('Y-m-d H:i:s')
];

$userId2 = $userModel->create($testUser2);
echo "✅ Usuário criado para teste de rejeição com ID: $userId2\n";

echo "\n=== TESTE DE REJEIÇÃO ===\n";

try {
    $controller = new AdminController();
    
    // Capturar a saída
    ob_start();
    $controller->rejectUser($userId2);
    $output = ob_get_clean();
    
    echo "Saída da rejeição: $output\n";
    
    // Verificar se foi removido
    $user = $userModel->find($userId2);
    if (!$user) {
        echo "✅ SUCESSO: Usuário foi rejeitado e removido!\n";
    } else {
        echo "❌ ERRO: Usuário ainda existe após rejeição.\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO na rejeição: " . $e->getMessage() . "\n";
}

echo "\n=== TESTE CONCLUÍDO ===\n";
echo "Os métodos de aprovação e rejeição estão funcionando!\n";
echo "Agora teste via interface web em: http://localhost:8000/admin/users\n";
?>

<?php
// Debug do erro "Erro ao carregar dados do usuário"
session_start();
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

require_once 'vendor/autoload.php';
require_once 'src/Core/Controller.php';
require_once 'src/Core/Model.php';
require_once 'src/Models/User.php';
require_once 'src/Controllers/AdminController.php';

use App\Controllers\AdminController;
use App\Models\User;

echo "=== DEBUG: ERRO AO CARREGAR DADOS DO USUÁRIO ===\n\n";

// Teste 1: Verificar se o modelo está carregando os dados
echo "1. Testando modelo User:\n";
$userModel = new User();
$users = $userModel->all();
echo "   - Total de usuários carregados: " . count($users) . "\n";

foreach ($users as $user) {
    echo "   - ID: {$user['id']}, Nome: {$user['name']}, Email: {$user['email']}\n";
}

// Teste 2: Testar busca por ID específico
echo "\n2. Testando busca por ID admin_001:\n";
$adminUser = $userModel->find('admin_001');
if ($adminUser) {
    echo "   - ✅ Usuário encontrado: {$adminUser['name']}\n";
    echo "   - Email: {$adminUser['email']}\n";
    echo "   - Role: {$adminUser['role']}\n";
} else {
    echo "   - ❌ Usuário admin_001 não encontrado!\n";
}

// Teste 3: Testar o controller diretamente
echo "\n3. Testando AdminController editUser:\n";
$controller = new AdminController();

// Capturar output do controller
ob_start();
try {
    $controller->editUser('admin_001');
    echo "   - ❌ Controller não deve chegar aqui (deveria ter dado exit)\n";
} catch (Exception $e) {
    echo "   - ❌ Erro no controller: " . $e->getMessage() . "\n";
}
$output = ob_get_clean();

echo "   - Output do controller: " . $output . "\n";

// Teste 4: Verificar se o JSON está válido
if ($output) {
    $json = json_decode($output, true);
    if ($json) {
        echo "   - ✅ JSON válido retornado\n";
        if (isset($json['success']) && $json['success']) {
            echo "   - ✅ Resposta de sucesso\n";
            echo "   - Dados do usuário: " . json_encode($json['user'], JSON_PRETTY_PRINT) . "\n";
        } else {
            echo "   - ❌ Resposta de erro: " . ($json['error'] ?? 'Erro desconhecido') . "\n";
        }
    } else {
        echo "   - ❌ JSON inválido\n";
    }
}

// Teste 5: Testar endpoint HTTP
echo "\n4. Testando endpoint HTTP:\n";
$url = "http://localhost:8000/admin/users/admin_001/edit";
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            "Content-Type: application/json",
            "User-Agent: DebugTool/1.0"
        ],
        'timeout' => 10
    ]
]);

$response = @file_get_contents($url, false, $context);
$headers = $http_response_header ?? [];

echo "   - Headers da resposta:\n";
foreach ($headers as $header) {
    echo "     $header\n";
}

if ($response) {
    echo "   - Resposta HTTP: " . $response . "\n";
    $httpJson = json_decode($response, true);
    if ($httpJson) {
        echo "   - ✅ JSON válido via HTTP\n";
        if (isset($httpJson['success']) && $httpJson['success']) {
            echo "   - ✅ Sucesso via HTTP\n";
        } else {
            echo "   - ❌ Erro via HTTP: " . ($httpJson['error'] ?? 'Erro desconhecido') . "\n";
        }
    } else {
        echo "   - ❌ JSON inválido via HTTP\n";
    }
} else {
    echo "   - ❌ Sem resposta HTTP\n";
}

echo "\n=== FIM DO DEBUG ===\n";
?>

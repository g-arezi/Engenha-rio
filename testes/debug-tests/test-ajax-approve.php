<?php
// Simular requisição AJAX para aprovar usuário
require_once 'vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;
use App\Controllers\AdminController;

// Iniciar sessão
Session::start();

// Simular login como admin
$_SESSION['user'] = [
    'id' => 'admin_001',
    'name' => 'Administrador',
    'email' => 'admin@engenhario.com',
    'role' => 'admin'
];

// Simular requisição POST para aprovar usuário
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/admin/users/68681893352fb/approve';

// Simular controller e método
$controller = new AdminController();

echo "=== Testando aprovação via Controller ===\n";

// Capturar saída
ob_start();
$controller->approveUser('68681893352fb');
$output = ob_get_clean();

echo "Resposta do controller: " . $output . "\n";

// Verificar se usuário foi aprovado
use App\Models\User;
$userModel = new User();
$user = $userModel->find('68681893352fb');

if ($user) {
    echo "Usuário {$user['name']} está " . ($user['approved'] ? "APROVADO" : "PENDENTE") . "\n";
} else {
    echo "Usuário não encontrado\n";
}

echo "=== Teste concluído ===\n";
?>

<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\AdminController;

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

// Testar apenas a parte de validação
echo "Testando validação de dados...\n";

// Verificar se é FormData (POST) ou JSON
$input = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se for FormData, usar $_POST
    if (!empty($_POST)) {
        $input = $_POST;
    } else {
        // Tentar JSON se $_POST estiver vazio
        $json = json_decode(file_get_contents('php://input'), true);
        if ($json) {
            $input = $json;
        }
    }
}

echo "Dados recebidos:\n";
print_r($input);

if (empty($input)) {
    echo "ERRO: Dados não fornecidos\n";
} else {
    echo "SUCCESS: Dados foram recebidos corretamente\n";
    
    // Validar dados obrigatórios
    $required = ['name', 'email', 'password', 'role'];
    $errors = [];
    
    foreach ($required as $field) {
        if (empty($input[$field])) {
            $errors[] = "Campo '$field' é obrigatório";
        }
    }
    
    if (empty($errors)) {
        echo "SUCCESS: Todos os campos obrigatórios estão preenchidos\n";
        
        // Testar processamento de checkboxes
        $active = isset($input['active']) ? ($input['active'] === 'on' || $input['active'] === true || $input['active'] === '1') : true;
        $approved = isset($input['approved']) ? ($input['approved'] === 'on' || $input['approved'] === true || $input['approved'] === '1') : true;
        
        echo "Checkbox 'active': " . ($active ? 'true' : 'false') . "\n";
        echo "Checkbox 'approved': " . ($approved ? 'true' : 'false') . "\n";
        
        // Validar função
        $allowedRoles = ['admin', 'analista', 'cliente'];
        if (in_array($input['role'], $allowedRoles)) {
            echo "SUCCESS: Função '" . $input['role'] . "' é válida\n";
        } else {
            echo "ERRO: Função '" . $input['role'] . "' é inválida\n";
        }
        
    } else {
        echo "ERRO: " . implode(', ', $errors) . "\n";
    }
}
?>

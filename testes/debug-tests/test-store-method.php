<?php
// Teste do método store do DocumentTemplateController

require_once '../vendor/autoload.php';

use App\Controllers\DocumentTemplateController;
use App\Core\Session;

// Iniciar sessão
session_start();

// Simular usuário admin logado (usar ID real do sistema)
$_SESSION['user_id'] = 'admin_002';
$_SESSION['user'] = [
    'id' => 'admin_002',
    'name' => 'Administrador do Sistema',
    'email' => 'admin@sistema.com',
    'role' => 'admin'
];

// Simular dados do POST (formato correto do formulário)
$_POST = [
    'name' => 'Template de Teste',
    'description' => 'Descrição do template de teste',
    'project_type' => 'residencial',
    'required_documents' => ['rg', 'cpf'], // IDs dos documentos
    'optional_documents' => ['escritura'] // IDs dos documentos
];

// Simular REQUEST_METHOD
$_SERVER['REQUEST_METHOD'] = 'POST';

echo "Testando método store do DocumentTemplateController\n";
echo "POST Data: " . json_encode($_POST, JSON_PRETTY_PRINT) . "\n\n";

// Configurar debug mode
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Criar instância do controller
    $controller = new DocumentTemplateController();
    
    echo "Controller criado com sucesso\n";
    
    // Testar método store
    ob_start();
    $controller->store();
    $output = ob_get_contents();
    ob_end_clean();
    
    echo "Método store executado com sucesso\n";
    echo "Output: " . $output . "\n";
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

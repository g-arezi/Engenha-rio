<?php
// Teste direto da criação de template

require_once __DIR__ . '/init.php';

use App\Controllers\DocumentTemplateController;
use App\Core\Session;
use App\Core\Auth;

// Simular login como admin para teste
Session::start();
Session::set('user_id', 'admin_test');

echo "<h1>Teste de Criação de Template</h1>";

// Simular dados do formulário
$_POST = [
    'name' => 'Template Teste Direto',
    'project_type' => 'residencial',
    'description' => 'Descrição do template de teste',
    'required_documents' => ['rg', 'cpf'],
    'optional_documents' => ['escritura']
];

echo "<h2>Dados simulados:</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

try {
    echo "<h2>Testando criação do controlador...</h2>";
    
    $controller = new DocumentTemplateController();
    echo "✅ Controlador criado com sucesso<br>";
    
    echo "<h2>Testando método store...</h2>";
    
    // Capturar output
    ob_start();
    $controller->store();
    $output = ob_get_clean();
    
    echo "✅ Método store executado<br>";
    
    if (!empty($output)) {
        echo "<h3>Output do método:</h3>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }
    
    // Verificar mensagens da sessão
    if (Session::has('error')) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px 0;'>";
        echo "<strong>❌ Erro:</strong> " . Session::get('error');
        echo "</div>";
    }
    
    if (Session::has('success')) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 10px; margin: 10px 0;'>";
        echo "<strong>✅ Sucesso:</strong> " . Session::get('success');
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px 0;'>";
    echo "<strong>❌ Exceção:</strong> " . $e->getMessage();
    echo "<br><strong>Arquivo:</strong> " . $e->getFile();
    echo "<br><strong>Linha:</strong> " . $e->getLine();
    echo "<br><strong>Stack trace:</strong><br><pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
?>

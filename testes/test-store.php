<?php
// Teste específico do método store
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/init.php';

echo "<h1>🔧 Teste do Método Store</h1>";

// Auto-login como admin
App\Core\Session::start();
$_SESSION['user_id'] = 'admin_002';

// Simular dados de POST
$_POST = [
    'name' => 'Template Teste Store',
    'project_type' => 'residencial',
    'description' => 'Descrição do teste',
    'required_documents' => ['rg', 'cpf'],
    'optional_documents' => ['escritura'],
    'active' => 'on'
];

echo "<h2>1. Dados simulados:</h2>";
echo "<pre>" . print_r($_POST, true) . "</pre>";

echo "<h2>2. Verificando autenticação:</h2>";
if (App\Core\Auth::check()) {
    echo "✅ Usuário logado: " . App\Core\Auth::user()['name'] . "<br>";
    if (App\Core\Auth::isAdmin()) {
        echo "✅ Usuário é admin<br>";
        
        echo "<h2>3. Testando método store:</h2>";
        try {
            $controller = new App\Controllers\DocumentTemplateController();
            
            // Simular requisição POST
            $_SERVER['REQUEST_METHOD'] = 'POST';
            
            echo "<h3>Executando store()...</h3>";
            ob_start();
            $controller->store();
            $output = ob_get_clean();
            
            echo "<h3>Output do método:</h3>";
            if (!empty($output)) {
                echo "<pre>" . htmlspecialchars($output) . "</pre>";
            } else {
                echo "Nenhum output direto<br>";
            }
            
            // Verificar mensagens de sessão
            if (App\Core\Session::has('success')) {
                echo "<div style='background: #d4edda; padding: 10px; border: 1px solid #c3e6cb; margin: 10px 0;'>";
                echo "✅ SUCESSO: " . App\Core\Session::get('success');
                echo "</div>";
            }
            
            if (App\Core\Session::has('error')) {
                echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; margin: 10px 0;'>";
                echo "❌ ERRO: " . App\Core\Session::get('error');
                echo "</div>";
            }
            
            echo "<h3>Verificando templates criados:</h3>";
            $model = new App\Models\DocumentTemplate();
            $templates = $model->all();
            echo "Total de templates: " . count($templates) . "<br>";
            
            foreach ($templates as $template) {
                if (strpos($template['name'], 'Teste') !== false) {
                    echo "✅ Template de teste encontrado: " . $template['name'] . " (ID: " . $template['id'] . ")<br>";
                }
            }
            
        } catch (Exception $e) {
            echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; margin: 10px 0;'>";
            echo "❌ EXCEÇÃO: " . $e->getMessage() . "<br>";
            echo "Arquivo: " . $e->getFile() . "<br>";
            echo "Linha: " . $e->getLine() . "<br>";
            echo "<strong>Stack trace:</strong><br>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
            echo "</div>";
        }
        
    } else {
        echo "❌ Usuário não é admin<br>";
    }
} else {
    echo "❌ Usuário não está logado<br>";
}
?>

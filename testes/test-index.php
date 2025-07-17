<?php
// Teste do método index
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/init.php';

echo "<h1>🔧 Teste do Index de Templates</h1>";

// Simular admin logado
App\Core\Session::start();
$_SESSION['user_id'] = 'admin_002';

echo "<h2>1. Verificando autenticação...</h2>";
if (App\Core\Auth::check()) {
    echo "✅ Usuário logado: " . App\Core\Auth::user()['name'] . "<br>";
    
    if (App\Core\Auth::isAdmin()) {
        echo "✅ Usuário é admin<br>";
        
        echo "<h2>2. Testando método index...</h2>";
        
        try {
            $controller = new App\Controllers\DocumentTemplateController();
            
            echo "<h3>Testando templateModel->all()...</h3>";
            $templateModel = new App\Models\DocumentTemplate();
            $templates = $templateModel->all();
            echo "✅ Encontrados " . count($templates) . " templates<br>";
            
            echo "<h3>Testando getUsageStats para cada template...</h3>";
            foreach ($templates as $template) {
                try {
                    $stats = $templateModel->getUsageStats($template['id']);
                    echo "✅ Template '{$template['name']}': " . ($stats['projects_count'] ?? 0) . " usos<br>";
                } catch (Exception $e) {
                    echo "❌ Erro em getUsageStats para '{$template['name']}': " . $e->getMessage() . "<br>";
                }
            }
            
            echo "<h3>Testando método index completo...</h3>";
            ob_start();
            $controller->index();
            $output = ob_get_clean();
            
            if (!empty($output)) {
                echo "✅ Método index executado com sucesso!<br>";
                echo "<h4>Preview da página:</h4>";
                echo "<div style='border: 1px solid #ccc; padding: 10px; max-height: 400px; overflow-y: auto;'>";
                echo $output;
                echo "</div>";
            } else {
                echo "⚠️ Método index executado mas sem output<br>";
            }
            
        } catch (Exception $e) {
            echo "❌ Erro no teste: " . $e->getMessage() . "<br>";
            echo "Arquivo: " . $e->getFile() . "<br>";
            echo "Linha: " . $e->getLine() . "<br>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
    } else {
        echo "❌ Usuário não é admin<br>";
    }
} else {
    echo "❌ Usuário não está logado<br>";
}
?>

<?php
require_once 'init.php';

use App\Models\DocumentTemplate;

echo "=== TESTE DA CLASSE DOCUMENTTEMPLATE ===\n";

try {
    $template = new DocumentTemplate();
    echo "✅ Classe DocumentTemplate instanciada com sucesso!\n";
    echo "Classe: " . get_class($template) . "\n";
    
    // Testar busca de templates
    $templates = $template->all();
    echo "✅ Total de templates encontrados: " . count($templates) . "\n";
    
    // Testar busca por tipo
    $residenciais = $template->getByProjectType('residencial');
    if (count($residenciais) > 0) {
        $primeiro = array_values($residenciais)[0];
        echo "✅ Template residencial encontrado: " . $primeiro['name'] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

echo "\n=== TESTE CONCLUÍDO ===\n";
?>

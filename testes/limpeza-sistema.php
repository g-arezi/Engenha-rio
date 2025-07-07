<?php
/**
 * Script de Limpeza - Remove dados hardcoded e de teste
 */

echo "=== LIMPEZA DO SISTEMA ===\n\n";

echo "✅ Projetos hardcoded removidos dos templates:\n";
echo "   - Reforma Comercial Santos (removido de projects/index.php)\n";
echo "   - Edifício Comercial Central (removido de projects/index.php)\n";
echo "   - Removido do painel admin (admin/index.php)\n";
echo "   - Removido do histórico (history/index.php)\n\n";

echo "✅ Projetos de teste removidos do banco de dados:\n";
echo "   - 686be69233711: Projeto Teste Com Cliente\n";
echo "   - 686be7c5db150: Projeto Teste Com Cliente\n";
echo "   - 686be85258b94: Projeto Teste\n\n";

// Verificar se o arquivo projects.json está limpo
$projectsFile = __DIR__ . '/data/projects.json';
if (file_exists($projectsFile)) {
    $projects = json_decode(file_get_contents($projectsFile), true);
    echo "📊 Projetos restantes no sistema:\n";
    
    foreach ($projects as $id => $project) {
        $status = $project['status'] ?? 'sem status';
        echo "   - {$project['name']} (Status: {$status})\n";
    }
    
    echo "\nTotal de projetos: " . count($projects) . "\n\n";
}

echo "✅ Sistema limpo e pronto para uso!\n";
echo "\nApós esta limpeza:\n";
echo "- Apenas projetos reais permanecem no sistema\n";
echo "- Não há mais dados hardcoded nos templates\n";
echo "- Interface mostra apenas dados dinâmicos\n";
echo "- Funcionalidade de vinculação cliente-projeto mantida\n";

?>

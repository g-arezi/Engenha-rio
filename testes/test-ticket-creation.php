<?php
// Teste simples para verificar criação de ticket com projeto

// Simular dados de POST
$data = [
    'project_id' => 'project_001',
    'subject' => 'Teste com projeto',
    'description' => 'Testando se o projeto está sendo salvo',
    'priority' => 'media'
];

echo "Dados enviados:\n";
print_r($data);

// Verificar arquivo de projetos
$projectsFile = __DIR__ . '/data/projects.json';
echo "\nArquivo de projetos: " . $projectsFile . "\n";
echo "Arquivo existe: " . (file_exists($projectsFile) ? 'SIM' : 'NÃO') . "\n";

if (file_exists($projectsFile)) {
    $projects = json_decode(file_get_contents($projectsFile), true);
    echo "\nProjetos encontrados:\n";
    
    $projectName = 'Projeto desconhecido';
    foreach ($projects as $projectKey => $project) {
        echo "- Key: $projectKey, ID: " . (isset($project['id']) ? $project['id'] : 'sem ID') . ", Nome: " . (isset($project['name']) ? $project['name'] : 'sem nome') . "\n";
        
        if (isset($project['id']) && $project['id'] == $data['project_id']) {
            $projectName = $project['name'];
            echo "  *** ENCONTRADO! Nome: $projectName\n";
        }
    }
    
    echo "\nProjeto final: $projectName\n";
}
?>

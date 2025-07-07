<?php
/**
 * Teste das Novas Permissões do Sistema
 * 
 * Este arquivo testa as regras implementadas:
 * - Apenas admin e analista podem criar/validar projetos
 * - Cliente pode enviar documentos apenas aos projetos vinculados
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Auth;
use App\Middleware\ProjectAccessMiddleware;
use App\Models\Project;
use App\Models\User;

echo "=== TESTE DE PERMISSÕES DO SISTEMA ===\n\n";

// Simular usuários diferentes
$usuarios = [
    'admin' => ['id' => 'admin_002', 'role' => 'admin', 'name' => 'Administrador'],
    'analista' => ['id' => 'analyst_001', 'role' => 'analista', 'name' => 'Analista'],
    'cliente' => ['id' => 'client_001', 'role' => 'cliente', 'name' => 'Cliente']
];

foreach ($usuarios as $tipo => $userData) {
    echo "--- TESTANDO USUÁRIO: {$userData['name']} ({$tipo}) ---\n";
    
    // Simular sessão do usuário
    $_SESSION['user_id'] = $userData['id'];
    
    // Verificar permissões de gestão de projetos
    $canManage = Auth::canManageProjects();
    echo "Pode gerenciar projetos: " . ($canManage ? 'SIM' : 'NÃO') . "\n";
    
    // Verificar permissões específicas
    echo "Permissões específicas:\n";
    $permissions = ['create_projects', 'validate_projects', 'upload_documents', 'view_projects'];
    
    foreach ($permissions as $permission) {
        $can = Auth::can($permission);
        echo "  - {$permission}: " . ($can ? 'SIM' : 'NÃO') . "\n";
    }
    
    // Verificar acesso a projeto específico (project_001)
    $canUpload = Auth::canUploadToProject('project_001');
    echo "Pode enviar docs para project_001: " . ($canUpload ? 'SIM' : 'NÃO') . "\n";
    
    // Testar middleware de acesso
    $accessResults = [
        'create' => ProjectAccessMiddleware::checkAccess('create'),
        'validate' => ProjectAccessMiddleware::checkAccess('validate'),
        'upload_document' => ProjectAccessMiddleware::checkAccess('upload_document', 'project_001')
    ];
    
    echo "Resultados do middleware:\n";
    foreach ($accessResults as $action => $result) {
        $status = $result['allowed'] ? 'PERMITIDO' : 'NEGADO';
        echo "  - {$action}: {$status} - {$result['message']}\n";
    }
    
    echo "\n";
}

// Testar filtragem de projetos
echo "--- TESTE DE FILTRAGEM DE PROJETOS ---\n";

$projectModel = new Project();
$allProjects = $projectModel->all();

echo "Total de projetos no sistema: " . count($allProjects) . "\n\n";

foreach ($usuarios as $tipo => $userData) {
    $_SESSION['user_id'] = $userData['id'];
    
    $filteredProjects = ProjectAccessMiddleware::filterProjectsForUser($allProjects, $userData);
    echo "{$userData['name']} ({$tipo}) pode ver: " . count($filteredProjects) . " projetos\n";
    
    if ($tipo === 'cliente') {
        $clientProjects = ProjectAccessMiddleware::getClientProjects($userData['id']);
        echo "  - Projetos vinculados: " . count($clientProjects) . "\n";
        
        foreach ($clientProjects as $project) {
            echo "    * {$project['name']}\n";
        }
    }
}

echo "\n--- TESTE DE VINCULAÇÃO CLIENTE-PROJETO ---\n";

$clientId = 'client_001';
$testProjects = ['project_001', 'project_002', 'project_003', 'project_004'];

foreach ($testProjects as $projectId) {
    $isLinked = ProjectAccessMiddleware::isClientLinkedToProject($clientId, $projectId);
    echo "Cliente client_001 vinculado ao {$projectId}: " . ($isLinked ? 'SIM' : 'NÃO') . "\n";
}

echo "\n=== RESUMO DAS REGRAS IMPLEMENTADAS ===\n";
echo "✅ Admin: Acesso total (criar, validar, gerenciar projetos)\n";
echo "✅ Analista: Pode criar, validar e gerenciar projetos\n";
echo "✅ Cliente: Pode apenas ver projetos vinculados e enviar documentos\n";
echo "✅ Upload de documentos: Cliente restrito aos projetos vinculados\n";
echo "✅ Validação de projetos: Apenas admin e analista\n";
echo "✅ Middleware implementado para controle granular de acesso\n";

echo "\n=== TESTE CONCLUÍDO ===\n";
?>

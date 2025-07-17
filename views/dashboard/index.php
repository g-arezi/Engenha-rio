<?php 
$title = 'Dashboard - Engenha Rio';
$pageTitle = 'Dashboard';
$showSidebar = true;
$activeMenu = 'dashboard';
ob_start();

// Incluir os modelos necessários
require_once __DIR__ . '/../../src/Models/Project.php';
require_once __DIR__ . '/../../src/Models/User.php';
require_once __DIR__ . '/../../src/Core/Auth.php';

use App\Models\Project;
use App\Models\User;
use App\Core\Auth;

// Obter o usuário atual
$currentUser = Auth::user();
$projectModel = new Project();
$userModel = new User();

// Obter projetos baseado no tipo de usuário
$projects = [];
if ($currentUser) {
    if (Auth::isAdmin()) {
        // Administradores veem todos os projetos
        $projects = $projectModel->all();
    } elseif (Auth::isAnalyst()) {
        // Analistas veem projetos atribuídos a eles
        $projects = $projectModel->getByAnalyst($currentUser['id']);
    } elseif (Auth::isClient()) {
        // Clientes veem apenas projetos vinculados a eles
        $projects = $projectModel->getByClient($currentUser['id']);
    }
}

// Dados para os cards de status baseados nos projetos reais
$statusCounts = [
    'em_analise' => 0,
    'reprovado' => 0,
    'pendentes' => 0,
    'aprovado' => 0
];

// Contar status dos projetos
foreach ($projects as $project) {
    $status = $project['status'] ?? '';
    switch ($status) {
        case 'aguardando':
            $statusCounts['em_analise']++;
            break;
        case 'atrasado':
            $statusCounts['reprovado']++;
            break;
        case 'pendente':
            $statusCounts['pendentes']++;
            break;
        case 'aprovado':
            $statusCounts['aprovado']++;
            break;
    }
}

$statusData = [
    'em_analise' => [
        'count' => $statusCounts['em_analise'],
        'color' => '#007BFF',
        'label' => 'Em análise',
        'code' => '#045DBD'
    ],
    'reprovado' => [
        'count' => $statusCounts['reprovado'],
        'color' => '#E32528',
        'label' => 'Reprovado',
        'code' => '#AD070A'
    ],
    'pendentes' => [
        'count' => $statusCounts['pendentes'],
        'color' => '#F9B800',
        'label' => 'Pendentes',
        'code' => '#B88700'
    ],
    'aprovado' => [
        'count' => $statusCounts['aprovado'],
        'color' => '#00A65A',
        'label' => 'Aprovado',
        'code' => '#028F46'
    ]
];

// Preparar dados da tabela de projetos (últimos 5)
$projectsData = [];
$recentProjects = array_slice($projects, 0, 5);
foreach ($recentProjects as $project) {
    // Obter nome do cliente se disponível
    $clientName = 'N/A';
    if (!empty($project['client_id'])) {
        $client = $userModel->find($project['client_id']);
        $clientName = $client ? $client['name'] : 'Cliente não encontrado';
    } elseif (!empty($project['user_id'])) {
        $client = $userModel->find($project['user_id']);
        $clientName = $client ? $client['name'] : 'Usuário não encontrado';
    }
    
    $statusTranslation = [
        'aguardando' => 'Em análise',
        'pendente' => 'Pendente',
        'aprovado' => 'Aprovado',
        'atrasado' => 'Atrasado',
        'concluido' => 'Concluído'
    ];
    
    $projectsData[] = [
        'name' => $project['name'],
        'client' => $clientName,
        'status' => $statusTranslation[$project['status']] ?? ucfirst($project['status']),
        'updated' => date('d.m.Y - H:i', strtotime($project['updated_at']))
    ];
}

// Se não houver projetos, mostrar dados de exemplo
if (empty($projectsData)) {
    $projectsData = [
        ['name' => 'Nenhum projeto encontrado', 'client' => '-', 'status' => '-', 'updated' => '-']
    ];
}
?>

<style>
    .dashboard-container {
        background-color: #FFFFFF;
        min-height: 100vh;
        padding: 0;
    }
    
    .stats-section {
        padding: 2rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .status-card {
        border-radius: 12px;
        color: white;
        position: relative;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        min-height: 120px;
        display: flex;
        flex-direction: column;
    }
    
    .status-stripe {
        width: 100%;
        height: 32px;
        margin: 0;
        border-radius: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        color: white;
    }
    
    .status-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        flex: 1;
    }
    
    .status-number {
        font-size: 3rem;
        font-weight: bold;
        margin: 0;
    }
    
    .projects-section {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .projects-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .projects-table th {
        background-color: #F8F9FA;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #495057;
        border-bottom: 1px solid #E9ECEF;
    }
    
    .projects-table td {
        padding: 1rem;
        border-bottom: 1px solid #F8F9FA;
    }
    
    .project-name {
        color: #007BFF;
        text-decoration: none;
        font-weight: 500;
    }
    
    .project-name:hover {
        text-decoration: underline;
    }
    
    .status-badge {
        background-color: #007BFF;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .main-background {
        background-color: #FFFFFF;
        color: #000000;
    }
    
    .sidebar-bg {
        background-color: #262626 !important;
    }
    
    .footer-bg {
        background-color: #F8F9FA;
    }
</style>

<div class="dashboard-container main-background">
    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-grid">
            <!-- Card Em Análise -->
            <div class="status-card" style="background-color: #007BFF;">
                <div class="status-content">
                    <div class="status-number"><?= $statusData['em_analise']['count'] ?></div>
                </div>
                <div class="status-stripe" style="background-color: #045DBD;">Em análise</div>
            </div>
            
            <!-- Card Reprovado -->
            <div class="status-card" style="background-color: #E32528;">
                <div class="status-content">
                    <div class="status-number"><?= $statusData['reprovado']['count'] ?></div>
                </div>
                <div class="status-stripe" style="background-color: #AD070A;">Reprovado</div>
            </div>
            
            <!-- Card Pendentes -->
            <div class="status-card" style="background-color: #F9B800;">
                <div class="status-content">
                    <div class="status-number"><?= $statusData['pendentes']['count'] ?></div>
                </div>
                <div class="status-stripe" style="background-color: #B88700;">Pendentes</div>
            </div>
            
            <!-- Card Aprovado -->
            <div class="status-card" style="background-color: #00A65A;">
                <div class="status-content">
                    <div class="status-number"><?= $statusData['aprovado']['count'] ?></div>
                </div>
                <div class="status-stripe" style="background-color: #028F46;">Aprovado</div>
            </div>
        </div>

        
        <!-- Projects Table -->
        <div class="projects-section">
            <table class="projects-table">
                <thead>
                    <tr>
                        <th>Projeto</th>
                        <th>Cliente</th>
                        <th>Status</th>
                        <th>Atualizado em</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projectsData as $project): ?>
                    <tr>
                        <td><a href="#" class="project-name"><?= htmlspecialchars($project['name']) ?></a></td>
                        <td><?= htmlspecialchars($project['client']) ?></td>
                        <td><span class="status-badge"><?= htmlspecialchars($project['status']) ?></span></td>
                        <td><?= htmlspecialchars($project['updated']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

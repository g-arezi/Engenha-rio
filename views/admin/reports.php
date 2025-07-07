<?php 
$title = 'Relatórios - Engenha Rio';
$showSidebar = true;
$activeMenu = 'admin';
ob_start();
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin">Administração</a></li>
                        <li class="breadcrumb-item active">Relatórios</li>
                    </ol>
                </nav>
                <h2 class="h4 mb-0">📊 Relatórios e Estatísticas</h2>
                <p class="text-muted">Visão geral das atividades e performance do sistema</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i>
                    Imprimir
                </button>
                <button class="btn btn-outline-success" onclick="exportReport()">
                    <i class="fas fa-download me-1"></i>
                    Exportar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas Gerais -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-folder-open"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0">Total de Projetos</h6>
                        <h4 class="mb-0"><?= number_format($stats['totalProjects']) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0">Total de Documentos</h6>
                        <h4 class="mb-0"><?= number_format($stats['totalDocuments']) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0">Total de Usuários</h6>
                        <h4 class="mb-0"><?= number_format($stats['totalUsers']) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0">Aguardando Aprovação</h6>
                        <h4 class="mb-0"><?= number_format($stats['pendingApprovals']) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos e Estatísticas Detalhadas -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Projetos por Status
                </h5>
            </div>
            <div class="card-body">
                <canvas id="projectStatusChart" width="400" height="200"></canvas>
                
                <div class="mt-3">
                    <?php 
                    $statusColors = [
                        'aguardando' => 'warning',
                        'pendente' => 'info',
                        'aprovado' => 'success',
                        'atrasado' => 'danger',
                        'concluido' => 'success'
                    ];
                    
                    $statusNames = [
                        'aguardando' => 'Aguardando',
                        'pendente' => 'Pendente',
                        'aprovado' => 'Aprovado',
                        'atrasado' => 'Atrasado',
                        'concluido' => 'Concluído'
                    ];
                    ?>
                    
                    <?php foreach ($projectsByStatus as $status => $count): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-<?= $statusColors[$status] ?? 'secondary' ?>">
                                <?= $statusNames[$status] ?? ucfirst($status) ?>
                            </span>
                            <span class="fw-bold"><?= $count ?> projeto(s)</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Usuários por Tipo
                </h5>
            </div>
            <div class="card-body">
                <canvas id="userRoleChart" width="400" height="200"></canvas>
                
                <div class="mt-3">
                    <?php 
                    $roleColors = [
                        'admin' => 'danger',
                        'analista' => 'primary',
                        'cliente' => 'success'
                    ];
                    
                    $roleNames = [
                        'admin' => 'Administradores',
                        'analista' => 'Analistas',
                        'cliente' => 'Clientes'
                    ];
                    ?>
                    
                    <?php foreach ($usersByRole as $role => $count): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-<?= $roleColors[$role] ?? 'secondary' ?>">
                                <?= $roleNames[$role] ?? ucfirst($role) ?>
                            </span>
                            <span class="fw-bold"><?= $count ?> usuário(s)</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas Mensais -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Atividade dos Últimos 6 Meses
                </h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" width="400" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Projetos e Documentos Recentes -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2"></i>
                    Top 5 Projetos (por documentos)
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($topProjects)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($topProjects as $index => $item): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($item['project']['name']) ?></h6>
                                    <small class="text-muted">
                                        Status: 
                                        <span class="badge bg-<?= $statusColors[$item['project']['status']] ?? 'secondary' ?> badge-sm">
                                            <?= $statusNames[$item['project']['status']] ?? ucfirst($item['project']['status']) ?>
                                        </span>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary rounded-pill"><?= $item['document_count'] ?></span>
                                    <small class="text-muted d-block">documentos</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Nenhum projeto encontrado</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Documentos Recentes
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentDocuments)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentDocuments as $document): ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <?php
                                        $extension = strtolower(pathinfo($document['name'], PATHINFO_EXTENSION));
                                        $iconClass = 'fas fa-file';
                                        $iconColor = 'text-secondary';
                                        
                                        switch ($extension) {
                                            case 'pdf':
                                                $iconClass = 'fas fa-file-pdf';
                                                $iconColor = 'text-danger';
                                                break;
                                            case 'jpg':
                                            case 'jpeg':
                                            case 'png':
                                                $iconClass = 'fas fa-file-image';
                                                $iconColor = 'text-success';
                                                break;
                                            case 'doc':
                                            case 'docx':
                                                $iconClass = 'fas fa-file-word';
                                                $iconColor = 'text-primary';
                                                break;
                                        }
                                        ?>
                                        <i class="<?= $iconClass ?> <?= $iconColor ?>"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= htmlspecialchars($document['name']) ?></h6>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($document['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">Nenhum documento encontrado</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Configurações dos gráficos
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom'
        }
    }
};

// Gráfico de Status dos Projetos
const projectStatusData = <?= json_encode($projectsByStatus) ?>;
const statusLabels = Object.keys(projectStatusData).map(status => {
    const names = {
        'aguardando': 'Aguardando',
        'pendente': 'Pendente', 
        'aprovado': 'Aprovado',
        'atrasado': 'Atrasado',
        'concluido': 'Concluído'
    };
    return names[status] || status.charAt(0).toUpperCase() + status.slice(1);
});
const statusValues = Object.values(projectStatusData);

new Chart(document.getElementById('projectStatusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusValues,
            backgroundColor: [
                '#ffc107', // warning
                '#17a2b8', // info  
                '#28a745', // success
                '#dc3545', // danger
                '#6f42c1'  // purple
            ]
        }]
    },
    options: chartOptions
});

// Gráfico de Tipos de Usuários
const userRoleData = <?= json_encode($usersByRole) ?>;
const roleLabels = Object.keys(userRoleData).map(role => {
    const names = {
        'admin': 'Administradores',
        'analista': 'Analistas',
        'cliente': 'Clientes'
    };
    return names[role] || role.charAt(0).toUpperCase() + role.slice(1);
});
const roleValues = Object.values(userRoleData);

new Chart(document.getElementById('userRoleChart'), {
    type: 'pie',
    data: {
        labels: roleLabels,
        datasets: [{
            data: roleValues,
            backgroundColor: [
                '#dc3545', // danger - admin
                '#007bff', // primary - analista
                '#28a745'  // success - cliente
            ]
        }]
    },
    options: chartOptions
});

// Gráfico de Atividade Mensal
const monthlyData = <?= json_encode($monthlyStats) ?>;
const monthLabels = monthlyData.map(item => item.month);
const projectsData = monthlyData.map(item => item.projects);
const documentsData = monthlyData.map(item => item.documents);
const usersData = monthlyData.map(item => item.users);

new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: monthLabels,
        datasets: [
            {
                label: 'Projetos',
                data: projectsData,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            },
            {
                label: 'Documentos',
                data: documentsData,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            },
            {
                label: 'Usuários',
                data: usersData,
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

function exportReport() {
    alert('Funcionalidade de exportação em desenvolvimento');
}
</script>

<style>
@media print {
    .btn, .breadcrumb {
        display: none !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
    }
}

canvas {
    max-height: 300px;
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

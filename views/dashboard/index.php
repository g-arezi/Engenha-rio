<?php 
$title = 'Dashboard - Engenha Rio';
$showSidebar = true;
$activeMenu = 'dashboard';
ob_start();
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Dashboard</h2>
            <span class="badge bg-primary">Admin</span>
        </div>
        <p class="text-muted">Bem-vindo, Administrador!</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="background: #007bff; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div class="text-end">
                <div class="stat-number"><?= $stats['users'] ?? 3 ?></div>
                <h6 class="mb-0">Usuários</h6>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: #28a745; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-folder fa-2x"></i>
            </div>
            <div class="text-end">
                <div class="stat-number"><?= $stats['projects'] ?? 3 ?></div>
                <h6 class="mb-0">Projetos</h6>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: #ffc107; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-clock fa-2x"></i>
            </div>
            <div class="text-end">
                <div class="stat-number"><?= $stats['pending'] ?? 1 ?></div>
                <h6 class="mb-0">Pendentes</h6>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: #17a2b8; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-file-alt fa-2x"></i>
            </div>
            <div class="text-end">
                <div class="stat-number"><?= $stats['documents'] ?? 8 ?></div>
                <h6 class="mb-0">Documentos</h6>
            </div>
        </div>
    </div>
</div>

<div class="content-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">📁 Projetos Recentes</h5>
        <a href="/projects" class="btn btn-outline-primary btn-sm">Ver Todos</a>
    </div>
    
    <?php if (!empty($recentProjects)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Projeto</th>
                        <th>Cliente</th>
                        <th>Status</th>
                        <th>Criado em</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentProjects as $project): ?>
                    <tr>
                        <td>
                            <a href="/projects/<?= $project['id'] ?>" class="text-decoration-none">
                                <?= htmlspecialchars($project['name'] ?? 'Projeto') ?>
                            </a>
                        </td>
                        <td>Cliente Teste</td>
                        <td>
                            <span class="badge bg-<?= $project['status'] === 'em_andamento' ? 'info' : ($project['status'] === 'concluido' ? 'success' : 'warning') ?>">
                                <?= $project['status'] === 'em_andamento' ? 'Em Andamento' : ($project['status'] === 'concluido' ? 'Concluído' : 'Pendente') ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($project['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">Nenhum projeto encontrado</p>
            <a href="/projects/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Criar Projeto
            </a>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($recentDocuments)): ?>
<div class="content-section">
    <div class="section-title">Documentos Recentes</div>
    
    <div class="row">
        <?php foreach ($recentDocuments as $document): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="card-title"><?= htmlspecialchars($document['name'] ?? 'Documento') ?></h6>
                                <p class="card-text text-muted small">
                                    <?= date('d/m/Y H:i', strtotime($document['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <small class="text-muted">
                            <?= $formatBytes($document['size'] ?? 0) ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

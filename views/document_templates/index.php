<?php
$title = 'Templates de Documentos - Engenha Rio';
$showSidebar = true;
$activeMenu = 'templates';
ob_start();
?>

<style>
.template-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
    transition: transform 0.2s;
}
.template-card:hover {
    transform: translateY(-2px);
}
.template-type {
    font-size: 0.8em;
    padding: 0.3em 0.8em;
    border-radius: 20px;
    font-weight: 500;
}
.template-stats {
    display: flex;
    gap: 15px;
    font-size: 0.9em;
}
.doc-count {
    background: #e3f2fd;
    color: #1976d2;
    padding: 2px 8px;
    border-radius: 12px;
}
.usage-badge {
    background: #f3e5f5;
    color: #7b1fa2;
    padding: 2px 8px;
    border-radius: 12px;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Templates de Documentos
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/admin">Administração</a></li>
                            <li class="breadcrumb-item active">Templates de Documentos</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="/admin/document-templates/create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Novo Template
                    </a>
                </div>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2"></i>
                                Lista de Templates
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($templates)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Nenhum template encontrado</h5>
                                    <p class="text-muted">Crie seu primeiro template para organizar os documentos dos projetos.</p>
                                    <a href="/admin/document-templates/create" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>
                                        Criar Template
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($templates as $template): ?>
                                        <div class="col-md-6 mb-4">
                                            <div class="template-card card h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <h6 class="card-title mb-0">
                                                            <?= htmlspecialchars($template['name']) ?>
                                                        </h6>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="/admin/document-templates/<?= $template['id'] ?>">
                                                                    <i class="fas fa-eye me-2"></i>Visualizar
                                                                </a></li>
                                                                <li><a class="dropdown-item" href="/admin/document-templates/<?= $template['id'] ?>/edit">
                                                                    <i class="fas fa-edit me-2"></i>Editar
                                                                </a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item" href="#" onclick="duplicateTemplate('<?= $template['id'] ?>')">
                                                                    <i class="fas fa-copy me-2"></i>Duplicar
                                                                </a></li>
                                                                <li><a class="dropdown-item" href="#" onclick="toggleTemplate('<?= $template['id'] ?>')">
                                                                    <i class="fas fa-<?= ($template['active'] ?? true) ? 'pause' : 'play' ?> me-2"></i>
                                                                    <?= ($template['active'] ?? true) ? 'Desativar' : 'Ativar' ?>
                                                                </a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteTemplate('<?= $template['id'] ?>')">
                                                                    <i class="fas fa-trash me-2"></i>Excluir
                                                                </a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                    <span class="template-type bg-<?= getProjectTypeColor($template['project_type']) ?> text-white">
                                                        <?= getProjectTypeName($template['project_type']) ?>
                                                    </span>
                                                    
                                                    <p class="card-text mt-3 text-muted">
                                                        <?= htmlspecialchars(substr($template['description'], 0, 100)) ?>
                                                        <?= strlen($template['description']) > 100 ? '...' : '' ?>
                                                    </p>
                                                    
                                                    <div class="template-stats mt-3">
                                                        <span class="doc-count">
                                                            <i class="fas fa-file-alt me-1"></i>
                                                            <?= count($template['required_documents'] ?? []) ?> obr.
                                                        </span>
                                                        <span class="doc-count">
                                                            <i class="fas fa-file me-1"></i>
                                                            <?= count($template['optional_documents'] ?? []) ?> opc.
                                                        </span>
                                                        <span class="usage-badge">
                                                            <i class="fas fa-chart-bar me-1"></i>
                                                            <?= $template['usage_count'] ?? 0 ?> projeto(s)
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="mt-3">
                                                        <?php if ($template['active'] ?? true): ?>
                                                            <span class="badge bg-success">Ativo</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Inativo</span>
                                                        <?php endif; ?>
                                                        
                                                        <small class="text-muted ms-2">
                                                            Criado em <?= date('d/m/Y', strtotime($template['created_at'])) ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Estatísticas
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php
                            $totalTemplates = count($templates);
                            $activeTemplates = count(array_filter($templates, function($t) { return $t['active'] ?? true; }));
                            $totalUsage = array_sum(array_column($templates, 'usage_count'));
                            
                            $typeStats = [];
                            foreach ($templates as $template) {
                                $type = $template['project_type'];
                                $typeStats[$type] = ($typeStats[$type] ?? 0) + 1;
                            }
                            ?>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-0"><?= $totalTemplates ?></h4>
                                        <small class="text-muted">Total</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success mb-0"><?= $activeTemplates ?></h4>
                                    <small class="text-muted">Ativos</small>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <strong>Uso Total:</strong>
                                <span class="float-end"><?= $totalUsage ?> projetos</span>
                            </div>
                            
                            <?php if (!empty($typeStats)): ?>
                                <div>
                                    <strong>Por Tipo:</strong>
                                    <div class="mt-2">
                                        <?php foreach ($typeStats as $type => $count): ?>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="template-type bg-<?= getProjectTypeColor($type) ?> text-white">
                                                    <?= getProjectTypeName($type) ?>
                                                </span>
                                                <span><?= $count ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-lightbulb me-2"></i>
                                Dicas
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">💡 Boas Práticas</h6>
                                <ul class="mb-0">
                                    <li>Crie templates específicos por tipo de projeto</li>
                                    <li>Inclua instruções claras para cada documento</li>
                                    <li>Defina tamanhos máximos adequados</li>
                                    <li>Separe documentos obrigatórios dos opcionais</li>
                                    <li>Revise e atualize templates regularmente</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function duplicateTemplate(templateId) {
    const name = prompt('Nome do novo template:');
    if (name) {
        fetch(`/admin/document-templates/${templateId}/duplicate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Template duplicado com sucesso!');
                location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        });
    }
}

function toggleTemplate(templateId) {
    if (confirm('Deseja alterar o status deste template?')) {
        fetch(`/admin/document-templates/${templateId}/toggle`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        });
    }
}

function deleteTemplate(templateId) {
    if (confirm('Tem certeza que deseja excluir este template? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/document-templates/${templateId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Template excluído com sucesso!');
                location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        });
    }
}
</script>

<?php
function getProjectTypeColor($type) {
    $colors = [
        'residencial' => 'success',
        'comercial' => 'info',
        'reforma' => 'warning',
        'regularizacao' => 'danger',
        'urbano' => 'primary',
        'infraestrutura' => 'dark',
        'outro' => 'secondary'
    ];
    return $colors[$type] ?? 'secondary';
}

function getProjectTypeName($type) {
    $names = [
        'residencial' => 'Residencial',
        'comercial' => 'Comercial',
        'reforma' => 'Reforma',
        'regularizacao' => 'Regularização',
        'urbano' => 'Urbano',
        'infraestrutura' => 'Infraestrutura',
        'outro' => 'Outros'
    ];
    return $names[$type] ?? ucfirst($type);
}

$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

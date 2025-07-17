<?php
$title = 'Template: ' . ($template['name'] ?? 'Sem nome') . ' - Engenhario';
$pageTitle = 'Visualizar Template';
$showSidebar = true;
$activeMenu = 'admin';
ob_start();

// Garantir que $template existe
$template = $template ?? [];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
                            <li class="breadcrumb-item"><a href="/admin/document-templates">Templates</a></li>
                            <li class="breadcrumb-item active"><?= htmlspecialchars($template['name'] ?? 'Template') ?></li>
                        </ol>
                    </nav>
                    <h1 class="h4 mb-0">📄 <?= htmlspecialchars($template['name'] ?? 'Template sem nome') ?></h1>
                </div>
                <div>
                    <a href="/admin/document-templates/<?= $template['id'] ?>/edit" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="/admin/document-templates" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            
            <div class="row">
                <!-- Informações Gerais -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações Gerais</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nome:</strong><br><?= htmlspecialchars($template['name'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Tipo de Projeto:</strong><br>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars(ucfirst($template['project_type'] ?? 'N/A')) ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <?php if (!empty($template['description'])): ?>
                            <div class="row">
                                <div class="col-12">
                                    <p><strong>Descrição:</strong><br>
                                    <?= htmlspecialchars($template['description']) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Status:</strong><br>
                                        <?php $isActive = $template['active'] ?? true; ?>
                                        <span class="badge <?= $isActive ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $isActive ? 'Ativo' : 'Inativo' ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>ID:</strong><br>
                                        <code><?= htmlspecialchars($template['id'] ?? 'N/A') ?></code>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Documentos Obrigatórios -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-file-alt text-danger"></i> Documentos Obrigatórios</h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            $requiredDocs = $template['required_documents'] ?? [];
                            if (empty($requiredDocs)): 
                            ?>
                                <p class="text-muted">Nenhum documento obrigatório definido.</p>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php foreach ($requiredDocs as $doc): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= htmlspecialchars($doc['name'] ?? 'Documento') ?></h6>
                                            <small class="text-danger">Obrigatório</small>
                                        </div>
                                        <?php if (!empty($doc['description'])): ?>
                                        <p class="mb-1"><?= htmlspecialchars($doc['description']) ?></p>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            Tipos aceitos: <?= htmlspecialchars($doc['accept'] ?? 'N/A') ?> | 
                                            Tamanho máximo: <?= htmlspecialchars($doc['max_size'] ?? 'N/A') ?>
                                        </small>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Documentos Opcionais -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-file text-info"></i> Documentos Opcionais</h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            $optionalDocs = $template['optional_documents'] ?? [];
                            if (empty($optionalDocs)): 
                            ?>
                                <p class="text-muted">Nenhum documento opcional definido.</p>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php foreach ($optionalDocs as $doc): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= htmlspecialchars($doc['name'] ?? 'Documento') ?></h6>
                                            <small class="text-info">Opcional</small>
                                        </div>
                                        <?php if (!empty($doc['description'])): ?>
                                        <p class="mb-1"><?= htmlspecialchars($doc['description']) ?></p>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            Tipos aceitos: <?= htmlspecialchars($doc['accept'] ?? 'N/A') ?> | 
                                            Tamanho máximo: <?= htmlspecialchars($doc['max_size'] ?? 'N/A') ?>
                                        </small>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar com estatísticas -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Estatísticas</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Documentos Obrigatórios:</strong><br>
                                <span class="badge bg-danger"><?= count($template['required_documents'] ?? []) ?></span>
                            </p>
                            <p><strong>Documentos Opcionais:</strong><br>
                                <span class="badge bg-info"><?= count($template['optional_documents'] ?? []) ?></span>
                            </p>
                            <p><strong>Total de Documentos:</strong><br>
                                <span class="badge bg-primary">
                                    <?= count($template['required_documents'] ?? []) + count($template['optional_documents'] ?? []) ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-cogs"></i> Ações</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/admin/document-templates/<?= $template['id'] ?>/edit" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar Template
                                </a>
                                <button class="btn btn-secondary" onclick="window.print()">
                                    <i class="fas fa-print"></i> Imprimir
                                </button>
                                <a href="/admin/document-templates" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Voltar à Lista
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

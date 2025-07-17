<?php
$title = 'Templates de Documentos - Engenhario';
$pageTitle = 'Templates de Documentos';
$showSidebar = true;
$activeMenu = 'admin';
ob_start();

// Garantir que $templates existe
$templates = $templates ?? [];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4">📄 Templates de Documentos</h1>
                <a href="/admin/document-templates/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Criar Template
                </a>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <?php if (empty($templates)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5>Nenhum template encontrado</h5>
                            <p class="text-muted">Crie seu primeiro template de documentos</p>
                            <a href="/admin/document-templates/create" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Criar Primeiro Template
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($templates as $template): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($template['name'] ?? 'Sem nome') ?></strong>
                                            <?php if (!empty($template['description'])): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($template['description']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars(ucfirst($template['project_type'] ?? 'N/A')) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php $isActive = $template['active'] ?? true; ?>
                                            <span class="badge <?= $isActive ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $isActive ? 'Ativo' : 'Inativo' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/admin/document-templates/<?= $template['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/admin/document-templates/<?= $template['id'] ?>/edit" 
                                               class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= ucfirst($template['project_type'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            $reqCount = count($template['required_documents'] ?? []);
                                            $optCount = count($template['optional_documents'] ?? []);
                                            ?>
                                            <small>
                                                <span class="badge bg-danger"><?= $reqCount ?> obr.</span>
                                                <span class="badge bg-info"><?= $optCount ?> opc.</span>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if ($template['active'] ?? true): ?>
                                                <span class="badge bg-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= $template['usage_count'] ?? 0 ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="/admin/document-templates/<?= $template['id'] ?>" 
                                                   class="btn btn-outline-primary" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="/admin/document-templates/<?= $template['id'] ?>/edit" 
                                                   class="btn btn-outline-secondary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

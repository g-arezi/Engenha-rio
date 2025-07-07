<?php 
$title = htmlspecialchars($project['name']) . ' - Engenha Rio';
$showSidebar = true;
$activeMenu = 'projects';
ob_start();

// Definir cores para status
$statusColors = [
    'aguardando' => 'warning',
    'pendente' => 'info',
    'aprovado' => 'success',
    'atrasado' => 'danger',
    'concluido' => 'success'
];

// Definir texto para status
$statusTexts = [
    'aguardando' => 'Aguardando',
    'pendente' => 'Pendente',
    'aprovado' => 'Aprovado',
    'atrasado' => 'Atrasado',
    'concluido' => 'Concluído'
];
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/projects">Projetos</a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($project['name']) ?></li>
                    </ol>
                </nav>
                <h2 class="h4 mb-0">📁 <?= htmlspecialchars($project['name']) ?></h2>
                <p class="text-muted">Criado em <?= date('d/m/Y', strtotime($project['created_at'])) ?> • Prazo: <?= date('d/m/Y', strtotime($project['deadline'])) ?></p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-<?= $statusColors[$project['status']] ?? 'secondary' ?>">
                    <?= $statusTexts[$project['status']] ?? ucfirst($project['status']) ?>
                </span>
                <button class="btn btn-outline-secondary" onclick="window.location.href='/projects'">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="content-section">
            <h5 class="mb-3">📋 Detalhes do Projeto</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ID do Projeto:</label>
                        <p><?= htmlspecialchars($project['id']) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Prioridade:</label>
                        <p>
                            <?php if ($project['priority'] === 'alta'): ?>
                                <span class="badge bg-danger">Alta</span>
                            <?php elseif ($project['priority'] === 'media'): ?>
                                <span class="badge bg-warning">Média</span>
                            <?php else: ?>
                                <span class="badge bg-success">Normal</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Descrição:</label>
                <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Data de Criação:</label>
                        <p><?= date('d/m/Y H:i', strtotime($project['created_at'])) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Última Atualização:</label>
                        <p><?= date('d/m/Y H:i', strtotime($project['updated_at'])) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Ações:</label>
                <div class="d-flex gap-2">
                    <?php if ($project['status'] !== 'concluido'): ?>
                    <button class="btn btn-success" onclick="updateProjectStatus('<?= $project['id'] ?>', 'concluido')">
                        <i class="fas fa-check me-1"></i>
                        Concluir
                    </button>
                    <?php endif; ?>
                    <?php if ($project['status'] !== 'aprovado'): ?>
                    <button class="btn btn-primary" onclick="updateProjectStatus('<?= $project['id'] ?>', 'aprovado')">
                        <i class="fas fa-thumbs-up me-1"></i>
                        Aprovar
                    </button>
                    <?php endif; ?>
                    <button class="btn btn-warning" onclick="editProject('<?= $project['id'] ?>')">
                        <i class="fas fa-edit me-1"></i>
                        Editar
                    </button>
                    <button class="btn btn-danger" onclick="deleteProject('<?= $project['id'] ?>')">
                        <i class="fas fa-trash me-1"></i>
                        Excluir
                    </button>
                </div>
            </div>
        </div>
        
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">📄 Documentos (<?= count($documents ?? []) ?>)</h5>
                <?php if ($isAdmin || $isAnalyst || $project['client_id'] === $user['id'] || (isset($project['clients']) && in_array($user['id'], $project['clients']))): ?>
                <button class="btn btn-primary" onclick="showUploadModal()">
                    <i class="fas fa-plus me-1"></i>
                    Upload
                </button>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($documents)): ?>
                <div class="row">
                    <?php foreach ($documents as $document): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
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
                                                case 'gif':
                                                    $iconClass = 'fas fa-file-image';
                                                    $iconColor = 'text-success';
                                                    break;
                                                case 'doc':
                                                case 'docx':
                                                    $iconClass = 'fas fa-file-word';
                                                    $iconColor = 'text-primary';
                                                    break;
                                                case 'xls':
                                                case 'xlsx':
                                                    $iconClass = 'fas fa-file-excel';
                                                    $iconColor = 'text-success';
                                                    break;
                                                case 'dwg':
                                                    $iconClass = 'fas fa-drafting-compass';
                                                    $iconColor = 'text-warning';
                                                    break;
                                            }
                                            ?>
                                            <i class="<?= $iconClass ?> fa-2x <?= $iconColor ?>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= htmlspecialchars($document['name']) ?></h6>
                                            <small class="text-muted">
                                                Enviado em <?= date('d/m/Y H:i', strtotime($document['created_at'])) ?>
                                                <?php if (isset($document['user_name'])): ?>
                                                    por <?= htmlspecialchars($document['user_name']) ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary" onclick="downloadDocument('<?= $document['id'] ?>')">
                                                <i class="fas fa-download"></i>
                                                Download
                                            </button>
                                            <?php if ($isAdmin || $document['user_id'] === $user['id']): ?>
                                                <button class="btn btn-sm btn-outline-danger ms-1" onclick="deleteDocument('<?= $document['id'] ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Nenhum documento encontrado</h6>
                    <p class="text-muted">Faça upload dos primeiros documentos do projeto</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="content-section">
            <h5 class="mb-3">ℹ️ Informações</h5>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Status:</label>
                <span class="badge bg-info">Em Andamento</span>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Criado:</label>
                <p class="mb-0">01/07/2025</p>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Prazo:</label>
                <p class="mb-0">15/08/2025</p>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Documentos:</label>
                <p class="mb-0">2</p>
            </div>
        </div>
        
        <div class="content-section">
            <h5 class="mb-3">📈 Histórico</h5>
            
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="timeline-content">
                        <h6 class="mb-1">Projeto criado</h6>
                        <small class="text-muted">01/07/2025 10:00</small>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-marker">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="timeline-content">
                        <h6 class="mb-1">Status: Em Andamento</h6>
                        <small class="text-muted">01/07/2025 10:00</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline:before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 1rem;
}

.timeline-marker {
    position: absolute;
    left: -1.5rem;
    top: 0;
    width: 1rem;
    height: 1rem;
    background: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.5rem;
    color: white;
}

.timeline-content {
    padding-left: 1rem;
}
</style>

<script>
function updateProjectStatus(projectId, status) {
    const statusTexts = {
        'aguardando': 'aguardando',
        'pendente': 'pendente',
        'aprovado': 'aprovado',
        'atrasado': 'atrasado',
        'concluido': 'concluído'
    };
    
    if (confirm(`Tem certeza que deseja alterar o status do projeto para "${statusTexts[status]}"?`)) {
        fetch(`/projects/${projectId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Status do projeto atualizado com sucesso!');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showAlert('error', data.message || 'Erro ao atualizar status do projeto');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao atualizar status do projeto');
        });
    }
}

function editProject(projectId) {
    showAlert('info', 'Funcionalidade de edição em desenvolvimento');
}

function deleteProject(projectId) {
    if (confirm('Tem certeza que deseja excluir este projeto? Esta ação não pode ser desfeita.')) {
        fetch(`/projects/${projectId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Projeto excluído com sucesso!');
                setTimeout(() => window.location.href = '/projects', 1000);
            } else {
                showAlert('error', data.message || 'Erro ao excluir projeto');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao excluir projeto');
        });
    }
}

function showAlert(type, message) {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const alertContainer = document.getElementById('alert-container') || createAlertContainer();
    alertContainer.innerHTML = alertHtml;
    
    // Auto-hide após 5 segundos
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

function createAlertContainer() {
    const container = document.createElement('div');
    container.id = 'alert-container';
    container.className = 'fixed-top m-3';
    container.style.zIndex = '9999';
    container.style.maxWidth = '500px';
    container.style.right = '20px';
    container.style.left = 'auto';
    document.body.appendChild(container);
    return container;
}

// Modal de Upload
function showUploadModal() {
    const modal = document.getElementById('uploadModal');
    if (modal) {
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }
}

function uploadDocument() {
    const form = document.getElementById('uploadForm');
    const formData = new FormData(form);
    formData.append('project_id', '<?= $project['id'] ?>');
    
    // Mostrar loading
    const submitBtn = document.getElementById('submitUpload');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enviando...';
    submitBtn.disabled = true;
    
    fetch('/documents/upload', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Documento enviado com sucesso!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', data.message || 'Erro ao enviar documento');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('error', 'Erro ao enviar documento');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function downloadDocument(documentId) {
    window.open(`/documents/${documentId}/download`, '_blank');
}

function deleteDocument(documentId) {
    if (confirm('Tem certeza que deseja excluir este documento?')) {
        fetch(`/documents/${documentId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Documento excluído com sucesso!');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('error', data.message || 'Erro ao excluir documento');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao excluir documento');
        });
    }
}
</script>

<!-- Modal de Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">
                    <i class="fas fa-upload me-2"></i>
                    Upload de Documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="documentFile" class="form-label">Arquivo *</label>
                        <input type="file" class="form-control" id="documentFile" name="file" required 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.dwg,.txt">
                        <div class="form-text">
                            Formatos aceitos: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, DWG, TXT (máx. 10MB)
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="documentName" class="form-label">Nome do Documento</label>
                        <input type="text" class="form-control" id="documentName" name="name" 
                               placeholder="Digite um nome para o documento (opcional)">
                    </div>
                    
                    <div class="mb-3">
                        <label for="documentDescription" class="form-label">Descrição</label>
                        <textarea class="form-control" id="documentDescription" name="description" 
                                  rows="3" placeholder="Descreva o conteúdo do documento (opcional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="submitUpload" onclick="uploadDocument()">
                    <i class="fas fa-upload me-1"></i>
                    Enviar Documento
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

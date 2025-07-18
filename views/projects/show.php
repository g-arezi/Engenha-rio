<?php 
/**
 * Sistema de Gestão de Projetos - Engenha Rio
 * 
 * © 2025 Engenha Rio - Todos os direitos reservados
 * Desenvolvido por: Gabriel Arezi
 * Portfolio: https://portifolio-beta-five-52.vercel.app/
 * GitHub: https://github.com/g-arezi
 * 
 * Este software é propriedade intelectual protegida.
 * Uso não autorizado será processado judicialmente.
 */

$title = htmlspecialchars($project['name']) . ' - Engenha Rio';
$showSidebar = true;
$activeMenu = 'projects';
ob_start();

// Importar classe Auth para verificações de permissão
use App\Core\Auth;

// Verificar permissões do usuário
$canEditProjects = Auth::canEditProjects();
$canApproveProjects = Auth::canApproveProjects();
$canCompleteProjects = Auth::canCompleteProjects();
$isClient = Auth::isClient();
$user = Auth::user();

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
                    <?php if (!$isClient): ?>
                        <?php if ($project['status'] !== 'concluido' && $canCompleteProjects): ?>
                        <button class="btn btn-success" onclick="updateProjectStatus('<?= $project['id'] ?>', 'concluido')">
                            <i class="fas fa-check me-1"></i>
                            Concluir
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($project['status'] !== 'aprovado' && $canApproveProjects): ?>
                        <button class="btn btn-primary" onclick="updateProjectStatus('<?= $project['id'] ?>', 'aprovado')">
                            <i class="fas fa-thumbs-up me-1"></i>
                            Aprovar
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($canEditProjects): ?>
                        <button class="btn btn-warning" onclick="editProject('<?= $project['id'] ?>')">
                            <i class="fas fa-edit me-1"></i>
                            Editar
                        </button>
                        
                        <button class="btn btn-danger" onclick="deleteProject('<?= $project['id'] ?>')">
                            <i class="fas fa-trash me-1"></i>
                            Excluir
                        </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0 p-2">
                            <i class="fas fa-info-circle me-1"></i>
                            <small>Como cliente, você pode visualizar os dados do projeto e fazer upload de documentos abaixo.</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">📄 Documentos do Projeto</h5>
                <?php 
                // Verificar se pode fazer upload para este projeto
                $canUpload = Auth::canUploadToProject($project['id']);
                if ($canUpload): 
                ?>
                <button class="btn btn-primary" onclick="showUploadModal()">
                    <i class="fas fa-plus me-1"></i>
                    Upload
                </button>
                <?php endif; ?>
            </div>

            <?php
            // Template do projeto já vem do controller
            // Documentos já enviados
            $sentDocuments = $documents ?? [];
            $sentDocTypes = array_column($sentDocuments, 'document_type');
            ?>

            <?php if ($template): ?>
                <!-- Mostrar documentos baseados no template -->
                <?php 
                $requiredDocs = $template['required_documents'] ?? [];
                $optionalDocs = $template['optional_documents'] ?? [];
                $pendingRequired = array_filter($requiredDocs, function($doc) use ($sentDocTypes) {
                    return !in_array($doc['type'], $sentDocTypes);
                });
                $pendingOptional = array_filter($optionalDocs, function($doc) use ($sentDocTypes) {
                    return !in_array($doc['type'], $sentDocTypes);
                });
                ?>

                <!-- Documentos Obrigatórios Pendentes -->
                <?php if (!empty($pendingRequired)): ?>
                    <div class="mb-4">
                        <h6 class="text-danger">
                            <i class="fas fa-exclamation-circle"></i> 
                            Documentos Obrigatórios Pendentes (<?= count($pendingRequired) ?>)
                        </h6>
                        <div class="row">
                            <?php foreach ($pendingRequired as $doc): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="card border-danger">
                                        <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0 text-danger">
                                                        <?= htmlspecialchars($doc['name'] ?? $doc['label'] ?? 'Documento') ?>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($doc['description'] ?? '') ?>
                                                    </small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-danger me-2">Pendente</span>
                                                    <?php if ($canUpload): ?>
                                                        <button class="btn btn-sm btn-outline-primary" 
                                                                onclick="openUploadForDocument('<?= htmlspecialchars($doc['type']) ?>', '<?= htmlspecialchars($doc['name'] ?? $doc['label'] ?? 'Documento') ?>')">
                                                            <i class="fas fa-upload"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Documentos Opcionais Pendentes -->
                <?php if (!empty($pendingOptional)): ?>
                    <div class="mb-4">
                        <h6 class="text-info">
                            <i class="fas fa-info-circle"></i> 
                            Documentos Opcionais Pendentes (<?= count($pendingOptional) ?>)
                        </h6>
                        <div class="row">
                            <?php foreach ($pendingOptional as $doc): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="card border-info">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0 text-info">
                                                        <?= htmlspecialchars($doc['name'] ?? $doc['label'] ?? 'Documento') ?>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($doc['description'] ?? '') ?>
                                                    </small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-info me-2">Opcional</span>
                                                    <?php if ($canUpload): ?>
                                                        <button class="btn btn-sm btn-outline-primary" 
                                                                onclick="openUploadForDocument('<?= htmlspecialchars($doc['type']) ?>', '<?= htmlspecialchars($doc['name'] ?? $doc['label'] ?? 'Documento') ?>')">
                                                            <i class="fas fa-upload"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Status dos documentos obrigatórios -->
                <div class="alert alert-light mb-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <strong class="d-block"><?= count($requiredDocs) ?></strong>
                            <small class="text-muted">Total obrigatórios</small>
                        </div>
                        <div class="col-4">
                            <strong class="d-block text-danger"><?= count($pendingRequired) ?></strong>
                            <small class="text-muted">Pendentes</small>
                        </div>
                        <div class="col-4">
                            <strong class="d-block text-success"><?= count($requiredDocs) - count($pendingRequired) ?></strong>
                            <small class="text-muted">Enviados</small>
                        </div>
                    </div>
                    
                    <?php if (count($pendingRequired) === 0): ?>
                        <div class="text-center mt-2">
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Todos os documentos obrigatórios foram enviados!
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Documentos Enviados -->
            <?php if (!empty($sentDocuments)): ?>
                <div class="mb-4">
                    <h6 class="text-success">
                        <i class="fas fa-check-circle"></i> 
                        Documentos Enviados (<?= count($sentDocuments) ?>)
                    </h6>
                    <div class="row">
                        <?php foreach ($sentDocuments as $document): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border-success">
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
                                                <?php if (!empty($document['document_type']) || !empty($document['type'])): ?>
                                                    <span class="badge bg-success mb-1">
                                                        <?php
                                                        $docType = $document['document_type'] ?? $document['type'];
                                                        if ($template) {
                                                            // Buscar nome amigável no template
                                                            $allDocs = array_merge($template['required_documents'] ?? [], $template['optional_documents'] ?? []);
                                                            foreach ($allDocs as $templateDoc) {
                                                                if ($templateDoc['type'] === $docType) {
                                                                    echo htmlspecialchars($templateDoc['name'] ?? $templateDoc['label'] ?? $docType);
                                                                    break;
                                                                }
                                                            }
                                                        } else {
                                                            echo htmlspecialchars($docType);
                                                        }
                                                        ?>
                                                    </span>
                                                    <br>
                                                <?php endif; ?>
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
                                                <?php if (Auth::isAdmin() || $document['user_id'] === $user['id']): ?>
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
                </div>
            <?php endif; ?>

            <?php if (empty($sentDocuments) && (!$template || (empty($pendingRequired) && empty($pendingOptional)))): ?>
                <div class="text-center py-4">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Nenhum documento encontrado</h6>
                    <p class="text-muted">
                        <?= $template ? 'Todos os documentos foram enviados!' : 'Faça upload dos primeiros documentos do projeto' ?>
                    </p>
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
    window.location.href = `/projects/${projectId}/edit`;
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
        // Limpar formulário
        resetUploadForm();
        
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
        
        // Configurar evento de mudança no tipo de documento
        const documentTypeSelect = document.getElementById('documentType');
        if (documentTypeSelect) {
            documentTypeSelect.addEventListener('change', updateDocumentTypeInfo);
        }
    }
}

function resetUploadForm() {
    const form = document.getElementById('uploadForm');
    if (form) {
        form.reset();
    }
    
    const helpText = document.getElementById('documentTypeHelp');
    if (helpText) {
        helpText.innerHTML = 'Selecione o tipo de documento que você está enviando';
        helpText.className = 'form-text';
    }
    
    const fileHelp = document.getElementById('fileHelp');
    if (fileHelp) {
        fileHelp.innerHTML = 'Formatos aceitos: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, DWG, TXT (máx. 10MB)';
    }
}

function updateDocumentTypeInfo() {
    const select = document.getElementById('documentType');
    const selectedOption = select.selectedOptions[0];
    const helpText = document.getElementById('documentTypeHelp');
    const fileInput = document.getElementById('documentFile');
    const fileHelp = document.getElementById('fileHelp');
    const nameInput = document.getElementById('documentName');
    
    if (selectedOption && selectedOption.value !== '') {
        const description = selectedOption.getAttribute('data-description');
        const accept = selectedOption.getAttribute('data-accept');
        
        // Atualizar texto de ajuda
        if (description) {
            helpText.innerHTML = `<strong>Descrição:</strong> ${description}`;
            helpText.className = 'form-text text-info';
        } else {
            helpText.innerHTML = 'Selecione o tipo de documento que você está enviando';
            helpText.className = 'form-text';
        }
        
        // Atualizar tipos de arquivo aceitos
        if (accept) {
            fileInput.setAttribute('accept', accept);
            fileHelp.innerHTML = `Formatos aceitos: ${accept.toUpperCase().replace(/\./g, '').replace(/,/g, ', ')} (máx. 10MB)`;
        } else {
            fileInput.setAttribute('accept', '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.dwg,.txt');
            fileHelp.innerHTML = 'Formatos aceitos: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, DWG, TXT (máx. 10MB)';
        }
        
        // Pré-preencher nome do documento se estiver vazio
        if (!nameInput.value) {
            nameInput.value = selectedOption.textContent.trim();
        }
    } else {
        helpText.innerHTML = 'Selecione o tipo de documento que você está enviando';
        helpText.className = 'form-text';
        fileInput.setAttribute('accept', '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.dwg,.txt');
        fileHelp.innerHTML = 'Formatos aceitos: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, DWG, TXT (máx. 10MB)';
    }
}

function openUploadForDocument(documentType, documentName) {
    // Abrir modal
    const modal = document.getElementById('uploadModal');
    if (modal) {
        // Limpar formulário primeiro
        resetUploadForm();
        
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
        
        // Pré-selecionar o tipo de documento
        setTimeout(() => {
            const documentTypeSelect = document.getElementById('documentType');
            const nameInput = document.getElementById('documentName');
            
            if (documentTypeSelect) {
                documentTypeSelect.value = documentType;
                documentTypeSelect.addEventListener('change', updateDocumentTypeInfo);
                updateDocumentTypeInfo(); // Atualizar informações imediatamente
            }
            
            if (nameInput && !nameInput.value) {
                nameInput.value = documentName;
            }
        }, 100);
    }
}

function uploadDocument() {
    const form = document.getElementById('uploadForm');
    
    // Validar formulário
    const fileInput = document.getElementById('documentFile');
    const documentTypeInput = document.getElementById('documentType') || document.getElementById('documentTypeManual');
    
    if (!fileInput.files.length) {
        showAlert('error', 'Por favor, selecione um arquivo');
        return;
    }
    
    if (documentTypeInput && !documentTypeInput.value) {
        showAlert('error', 'Por favor, selecione o tipo de documento');
        return;
    }
    
    const formData = new FormData(form);
    formData.append('project_id', '<?= $project['id'] ?>');
    
    // Debug - Verificar dados do formulário
    console.log('Dados do formulário:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    
    // Mostrar loading
    const submitBtn = document.getElementById('submitUpload');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enviando...';
    submitBtn.disabled = true;
    
    fetch('/documents/upload', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.text().then(text => {
            console.log('Response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Erro ao fazer parse do JSON:', e);
                throw new Error('Resposta não é um JSON válido: ' + text);
            }
        });
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showAlert('success', 'Documento enviado com sucesso!');
            // Fechar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
            if (modal) {
                modal.hide();
            }
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', data.message || 'Erro ao enviar documento');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('error', 'Erro ao enviar documento: ' + error.message);
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
                    <?php if ($template): ?>
                    <!-- Seleção do Tipo de Documento -->
                    <div class="mb-3">
                        <label for="documentType" class="form-label">Tipo de Documento *</label>
                        <select class="form-select" id="documentType" name="document_type" required>
                            <option value="">Selecione o tipo de documento</option>
                            
                            <?php if (!empty($template['required_documents'])): ?>
                                <optgroup label="📋 Documentos Obrigatórios">
                                    <?php foreach ($template['required_documents'] as $doc): ?>
                                        <?php if (!in_array($doc['type'], $sentDocTypes)): ?>
                                            <option value="<?= htmlspecialchars($doc['type']) ?>" 
                                                    data-description="<?= htmlspecialchars($doc['description'] ?? '') ?>"
                                                    data-accept="<?= htmlspecialchars($doc['accept'] ?? '') ?>">
                                                <?= htmlspecialchars($doc['name'] ?? $doc['label'] ?? 'Documento') ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            
                            <?php if (!empty($template['optional_documents'])): ?>
                                <optgroup label="📄 Documentos Opcionais">
                                    <?php foreach ($template['optional_documents'] as $doc): ?>
                                        <?php if (!in_array($doc['type'], $sentDocTypes)): ?>
                                            <option value="<?= htmlspecialchars($doc['type']) ?>" 
                                                    data-description="<?= htmlspecialchars($doc['description'] ?? '') ?>"
                                                    data-accept="<?= htmlspecialchars($doc['accept'] ?? '') ?>">
                                                <?= htmlspecialchars($doc['name'] ?? $doc['label'] ?? 'Documento') ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            
                            <optgroup label="📁 Outros Documentos">
                                <option value="other">Outro documento não listado</option>
                            </optgroup>
                        </select>
                        <div class="form-text" id="documentTypeHelp">
                            Selecione o tipo de documento que você está enviando
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Caso não tenha template -->
                    <div class="mb-3">
                        <label for="documentTypeManual" class="form-label">Tipo de Documento</label>
                        <input type="text" class="form-control" id="documentTypeManual" name="document_type" 
                               placeholder="Digite o tipo do documento (ex: RG, CPF, Escritura...)">
                        <div class="form-text">
                            Informe o tipo/categoria do documento que está enviando
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="documentFile" class="form-label">Arquivo *</label>
                        <input type="file" class="form-control" id="documentFile" name="file" required 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.dwg,.txt">
                        <div class="form-text" id="fileHelp">
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

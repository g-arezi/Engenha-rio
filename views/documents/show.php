<?php 
$title = htmlspecialchars($document['name']) . ' - Engenha Rio';
$showSidebar = true;
$activeMenu = 'documents';
ob_start();

// Função para formatar tamanho de arquivo
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

// Função para obter ícone do tipo de arquivo
function getFileIcon($mimeType) {
    if (strpos($mimeType, 'pdf') !== false) {
        return 'fas fa-file-pdf text-danger';
    } elseif (strpos($mimeType, 'word') !== false || strpos($mimeType, 'document') !== false) {
        return 'fas fa-file-word text-primary';
    } elseif (strpos($mimeType, 'excel') !== false || strpos($mimeType, 'spreadsheet') !== false) {
        return 'fas fa-file-excel text-success';
    } elseif (strpos($mimeType, 'image') !== false) {
        return 'fas fa-file-image text-warning';
    } elseif (strpos($mimeType, 'dwg') !== false || strpos($mimeType, 'cad') !== false) {
        return 'fas fa-drafting-compass text-info';
    } else {
        return 'fas fa-file text-secondary';
    }
}

// Definir cores para tipos de documento
$typeColors = [
    'planta' => 'primary',
    'memorial' => 'success',
    'estrutural' => 'warning',
    'eletrico' => 'info',
    'hidraulico' => 'secondary',
    'outros' => 'dark'
];

$typeTexts = [
    'planta' => 'Planta Baixa',
    'memorial' => 'Memorial Descritivo',
    'estrutural' => 'Projeto Estrutural',
    'eletrico' => 'Projeto Elétrico',
    'hidraulico' => 'Projeto Hidráulico',
    'outros' => 'Outros'
];
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/documents">Documentos</a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($document['name']) ?></li>
                    </ol>
                </nav>
                <h2 class="h4 mb-0">📄 <?= htmlspecialchars($document['name']) ?></h2>
                <p class="text-muted">Enviado em <?= date('d/m/Y H:i', strtotime($document['created_at'])) ?></p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" onclick="downloadDocument('<?= $document['id'] ?>')">
                    <i class="fas fa-download me-1"></i>
                    Baixar
                </button>
                <button class="btn btn-outline-secondary" onclick="window.location.href='/documents'">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash_message'])): ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-<?= $_SESSION['flash_message']['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['flash_message']['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
<?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> Detalhes do Documento</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center mb-3">
                        <div class="document-icon mb-2">
                            <i class="<?= getFileIcon($document['mime_type']) ?>" style="font-size: 3rem;"></i>
                        </div>
                        <span class="badge bg-<?= $typeColors[$document['type']] ?? 'secondary' ?>">
                            <?= $typeTexts[$document['type']] ?? ucfirst($document['type']) ?>
                        </span>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nome do Arquivo:</label>
                                    <p><?= htmlspecialchars($document['name']) ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tamanho:</label>
                                    <p><?= formatFileSize($document['size']) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tipo MIME:</label>
                                    <p><code><?= htmlspecialchars($document['mime_type']) ?></code></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ID do Documento:</label>
                                    <p><code><?= htmlspecialchars($document['id']) ?></code></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Descrição:</label>
                    <p><?= nl2br(htmlspecialchars($document['description'])) ?></p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Data de Upload:</label>
                            <p><?= date('d/m/Y H:i', strtotime($document['created_at'])) ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Última Atualização:</label>
                            <p><?= date('d/m/Y H:i', strtotime($document['updated_at'])) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Caminho do Arquivo:</label>
                    <p><code><?= htmlspecialchars($document['file_path']) ?></code></p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="fas fa-tools"></i> Ações</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-primary" onclick="downloadDocument('<?= $document['id'] ?>')">
                        <i class="fas fa-download me-1"></i>
                        Baixar Documento
                    </button>
                    <button class="btn btn-outline-info" onclick="previewDocument('<?= $document['id'] ?>')">
                        <i class="fas fa-eye me-1"></i>
                        Visualizar
                    </button>
                    <?php if ($isAdmin || $document['user_id'] === $user['id']): ?>
                    <button class="btn btn-outline-warning" onclick="editDocument('<?= $document['id'] ?>')">
                        <i class="fas fa-edit me-1"></i>
                        Editar
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteDocument('<?= $document['id'] ?>')">
                        <i class="fas fa-trash me-1"></i>
                        Excluir
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-project-diagram"></i> Projeto Relacionado</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($document['project_id'])): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">ID do Projeto:</label>
                    <p><?= htmlspecialchars($document['project_id']) ?></p>
                </div>
                <button class="btn btn-outline-primary btn-sm" onclick="window.location.href='/projects/<?= $document['project_id'] ?>'">
                    <i class="fas fa-external-link-alt me-1"></i>
                    Ver Projeto
                </button>
                <?php else: ?>
                <p class="text-muted">Este documento não está associado a nenhum projeto.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="fas fa-chart-pie"></i> Estatísticas</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="fw-bold text-primary"><?= formatFileSize($document['size']) ?></div>
                        <small class="text-muted">Tamanho</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold text-success"><?= date('d/m/Y', strtotime($document['created_at'])) ?></div>
                        <small class="text-muted">Upload</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="fas fa-shield-alt"></i> Permissões</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <i class="fas fa-check text-success me-2"></i>
                    <span>Visualizar</span>
                </div>
                <div class="mb-2">
                    <i class="fas fa-check text-success me-2"></i>
                    <span>Baixar</span>
                </div>
                <?php if ($isAdmin || $document['user_id'] === $user['id']): ?>
                <div class="mb-2">
                    <i class="fas fa-check text-success me-2"></i>
                    <span>Editar</span>
                </div>
                <div class="mb-2">
                    <i class="fas fa-check text-success me-2"></i>
                    <span>Excluir</span>
                </div>
                <?php else: ?>
                <div class="mb-2">
                    <i class="fas fa-times text-danger me-2"></i>
                    <span>Editar</span>
                </div>
                <div class="mb-2">
                    <i class="fas fa-times text-danger me-2"></i>
                    <span>Excluir</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function downloadDocument(documentId) {
    window.location.href = `/documents/${documentId}/download`;
}

function previewDocument(documentId) {
    // Abrir preview em nova janela
    window.open(`/documents/${documentId}/preview`, '_blank');
}

function editDocument(documentId) {
    window.location.href = `/documents/${documentId}/edit`;
}

function deleteDocument(documentId) {
    if (confirm('Tem certeza que deseja excluir este documento? Esta ação não pode ser desfeita.')) {
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
                setTimeout(() => window.location.href = '/documents', 1000);
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
</script>

<style>
.document-icon {
    padding: 1rem;
    border-radius: 0.5rem;
    background-color: #f8f9fa;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.25rem;
}

.card-header h5 {
    margin-bottom: 0;
    color: #2c3e50;
}

.badge {
    font-size: 0.75rem;
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.alert {
    border: none;
    border-radius: 0.375rem;
}

#alert-container {
    z-index: 9999;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 0.5rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
}

code {
    background-color: #f8f9fa;
    color: #e83e8c;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

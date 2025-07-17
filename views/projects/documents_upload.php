<?php
$title = 'Upload de Documentos - ' . htmlspecialchars($project['name']);
$showSidebar = true;
$activeMenu = 'projects';
ob_start();
?>

<style>
.document-item {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s;
}
.document-item:hover {
    border-color: #007bff;
    background-color: #f8f9ff;
}
.document-item.uploaded {
    border-color: #28a745;
    background-color: #f8fff9;
    border-style: solid;
}
.document-item.required {
    border-color: #ffc107;
    background-color: #fffbf0;
}
.upload-area {
    text-align: center;
    padding: 30px;
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s;
}
.upload-area:hover {
    border-color: #007bff;
    background-color: #f8f9ff;
}
.upload-progress {
    display: none;
}
.doc-type-badge {
    font-size: 0.8em;
    padding: 0.3em 0.8em;
    border-radius: 20px;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-cloud-upload-alt me-2"></i>
                        Upload de Documentos
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/projects">Projetos</a></li>
                            <li class="breadcrumb-item"><a href="/projects/<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></a></li>
                            <li class="breadcrumb-item active">Upload de Documentos</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="/projects/<?= $project['id'] ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Voltar ao Projeto
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
                                <i class="fas fa-upload me-2"></i>
                                Documentos do Projeto
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($template)): ?>
                                <!-- Template específico selecionado -->
                                <div class="alert alert-info mb-4">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-file-alt me-2"></i>
                                        <?= htmlspecialchars($template['name']) ?>
                                    </h6>
                                    <p class="mb-0"><?= htmlspecialchars($template['description']) ?></p>
                                    <?php if (!empty($template['instructions'])): ?>
                                        <hr>
                                        <div class="mt-2">
                                            <strong>Instruções:</strong>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($template['instructions'])) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($template['required_documents'])): ?>
                                    <h6 class="text-danger mb-3">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        Documentos Obrigatórios
                                    </h6>
                                    
                                    <?php foreach ($template['required_documents'] as $docType): ?>
                                        <?php 
                                        $isUploaded = false;
                                        $uploadedDoc = null;
                                        foreach ($uploadedDocuments as $doc) {
                                            if ($doc['type'] === $docType['type']) {
                                                $isUploaded = true;
                                                $uploadedDoc = $doc;
                                                break;
                                            }
                                        }
                                        ?>
                                        <div class="document-item required <?= $isUploaded ? 'uploaded' : '' ?>">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h6 class="mb-1">
                                                        <?= htmlspecialchars($docType['name']) ?>
                                                        <span class="doc-type-badge bg-danger text-white ms-2">OBRIGATÓRIO</span>
                                                        <?php if ($isUploaded): ?>
                                                            <span class="doc-type-badge bg-success text-white ms-1">
                                                                <i class="fas fa-check me-1"></i>ENVIADO
                                                            </span>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <p class="text-muted mb-2"><?= htmlspecialchars($docType['description']) ?></p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-file me-1"></i>
                                                        Formatos: <?= htmlspecialchars($docType['accept']) ?> | 
                                                        Tamanho máximo: <?= htmlspecialchars($docType['max_size']) ?>
                                                    </small>
                                                    
                                                    <?php if ($isUploaded): ?>
                                                        <div class="mt-2">
                                                            <a href="/documents/<?= $uploadedDoc['id'] ?>/download" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download me-1"></i>
                                                                <?= htmlspecialchars($uploadedDoc['name']) ?>
                                                            </a>
                                                            <button class="btn btn-sm btn-outline-danger ms-2" onclick="replaceDocument('<?= $docType['type'] ?>')">
                                                                <i class="fas fa-sync me-1"></i>Substituir
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php if (!$isUploaded): ?>
                                                        <div class="upload-area" onclick="triggerUpload('<?= $docType['type'] ?>')">
                                                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                            <p class="mb-0">Clique para enviar</p>
                                                        </div>
                                                        <input type="file" id="upload_<?= $docType['type'] ?>" 
                                                               accept="<?= htmlspecialchars($docType['accept']) ?>" 
                                                               style="display: none;" 
                                                               onchange="uploadDocument('<?= $docType['type'] ?>', this)">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <?php if (!empty($template['optional_documents'])): ?>
                                    <h6 class="text-info mb-3 mt-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Documentos Opcionais
                                    </h6>
                                    
                                    <?php foreach ($template['optional_documents'] as $docType): ?>
                                        <?php 
                                        $isUploaded = false;
                                        $uploadedDoc = null;
                                        foreach ($uploadedDocuments as $doc) {
                                            if ($doc['type'] === $docType['type']) {
                                                $isUploaded = true;
                                                $uploadedDoc = $doc;
                                                break;
                                            }
                                        }
                                        ?>
                                        <div class="document-item <?= $isUploaded ? 'uploaded' : '' ?>">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h6 class="mb-1">
                                                        <?= htmlspecialchars($docType['name']) ?>
                                                        <span class="doc-type-badge bg-info text-white ms-2">OPCIONAL</span>
                                                        <?php if ($isUploaded): ?>
                                                            <span class="doc-type-badge bg-success text-white ms-1">
                                                                <i class="fas fa-check me-1"></i>ENVIADO
                                                            </span>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <p class="text-muted mb-2"><?= htmlspecialchars($docType['description']) ?></p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-file me-1"></i>
                                                        Formatos: <?= htmlspecialchars($docType['accept']) ?> | 
                                                        Tamanho máximo: <?= htmlspecialchars($docType['max_size']) ?>
                                                    </small>
                                                    
                                                    <?php if ($isUploaded): ?>
                                                        <div class="mt-2">
                                                            <a href="/documents/<?= $uploadedDoc['id'] ?>/download" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download me-1"></i>
                                                                <?= htmlspecialchars($uploadedDoc['name']) ?>
                                                            </a>
                                                            <button class="btn btn-sm btn-outline-danger ms-2" onclick="replaceDocument('<?= $docType['type'] ?>')">
                                                                <i class="fas fa-sync me-1"></i>Substituir
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php if (!$isUploaded): ?>
                                                        <div class="upload-area" onclick="triggerUpload('<?= $docType['type'] ?>')">
                                                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                            <p class="mb-0">Clique para enviar</p>
                                                        </div>
                                                        <input type="file" id="upload_<?= $docType['type'] ?>" 
                                                               accept="<?= htmlspecialchars($docType['accept']) ?>" 
                                                               style="display: none;" 
                                                               onchange="uploadDocument('<?= $docType['type'] ?>', this)">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                            <?php else: ?>
                                <!-- Upload livre (sem template) -->
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Upload Livre de Documentos
                                    </h6>
                                    <p class="mb-0">Este projeto não possui um template específico de documentos. Você pode enviar qualquer documento relacionado ao projeto.</p>
                                </div>
                                
                                <div class="upload-area" onclick="document.getElementById('freeUpload').click()">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h5>Arraste arquivos aqui ou clique para selecionar</h5>
                                    <p class="text-muted">Suporte para PDF, DOC, XLS, DWG, imagens e outros formatos</p>
                                </div>
                                <input type="file" id="freeUpload" multiple style="display: none;" onchange="uploadFreeDocuments(this)">
                                
                                <?php if (!empty($uploadedDocuments)): ?>
                                    <h6 class="mt-4 mb-3">Documentos Enviados</h6>
                                    <div class="row">
                                        <?php foreach ($uploadedDocuments as $doc): ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="card-title"><?= htmlspecialchars($doc['name']) ?></h6>
                                                        <p class="card-text text-muted"><?= htmlspecialchars($doc['description'] ?? '') ?></p>
                                                        <div class="d-flex gap-2">
                                                            <a href="/documents/<?= $doc['id'] ?>/download" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download me-1"></i>Download
                                                            </a>
                                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteDocument('<?= $doc['id'] ?>')">
                                                                <i class="fas fa-trash me-1"></i>Excluir
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Progresso
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (isset($documentStats)): ?>
                                <div class="text-center mb-3">
                                    <div class="display-4 text-primary"><?= $documentStats['completion_percentage'] ?>%</div>
                                    <p class="text-muted">Documentos obrigatórios</p>
                                </div>
                                
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?= $documentStats['completion_percentage'] ?>%"></div>
                                </div>
                                
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="border-end">
                                            <h5 class="text-success mb-0"><?= $documentStats['required_uploaded'] ?></h5>
                                            <small class="text-muted">Enviados</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="text-warning mb-0"><?= $documentStats['required_total'] - $documentStats['required_uploaded'] ?></h5>
                                        <small class="text-muted">Pendentes</small>
                                    </div>
                                </div>
                                
                                <?php if ($documentStats['optional_total'] > 0): ?>
                                    <hr>
                                    <div class="text-center">
                                        <strong>Opcionais:</strong>
                                        <?= $documentStats['optional_uploaded'] ?> de <?= $documentStats['optional_total'] ?> enviados
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-center">
                                    <h5 class="text-info mb-0"><?= count($uploadedDocuments) ?></h5>
                                    <small class="text-muted">Documentos enviados</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Informações
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">💡 Dicas para Upload</h6>
                                <ul class="mb-0">
                                    <li>Escaneie documentos em alta qualidade</li>
                                    <li>Use formato PDF sempre que possível</li>
                                    <li>Certifique-se de que o texto está legível</li>
                                    <li>Organize os arquivos por tipo</li>
                                    <li>Verifique o tamanho máximo permitido</li>
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
function triggerUpload(docType) {
    document.getElementById('upload_' + docType).click();
}

function uploadDocument(docType, input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const formData = new FormData();
        formData.append('file', file);
        formData.append('project_id', '<?= $project['id'] ?>');
        formData.append('type', docType);
        formData.append('name', file.name);
        formData.append('description', 'Documento enviado via template');
        
        // Mostrar progresso
        showUploadProgress(docType);
        
        fetch('/documents/upload', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Documento enviado com sucesso!');
                location.reload();
            } else {
                alert('Erro: ' + data.message);
                hideUploadProgress(docType);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao enviar documento');
            hideUploadProgress(docType);
        });
    }
}

function uploadFreeDocuments(input) {
    if (input.files && input.files.length > 0) {
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const formData = new FormData();
            formData.append('file', file);
            formData.append('project_id', '<?= $project['id'] ?>');
            formData.append('name', file.name);
            formData.append('description', 'Documento enviado pelo cliente');
            
            fetch('/documents/upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Arquivo enviado:', file.name);
                    if (i === input.files.length - 1) {
                        alert('Documentos enviados com sucesso!');
                        location.reload();
                    }
                } else {
                    alert('Erro ao enviar ' + file.name + ': ' + data.message);
                }
            });
        }
    }
}

function replaceDocument(docType) {
    if (confirm('Deseja substituir o documento atual?')) {
        triggerUpload(docType);
    }
}

function deleteDocument(documentId) {
    if (confirm('Tem certeza que deseja excluir este documento?')) {
        fetch('/documents/' + documentId, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Documento excluído com sucesso!');
                location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        });
    }
}

function showUploadProgress(docType) {
    // Implementar barra de progresso
}

function hideUploadProgress(docType) {
    // Ocultar barra de progresso
}

// Drag and drop para upload livre
document.addEventListener('DOMContentLoaded', function() {
    const uploadAreas = document.querySelectorAll('.upload-area');
    
    uploadAreas.forEach(area => {
        area.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        
        area.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });
        
        area.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                // Implementar upload por drag and drop
                console.log('Files dropped:', files);
            }
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

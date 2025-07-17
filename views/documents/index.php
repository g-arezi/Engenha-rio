<?php 
$title = 'Documentos - Engenha Rio';
$pageTitle = 'Documentos';
$showSidebar = true;
$activeMenu = 'documents';
$hideTopBar = true;  // Remove a top-bar para evitar duplicidade
ob_start();
?>

<!-- Filtros -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">🔍 Buscar documentos</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-dark"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Nome, descrição..." id="searchInput">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">📁 Tipo</label>
                        <select class="form-select" id="typeFilter">
                            <option value="">Todos</option>
                            <option value="projeto">Projeto</option>
                            <option value="contrato">Contrato</option>
                            <option value="licenca">Licença</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">🏗️ Projeto</label>
                        <select class="form-select" id="projectFilter">
                            <option value="">Todos os projetos</option>
                            <!-- Opções serão preenchidas via JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">🗓️ Período</label>
                        <select class="form-select" id="dateFilter">
                            <option value="">Todos</option>
                            <option value="today">Hoje</option>
                            <option value="week">Esta semana</option>
                            <option value="month">Este mês</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label fw-bold">&nbsp;</label>
                        <button class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center" onclick="clearFilters()" title="Limpar filtros">
                            <i class="fas fa-times text-dark"></i>
                        </button>
                    </div>
                    <?php if ($canUpload ?? true): ?>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">&nbsp;</label>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fas fa-upload me-1"></i>
                            Novo Upload
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
    
<!-- Lista de Documentos -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Documentos Disponíveis</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($documents)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum documento encontrado</h5>
                    <p class="text-muted">Faça upload do primeiro documento para começar.</p>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload me-2"></i>Fazer Upload
                    </button>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Nome</th>
                                <th class="border-0">Tipo</th>
                                <th class="border-0">Projeto</th>
                                <th class="border-0">Tamanho</th>
                                <th class="border-0">Enviado por</th>
                                <th class="border-0">Data</th>
                                <th class="border-0 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $document): ?>
                            <tr>
                                <td class="align-middle">
                                    <?php 
                                    $fileType = $document['type'] ?? 'file';
                                    $icons = [
                                        'pdf' => 'pdf', 'doc' => 'word', 'docx' => 'word',
                                        'xls' => 'excel', 'xlsx' => 'excel',
                                        'ppt' => 'powerpoint', 'pptx' => 'powerpoint',
                                        'jpg' => 'image', 'jpeg' => 'image', 'png' => 'image', 'gif' => 'image'
                                    ];
                                    $icon = $icons[strtolower($fileType)] ?? 'file';
                                    ?>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-<?= $icon ?> text-dark me-2 fa-lg"></i>
                                        <div>
                                            <strong><?= htmlspecialchars($document['name'] ?? 'Documento') ?></strong>
                                            <?php if (!empty($document['description'])): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($document['description']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-primary rounded-pill"><?= ucfirst($document['type'] ?? 'Outros') ?></span>
                                </td>
                                <td class="align-middle"><?= htmlspecialchars($document['project_name'] ?? 'N/A') ?></td>
                                <td class="align-middle">
                                    <span class="text-muted"><?= $formatBytes($document['file_size'] ?? 0) ?></span>
                                </td>
                                <td class="align-middle"><?= htmlspecialchars($document['uploaded_by'] ?? 'Usuário') ?></td>
                                <td class="align-middle">
                                    <span class="text-muted"><?= date('d/m/Y H:i', strtotime($document['created_at'] ?? 'now')) ?></span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group" role="group">
                                        <a href="/documents/<?= $document['id'] ?? '' ?>" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                            <i class="fas fa-eye text-dark"></i>
                                        </a>
                                        <a href="/documents/<?= $document['id'] ?? '' ?>/download" class="btn btn-sm btn-outline-success" title="Download">
                                            <i class="fas fa-download text-dark"></i>
                                        </a>
                                        <?php if ($isAdmin || ($document['uploaded_by'] ?? '') === ($user['name'] ?? '')): ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteDocument('<?= $document['id'] ?? '' ?>')" title="Excluir">
                                            <i class="fas fa-trash text-dark"></i>
                                        </button>
                                        <?php endif; ?>
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

<!-- Modal de Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-upload"></i> Upload de Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="uploadAlert" class="alert d-none" role="alert"></div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Arquivo *</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                        <small class="text-muted">Máximo 50MB. Formatos aceitos: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, GIF, WEBP, TXT, ZIP, RAR</small>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do Documento *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo *</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Selecione um tipo</option>
                            <option value="projeto">Projeto</option>
                            <option value="contrato">Contrato</option>
                            <option value="licenca">Licença</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="project_id" class="form-label">Projeto (opcional)</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value="">Nenhum projeto específico</option>
                            <!-- Opções serão preenchidas via JavaScript -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <i class="fas fa-upload"></i> Fazer Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteDocument(documentId) {
    if (confirm('Tem certeza que deseja excluir este documento?')) {
        fetch('/documents/' + documentId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao excluir documento: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            alert('Erro ao excluir documento');
        });
    }
}

// Filtros
document.getElementById('searchInput')?.addEventListener('input', filterDocuments);
document.getElementById('typeFilter')?.addEventListener('change', filterDocuments);
document.getElementById('projectFilter')?.addEventListener('change', filterDocuments);

function filterDocuments() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const type = document.getElementById('typeFilter').value;
    const project = document.getElementById('projectFilter').value;
    
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        const docType = row.cells[1].textContent.toLowerCase();
        const docProject = row.cells[2].textContent;
        
        const matchSearch = name.includes(search);
        const matchType = !type || docType.includes(type);
        const matchProject = !project || docProject.includes(project);
        
        row.style.display = (matchSearch && matchType && matchProject) ? '' : 'none';
    });
}
</script>

<style>
.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.page-header h1 {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #6c757d;
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.card-header h5 {
    margin-bottom: 0;
    color: #2c3e50;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

.badge {
    font-size: 0.75rem;
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e3e6f0;
}
</style>

<script>
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('projectFilter').value = '';
    filterDocuments();
}

// Carregar projetos no modal de upload
document.addEventListener('DOMContentLoaded', function() {
    loadProjects();
    setupUploadForm();
});

function setupUploadForm() {
    const uploadForm = document.getElementById('uploadForm');
    const uploadModal = document.getElementById('uploadModal');
    
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const uploadBtn = document.getElementById('uploadBtn');
            const uploadAlert = document.getElementById('uploadAlert');
            
            // Mostrar loading
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            
            // Esconder alertas anteriores
            uploadAlert.classList.add('d-none');
            
            fetch('/documents/upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar sucesso
                    uploadAlert.className = 'alert alert-success';
                    uploadAlert.textContent = data.message || 'Documento enviado com sucesso!';
                    uploadAlert.classList.remove('d-none');
                    
                    // Limpar formulário
                    uploadForm.reset();
                    
                    // Fechar modal após 2 segundos
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
                        if (modal) {
                            modal.hide();
                        }
                        // Recarregar a página para mostrar o novo documento
                        window.location.reload();
                    }, 2000);
                } else {
                    // Mostrar erro
                    uploadAlert.className = 'alert alert-danger';
                    uploadAlert.textContent = data.message || 'Erro ao enviar documento';
                    uploadAlert.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                uploadAlert.className = 'alert alert-danger';
                uploadAlert.textContent = 'Erro interno do servidor';
                uploadAlert.classList.remove('d-none');
            })
            .finally(() => {
                // Restaurar botão
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Fazer Upload';
            });
        });
    }
    
    // Limpar formulário quando modal for fechado
    if (uploadModal) {
        uploadModal.addEventListener('hidden.bs.modal', function () {
            if (uploadForm) {
                uploadForm.reset();
                const uploadAlert = document.getElementById('uploadAlert');
                if (uploadAlert) {
                    uploadAlert.classList.add('d-none');
                }
            }
        });
    }
}

function loadProjects() {
    fetch('/api/projects')
        .then(response => response.json())
        .then(data => {
            const projectSelect = document.getElementById('project_id');
            const projectFilter = document.getElementById('projectFilter');
            
            if (data && data.length > 0) {
                data.forEach(project => {
                    const option = document.createElement('option');
                    option.value = project.id;
                    option.textContent = project.name;
                    
                    const filterOption = option.cloneNode(true);
                    
                    if (projectSelect) projectSelect.appendChild(option);
                    if (projectFilter) projectFilter.appendChild(filterOption);
                });
            }
        })
        .catch(error => {
            console.error('Erro ao carregar projetos:', error);
        });
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

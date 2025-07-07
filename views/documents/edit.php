<?php 
$title = 'Editar Documento - Engenha Rio';
$showSidebar = true;
$activeMenu = 'documents';
ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/documents">Documentos</a></li>
                            <li class="breadcrumb-item"><a href="/documents/<?= $document['id'] ?>"><?= htmlspecialchars($document['name']) ?></a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </nav>
                    <h2 class="h4 mb-0">✏️ Editar Documento</h2>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary" onclick="window.location.href='/documents/<?= $document['id'] ?>'">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Informações do Documento
                </h5>
            </div>
            <div class="card-body">
                <form id="editDocumentForm" method="POST" action="/documents/<?= $document['id'] ?>">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome do Documento *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($document['name']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipo *</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="planta" <?= ($document['type'] === 'planta') ? 'selected' : '' ?>>Planta Baixa</option>
                                    <option value="memorial" <?= ($document['type'] === 'memorial') ? 'selected' : '' ?>>Memorial Descritivo</option>
                                    <option value="estrutural" <?= ($document['type'] === 'estrutural') ? 'selected' : '' ?>>Projeto Estrutural</option>
                                    <option value="eletrico" <?= ($document['type'] === 'eletrico') ? 'selected' : '' ?>>Projeto Elétrico</option>
                                    <option value="hidraulico" <?= ($document['type'] === 'hidraulico') ? 'selected' : '' ?>>Projeto Hidráulico</option>
                                    <option value="outros" <?= ($document['type'] === 'outros') ? 'selected' : '' ?>>Outros</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($document['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="project_id" class="form-label">Projeto Relacionado</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value="">Nenhum projeto relacionado</option>
                            <?php if ($projects): ?>
                                <?php foreach ($projects as $proj): ?>
                                    <option value="<?= $proj['id'] ?>" 
                                            <?= ($document['project_id'] === $proj['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($proj['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Salvar Alterações
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='/documents/<?= $document['id'] ?>'">
                            <i class="fas fa-times me-1"></i>
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informações do Arquivo
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            <strong>Arquivo Original</strong>
                        </div>
                        <p class="text-muted mb-0"><?= htmlspecialchars($document['name']) ?></p>
                    </div>
                    
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h5 mb-0"><?= number_format($document['size'] / 1024 / 1024, 2) ?> MB</div>
                            <small class="text-muted">Tamanho</small>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h5 mb-0"><?= date('d/m/Y', strtotime($document['created_at'])) ?></div>
                            <small class="text-muted">Upload</small>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <hr>
                        <div class="mb-2">
                            <strong>Tipo MIME:</strong>
                            <span class="text-muted"><?= htmlspecialchars($document['mime_type']) ?></span>
                        </div>
                        <div class="mb-2">
                            <strong>ID do Documento:</strong>
                            <code><?= htmlspecialchars($document['id']) ?></code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Histórico
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <i class="fas fa-upload text-primary"></i>
                        <div class="timeline-content">
                            <strong>Upload inicial</strong>
                            <br>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($document['created_at'])) ?></small>
                        </div>
                    </div>
                    
                    <?php if ($document['updated_at'] !== $document['created_at']): ?>
                    <div class="timeline-item">
                        <i class="fas fa-edit text-success"></i>
                        <div class="timeline-content">
                            <strong>Última atualização</strong>
                            <br>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($document['updated_at'])) ?></small>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-left: 2px solid #dee2e6;
}

.timeline-item:last-child {
    border-left: none;
}

.timeline-item i {
    position: absolute;
    left: -1.5rem;
    top: 0.25rem;
    width: 1.5rem;
    height: 1.5rem;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    border: 2px solid #dee2e6;
}

.timeline-content {
    padding-left: 1rem;
}
</style>

<script>
document.getElementById('editDocumentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Salvando...';
    
    fetch('/documents/<?= $document['id'] ?>', {
        method: 'PUT',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensagem de sucesso
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${data.success}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.querySelector('.card-body').insertBefore(alert, document.querySelector('form'));
            
            // Redirecionar após 2 segundos
            setTimeout(() => {
                window.location.href = '/documents/<?= $document['id'] ?>';
            }, 2000);
        } else {
            throw new Error(data.error || 'Erro ao salvar');
        }
    })
    .catch(error => {
        // Mostrar erro
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>
            ${error.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.querySelector('.card-body').insertBefore(alert, document.querySelector('form'));
        
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

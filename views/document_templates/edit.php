<?php
$title = 'Editar Template: ' . ($template['name'] ?? 'Sem nome') . ' - Engenhario';
$pageTitle = 'Editar Template';
$showSidebar = true;
$activeMenu = 'admin';
ob_start();

// Garantir que $template existe
$template = $template ?? [];
$documentTypes = $documentTypes ?? [];
$projectTypes = $projectTypes ?? [];
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
                            <li class="breadcrumb-item"><a href="/admin/document-templates/<?= $template['id'] ?>"><?= htmlspecialchars($template['name'] ?? 'Template') ?></a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </nav>
                    <h1 class="h4 mb-0">✏️ Editar Template</h1>
                </div>
                <div>
                    <a href="/admin/document-templates/<?= $template['id'] ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            
            <!-- Formulário -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Template de Documentos</h5>
                </div>
                <div class="card-body">
                    <form action="/admin/document-templates/<?= $template['id'] ?>" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        
                        <!-- Informações Básicas -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nome do Template *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($template['name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="project_type" class="form-label">Tipo de Projeto *</label>
                                <select class="form-control" id="project_type" name="project_type" required>
                                    <option value="">Selecione o tipo de projeto</option>
                                    <?php foreach ($projectTypes as $value => $label): ?>
                                    <option value="<?= $value ?>" 
                                            <?= ($template['project_type'] ?? '') === $value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="description" class="form-label">Descrição</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                          placeholder="Descreva quando usar este template"><?= htmlspecialchars($template['description'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                                           <?= ($template['active'] ?? true) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="active">
                                        Template ativo
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Documentos Obrigatórios -->
                        <div class="mb-4">
                            <h5><i class="fas fa-file-alt text-danger"></i> Documentos Obrigatórios</h5>
                            <p class="text-muted">Selecione os documentos que são obrigatórios para este tipo de projeto:</p>
                            
                            <div class="row">
                                <?php 
                                $currentRequired = array_column($template['required_documents'] ?? [], 'type');
                                foreach ($documentTypes as $type => $info): 
                                ?>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="required_<?= $type ?>" name="required_documents[]" value="<?= $type ?>"
                                               <?= in_array($type, $currentRequired) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="required_<?= $type ?>">
                                            <?= htmlspecialchars($info['label'] ?? $info['name'] ?? 'Documento') ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Documentos Opcionais -->
                        <div class="mb-4">
                            <h5><i class="fas fa-file text-info"></i> Documentos Opcionais</h5>
                            <p class="text-muted">Selecione os documentos que são opcionais para este tipo de projeto:</p>
                            
                            <div class="row">
                                <?php 
                                $currentOptional = array_column($template['optional_documents'] ?? [], 'type');
                                foreach ($documentTypes as $type => $info): 
                                ?>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="optional_<?= $type ?>" name="optional_documents[]" value="<?= $type ?>"
                                               <?= in_array($type, $currentOptional) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="optional_<?= $type ?>">
                                            <?= htmlspecialchars($info['label'] ?? $info['name'] ?? 'Documento') ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Botões -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                                <a href="/admin/document-templates/<?= $template['id'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Prevenir seleção de mesmo documento como obrigatório e opcional
document.addEventListener('DOMContentLoaded', function() {
    const requiredCheckboxes = document.querySelectorAll('input[name="required_documents[]"]');
    const optionalCheckboxes = document.querySelectorAll('input[name="optional_documents[]"]');
    
    function updateCheckboxes(changedBox, otherBoxes) {
        if (changedBox.checked) {
            const value = changedBox.value;
            otherBoxes.forEach(box => {
                if (box.value === value) {
                    box.checked = false;
                }
            });
        }
    }
    
    requiredCheckboxes.forEach(box => {
        box.addEventListener('change', function() {
            updateCheckboxes(this, optionalCheckboxes);
        });
    });
    
    optionalCheckboxes.forEach(box => {
        box.addEventListener('change', function() {
            updateCheckboxes(this, requiredCheckboxes);
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

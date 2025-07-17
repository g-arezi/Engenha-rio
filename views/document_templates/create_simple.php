<?php
// Versão simplificada da view para teste
$title = 'Criar Template - Teste';
$showSidebar = true;
$activeMenu = 'admin';
ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>🧪 Teste - Criar Template de Documentos</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/document-templates">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome do Template *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="project_type" class="form-label">Tipo de Projeto *</label>
                                    <select class="form-select" id="project_type" name="project_type" required>
                                        <option value="">Selecione...</option>
                                        <option value="residencial">Residencial</option>
                                        <option value="comercial">Comercial</option>
                                        <option value="reforma">Reforma</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descrição</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6>Preview do Template</h6>
                                <div id="templatePreview" class="border p-3 bg-light">
                                    <p class="text-muted">Configure o template para ver o preview</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6>Documentos Obrigatórios</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="required_documents[]" value="rg" id="req_rg">
                                    <label class="form-check-label" for="req_rg">RG/CNH</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="required_documents[]" value="cpf" id="req_cpf">
                                    <label class="form-check-label" for="req_cpf">CPF</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6>Documentos Opcionais</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="optional_documents[]" value="escritura" id="opt_escritura">
                                    <label class="form-check-label" for="opt_escritura">Escritura</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">💾 Salvar Template</button>
                            <a href="/admin/document-templates" class="btn btn-secondary">❌ Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

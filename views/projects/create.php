<?php 
use App\Core\Auth;

$title = 'Criar Novo Projeto - Engenha Rio';
$showSidebar = true;
$activeMenu = 'projects';
ob_start();
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">➕ Criar Novo Projeto</h2>
                <p class="text-muted">Preencha os dados do seu projeto</p>
            </div>
            <button class="btn btn-outline-secondary" onclick="window.location.href='/projects'">
                <i class="fas fa-arrow-left me-1"></i>
                Voltar
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="content-section">
            <form method="POST" action="/projects">
                <div class="mb-3">
                    <label class="form-label">📝 Título do Projeto *</label>
                    <input type="text" name="name" class="form-control" placeholder="Ex: Projeto Residencial João Silva" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">📄 Descrição do Projeto *</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Descreva detalhadamente o projeto, incluindo objetivos, especificações e requisitos..." required></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">🏗️ Tipo de Projeto *</label>
                            <select name="project_type" class="form-select" required onchange="loadDocumentTemplates()">
                                <option value="">Selecione o tipo</option>
                                <option value="residencial">Residencial</option>
                                <option value="comercial">Comercial/Industrial</option>
                                <option value="reforma">Reforma e Adequação</option>
                                <option value="regularizacao">Regularização Predial</option>
                                <option value="urbano">Projeto Urbano</option>
                                <option value="infraestrutura">Infraestrutura</option>
                                <option value="outro">Outros</option>
                            </select>
                            <small class="form-text text-muted">Defina o tipo para selecionar template de documentos</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">📋 Template de Documentos</label>
                            <select name="document_template_id" class="form-select" id="documentTemplateSelect" disabled>
                                <option value="">Primeiro selecione o tipo de projeto</option>
                            </select>
                            <small class="form-text text-muted">Define quais documentos o cliente deve enviar</small>
                        </div>
                    </div>
                </div>
                
                <!-- Seleção de Cliente - Obrigatório para Admin/Analista -->
                <div class="mb-3">
                    <label class="form-label">👤 Cliente Responsável *</label>
                    <select name="client_id" class="form-select" required>
                        <option value="">Selecione o cliente responsável pelo projeto</option>
                        <?php if (!empty($clients)): ?>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['name']) ?> (<?= htmlspecialchars($client['email']) ?>)</option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="form-text text-muted">Selecione o cliente que será responsável por este projeto</small>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">📅 Prazo de Entrega</label>
                            <input type="date" name="deadline" class="form-control" required>
                            <small class="form-text text-muted">Data limite para conclusão do projeto</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">👨‍💼 Analista Responsável</label>
                            <select name="analyst_id" class="form-select">
                                <?php if (Auth::isAnalyst()): ?>
                                    <option value="">Eu serei o responsável</option>
                                    <?php if (!empty($analysts)): ?>
                                        <?php foreach ($analysts as $analyst): ?>
                                            <?php if ($analyst['id'] !== Auth::user()['id']): ?>
                                                <option value="<?= $analyst['id'] ?>"><?= htmlspecialchars($analyst['name']) ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <option value="">Selecionar mais tarde</option>
                                    <?php if (!empty($analysts)): ?>
                                        <?php foreach ($analysts as $analyst): ?>
                                            <option value="<?= $analyst['id'] ?>"><?= htmlspecialchars($analyst['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">⚡ Prioridade</label>
                            <select name="priority" class="form-select">
                                <option value="normal">Normal</option>
                                <option value="media">Média</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                    </div>
                
                <div class="alert alert-info">
                    <h6 class="alert-heading">ℹ️ Informações Importantes</h6>
                    <ul class="mb-0">
                        <li><strong>Cliente obrigatório:</strong> Todo projeto deve ser vinculado a um cliente</li>
                        <?php if (Auth::isAnalyst()): ?>
                            <li><strong>Analista responsável:</strong> Você será automaticamente definido como analista responsável, a menos que selecione outro</li>
                        <?php endif; ?>
                        <li>Após criar o projeto, o cliente poderá fazer upload de documentos</li>
                        <li>O analista será notificado por email sobre o novo projeto</li>
                        <li>O cliente receberá atualizações sobre o progresso do projeto</li>
                    </ul>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Criar Projeto
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='/projects'">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="content-section">
            <h5 class="mb-3">💡 Dicas para seu Projeto</h5>
            
            <div class="mb-3">
                <h6 class="text-primary">� Cliente Responsável</h6>
                <p class="text-muted small">Selecione o cliente que será responsável e poderá acessar este projeto</p>
            </div>
            
            <div class="mb-3">
                <h6 class="text-primary">�📝 Título Claro</h6>
                <p class="text-muted small">Use um título descritivo que identifique facilmente o projeto</p>
            </div>
            
            <div class="mb-3">
                <h6 class="text-primary">📄 Descrição Detalhada</h6>
                <p class="text-muted small">Inclua todos os detalhes importantes como localização, área, tipo de construção, etc.</p>
            </div>
            
            <div class="mb-3">
                <h6 class="text-primary">📅 Prazo Realista</h6>
                <p class="text-muted small">Defina um prazo que permita uma análise adequada do projeto</p>
            </div>
            
            <div class="mb-3">
                <h6 class="text-primary">👨‍💼 Analista Experiente</h6>
                <p class="text-muted small">Escolha um analista com experiência no tipo de projeto</p>
            </div>
        </div>
        
        <div class="content-section">
            <h5 class="mb-3">📋 Próximos Passos</h5>
            
            <div class="step-list">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h6>Criar Projeto</h6>
                        <p class="text-muted small">Preencha as informações básicas</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h6>Upload de Documentos</h6>
                        <p class="text-muted small">Anexe plantas, fotos e documentos</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h6>Análise Técnica</h6>
                        <p class="text-muted small">Aguarde a análise do especialista</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h6>Aprovação Final</h6>
                        <p class="text-muted small">Receba o projeto aprovado</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-list {
    position: relative;
}

.step-item {
    display: flex;
    align-items: start;
    margin-bottom: 1.5rem;
    position: relative;
}

.step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 2rem;
    bottom: -1.5rem;
    width: 2px;
    background: #e9ecef;
}

.step-number {
    width: 1.5rem;
    height: 1.5rem;
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.step-content h6 {
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.step-content p {
    margin: 0;
    font-size: 0.75rem;
}
</style>

<script>
function loadDocumentTemplates() {
    const projectType = document.querySelector('select[name="project_type"]').value;
    const templateSelect = document.getElementById('documentTemplateSelect');
    
    if (!projectType) {
        templateSelect.innerHTML = '<option value="">Primeiro selecione o tipo de projeto</option>';
        templateSelect.disabled = true;
        return;
    }
    
    templateSelect.innerHTML = '<option value="">Carregando...</option>';
    templateSelect.disabled = false;
    
    fetch(`/api/document-templates?project_type=${projectType}`)
        .then(response => response.json())
        .then(data => {
            templateSelect.innerHTML = '<option value="">Nenhum template (documentos livres)</option>';
            
            if (data.success && data.templates.length > 0) {
                data.templates.forEach(template => {
                    const option = document.createElement('option');
                    option.value = template.id;
                    option.textContent = template.name;
                    option.title = template.description;
                    templateSelect.appendChild(option);
                });
            } else {
                templateSelect.innerHTML += '<option value="" disabled>Nenhum template disponível para este tipo</option>';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar templates:', error);
            templateSelect.innerHTML = '<option value="">Erro ao carregar templates</option>';
        });
}

// Mostrar preview do template selecionado
document.addEventListener('DOMContentLoaded', function() {
    const templateSelect = document.getElementById('documentTemplateSelect');
    if (templateSelect) {
        templateSelect.addEventListener('change', function() {
            const templateId = this.value;
            const previewDiv = document.getElementById('templatePreview');
            
            if (!templateId) {
                if (previewDiv) previewDiv.remove();
                return;
            }
            
            fetch(`/api/document-templates/${templateId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showTemplatePreview(data.template);
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar detalhes do template:', error);
                });
        });
    }
});

function showTemplatePreview(template) {
    // Remover preview anterior
    const existingPreview = document.getElementById('templatePreview');
    if (existingPreview) existingPreview.remove();
    
    // Criar novo preview
    const previewHtml = `
        <div id="templatePreview" class="mt-3 alert alert-info">
            <h6><i class="fas fa-file-alt me-2"></i>${template.name}</h6>
            <p class="mb-2">${template.description}</p>
            <div class="row">
                <div class="col-md-6">
                    <strong>Documentos Obrigatórios (${template.required_documents.length}):</strong>
                    <ul class="mb-0">
                        ${template.required_documents.map(doc => `<li>${doc.name}</li>`).join('')}
                    </ul>
                </div>
                <div class="col-md-6">
                    <strong>Documentos Opcionais (${template.optional_documents.length}):</strong>
                    <ul class="mb-0">
                        ${template.optional_documents.length > 0 
                            ? template.optional_documents.map(doc => `<li>${doc.name}</li>`).join('')
                            : '<li class="text-muted">Nenhum documento opcional</li>'
                        }
                    </ul>
                </div>
            </div>
        </div>
    `;
    
    // Inserir após o select
    document.getElementById('documentTemplateSelect').insertAdjacentHTML('afterend', previewHtml);
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

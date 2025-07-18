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

// Debug: Verificar se chegou até aqui
error_log("CREATE.PHP - View carregada com sucesso");

$title = 'Criar Template de Documentos - Engenha Rio';
$showSidebar = true;
$activeMenu = 'admin';
ob_start();
?>

<style>
    .document-item {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
    }
    .document-type-selector {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 10px;
    }
    .form-check {
        margin-bottom: 8px;
    }
    .preview-section {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Criar Template de Documentos
                    </h1>
                    <p class="text-muted mb-0">Configure os documentos necessários para diferentes tipos de projeto</p>
                </div>
                <div>
                    <a href="/admin/document-templates" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Voltar
                    </a>
                </div>
            </div>

            <form id="templateForm" method="POST" action="/admin/document-templates">
                <div class="row">
                    <!-- Configurações Básicas -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Configurações Básicas</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome do Template *</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                           placeholder="Ex: Projeto Residencial Unifamiliar">
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Descrição</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                              placeholder="Descrição detalhada do template"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="project_type" class="form-label">Tipo de Projeto *</label>
                                        <select class="form-select" id="project_type" name="project_type" required>
                                            <option value="">Selecione um tipo</option>
                                            <option value="residencial">Residencial</option>
                                            <option value="comercial">Comercial</option>
                                            <option value="industrial">Industrial</option>
                                            <option value="reforma">Reforma</option>
                                            <option value="regularizacao">Regularização</option>
                                            <option value="outros">Outros</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="active" class="form-label">Status</label>
                                        <select class="form-select" id="active" name="active">
                                            <option value="1">Ativo</option>
                                            <option value="0">Inativo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documentos Obrigatórios -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                    Documentos Obrigatórios
                                </h5>
                                <small class="text-muted">Documentos que o cliente deve enviar obrigatoriamente</small>
                            </div>
                            <div class="card-body">
                                <div class="document-type-selector mb-3">
                                    <div class="row" id="requiredDocuments">
                                        <!-- Será preenchido via JavaScript -->
                                    </div>
                                </div>
                                <div id="selectedRequired" class="mt-3">
                                    <small class="text-muted">Nenhum documento obrigatório selecionado</small>
                                </div>
                            </div>
                        </div>

                        <!-- Documentos Opcionais -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle text-info me-2"></i>
                                    Documentos Opcionais
                                </h5>
                                <small class="text-muted">Documentos que o cliente pode enviar opcionalmente</small>
                            </div>
                            <div class="card-body">
                                <div class="document-type-selector mb-3">
                                    <div class="row" id="optionalDocuments">
                                        <!-- Será preenchido via JavaScript -->
                                    </div>
                                </div>
                                <div id="selectedOptional" class="mt-3">
                                    <small class="text-muted">Nenhum documento opcional selecionado</small>
                                </div>
                            </div>
                        </div>

                        <!-- Documentos Customizados -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-plus-circle text-success me-2"></i>
                                    Documentos Customizados
                                </h5>
                                <small class="text-muted">Adicione documentos específicos para este template</small>
                            </div>
                            <div class="card-body">
                                <!-- Documentos Obrigatórios Customizados -->
                                <div class="mb-4">
                                    <h6 class="text-danger">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Documentos Obrigatórios Customizados
                                    </h6>
                                    <div id="customRequiredContainer">
                                        <div class="custom-doc-row mb-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="custom_required_documents[]" 
                                                       placeholder="Ex: Laudo específico do projeto, Certidão especial...">
                                                <button type="button" class="btn btn-success" onclick="addCustomRequired()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Documentos Opcionais Customizados -->
                                <div class="mb-3">
                                    <h6 class="text-info">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Documentos Opcionais Customizados
                                    </h6>
                                    <div id="customOptionalContainer">
                                        <div class="custom-doc-row mb-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="custom_optional_documents[]" 
                                                       placeholder="Ex: Fotos complementares, Documentos adicionais...">
                                                <button type="button" class="btn btn-success" onclick="addCustomOptional()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    <strong>Dica:</strong> Use documentos customizados para requisitos específicos do seu projeto que não estão na lista padrão.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="col-lg-4">
                        <div class="card sticky-top">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Preview do Template</h5>
                            </div>
                            <div class="card-body">
                                <div id="templatePreview">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-file-alt fa-3x mb-3"></i>
                                        <p>Configure o template para ver o preview</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs para documentos selecionados -->
                <div id="hiddenInputs"></div>

                <!-- Botões de Ação -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="/admin/document-templates" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Criar Template
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
// Tipos de documentos disponíveis
const documentTypes = [
    { id: 'rg', label: 'RG/CNH', description: 'Documento de identidade com foto' },
    { id: 'cpf', label: 'CPF', description: 'Cadastro de Pessoa Física' },
    { id: 'cnpj', label: 'CNPJ', description: 'Cadastro Nacional de Pessoa Jurídica' },
    { id: 'escritura', label: 'Escritura do Imóvel', description: 'Documento de propriedade' },
    { id: 'comprovante_endereco', label: 'Comprovante de Endereço', description: 'Conta de luz, água ou gás' },
    { id: 'levantamento_topografico', label: 'Levantamento Topográfico', description: 'Planta topográfica do terreno' },
    { id: 'alvara_construcao', label: 'Alvará de Construção', description: 'Licença para construir' },
    { id: 'projeto_arquitetonico', label: 'Projeto Arquitetônico', description: 'Plantas baixas e cortes' },
    { id: 'memorial_descritivo', label: 'Memorial Descritivo', description: 'Descrição técnica da obra' },
    { id: 'art_rrt', label: 'ART/RRT', description: 'Anotação de Responsabilidade Técnica' },
    { id: 'analise_solo', label: 'Análise do Solo', description: 'Sondagem do terreno' },
    { id: 'projeto_estrutural', label: 'Projeto Estrutural', description: 'Cálculo e detalhamento estrutural' },
    { id: 'projeto_eletrico', label: 'Projeto Elétrico', description: 'Instalações elétricas' },
    { id: 'projeto_hidraulico', label: 'Projeto Hidráulico', description: 'Instalações hidráulicas' },
    { id: 'licenca_ambiental', label: 'Licença Ambiental', description: 'Autorização ambiental' },
    { id: 'contrato_social', label: 'Contrato Social', description: 'Documento constitutivo da empresa' },
    { id: 'alvara_funcionamento', label: 'Alvará de Funcionamento', description: 'Licença para operar' },
    { id: 'plantas_atuais', label: 'Plantas Atuais', description: 'Plantas do estado atual da construção' },
    { id: 'laudo_estrutural', label: 'Laudo Estrutural', description: 'Avaliação da estrutura existente' },
    { id: 'as_built', label: 'As Built', description: 'Levantamento da construção executada' }
];

let selectedRequired = [];
let selectedOptional = [];

// Inicializar interface
document.addEventListener('DOMContentLoaded', function() {
    renderDocumentOptions();
    updatePreview();
});

// Renderizar opções de documentos
function renderDocumentOptions() {
    const requiredContainer = document.getElementById('requiredDocuments');
    const optionalContainer = document.getElementById('optionalDocuments');

    documentTypes.forEach(doc => {
        // Checkbox para obrigatórios
        const requiredCheckbox = createDocumentCheckbox(doc, 'required');
        requiredContainer.appendChild(requiredCheckbox);

        // Checkbox para opcionais
        const optionalCheckbox = createDocumentCheckbox(doc, 'optional');
        optionalContainer.appendChild(optionalCheckbox);
    });
}

// Criar checkbox para documento
function createDocumentCheckbox(doc, type) {
    const col = document.createElement('div');
    col.className = 'col-12';

    col.innerHTML = `
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="${type}_${doc.id}" 
                   onchange="toggleDocument('${doc.id}', '${type}')">
            <label class="form-check-label" for="${type}_${doc.id}">
                <strong>${doc.label}</strong><br>
                <small class="text-muted">${doc.description}</small>
            </label>
        </div>
    `;

    return col;
}

// Toggle documento
function toggleDocument(docId, type) {
    const doc = documentTypes.find(d => d.id === docId);
    
    if (type === 'required') {
        if (selectedRequired.includes(docId)) {
            selectedRequired = selectedRequired.filter(id => id !== docId);
        } else {
            selectedRequired.push(docId);
            // Remover dos opcionais se estiver lá
            selectedOptional = selectedOptional.filter(id => id !== docId);
            document.getElementById(`optional_${docId}`).checked = false;
        }
    } else {
        if (selectedOptional.includes(docId)) {
            selectedOptional = selectedOptional.filter(id => id !== docId);
        } else {
            selectedOptional.push(docId);
            // Remover dos obrigatórios se estiver lá
            selectedRequired = selectedRequired.filter(id => id !== docId);
            document.getElementById(`required_${docId}`).checked = false;
        }
    }

    updateSelectedLists();
    updatePreview();
    updateHiddenInputs();
}

// Atualizar listas de selecionados
function updateSelectedLists() {
    const requiredDiv = document.getElementById('selectedRequired');
    const optionalDiv = document.getElementById('selectedOptional');

    // Obrigatórios
    if (selectedRequired.length > 0) {
        const requiredDocs = selectedRequired.map(id => {
            const doc = documentTypes.find(d => d.id === id);
            return `<span class="badge bg-danger me-1">${doc.label}</span>`;
        }).join('');
        requiredDiv.innerHTML = requiredDocs;
    } else {
        requiredDiv.innerHTML = '<small class="text-muted">Nenhum documento obrigatório selecionado</small>';
    }

    // Opcionais
    if (selectedOptional.length > 0) {
        const optionalDocs = selectedOptional.map(id => {
            const doc = documentTypes.find(d => d.id === id);
            return `<span class="badge bg-info me-1">${doc.label}</span>`;
        }).join('');
        optionalDiv.innerHTML = optionalDocs;
    } else {
        optionalDiv.innerHTML = '<small class="text-muted">Nenhum documento opcional selecionado</small>';
    }
}

// Atualizar preview
function updatePreview() {
    const name = document.getElementById('name').value;
    const projectType = document.getElementById('project_type').value;
    const description = document.getElementById('description').value;

    let preview = `
        <div class="mb-3">
            <h6>${name || 'Nome do Template'}</h6>
            <small class="text-muted">${projectType ? projectType.charAt(0).toUpperCase() + projectType.slice(1) : 'Tipo não selecionado'}</small>
        </div>
    `;

    if (description) {
        preview += `<p class="small">${description}</p>`;
    }

    if (selectedRequired.length > 0) {
        preview += '<h6 class="text-danger">Obrigatórios:</h6><ul class="small">';
        selectedRequired.forEach(id => {
            const doc = documentTypes.find(d => d.id === id);
            preview += `<li>${doc.label}</li>`;
        });
        preview += '</ul>';
    }

    if (selectedOptional.length > 0) {
        preview += '<h6 class="text-info">Opcionais:</h6><ul class="small">';
        selectedOptional.forEach(id => {
            const doc = documentTypes.find(d => d.id === id);
            preview += `<li>${doc.label}</li>`;
        });
        preview += '</ul>';
    }

    document.getElementById('templatePreview').innerHTML = preview;
}

// Atualizar inputs hidden
function updateHiddenInputs() {
    const hiddenDiv = document.getElementById('hiddenInputs');
    hiddenDiv.innerHTML = '';

    // Adicionar documentos obrigatórios
    selectedRequired.forEach(docId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'required_documents[]';
        input.value = docId;
        hiddenDiv.appendChild(input);
    });

    // Adicionar documentos opcionais
    selectedOptional.forEach(docId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'optional_documents[]';
        input.value = docId;
        hiddenDiv.appendChild(input);
    });
}

// Atualizar preview em tempo real
document.getElementById('name').addEventListener('input', updatePreview);
document.getElementById('project_type').addEventListener('change', updatePreview);
document.getElementById('description').addEventListener('input', updatePreview);

// Debug: interceptar submissão do formulário
document.getElementById('templateForm').addEventListener('submit', function(e) {
    console.log('=== FORM SUBMISSION DEBUG ===');
    console.log('Required docs:', selectedRequired);
    console.log('Optional docs:', selectedOptional);
    
    // Verificar inputs hidden
    const hiddenInputs = document.getElementById('hiddenInputs');
    console.log('Hidden inputs HTML:', hiddenInputs.innerHTML);
    
    // Verificar FormData
    const formData = new FormData(this);
    console.log('FormData entries:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ':', value);
    }
    
    // Se não há documentos obrigatórios, mostrar alerta
    if (selectedRequired.length === 0) {
        alert('ERRO: Nenhum documento obrigatório selecionado!');
        e.preventDefault();
        return false;
    }
    
    console.log('Form will be submitted...');
});

// Funções para documentos customizados
function addCustomRequired() {
    const container = document.getElementById('customRequiredContainer');
    const newRow = document.createElement('div');
    newRow.className = 'custom-doc-row mb-2';
    newRow.innerHTML = `
        <div class="input-group">
            <input type="text" class="form-control" name="custom_required_documents[]" 
                   placeholder="Ex: Laudo específico do projeto...">
            <button type="button" class="btn btn-danger" onclick="removeCustomRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
}

function addCustomOptional() {
    const container = document.getElementById('customOptionalContainer');
    const newRow = document.createElement('div');
    newRow.className = 'custom-doc-row mb-2';
    newRow.innerHTML = `
        <div class="input-group">
            <input type="text" class="form-control" name="custom_optional_documents[]" 
                   placeholder="Ex: Fotos complementares...">
            <button type="button" class="btn btn-danger" onclick="removeCustomRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
}

function removeCustomRow(button) {
    button.closest('.custom-doc-row').remove();
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

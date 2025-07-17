<?php 
$title = 'Projetos - Engenha Rio';
$pageTitle = 'Projetos';
$showSidebar = true;
$activeMenu = 'projects';
ob_start();

// Importar classe Auth para verificações de permissão
use App\Core\Auth;

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

// Verificar permissões do usuário
$canCreateProjects = Auth::canManageProjects();
$canEditProjects = Auth::canEditProjects();
$canApproveProjects = Auth::canApproveProjects();
$canCompleteProjects = Auth::canCompleteProjects();
$isClient = Auth::isClient();
?>

<div class="row">
    <div class="col-12 mb-4">
        <?php if ($isClient): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Área do Cliente:</strong> Você pode visualizar seus projetos, acompanhar o status e fazer upload de documentos. 
            Para criar, editar, aprovar ou concluir projetos, entre em contato com nossa equipe.
        </div>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between align-items-center">
            <div class="flex-shrink-0">
                <h2 class="h4 mb-0">📁 Projetos</h2>
                <p class="text-muted mb-0">
                    <?php if ($isClient): ?>
                        Acompanhe seus projetos de arquitetura
                    <?php else: ?>
                        Gerencie seus projetos de arquitetura
                    <?php endif; ?>
                </p>
            </div>
            
            <!-- Barra de Pesquisa no Centro -->
            <div class="flex-grow-1 d-flex justify-content-center mx-4">
                <div class="input-group" style="max-width: 400px;">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           id="searchProject" 
                           placeholder="Pesquisar projeto por nome..."
                           onkeyup="filterProjects()"
                           autocomplete="off">
                    <button class="btn btn-outline-secondary" onclick="clearSearch()" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="d-flex gap-2 flex-shrink-0">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1"></i>
                        Filtrar por Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/projects">Todos</a></li>
                        <li><a class="dropdown-item" href="/projects?status=aguardando">Aguardando</a></li>
                        <li><a class="dropdown-item" href="/projects?status=pendente">Pendente</a></li>
                        <li><a class="dropdown-item" href="/projects?status=aprovado">Aprovado</a></li>
                        <li><a class="dropdown-item" href="/projects?status=atrasado">Atrasado</a></li>
                        <li><a class="dropdown-item" href="/projects?status=concluido">Concluído</a></li>
                    </ul>
                </div>
                <?php if ($canCreateProjects): ?>
                <button class="btn btn-primary" onclick="window.location.href='/projects/create'">
                    <i class="fas fa-plus me-1"></i>
                    Novo Projeto
                </button>
                <?php endif; ?>
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

<?php if (empty($projects)): ?>
<div class="row">
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Nenhum projeto encontrado</h4>
            <?php if ($isClient): ?>
                <p class="text-muted">Aguarde a criação de projetos pelos analistas</p>
            <?php else: ?>
                <p class="text-muted">Crie seu primeiro projeto para começar</p>
                <button class="btn btn-primary" onclick="window.location.href='/projects/create'">
                    <i class="fas fa-plus me-1"></i>
                    Criar Projeto
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <?php foreach ($projects as $project): ?>
    <div class="col-lg-4 mb-4 project-card">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title text-primary project-title"><?= htmlspecialchars($project['name']) ?></h5>
                    <span class="badge bg-<?= $statusColors[$project['status']] ?? 'secondary' ?>">
                        <?= $statusTexts[$project['status']] ?? ucfirst($project['status']) ?>
                    </span>
                </div>
                
                <p class="card-text text-muted mb-3 project-description">
                    <?= htmlspecialchars(substr($project['description'], 0, 100)) ?>
                    <?= strlen($project['description']) > 100 ? '...' : '' ?>
                </p>
                
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="fw-bold">Prazo</div>
                        <div class="text-muted"><?= date('d/m/Y', strtotime($project['deadline'])) ?></div>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold">Prioridade</div>
                        <div class="text-muted">
                            <?php if ($project['priority'] === 'alta'): ?>
                                <span class="badge bg-danger">Alta</span>
                            <?php elseif ($project['priority'] === 'media'): ?>
                                <span class="badge bg-warning">Média</span>
                            <?php else: ?>
                                <span class="badge bg-success">Normal</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="row text-center mb-3">
                    <div class="col-12">
                        <div class="fw-bold">Criado em</div>
                        <div class="text-muted"><?= date('d/m/Y', strtotime($project['created_at'])) ?></div>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm flex-fill" onclick="viewProject('<?= $project['id'] ?>')">
                        <i class="fas fa-eye me-1"></i>
                        Ver Detalhes
                    </button>
                    
                    <?php if (!$isClient): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <?php if ($canEditProjects): ?>
                            <li><a class="dropdown-item" href="#" onclick="editProject('<?= $project['id'] ?>')">
                                <i class="fas fa-edit me-2"></i>Editar
                            </a></li>
                            <?php endif; ?>
                            
                            <?php if ($project['status'] !== 'concluido'): ?>
                                <?php if ($canApproveProjects): ?>
                                <li><a class="dropdown-item" href="#" onclick="updateStatus('<?= $project['id'] ?>', 'aprovado')">
                                    <i class="fas fa-check me-2"></i>Aprovar
                                </a></li>
                                <?php endif; ?>
                                
                                <?php if ($canCompleteProjects): ?>
                                <li><a class="dropdown-item" href="#" onclick="updateStatus('<?= $project['id'] ?>', 'concluido')">
                                    <i class="fas fa-check-double me-2"></i>Concluir
                                </a></li>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php if ($canEditProjects): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteProject('<?= $project['id'] ?>')">
                                <i class="fas fa-trash me-2"></i>Excluir
                            </a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <?php else: ?>
                    <!-- Para clientes, mostrar apenas botão de upload de documentos -->
                    <button class="btn btn-outline-success btn-sm" onclick="uploadDocuments('<?= $project['id'] ?>')">
                        <i class="fas fa-upload me-1"></i>
                        Upload
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Projetos dinâmicos serão exibidos aqui -->

<script>
// Funções de pesquisa
function filterProjects() {
    const searchTerm = document.getElementById('searchProject').value.toLowerCase().trim();
    const projectCards = document.querySelectorAll('.project-card');
    
    projectCards.forEach(card => {
        const projectName = card.querySelector('.project-title').textContent.toLowerCase();
        const projectDescription = card.querySelector('.project-description').textContent.toLowerCase();
        
        if (projectName.includes(searchTerm) || projectDescription.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function clearSearch() {
    document.getElementById('searchProject').value = '';
    const projectCards = document.querySelectorAll('.project-card');
    
    projectCards.forEach(card => {
        card.style.display = 'block';
    });
}

// Adicionar evento de Enter para pesquisa
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchProject');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterProjects();
            }
        });
    }
});

function viewProject(projectId) {
    window.location.href = `/projects/${projectId}`;
}

function editProject(projectId) {
    window.location.href = `/projects/${projectId}/edit`;
}

function uploadDocuments(projectId) {
    window.location.href = `/projects/${projectId}/documents`;
}

function updateStatus(projectId, status) {
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
                setTimeout(() => window.location.reload(), 1000);
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

// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.badge {
    font-size: 0.75rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.dropdown-menu {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
}

.text-primary {
    color: #0d6efd !important;
}

.alert {
    border: none;
    border-radius: 0.375rem;
}

#alert-container {
    z-index: 9999;
}

.fade.show {
    opacity: 1;
}

.fade {
    transition: opacity 0.15s linear;
}

/* Estilos para a barra de pesquisa */
.search-card {
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.search-card:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
}

#searchProject:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.project-card {
    transition: all 0.3s ease;
}

.project-card[style*="display: none"] {
    opacity: 0;
    transform: scale(0.95);
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
}

/* Layout responsivo para a barra superior */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .flex-grow-1.mx-4 {
        margin-left: 0 !important;
        margin-right: 0 !important;
        order: 3;
    }
    
    .flex-shrink-0:last-child {
        order: 2;
        align-self: center;
    }
    
    .input-group {
        max-width: 100% !important;
    }
}

/* Estilo do botão de limpar pesquisa */
.btn-outline-secondary {
    border-color: #ced4da;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    border-color: #adb5bd;
    color: #495057;
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

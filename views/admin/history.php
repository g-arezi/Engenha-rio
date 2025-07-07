<?php 
$title = 'Histórico do Sistema - Engenha Rio';
$showSidebar = true;
$activeMenu = 'admin';
ob_start();
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">📊 Histórico do Sistema</h2>
                <p class="text-muted">Acompanhe todas as atividades e mudanças no sistema</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-1"></i>
                    Atualizar
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="window.location.href='/admin'">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar ao Admin
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="content-section">
            <h5 class="mb-3">🔍 Filtros</h5>
            <div class="row">
                <div class="col-md-3">
                    <select class="form-select" id="filterType">
                        <option value="">Todos os tipos</option>
                        <option value="user">Usuários</option>
                        <option value="project">Projetos</option>
                        <option value="document">Documentos</option>
                        <option value="system">Sistema</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterUser">
                        <option value="">Todos os usuários</option>
                        <option value="admin">Administradores</option>
                        <option value="analista">Analistas</option>
                        <option value="cliente">Clientes</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="filterDate" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" onclick="applyFilters()">
                        <i class="fas fa-filter me-1"></i>
                        Aplicar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline de Atividades -->
<div class="row">
    <div class="col-md-12">
        <div class="content-section">
            <h5 class="mb-4">📈 Timeline de Atividades</h5>
            
            <div class="activity-timeline">
                <!-- Atividade 1: Sistema Iniciado -->
                <div class="activity-item">
                    <div class="activity-icon bg-success">
                        <i class="fas fa-power-off"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h6 class="mb-1">Sistema iniciado</h6>
                            <small class="text-muted"><?= date('d/m/Y H:i') ?></small>
                        </div>
                        <p class="text-muted mb-1">Sistema Engenha Rio foi inicializado com sucesso</p>
                        <span class="badge bg-success">Sistema</span>
                    </div>
                </div>

                <!-- Atividade 2: Funcionalidade Implementada -->
                <div class="activity-item">
                    <div class="activity-icon bg-primary">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h6 class="mb-1">Funcionalidade implementada</h6>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime('-1 hour')) ?></small>
                        </div>
                        <p class="text-muted mb-1">Vinculação obrigatória cliente-projeto ativada</p>
                        <span class="badge bg-primary">Desenvolvimento</span>
                    </div>
                </div>

                <!-- Atividade 3: Limpeza de Dados -->
                <div class="activity-item">
                    <div class="activity-icon bg-warning">
                        <i class="fas fa-broom"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h6 class="mb-1">Limpeza de dados realizada</h6>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime('-2 hours')) ?></small>
                        </div>
                        <p class="text-muted mb-1">Dados hardcoded removidos dos templates</p>
                        <span class="badge bg-warning">Manutenção</span>
                    </div>
                </div>

                <!-- Atividade 4: Validações Implementadas -->
                <div class="activity-item">
                    <div class="activity-icon bg-info">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h6 class="mb-1">Validações de segurança ativadas</h6>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime('-3 hours')) ?></small>
                        </div>
                        <p class="text-muted mb-1">Sistema de validação de arquivos e dados implementado</p>
                        <span class="badge bg-info">Segurança</span>
                    </div>
                </div>

                <!-- Placeholder para atividades dinâmicas -->
                <div id="dynamic-activities">
                    <!-- Atividades dinâmicas serão carregadas aqui via JavaScript -->
                </div>

                <!-- Mensagem quando não há mais atividades -->
                <div class="text-center mt-4">
                    <div class="text-muted">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <p>Fim do histórico de atividades</p>
                        <small>Mostrando últimas 24 horas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas do Histórico -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="content-section text-center">
            <div class="stat-icon mb-2">
                <i class="fas fa-users fa-2x text-primary"></i>
            </div>
            <h5 class="mb-1">25</h5>
            <p class="text-muted mb-0">Ações de Usuários</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-section text-center">
            <div class="stat-icon mb-2">
                <i class="fas fa-project-diagram fa-2x text-success"></i>
            </div>
            <h5 class="mb-1">8</h5>
            <p class="text-muted mb-0">Projetos Modificados</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-section text-center">
            <div class="stat-icon mb-2">
                <i class="fas fa-file-upload fa-2x text-warning"></i>
            </div>
            <h5 class="mb-1">12</h5>
            <p class="text-muted mb-0">Documentos Enviados</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-section text-center">
            <div class="stat-icon mb-2">
                <i class="fas fa-cog fa-2x text-info"></i>
            </div>
            <h5 class="mb-1">3</h5>
            <p class="text-muted mb-0">Ações do Sistema</p>
        </div>
    </div>
</div>

<style>
.activity-timeline {
    position: relative;
    padding-left: 1rem;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 1.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.activity-item {
    position: relative;
    display: flex;
    margin-bottom: 2rem;
}

.activity-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    margin-right: 1rem;
    flex-shrink: 0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    z-index: 1;
}

.activity-content {
    flex: 1;
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    border-left: 3px solid #dee2e6;
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.activity-header h6 {
    margin: 0;
    color: #495057;
}

.activity-header small {
    margin-left: auto;
    font-weight: 500;
}

.activity-content p {
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.badge {
    font-size: 0.75rem;
}

.stat-icon {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.content-section {
    background: #fff;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid #dee2e6;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .activity-timeline::before {
        left: 1rem;
    }
    
    .activity-icon {
        width: 2.5rem;
        height: 2.5rem;
    }
    
    .activity-content {
        padding: 0.75rem;
    }
}
</style>

<script>
function applyFilters() {
    const type = document.getElementById('filterType').value;
    const user = document.getElementById('filterUser').value;
    const date = document.getElementById('filterDate').value;
    
    // Criar um alerta temporário
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-info alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-info-circle me-2"></i>
        Filtros aplicados! Funcionalidade será implementada em próxima versão.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Inserir o alerta no topo da página
    const container = document.querySelector('.row');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Remover o alerta após 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
    
    // Aqui seria implementada a lógica de filtro real
    // fetch('/admin/history/filter', { ... })
}

function loadMoreActivities() {
    // Simular carregamento de mais atividades
    const container = document.getElementById('dynamic-activities');
    const newActivity = createActivityElement('Mais atividades carregadas', 'info', 'Sistema');
    container.appendChild(newActivity);
}

function createActivityElement(title, type, category) {
    const iconMap = {
        'info': 'fas fa-info-circle',
        'success': 'fas fa-check-circle', 
        'warning': 'fas fa-exclamation-triangle',
        'danger': 'fas fa-times-circle'
    };
    
    const colorMap = {
        'info': 'bg-info',
        'success': 'bg-success',
        'warning': 'bg-warning', 
        'danger': 'bg-danger'
    };
    
    const div = document.createElement('div');
    div.className = 'activity-item';
    div.innerHTML = `
        <div class="activity-icon ${colorMap[type]}">
            <i class="${iconMap[type]}"></i>
        </div>
        <div class="activity-content">
            <div class="activity-header">
                <h6 class="mb-1">${title}</h6>
                <small class="text-muted">Agora</small>
            </div>
            <p class="text-muted mb-1">Atividade gerada dinamicamente</p>
            <span class="badge bg-secondary">${category}</span>
        </div>
    `;
    
    return div;
}

// Simular atualização em tempo real
setInterval(() => {
    // Aqui seria implementada a lógica de atualização em tempo real
    // checkForNewActivities();
}, 30000); // Verificar a cada 30 segundos
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

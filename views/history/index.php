<?php 
$title = 'Histórico de Atividades - Engenha Rio';
$showSidebar = true;
$activeMenu = 'history';
ob_start();
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">🕐 Histórico de Atividades</h2>
                <p class="text-muted">Registro de todas as atividades do sistema</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar ao Admin
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i>
                    Atualizar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Tipo:</label>
            <select class="form-select">
                <option selected>Todos os tipos</option>
                <option>Usuário</option>
                <option>Projeto</option>
                <option>Documento</option>
                <option>Sistema</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Período:</label>
            <select class="form-select">
                <option selected>Todo o histórico</option>
                <option>Hoje</option>
                <option>Última semana</option>
                <option>Último mês</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Buscar:</label>
            <input type="text" class="form-control" placeholder="Buscar atividades...">
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-3">
            <label class="form-label">&nbsp;</label>
            <button class="btn btn-primary d-block w-100">
                <i class="fas fa-search me-1"></i>
                Filtrar
            </button>
        </div>
    </div>
</div>

<div class="content-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">📋 Atividades Registradas</h5>
        <span class="badge bg-primary">10 atividades</span>
    </div>
    
    <div class="activity-timeline">
        <div class="activity-item">
            <div class="activity-icon bg-primary">
                <i class="fas fa-user"></i>
            </div>
            <div class="activity-content">
                <div class="activity-header">
                    <h6 class="mb-1">Novo usuário cadastrado</h6>
                    <small class="text-muted">2h atrás</small>
                </div>
                <p class="text-muted mb-0">Cliente Teste</p>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-icon bg-success">
                <i class="fas fa-edit"></i>
            </div>
            <div class="activity-content">
                <div class="activity-header">
                    <h6 class="mb-1">Projeto atualizado</h6>
                    <small class="text-muted">4h atrás</small>
                </div>
                <p class="text-muted mb-0">Casa Residencial</p>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-icon bg-info">
                <i class="fas fa-file"></i>
            </div>
            <div class="activity-content">
                <div class="activity-header">
                    <h6 class="mb-1">Documento enviado</h6>
                    <small class="text-muted">6h atrás</small>
                </div>
                <p class="text-muted mb-0">Planta baixa.pdf</p>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-icon bg-warning">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="activity-content">
                <div class="activity-header">
                    <h6 class="mb-1">Status alterado</h6>
                    <small class="text-muted">1d atrás</small>
                </div>
                <p class="text-muted mb-0">Projeto concluído</p>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-icon bg-success">
                <i class="fas fa-plus"></i>
            </div>
            <div class="activity-content">
                <div class="activity-header">
                    <h6 class="mb-1">Novo projeto criado</h6>
                    <small class="text-muted">3 dias atrás</small>
                </div>
                <p class="text-muted mb-0">Edifício Residencial Aurora</p>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-icon bg-warning">
                <i class="fas fa-user-edit"></i>
            </div>
            <div class="activity-content">
                <div class="activity-header">
                    <h6 class="mb-1">Usuário atualizado</h6>
                    <small class="text-muted">4 dias atrás</small>
                </div>
                <p class="text-muted mb-0">Analista Sistema</p>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-icon bg-danger">
                <i class="fas fa-trash"></i>
            </div>
            <div class="activity-content">
                <div class="activity-header">
                    <h6 class="mb-1">Documento removido</h6>
                    <small class="text-muted">5 dias atrás</small>
                </div>
                <p class="text-muted mb-0">Documento antigo.pdf</p>
            </div>
        </div>
    </div>
</div>

<style>
.activity-timeline {
    position: relative;
    padding-left: 3rem;
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
    margin-bottom: 2rem;
    padding-left: 1rem;
}

.activity-icon {
    position: absolute;
    left: -2.5rem;
    top: 0;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    color: white;
    border: 3px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.activity-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.activity-header h6 {
    margin: 0;
    flex-grow: 1;
}

.activity-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border-left: 3px solid #007bff;
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

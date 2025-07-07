<?php 
$title = 'Administração - Engenha Rio';
$showSidebar = true;
$activeMenu = 'admin';
ob_start();
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">⚙️ Administração</h2>
                <p class="text-muted">Painel de controle do sistema</p>
            </div>
            <span class="badge bg-danger">Admin</span>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="background: #007bff; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div class="text-end">
                <div class="stat-number">3</div>
                <h6 class="mb-0">Usuários</h6>
                <small>→ Gerenciar</small>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: #28a745; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-folder fa-2x"></i>
            </div>
            <div class="text-end">
                <div class="stat-number">3</div>
                <h6 class="mb-0">Projetos</h6>
                <small>→ Gerenciar</small>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: #17a2b8; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-file-alt fa-2x"></i>
            </div>
            <div class="text-end">
                <div class="stat-number">8</div>
                <h6 class="mb-0">Documentos</h6>
                <small>ℹ️ Detalhes</small>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: #ffc107; color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-clock fa-2x"></i>
            </div>
            <div class="text-end">
                <div class="stat-number">1</div>
                <h6 class="mb-0">Pendentes</h6>
                <small>⚠️ Atenção</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="content-section">
            <h5 class="mb-3">⚡ Ações Rápidas</h5>
            
            <div class="mb-3">
                <a href="/admin/users" class="btn btn-outline-primary d-block text-start">
                    <i class="fas fa-users me-2"></i>
                    Gerenciar Usuários
                    <span class="badge bg-primary ms-2">3</span>
                </a>
            </div>
            
            <div class="mb-3">
                <a href="/admin/projects" class="btn btn-outline-success d-block text-start">
                    <i class="fas fa-folder me-2"></i>
                    Gerenciar Projetos
                    <span class="badge bg-success ms-2">3</span>
                </a>
            </div>
            
            <div class="mb-3">
                <a href="/admin/reports" class="btn btn-outline-info d-block text-start">
                    <i class="fas fa-chart-line me-2"></i>
                    Relatórios
                </a>
            </div>
            
            <div class="mb-3">
                <a href="/projects/create" class="btn btn-outline-warning d-block text-start">
                    <i class="fas fa-plus me-2"></i>
                    Novo Projeto Completo
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">📈 Atividades Recentes</h5>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-sync-alt me-1"></i>
                    Atualizar
                </button>
            </div>
            <!-- Atividades dinâmicas serão exibidas aqui -->
            
            <div class="text-center mt-3">
                <a href="/admin/history" class="btn btn-outline-secondary">
                    <i class="fas fa-history me-1"></i>
                    Ver Histórico Completo
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">📁 Projetos Recentes</h5>
        <a href="/projects" class="btn btn-outline-primary btn-sm">Ver Todos</a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Projeto</th>
                    <th>Cliente</th>
                    <th>Analista</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Projetos dinâmicos serão exibidos aqui -->
            </tbody>
        </table>
    </div>
</div>

<style>
.activity-item {
    padding: 0.75rem;
    border-left: 3px solid #e9ecef;
    background: #f8f9fa;
    border-radius: 0.375rem;
}

.activity-item:hover {
    background: #e9ecef;
}

.avatar-initials {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.8rem;
    color: white;
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

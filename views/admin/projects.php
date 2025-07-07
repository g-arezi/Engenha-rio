<?php
$title = 'Gerenciar Projetos - Engenha Rio';
$showSidebar = true;
$activeMenu = 'admin';
ob_start();
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">🚀 Gerenciamento de Projetos</h2>
                <p class="text-muted">Administre e monitore todos os projetos do sistema</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-success btn-sm" onclick="window.location.href='/projects/create'">
                    <i class="fas fa-plus me-1"></i>
                    Novo Projeto
                </button>
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

<!-- Estatísticas dos Projetos -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="content-section text-center">
            <div class="stat-icon mb-2">
                <i class="fas fa-project-diagram fa-2x text-primary"></i>
            </div>
            <h5 class="mb-1"><?= count($projects ?? []) ?></h5>
            <p class="text-muted mb-0">Total de Projetos</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-section text-center">
            <div class="stat-icon mb-2">
                <i class="fas fa-play-circle fa-2x text-success"></i>
            </div>
            <h5 class="mb-1"><?= count(array_filter($projects ?? [], function($p) { return ($p['status'] ?? '') === 'ativo'; })) ?></h5>
            <p class="text-muted mb-0">Projetos Ativos</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-section text-center">
            <div class="stat-icon mb-2">
                <i class="fas fa-check-circle fa-2x text-warning"></i>
            </div>
            <h5 class="mb-1"><?= count(array_filter($projects ?? [], function($p) { return ($p['status'] ?? '') === 'concluido'; })) ?></h5>
            <p class="text-muted mb-0">Concluídos</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="content-section text-center">
            <div class="stat-icon mb-2">
                <i class="fas fa-pause-circle fa-2x text-danger"></i>
            </div>
            <h5 class="mb-1"><?= count(array_filter($projects ?? [], function($p) { return ($p['status'] ?? '') === 'pausado'; })) ?></h5>
            <p class="text-muted mb-0">Pausados</p>
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
                    <select class="form-select" id="filterStatus">
                        <option value="">Todos os status</option>
                        <option value="ativo">Ativo</option>
                        <option value="pausado">Pausado</option>
                        <option value="concluido">Concluído</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterClient">
                        <option value="">Todos os clientes</option>
                        <?php 
                        $clients = array_unique(array_column($projects ?? [], 'client_name'));
                        foreach ($clients as $client): 
                        ?>
                            <option value="<?= htmlspecialchars($client) ?>"><?= htmlspecialchars($client) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="filterDate" placeholder="Data de criação">
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

<!-- Lista de Projetos -->
<div class="row">
    <div class="col-md-12">
        <div class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">📋 Lista de Projetos</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success btn-sm" onclick="exportProjects()">
                        <i class="fas fa-file-export me-1"></i>
                        Exportar
                    </button>
                </div>
            </div>
            
            <?php if (!empty($projects)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome do Projeto</th>
                                <th>Cliente</th>
                                <th>Status</th>
                                <th>Data de Criação</th>
                                <th>Última Atualização</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td><span class="badge bg-secondary">#<?= $project['id'] ?></span></td>
                                    <td>
                                        <strong><?= htmlspecialchars($project['name']) ?></strong>
                                        <?php if (!empty($project['description'])): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars(substr($project['description'], 0, 50)) ?>...</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-user me-1"></i>
                                        <?= htmlspecialchars($project['client_name'] ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        $statusIcon = '';
                                        switch ($project['status']) {
                                            case 'ativo':
                                                $statusClass = 'bg-success';
                                                $statusIcon = 'fas fa-play';
                                                break;
                                            case 'pausado':
                                                $statusClass = 'bg-warning';
                                                $statusIcon = 'fas fa-pause';
                                                break;
                                            case 'concluido':
                                                $statusClass = 'bg-primary';
                                                $statusIcon = 'fas fa-check';
                                                break;
                                            case 'cancelado':
                                                $statusClass = 'bg-danger';
                                                $statusIcon = 'fas fa-times';
                                                break;
                                            default:
                                                $statusClass = 'bg-secondary';
                                                $statusIcon = 'fas fa-question';
                                        }
                                        ?>
                                        <span class="badge <?= $statusClass ?>">
                                            <i class="<?= $statusIcon ?> me-1"></i>
                                            <?= ucfirst($project['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($project['created_at'])) ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-clock me-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($project['updated_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="viewProject(<?= $project['id'] ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="editProject(<?= $project['id'] ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteProject(<?= $project['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum projeto encontrado</h5>
                    <p class="text-muted">Comece criando seu primeiro projeto!</p>
                    <button class="btn btn-success" onclick="window.location.href='/projects/create'">
                        <i class="fas fa-plus me-1"></i>
                        Criar Projeto
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.content-section {
    background: #fff;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid #dee2e6;
    margin-bottom: 1rem;
}

.stat-icon {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.8rem;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 0.25rem;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 0.25rem;
    }
}
</style>

<script>
function applyFilters() {
    const status = document.getElementById('filterStatus').value;
    const client = document.getElementById('filterClient').value;
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
}

function viewProject(id) {
    window.location.href = `/projects/${id}`;
}

function editProject(id) {
    window.location.href = `/projects/${id}/edit`;
}

function deleteProject(id) {
    if (confirm('Tem certeza que deseja excluir este projeto? Esta ação não pode ser desfeita.')) {
        // Implementar lógica de exclusão
        fetch(`/projects/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao excluir o projeto: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erro ao excluir o projeto: ' + error.message);
        });
    }
}

function exportProjects() {
    window.location.href = '/admin/projects/export';
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

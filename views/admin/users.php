<?php
$title = 'Gerenciar Usuários';
$activeMenu = 'admin';
$showSidebar = false; // Ocultar sidebar nesta página
$hideTopBar = true; // Adiciona variável para ocultar o top-bar
?>

<link rel="stylesheet" href="/public/assets/css/admin-layout.css">

<style>
    .h2 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #2c3e50 !important;
    }
    
    .text-muted {
        color: #6c757d !important;
    }
    
    .border-bottom {
        border-bottom: 1px solid #dee2e6 !important;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.25rem;
    }
    
    .table-responsive {
        overflow-x: auto;
        min-height: 200px;
    }
    
    .table {
        margin-bottom: 0;
        white-space: nowrap;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 0.8rem;
        }
        
        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
            min-width: 100% !important;
        }
        
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
    
    /* Ajustes para garantir que o título não seja cortado */
    .container-fluid {
        width: 100%;
        max-width: none;
        padding: 0 !important;
        margin: 0 !important;
        box-sizing: border-box;
    }
    
    /* Quando a sidebar estiver oculta, o conteúdo ocupa toda a largura */
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        overflow: visible !important;
    }
    
    .page-header {
        width: 100%;
        margin-bottom: 1rem;
        overflow: visible;
    }
    
    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        word-wrap: break-word;
        white-space: normal;
        overflow: visible;
    }
    
    .page-header p {
        color: #6c757d;
        margin-bottom: 0;
        white-space: normal;
        overflow: visible;
    }
    
    /* Remover margens e padding desnecessários */
    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    .col-12 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    /* Melhorar espaçamento das cards */
    .card {
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    /* Ajustar tabelas para não quebrar */
    .table-responsive {
        overflow-x: auto;
        white-space: nowrap;
        width: 100%;
    }
    
    .table {
        width: 100%;
        min-width: 800px; /* Largura mínima para evitar quebras */
    }
    
    /* Garantir que as cards não ultrapassem a largura */
    .card {
        max-width: 100%;
        overflow: hidden;
    }
    
    /* Ajustes responsivos melhorados */
    @media (min-width: 769px) {
        .main-content {
            padding: 0 !important;
            width: 100% !important;
            max-width: none !important;
            margin-left: 0 !important;
        }
    }
    
    @media (max-width: 768px) {
        .main-content {
            padding: 1rem !important;
            width: 100% !important;
            margin-left: 0 !important;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
    }
</style>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-12">
            <div class="page-header p-3">
                <!-- Barra de navegação -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="/dashboard">
                            <i class="fas fa-home"></i> Sistema Arquitetura
                        </a>
                        <div class="navbar-nav ms-auto">
                            <a class="nav-link" href="/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a class="nav-link" href="/admin">
                                <i class="fas fa-cogs"></i> Administração
                            </a>
                            <a class="nav-link" href="/admin/settings">
                                <i class="fas fa-cog"></i> Configurações
                            </a>
                            <a class="nav-link text-danger" href="/logout">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </a>
                        </div>
                    </div>
                </nav>
                
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 pb-3 border-bottom">
                    <div class="flex-grow-1">
                        <h1 class="h2 mb-1 text-dark d-flex align-items-center">
                            <i class="fas fa-users me-2 text-dark"></i>Gerenciar Usuários
                        </h1>
                        <p class="text-muted mb-0">Gerencie usuários do sistema, aprovações e permissões</p>
                    </div>
                    <div class="d-flex gap-2 mt-3 mt-sm-0">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                            <i class="fas fa-plus me-1 text-dark"></i>
                            Novo Usuário
                        </button>
                        <button class="btn btn-outline-primary" onclick="exportUsers()">
                            <i class="fas fa-download me-1 text-dark"></i>
                            Exportar
                        </button>
                        <button class="btn btn-outline-secondary" onclick="refreshUsers()">
                            <i class="fas fa-sync me-1 text-dark"></i>
                            Atualizar
                        </button>
                    </div>
                </div>
                
                <!-- Filtros e Busca -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="searchUser" class="form-label">Buscar Usuário</label>
                                <input type="text" class="form-control" id="searchUser" placeholder="Nome, email ou função...">
                            </div>
                            <div class="col-md-2">
                                <label for="roleFilter" class="form-label">Função</label>
                                <select class="form-select" id="roleFilter">
                                    <option value="all">Todas</option>
                                    <option value="admin">Administrador</option>
                                    <option value="analista">Analista</option>
                                    <option value="cliente">Cliente</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="all">Todos</option>
                                    <option value="active">Ativo</option>
                                    <option value="inactive">Inativo</option>
                                    <option value="pending">Pendente</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="approvalFilter" class="form-label">Aprovação</label>
                                <select class="form-select" id="approvalFilter">
                                    <option value="all">Todos</option>
                                    <option value="approved">Aprovado</option>
                                    <option value="pending">Pendente</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary" onclick="clearFilters()">
                                        <i class="fas fa-times text-dark"></i>
                                    </button>
                                    <button class="btn btn-primary" onclick="applyFilters()">
                                        <i class="fas fa-search text-dark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Usuários Pendentes -->
    <?php if (!empty($pendingUsers)): ?>
    <div class="row g-0 mb-4">
        <div class="col-12">
            <div class="card mx-3">
                <div class="card-header bg-warning text-dark">
                    <h5><i class="fas fa-clock text-dark"></i> Usuários Aguardando Aprovação</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 120px;">Nome</th>
                                    <th style="min-width: 180px;">Email</th>
                                    <th style="min-width: 80px;">Função</th>
                                    <th style="min-width: 120px;">Data de Registro</th>
                                    <th style="min-width: 150px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingUsers as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    onclick="approveUser(<?= $user['id'] ?>)">
                                                <i class="fas fa-check text-dark"></i> Aprovar
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="rejectUser(<?= $user['id'] ?>)">
                                                <i class="fas fa-times text-dark"></i> Rejeitar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Todos os Usuários -->
    <div class="row g-0">
        <div class="col-12">
            <div class="card mx-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-users text-dark"></i> Todos os Usuários</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary" id="bulkActionBtn" style="display: none;" onclick="showBulkActionModal()">
                            <i class="fas fa-tasks me-1 text-dark"></i>
                            Ações
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="masterCheckbox" onchange="toggleAllUsers()">
                                        </div>
                                    </th>
                                    <th style="min-width: 120px;">Nome</th>
                                    <th style="min-width: 180px;">Email</th>
                                    <th style="min-width: 80px;">Função</th>
                                    <th style="min-width: 80px;">Status</th>
                                    <th style="min-width: 90px;">Aprovação</th>
                                    <th style="min-width: 90px;">Cadastro</th>
                                    <th style="min-width: 150px;">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input user-checkbox" type="checkbox" 
                                                   id="user-<?= $user['id'] ?>" value="<?= $user['id'] ?>"
                                                   onchange="toggleUserSelection('<?= $user['id'] ?>')">
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= ($user['active'] ?? true) ? 'success' : 'danger' ?>">
                                            <?= ($user['active'] ?? true) ? 'Ativo' : 'Inativo' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <span class="badge bg-info">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-<?= ($user['approved'] ?? false) ? 'success' : 'warning' ?>">
                                                <?= ($user['approved'] ?? false) ? 'Aprovado' : 'Pendente' ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    onclick="viewUser('<?= $user['id'] ?>')"
                                                    title="Visualizar">
                                                <i class="fas fa-eye text-dark"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" 
                                                    onclick="editUser('<?= $user['id'] ?>')"
                                                    title="Editar">
                                                <i class="fas fa-edit text-dark"></i>
                                            </button>
                                            <?php if ($user['role'] !== 'admin'): ?>
                                                <?php if (!($user['approved'] ?? false)): ?>
                                                <button type="button" class="btn btn-outline-success btn-sm" 
                                                        onclick="approveUser('<?= $user['id'] ?>')"
                                                        title="Aprovar">
                                                    <i class="fas fa-check text-dark"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                        onclick="rejectUser('<?= $user['id'] ?>')"
                                                        title="Rejeitar">
                                                    <i class="fas fa-times text-dark"></i>
                                                </button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                        onclick="toggleUserStatus('<?= $user['id'] ?>', <?= ($user['active'] ?? true) ? 'false' : 'true' ?>)"
                                                        title="<?= ($user['active'] ?? true) ? 'Desativar' : 'Ativar' ?>">
                                                    <i class="fas fa-<?= ($user['active'] ?? true) ? 'ban' : 'check' ?> text-dark"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                        onclick="deleteUser('<?= $user['id'] ?>', '<?= htmlspecialchars($user['name']) ?>')"
                                                        title="Excluir">
                                                    <i class="fas fa-trash text-dark"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-outline-warning btn-sm" 
                                                        onclick="resetPassword('<?= $user['id'] ?>')"
                                                        title="Resetar Senha">
                                                    <i class="fas fa-key text-dark"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Criação de Usuário -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">
                    <i class="fas fa-user-plus me-2 text-dark"></i>
                    Criar Novo Usuário
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createUserForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="createUserName" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control" id="createUserName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="createUserEmail" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="createUserEmail" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="createUserPassword" class="form-label">Senha *</label>
                            <input type="password" class="form-control" id="createUserPassword" name="password" required minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label for="createUserRole" class="form-label">Função *</label>
                            <select class="form-select" id="createUserRole" name="role" required>
                                <option value="">Selecione uma função</option>
                                <option value="admin">Administrador</option>
                                <option value="analista">Analista</option>
                                <option value="cliente">Cliente</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="createUserActive" name="active" checked>
                                <label class="form-check-label" for="createUserActive">
                                    Usuário Ativo
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="createUserApproved" name="approved" checked>
                                <label class="form-check-label" for="createUserApproved">
                                    Usuário Aprovado
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1 text-dark"></i>
                        Criar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Edição de Usuário -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="fas fa-user-edit me-2 text-dark"></i>
                    Editar Usuário
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm">
                <input type="hidden" id="editUserId" name="user_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editUserName" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control" id="editUserName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editUserEmail" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="editUserEmail" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editUserPassword" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="editUserPassword" name="password" minlength="6">
                            <div class="form-text">Deixe em branco para manter a senha atual</div>
                        </div>
                        <div class="col-md-6">
                            <label for="editUserRole" class="form-label">Função *</label>
                            <select class="form-select" id="editUserRole" name="role" required>
                                <option value="">Selecione uma função</option>
                                <option value="admin">Administrador</option>
                                <option value="analista">Analista</option>
                                <option value="cliente">Cliente</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editUserActive" name="active">
                                <label class="form-check-label" for="editUserActive">
                                    Usuário Ativo
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editUserApproved" name="approved">
                                <label class="form-check-label" for="editUserApproved">
                                    Usuário Aprovado
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1 text-dark"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Ações em Lote -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionModalLabel">
                    <i class="fas fa-tasks me-2 text-dark"></i>
                    Ações em Lote
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Selecione a ação a ser aplicada aos usuários selecionados:</p>
                <div class="mb-3">
                    <select class="form-select" id="bulkActionSelect">
                        <option value="">Selecione uma ação</option>
                        <option value="approve">Aprovar</option>
                        <option value="reject">Rejeitar</option>
                        <option value="activate">Ativar</option>
                        <option value="deactivate">Desativar</option>
                        <option value="delete">Excluir</option>
                    </select>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2 text-dark"></i>
                    Esta ação será aplicada a <span id="selectedUsersCount">0</span> usuário(s) selecionado(s).
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">
                    <i class="fas fa-check me-1 text-dark"></i>
                    Executar Ação
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function approveUser(userId) {
    if (confirm('Tem certeza que deseja aprovar este usuário?')) {
        fetch('/admin/users/approve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Usuário aprovado com sucesso!');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', data.error || 'Erro ao aprovar usuário');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao aprovar usuário');
        });
    }
}

function rejectUser(userId) {
    if (confirm('Tem certeza que deseja rejeitar este usuário?')) {
        fetch('/admin/users/reject', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Usuário rejeitado com sucesso!');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', data.error || 'Erro ao rejeitar usuário');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao rejeitar usuário');
        });
    }
}

function toggleUserStatus(userId, newStatus) {
    const action = newStatus === 'true' ? 'ativar' : 'desativar';
    
    if (confirm(`Tem certeza que deseja ${action} este usuário?`)) {
        const formData = new FormData();
        formData.append('user_id', userId);
        formData.append('status', newStatus);
        
        fetch('/admin/users/toggle-status', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', `Usuário ${action === 'ativar' ? 'ativado' : 'desativado'} com sucesso!`);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', data.error || `Erro ao ${action} usuário`);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', `Erro ao ${action} usuário`);
        });
    }
}

// Função para criar usuário
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Criando...';
    
    fetch('/admin/users/create', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.success);
            const modal = bootstrap.Modal.getInstance(document.getElementById('createUserModal'));
            modal.hide();
            this.reset();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', data.error || 'Erro ao criar usuário');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('error', 'Erro ao criar usuário');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Função para editar usuário
function editUser(userId) {
    console.log('editUser called with userId:', userId);
    
    // Mostrar loading
    const loadingHtml = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2">Carregando dados do usuário...</p>
        </div>
    `;
    
    fetch(`/admin/users/${userId}/edit`)
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.text();
        })
        .then(text => {
            console.log('Response text:', text);
            
            try {
                const data = JSON.parse(text);
                console.log('Parsed JSON:', data);
                
                if (data.success) {
                    const user = data.user;
                    console.log('User data:', user);
                    
                    // Preencher formulário
                    document.getElementById('editUserId').value = user.id;
                    document.getElementById('editUserName').value = user.name;
                    document.getElementById('editUserEmail').value = user.email;
                    document.getElementById('editUserRole').value = user.role;
                    document.getElementById('editUserActive').checked = user.active;
                    document.getElementById('editUserApproved').checked = user.approved;
                    
                    // Mostrar modal
                    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    modal.show();
                    
                    console.log('Modal opened successfully');
                } else {
                    console.error('Server error:', data.error);
                    showAlert('error', data.error || 'Erro ao carregar dados do usuário');
                }
            } catch (jsonError) {
                console.error('JSON parse error:', jsonError);
                console.error('Response text was:', text);
                showAlert('error', 'Erro ao processar resposta do servidor');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showAlert('error', 'Erro ao carregar dados do usuário: ' + error.message);
        });
}

// Função para atualizar usuário
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const userId = document.getElementById('editUserId').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Converter FormData para JSON
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key === 'active' || key === 'approved') {
            data[key] = value === 'on';
        } else {
            data[key] = value;
        }
    }
    
    // Incluir checkboxes não marcados
    data.active = document.getElementById('editUserActive').checked;
    data.approved = document.getElementById('editUserApproved').checked;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Salvando...';
    
    fetch(`/admin/users/${userId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message || 'Usuário atualizado com sucesso');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
            modal.hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', data.error || 'Erro ao atualizar usuário');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('error', 'Erro ao atualizar usuário');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Função para excluir usuário
function deleteUser(userId, userName) {
    if (confirm(`Tem certeza que deseja excluir o usuário "${userName}"?\n\nEsta ação não pode ser desfeita.`)) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message || 'Usuário excluído com sucesso');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', data.error || 'Erro ao excluir usuário');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao excluir usuário');
        });
    }
}

// Função para seleção múltipla
let selectedUsers = [];

function toggleUserSelection(userId) {
    const checkbox = document.getElementById(`user-${userId}`);
    const row = checkbox.closest('tr');
    
    if (checkbox.checked) {
        selectedUsers.push(userId);
        row.classList.add('table-active');
    } else {
        selectedUsers = selectedUsers.filter(id => id !== userId);
        row.classList.remove('table-active');
    }
    
    updateBulkActions();
}

function toggleAllUsers() {
    const masterCheckbox = document.getElementById('masterCheckbox');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    
    selectedUsers = [];
    
    userCheckboxes.forEach(checkbox => {
        checkbox.checked = masterCheckbox.checked;
        const row = checkbox.closest('tr');
        
        if (masterCheckbox.checked) {
            selectedUsers.push(checkbox.value);
            row.classList.add('table-active');
        } else {
            row.classList.remove('table-active');
        }
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const bulkActionBtn = document.getElementById('bulkActionBtn');
    const selectedCount = selectedUsers.length;
    
    if (selectedCount > 0) {
        bulkActionBtn.style.display = 'block';
        bulkActionBtn.innerHTML = `<i class="fas fa-tasks me-1"></i> Ações (${selectedCount})`;
    } else {
        bulkActionBtn.style.display = 'none';
    }
}

// Função para mostrar modal de ações em lote
function showBulkActionModal() {
    document.getElementById('selectedUsersCount').textContent = selectedUsers.length;
    const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
    modal.show();
}

// Função para executar ação em lote
function executeBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    
    if (!action) {
        showAlert('warning', 'Selecione uma ação');
        return;
    }
    
    if (selectedUsers.length === 0) {
        showAlert('warning', 'Nenhum usuário selecionado');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', action);
    selectedUsers.forEach(userId => {
        formData.append('user_ids[]', userId);
    });
    
    fetch('/admin/users/bulk-action', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.success);
            const modal = bootstrap.Modal.getInstance(document.getElementById('bulkActionModal'));
            modal.hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', data.error || 'Erro ao executar ação');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('error', 'Erro ao executar ação');
    });
}

// Função para filtrar usuários
function applyFilters() {
    const search = document.getElementById('searchUser').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const approvalFilter = document.getElementById('approvalFilter').value;
    
    const rows = document.querySelectorAll('#usersTableBody tr');
    
    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const email = row.cells[2].textContent.toLowerCase();
        const role = row.cells[3].textContent.toLowerCase();
        const status = row.querySelector('.badge-success, .badge-danger');
        const approval = row.querySelector('.badge-primary, .badge-warning');
        
        let show = true;
        
        // Filtro de busca
        if (search && !name.includes(search) && !email.includes(search) && !role.includes(search)) {
            show = false;
        }
        
        // Filtro de função
        if (roleFilter !== 'all' && !role.includes(roleFilter)) {
            show = false;
        }
        
        // Filtro de status
        if (statusFilter !== 'all') {
            const isActive = status && status.classList.contains('badge-success');
            if ((statusFilter === 'active' && !isActive) || (statusFilter === 'inactive' && isActive)) {
                show = false;
            }
        }
        
        // Filtro de aprovação
        if (approvalFilter !== 'all') {
            const isApproved = approval && approval.classList.contains('badge-primary');
            if ((approvalFilter === 'approved' && !isApproved) || (approvalFilter === 'pending' && isApproved)) {
                show = false;
            }
        }
        
        row.style.display = show ? '' : 'none';
    });
}

// Função para limpar filtros
function clearFilters() {
    document.getElementById('searchUser').value = '';
    document.getElementById('roleFilter').value = 'all';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('approvalFilter').value = 'all';
    applyFilters();
}

// Função para exportar usuários
function exportUsers() {
    window.location.href = '/admin/users/export';
}

// Função para atualizar lista
function refreshUsers() {
    location.reload();
}

// Event listeners para filtros
document.getElementById('searchUser').addEventListener('input', applyFilters);
document.getElementById('roleFilter').addEventListener('change', applyFilters);
document.getElementById('statusFilter').addEventListener('change', applyFilters);
document.getElementById('approvalFilter').addEventListener('change', applyFilters);

function showAlert(type, message) {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
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
.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.page-header h1 {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #6c757d;
    margin-bottom: 0;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.card-header h5 {
    margin-bottom: 0;
    color: #2c3e50;
}

.avatar-initials {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    color: white;
}
</style>

<script>
// Funções JavaScript para gerenciamento de usuários
function approveUser(userId) {
    if (confirm('Tem certeza que deseja aprovar este usuário?')) {
        fetch('/admin/approve-user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao aprovar usuário');
        });
    }
}

function rejectUser(userId) {
    if (confirm('Tem certeza que deseja rejeitar este usuário? Esta ação não pode ser desfeita.')) {
        fetch('/admin/reject-user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao rejeitar usuário');
        });
    }
}

function toggleUserStatus(userId, currentStatus) {
    const action = currentStatus ? 'desativar' : 'ativar';
    if (confirm(`Tem certeza que deseja ${action} este usuário?`)) {
        fetch('/admin/toggle-user-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: userId, active: !currentStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao alterar status do usuário');
        });
    }
}

function viewUser(userId) {
    fetch(`/admin/view-user/${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showUserModal(data.user);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao carregar dados do usuário');
        });
}

function editUser(userId) {
    window.location.href = `/admin/edit-user/${userId}`;
}

function showUserModal(user) {
    const modalHtml = `
        <div class="modal fade" id="userModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalhes do Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nome:</strong> ${user.name}</p>
                                <p><strong>Email:</strong> ${user.email}</p>
                                <p><strong>Função:</strong> ${user.role}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> ${user.active ? 'Ativo' : 'Inativo'}</p>
                                <p><strong>Aprovado:</strong> ${user.approved ? 'Sim' : 'Não'}</p>
                                <p><strong>Cadastrado:</strong> ${new Date(user.created_at).toLocaleDateString('pt-BR')}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="editUser('${user.id}')">Editar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove modal existente
    const existingModal = document.getElementById('userModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Adiciona novo modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Mostra modal
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

function resetPassword(userId) {
    const newPassword = prompt('Digite a nova senha (mínimo 6 caracteres):');
    
    if (newPassword !== null) {
        if (newPassword.length >= 6) {
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('new_password', newPassword);
            
            fetch('/admin/users/reset-password', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Senha resetada com sucesso!');
                } else {
                    showAlert('error', data.error || 'Erro ao resetar senha');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showAlert('error', 'Erro ao resetar senha');
            });
        } else {
            showAlert('warning', 'A senha deve ter pelo menos 6 caracteres');
        }
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

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

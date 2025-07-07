<?php $this->layout('admin/layout'); ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Visualizar Usuário</h6>
                    <a href="/admin/users" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Informações do Usuário</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">ID:</th>
                                            <td><?= $user['id'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nome:</th>
                                            <td><?= htmlspecialchars($user['name']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Função:</th>
                                            <td>
                                                <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'moderator' ? 'warning' : 'info') ?>">
                                                    <?= ucfirst($user['role']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge badge-<?= ($user['active'] ?? true) ? 'success' : 'secondary' ?>">
                                                    <?= ($user['active'] ?? true) ? 'Ativo' : 'Inativo' ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Aprovação:</th>
                                            <td>
                                                <span class="badge badge-<?= ($user['approved'] ?? false) ? 'success' : 'warning' ?>">
                                                    <?= ($user['approved'] ?? false) ? 'Aprovado' : 'Pendente' ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Data de Cadastro:</th>
                                            <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Última Atualização:</th>
                                            <td><?= date('d/m/Y H:i', strtotime($user['updated_at'])) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tempo de Conta:</th>
                                            <td><?= $stats['accountAge'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Último Login:</th>
                                            <td><?= $stats['lastLogin'] ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Ações do Usuário</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <button class="btn btn-success btn-sm w-100 mb-2" onclick="editUser(<?= $user['id'] ?>)">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-warning btn-sm w-100 mb-2" onclick="resetPassword(<?= $user['id'] ?>)">
                                                <i class="fas fa-key"></i> Resetar Senha
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-<?= ($user['active'] ?? true) ? 'secondary' : 'info' ?> btn-sm w-100 mb-2" onclick="toggleStatus(<?= $user['id'] ?>)">
                                                <i class="fas fa-power-off"></i> <?= ($user['active'] ?? true) ? 'Desativar' : 'Ativar' ?>
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <?php if (!($user['approved'] ?? false)): ?>
                                                <button class="btn btn-primary btn-sm w-100 mb-2" onclick="approveUser(<?= $user['id'] ?>)">
                                                    <i class="fas fa-check"></i> Aprovar
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-danger btn-sm w-100" onclick="deleteUser(<?= $user['id'] ?>)">
                                                <i class="fas fa-trash"></i> Excluir Usuário
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editUser(id) {
    fetch(`/admin/users/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirecionar para página de edição ou abrir modal
                window.location.href = `/admin/users/${id}/edit`;
            } else {
                alert('Erro ao carregar dados do usuário');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar dados do usuário');
        });
}

function resetPassword(id) {
    const newPassword = prompt('Digite a nova senha (mínimo 6 caracteres):');
    if (newPassword && newPassword.length >= 6) {
        fetch(`/admin/users/${id}/reset-password`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ new_password: newPassword })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Senha resetada com sucesso');
            } else {
                alert('Erro ao resetar senha: ' + (data.error || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao resetar senha');
        });
    } else if (newPassword !== null) {
        alert('A senha deve ter pelo menos 6 caracteres');
    }
}

function toggleStatus(id) {
    if (confirm('Tem certeza que deseja alterar o status deste usuário?')) {
        fetch(`/admin/users/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erro ao alterar status: ' + (data.error || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao alterar status');
        });
    }
}

function approveUser(id) {
    if (confirm('Tem certeza que deseja aprovar este usuário?')) {
        fetch(`/admin/users/${id}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuário aprovado com sucesso');
                location.reload();
            } else {
                alert('Erro ao aprovar usuário: ' + (data.error || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao aprovar usuário');
        });
    }
}

function deleteUser(id) {
    if (confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/users/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuário excluído com sucesso');
                window.location.href = '/admin/users';
            } else {
                alert('Erro ao excluir usuário: ' + (data.error || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir usuário');
        });
    }
}
</script>

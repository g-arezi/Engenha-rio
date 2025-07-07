<?php
$title = 'Editar Usuário';
$activeMenu = 'admin';
$showSidebar = true;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1><i class="fas fa-user-edit"></i> Editar Usuário</h1>
                <p>Altere as informações do usuário</p>
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
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user"></i> Informações do Usuário</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/edit-user/<?= $user['id'] ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= htmlspecialchars($user['name']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Função</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="client" <?= $user['role'] === 'client' ? 'selected' : '' ?>>Cliente</option>
                                        <option value="analyst" <?= $user['role'] === 'analyst' ? 'selected' : '' ?>>Analista</option>
                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="active">
                                        <option value="1" <?= ($user['active'] ?? true) ? 'selected' : '' ?>>Ativo</option>
                                        <option value="0" <?= !($user['active'] ?? true) ? 'selected' : '' ?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="approved" 
                                               name="approved" <?= ($user['approved'] ?? false) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="approved">
                                            Usuário Aprovado
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/admin/users" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Informações Adicionais</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>ID do Usuário:</strong> <?= $user['id'] ?>
                    </div>
                    <div class="mb-3">
                        <strong>Data de Cadastro:</strong><br>
                        <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?>
                    </div>
                    <div class="mb-3">
                        <strong>Última Atualização:</strong><br>
                        <?= isset($user['updated_at']) ? date('d/m/Y H:i', strtotime($user['updated_at'])) : 'Nunca' ?>
                    </div>
                    <div class="mb-3">
                        <strong>Status Atual:</strong><br>
                        <span class="badge bg-<?= ($user['active'] ?? true) ? 'success' : 'danger' ?>">
                            <?= ($user['active'] ?? true) ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Aprovação:</strong><br>
                        <span class="badge bg-<?= ($user['approved'] ?? false) ? 'success' : 'warning' ?>">
                            <?= ($user['approved'] ?? false) ? 'Aprovado' : 'Pendente' ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-tools"></i> Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-warning" onclick="resetUserPassword()">
                            <i class="fas fa-key"></i> Resetar Senha
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="sendWelcomeEmail()">
                            <i class="fas fa-envelope"></i> Enviar Email de Boas-vindas
                        </button>
                        <?php if ($user['role'] !== 'admin'): ?>
                        <button type="button" class="btn btn-outline-danger" onclick="deactivateUser()">
                            <i class="fas fa-ban"></i> Desativar Usuário
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetUserPassword() {
    if (confirm('Tem certeza que deseja resetar a senha deste usuário?')) {
        const newPassword = prompt('Digite a nova senha (mínimo 6 caracteres):');
        if (newPassword && newPassword.length >= 6) {
            fetch('/admin/reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ user_id: '<?= $user['id'] ?>', new_password: newPassword })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Senha resetada com sucesso!');
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                alert('Erro ao resetar senha.');
            });
        } else if (newPassword !== null) {
            alert('A senha deve ter pelo menos 6 caracteres');
        }
    }
}

function sendWelcomeEmail() {
    if (confirm('Deseja enviar um email de boas-vindas para este usuário?')) {
        alert('Funcionalidade em desenvolvimento');
    }
}

function deactivateUser() {
    if (confirm('Tem certeza que deseja desativar este usuário?')) {
        fetch('/admin/toggle-user-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: '<?= $user['id'] ?>', active: false })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuário desativado com sucesso!');
                window.location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erro ao desativar usuário.');
        });
    }
}
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

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.card-header h5 {
    margin-bottom: 0;
    color: #2c3e50;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}
</style>

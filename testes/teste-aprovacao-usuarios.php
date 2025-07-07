<?php
// Teste dos botões de aprovação/rejeição de usuários
require_once 'vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;
use App\Models\User;

// Iniciar sessão
Session::start();

// Login automático como admin se não estiver logado
if (!Auth::check()) {
    Session::set('user_id', 'admin_002');
    Session::set('user_data', [
        'id' => 'admin_002',
        'name' => 'Administrador do Sistema',
        'email' => 'admin@sistema.com',
        'role' => 'admin',
        'active' => true,
        'approved' => true
    ]);
}

// Header HTML
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste - Botões de Aprovação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 20px; }
        .card { margin-bottom: 20px; }
        .btn-test { margin: 5px; }
        .status-ok { color: #28a745; font-weight: bold; }
        .status-error { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-check-circle"></i> Teste dos Botões de Aprovação/Rejeição</h1>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Status da Autenticação</h3>
            </div>
            <div class="card-body">
                <?php if (Auth::check()): ?>
                    <?php $user = Auth::user(); ?>
                    <p>✅ <strong>Usuário logado:</strong> <?= htmlspecialchars($user['name']) ?></p>
                    <p>✅ <strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
                    <p>✅ <strong>É Admin:</strong> <?= Auth::isAdmin() ? 'SIM' : 'NÃO' ?></p>
                <?php else: ?>
                    <p class="status-error">❌ Usuário não está logado</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h3>Usuários Pendentes de Aprovação</h3>
            </div>
            <div class="card-body">
                <?php
                $userModel = new User();
                $pendingUsers = $userModel->getPendingUsers();
                
                if (empty($pendingUsers)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Nenhum usuário pendente encontrado.
                    </div>
                    <button class="btn btn-secondary" onclick="createTestUser()">
                        <i class="fas fa-plus"></i> Criar Usuário de Teste
                    </button>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Função</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingUsers as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($user['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm btn-test" 
                                                onclick="approveUser('<?= $user['id'] ?>')">
                                            <i class="fas fa-check"></i> Aprovar
                                        </button>
                                        <button class="btn btn-danger btn-sm btn-test" 
                                                onclick="rejectUser('<?= $user['id'] ?>')">
                                            <i class="fas fa-times"></i> Rejeitar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h3>Todos os Usuários</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Função</th>
                                <th>Status</th>
                                <th>Aprovação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $allUsers = $userModel->all();
                            foreach ($allUsers as $user): 
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= htmlspecialchars($user['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= ($user['active'] ?? true) ? 'success' : 'danger' ?>">
                                        <?= ($user['active'] ?? true) ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= ($user['approved'] ?? false) ? 'success' : 'warning' ?>">
                                        <?= ($user['approved'] ?? false) ? 'Aprovado' : 'Pendente' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!($user['approved'] ?? false) && $user['role'] !== 'admin'): ?>
                                        <button class="btn btn-success btn-sm btn-test" 
                                                onclick="approveUser('<?= $user['id'] ?>')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm btn-test" 
                                                onclick="rejectUser('<?= $user['id'] ?>')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-info btn-sm btn-test" 
                                            onclick="toggleStatus('<?= $user['id'] ?>', <?= ($user['active'] ?? true) ? 'false' : 'true' ?>)">
                                        <i class="fas fa-<?= ($user['active'] ?? true) ? 'ban' : 'check' ?>"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info text-white">
                <h3>Log de Ações</h3>
            </div>
            <div class="card-body">
                <div id="action-log" style="background: #f8f9fa; padding: 15px; border-radius: 5px; min-height: 100px;">
                    <strong>Log de ações realizadas:</strong><br>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3>Links Úteis</h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/admin/users" class="btn btn-primary">Página Oficial de Gerenciar Usuários</a>
                    <a href="/dashboard" class="btn btn-secondary">Dashboard</a>
                    <a href="/" class="btn btn-light">Página Inicial</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function log(message) {
            const logDiv = document.getElementById('action-log');
            const time = new Date().toLocaleTimeString();
            logDiv.innerHTML += `<br>[${time}] ${message}`;
        }

        function approveUser(userId) {
            log(`Tentando aprovar usuário: ${userId}`);
            
            fetch(`/admin/users/${userId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ user_id: userId })
            })
            .then(response => {
                log(`Resposta do servidor: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    log(`✅ Usuário aprovado com sucesso!`);
                    alert('Usuário aprovado com sucesso!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    log(`❌ Erro ao aprovar: ${data.error || 'Erro desconhecido'}`);
                    alert('Erro ao aprovar usuário: ' + (data.error || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                log(`❌ Erro de rede: ${error.message}`);
                alert('Erro de rede: ' + error.message);
            });
        }

        function rejectUser(userId) {
            if (confirm('Tem certeza que deseja rejeitar este usuário? Esta ação irá excluí-lo permanentemente.')) {
                log(`Tentando rejeitar usuário: ${userId}`);
                
                fetch(`/admin/users/${userId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ user_id: userId })
                })
                .then(response => {
                    log(`Resposta do servidor: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        log(`✅ Usuário rejeitado com sucesso!`);
                        alert('Usuário rejeitado com sucesso!');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        log(`❌ Erro ao rejeitar: ${data.error || 'Erro desconhecido'}`);
                        alert('Erro ao rejeitar usuário: ' + (data.error || 'Erro desconhecido'));
                    }
                })
                .catch(error => {
                    log(`❌ Erro de rede: ${error.message}`);
                    alert('Erro de rede: ' + error.message);
                });
            }
        }

        function toggleStatus(userId, newStatus) {
            const action = newStatus === 'true' ? 'ativar' : 'desativar';
            log(`Tentando ${action} usuário: ${userId}`);
            
            fetch(`/admin/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    user_id: userId,
                    status: newStatus === 'true' 
                })
            })
            .then(response => {
                log(`Resposta do servidor: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    log(`✅ Status alterado com sucesso!`);
                    alert(`Usuário ${action}do com sucesso!`);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    log(`❌ Erro ao alterar status: ${data.error || 'Erro desconhecido'}`);
                    alert('Erro ao alterar status: ' + (data.error || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                log(`❌ Erro de rede: ${error.message}`);
                alert('Erro de rede: ' + error.message);
            });
        }

        function createTestUser() {
            const name = prompt('Nome do usuário de teste:');
            const email = prompt('Email do usuário de teste:');
            
            if (name && email) {
                log(`Criando usuário de teste: ${name} (${email})`);
                
                fetch('/admin/users/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: '123456',
                        role: 'cliente',
                        active: true,
                        approved: false
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        log(`✅ Usuário de teste criado!`);
                        alert('Usuário de teste criado com sucesso!');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        log(`❌ Erro ao criar usuário: ${data.error || 'Erro desconhecido'}`);
                        alert('Erro ao criar usuário: ' + (data.error || 'Erro desconhecido'));
                    }
                })
                .catch(error => {
                    log(`❌ Erro de rede: ${error.message}`);
                    alert('Erro de rede: ' + error.message);
                });
            }
        }

        // Log inicial
        document.addEventListener('DOMContentLoaded', function() {
            log('Página de teste carregada');
        });
    </script>
</body>
</html>

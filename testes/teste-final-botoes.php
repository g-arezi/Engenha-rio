<?php
// Teste completo do sistema de aprovação via HTTP
require_once 'init.php';

use App\Models\User;

session_start();

// Fazer login como admin
$userModel = new User();
$admin = $userModel->findByEmail('admin@engenhario.com');

if ($admin) {
    $_SESSION['user_id'] = $admin['id'];
    $_SESSION['user_name'] = $admin['name'];
    $_SESSION['user_email'] = $admin['email'];
    $_SESSION['user_role'] = $admin['role'];
}

// Criar usuário pendente para teste
$testUser = [
    'name' => 'Usuário Final Teste',
    'email' => 'final.teste@example.com',
    'password' => password_hash('123456', PASSWORD_DEFAULT),
    'role' => 'cliente',
    'approved' => false,
    'active' => true,
    'created_at' => date('Y-m-d H:i:s')
];

$existingUser = $userModel->findByEmail($testUser['email']);
if (!$existingUser) {
    $userId = $userModel->create($testUser);
} else {
    $userId = $existingUser['id'];
    $userModel->update($userId, ['approved' => false]);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Final - Botões de Aprovação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>🧪 Teste Final - Sistema de Aprovação</h1>
        
        <div class="alert alert-info">
            <strong>Status:</strong> Logado como <?= $admin['name'] ?> (<?= $admin['role'] ?>)
        </div>
        
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5><i class="fas fa-user-clock"></i> Usuário Aguardando Aprovação</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Função</th>
                            <th>Data de Registro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Usuário Final Teste</td>
                            <td>final.teste@example.com</td>
                            <td><span class="badge bg-secondary">Cliente</span></td>
                            <td><?= date('d/m/Y H:i') ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-success btn-sm" 
                                            onclick="approveUser('<?= $userId ?>')">
                                        <i class="fas fa-check text-dark"></i> Aprovar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            onclick="rejectUser('<?= $userId ?>')">
                                        <i class="fas fa-times text-dark"></i> Rejeitar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="alert-container"></div>
        
        <div class="mt-4">
            <a href="/admin/users" class="btn btn-primary">
                <i class="fas fa-users"></i> Ir para Página de Usuários Real
            </a>
        </div>
    </div>

    <script>
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            alertContainer.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show mt-3" role="alert">
                    <i class="fas ${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Auto-remover após 5 segundos
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) alert.remove();
            }, 5000);
        }

        function approveUser(userId) {
            if (confirm('Tem certeza que deseja aprovar este usuário?')) {
                console.log('Enviando aprovação para userId:', userId);
                
                fetch(`/admin/users/${userId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ user_id: userId })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.text().then(text => {
                        console.log('Response text:', text);
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Erro ao parsear JSON:', e);
                            return { success: false, error: 'Resposta inválida do servidor: ' + text };
                        }
                    });
                })
                .then(data => {
                    console.log('Data received:', data);
                    if (data.success) {
                        showAlert('success', '✅ Usuário aprovado com sucesso!');
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showAlert('error', '❌ Erro ao aprovar usuário: ' + (data.error || 'Erro desconhecido'));
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    showAlert('error', '❌ Erro de conexão: ' + error.message);
                });
            }
        }

        function rejectUser(userId) {
            if (confirm('Tem certeza que deseja rejeitar este usuário? Esta ação removerá o usuário permanentemente.')) {
                console.log('Enviando rejeição para userId:', userId);
                
                fetch(`/admin/users/${userId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ user_id: userId })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.text().then(text => {
                        console.log('Response text:', text);
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Erro ao parsear JSON:', e);
                            return { success: false, error: 'Resposta inválida do servidor: ' + text };
                        }
                    });
                })
                .then(data => {
                    console.log('Data received:', data);
                    if (data.success) {
                        showAlert('success', '✅ Usuário rejeitado com sucesso!');
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showAlert('error', '❌ Erro ao rejeitar usuário: ' + (data.error || 'Erro desconhecido'));
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    showAlert('error', '❌ Erro de conexão: ' + error.message);
                });
            }
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

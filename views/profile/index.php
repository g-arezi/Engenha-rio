<?php 
$title = 'Meu Perfil - Engenha Rio';
$pageTitle = 'Perfil';
$showSidebar = true;
$activeMenu = 'profile';
ob_start();

// Verificar mensagens de sucesso e erro
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0">👤 Meu Perfil</h2>
                <p class="text-muted">Gerencie suas informações pessoais</p>
            </div>
        </div>
    </div>
</div>

<?php if ($success): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php if ($success === 'password_updated'): ?>
                    Senha alterada com sucesso!
                <?php else: ?>
                    Perfil atualizado com sucesso!
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php
                switch ($error) {
                    case 'current_password_required':
                        echo 'A senha atual é obrigatória.';
                        break;
                    case 'new_password_required':
                        echo 'A nova senha é obrigatória.';
                        break;
                    case 'password_mismatch':
                        echo 'A confirmação da senha não confere.';
                        break;
                    case 'password_too_short':
                        echo 'A senha deve ter pelo menos 6 caracteres.';
                        break;
                    case 'invalid_current_password':
                        echo 'A senha atual está incorreta.';
                        break;
                    default:
                        echo 'Ocorreu um erro. Tente novamente.';
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="content-section">
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">👤 Nome Completo</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['name'] ?? 'Usuário') ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">📧 Email</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? 'N/A') ?>" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">🏷️ Tipo de Usuário</label>
                            <input type="text" class="form-control" value="<?= 
                                match($user['role'] ?? 'cliente') {
                                    'admin' => 'Administrador',
                                    'analista' => 'Analista',
                                    'cliente' => 'Cliente',
                                    default => 'Usuário'
                                }
                            ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">📅 Membro desde</label>
                            <input type="text" class="form-control" value="<?= isset($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : 'N/A' ?>" readonly>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="content-section">
            <h5 class="mb-3">🔐 Alterar Senha</h5>
            
            <form method="POST" action="/profile" id="passwordForm">
                <input type="hidden" name="_method" value="PUT">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Senha Atual</label>
                            <input type="password" class="form-control" name="current_password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" name="new_password" placeholder="••••••••" minlength="6" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" name="confirm_password" placeholder="••••••••" minlength="6" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">
                        A senha deve ter pelo menos 6 caracteres
                    </small>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Alterar Senha
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='/dashboard'">
                        <i class="fas fa-arrow-left me-1"></i>
                        Voltar ao Dashboard
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="content-section">
            <h5 class="mb-3">🛡️ Segurança da Conta</h5>
            
            <div class="mb-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <span>Email verificado</span>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <span>Conta ativa</span>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-circle text-muted me-2"></i>
                    <span>Senha criptografada</span>
                </div>
            </div>
        </div>
        
        <div class="content-section">
            <h5 class="mb-3">💡 Dicas de Segurança</h5>
            
            <ul class="list-unstyled">
                <li class="mb-2">
                    <i class="fas fa-shield-alt text-primary me-2"></i>
                    Use uma senha forte com pelo menos 8 caracteres
                </li>
                <li class="mb-2">
                    <i class="fas fa-eye text-primary me-2"></i>
                    Inclua letras maiúsculas, minúsculas e números
                </li>
                <li class="mb-2">
                    <i class="fas fa-user-secret text-primary me-2"></i>
                    Não compartilhe sua senha com outras pessoas
                </li>
                <li class="mb-2">
                    <i class="fas fa-desktop text-primary me-2"></i>
                    Faça logout ao usar computadores públicos
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordForm = document.getElementById('passwordForm');
    const newPasswordInput = document.querySelector('input[name="new_password"]');
    const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
    const submitButton = passwordForm.querySelector('button[type="submit"]');
    
    // Validação em tempo real
    function validatePasswords() {
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        // Remover classes anteriores
        confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
        
        if (confirmPassword && newPassword) {
            if (newPassword === confirmPassword) {
                confirmPasswordInput.classList.add('is-valid');
                submitButton.disabled = false;
            } else {
                confirmPasswordInput.classList.add('is-invalid');
                submitButton.disabled = true;
            }
        } else {
            submitButton.disabled = false;
        }
    }
    
    // Adicionar eventos
    newPasswordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);
    
    // Validação no submit
    passwordForm.addEventListener('submit', function(e) {
        const currentPassword = document.querySelector('input[name="current_password"]').value;
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (!currentPassword || !newPassword || !confirmPassword) {
            e.preventDefault();
            alert('Todos os campos são obrigatórios!');
            return;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('As senhas não conferem!');
            return;
        }
        
        if (newPassword.length < 6) {
            e.preventDefault();
            alert('A nova senha deve ter pelo menos 6 caracteres!');
            return;
        }
        
        // Confirmar alteração
        if (!confirm('Tem certeza que deseja alterar sua senha?')) {
            e.preventDefault();
        }
    });
    
    // Auto-dismiss alerts após 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

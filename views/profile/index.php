<?php 
$title = 'Meu Perfil - Engenha Rio';
$showSidebar = true;
$activeMenu = 'profile';
ob_start();
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

<div class="row">
    <div class="col-lg-8">
        <div class="content-section">
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">👤 Nome Completo</label>
                            <input type="text" class="form-control" value="Administrador" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">📧 Email</label>
                            <input type="email" class="form-control" value="admin@sistema.com" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">🏷️ Tipo de Usuário</label>
                            <input type="text" class="form-control" value="<?= ucfirst($user['role'] ?? 'Usuário') ?>" readonly>
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
            
            <form>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Senha Atual</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">
                        Deixe em branco para manter a senha atual
                    </small>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Salvar Alterações
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
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

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/app.php';
?>

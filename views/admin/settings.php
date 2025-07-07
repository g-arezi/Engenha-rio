<?php
$title = 'Configurações do Sistema';
$activeMenu = 'admin';
$showSidebar = true;

// Garantir que $settings existe
if (!isset($settings)) {
    $settings = [
        'site_name' => 'Sistema Engenha-rio',
        'site_description' => 'Sistema de Gestão de Projetos de Engenharia',
        'admin_email' => 'admin@engenhario.com',
        'maintenance_mode' => false,
        'user_registration' => true,
        'email_notifications' => true,
        'auto_approve_users' => false,
        'max_file_size' => 10,
        'allowed_file_types' => 'pdf,doc,docx,jpg,jpeg,png'
    ];
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1><i class="fas fa-cog"></i> Configurações do Sistema</h1>
                <p>Gerencie as configurações globais do sistema</p>
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
                    <h5><i class="fas fa-sliders-h"></i> Configurações Gerais</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/settings/update">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Nome do Sistema</label>
                                    <input type="text" class="form-control" id="site_name" name="site_name" 
                                           value="<?= htmlspecialchars($settings['site_name']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Email do Administrador</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email" 
                                           value="<?= htmlspecialchars($settings['admin_email']) ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="site_description" class="form-label">Descrição do Sistema</label>
                            <textarea class="form-control" id="site_description" name="site_description" rows="3"><?= htmlspecialchars($settings['site_description']) ?></textarea>
                        </div>
                        
                        <hr>
                        
                        <h6><i class="fas fa-shield-alt"></i> Configurações de Segurança</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenance_mode" 
                                               name="maintenance_mode" <?= $settings['maintenance_mode'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="maintenance_mode">
                                            Modo de Manutenção
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Quando ativado, apenas admins podem acessar o sistema</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="user_registration" 
                                               name="user_registration" <?= $settings['user_registration'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="user_registration">
                                            Permitir Registro de Usuários
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Permite que novos usuários se registrem no sistema</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="auto_approve_users" 
                                               name="auto_approve_users" <?= $settings['auto_approve_users'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="auto_approve_users">
                                            Aprovar Usuários Automaticamente
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Novos usuários são aprovados automaticamente</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" 
                                               name="email_notifications" <?= $settings['email_notifications'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="email_notifications">
                                            Notificações por Email
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Enviar notificações por email aos usuários</small>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6><i class="fas fa-file-upload"></i> Configurações de Upload</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_file_size" class="form-label">Tamanho Máximo de Arquivo (MB)</label>
                                    <input type="number" class="form-control" id="max_file_size" name="max_file_size" 
                                           min="1" max="100" value="<?= $settings['max_file_size'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="allowed_file_types" class="form-label">Tipos de Arquivo Permitidos</label>
                                    <input type="text" class="form-control" id="allowed_file_types" name="allowed_file_types" 
                                           value="<?= htmlspecialchars($settings['allowed_file_types']) ?>">
                                    <small class="form-text text-muted">Separados por vírgula (ex: pdf,doc,jpg,png)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Informações do Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Versão:</strong> 1.0.0
                    </div>
                    <div class="mb-3">
                        <strong>PHP:</strong> <?= phpversion() ?>
                    </div>
                    <div class="mb-3">
                        <strong>Última Atualização:</strong><br>
                        <?= isset($settings['updated_at']) ? date('d/m/Y H:i', strtotime($settings['updated_at'])) : 'Nunca' ?>
                    </div>
                    <div class="mb-3">
                        <strong>Atualizado por:</strong><br>
                        <?= $settings['updated_by'] ?? 'Sistema' ?>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <strong>Espaço em Disco:</strong><br>
                        <?php
                        $bytes = disk_free_space('.');
                        $gb = round($bytes / 1024 / 1024 / 1024, 2);
                        echo $gb . ' GB disponível';
                        ?>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Memória PHP:</strong><br>
                        <?= ini_get('memory_limit') ?>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Upload Máximo:</strong><br>
                        <?= ini_get('upload_max_filesize') ?>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-tools"></i> Ações do Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" id="clearCacheBtn" class="btn btn-outline-info" onclick="clearCache()">
                            <i class="fas fa-broom"></i> Limpar Cache
                        </button>
                        <button type="button" id="exportBtn" class="btn btn-outline-warning" onclick="exportData()">
                            <i class="fas fa-download"></i> Exportar Dados
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="viewLogs()">
                            <i class="fas fa-file-alt"></i> Ver Logs
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function clearCache() {
    if (confirm('Tem certeza que deseja limpar o cache do sistema?')) {
        showLoadingButton('clearCacheBtn', 'Limpando...');
        
        fetch('/admin/cache/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Cache limpo com sucesso!');
            } else {
                showAlert('error', 'Erro ao limpar cache: ' + data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Erro ao limpar cache.');
        })
        .finally(() => {
            hideLoadingButton('clearCacheBtn', 'Limpar Cache');
        });
    }
}

function exportData() {
    if (confirm('Deseja exportar todos os dados do sistema?')) {
        showLoadingButton('exportBtn', 'Exportando...');
        window.location.href = '/admin/export/data';
        setTimeout(() => {
            hideLoadingButton('exportBtn', 'Exportar Dados');
        }, 2000);
    }
}

function viewLogs() {
    window.open('/admin/logs', '_blank');
}

function showLoadingButton(buttonId, text) {
    const button = document.getElementById(buttonId);
    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + text;
    }
}

function hideLoadingButton(buttonId, originalText) {
    const button = document.getElementById(buttonId);
    if (button) {
        button.disabled = false;
        if (buttonId === 'clearCacheBtn') {
            button.innerHTML = '<i class="fas fa-broom"></i> ' + originalText;
        } else if (buttonId === 'exportBtn') {
            button.innerHTML = '<i class="fas fa-download"></i> ' + originalText;
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
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
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

// Validação do formulário
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const siteName = document.getElementById('site_name').value.trim();
            const adminEmail = document.getElementById('admin_email').value.trim();
            const maxFileSize = document.getElementById('max_file_size').value;
            
            if (!siteName) {
                e.preventDefault();
                showAlert('error', 'Nome do Sistema é obrigatório');
                return;
            }
            
            if (!adminEmail || !isValidEmail(adminEmail)) {
                e.preventDefault();
                showAlert('error', 'Email do Administrador deve ser válido');
                return;
            }
            
            if (maxFileSize < 1 || maxFileSize > 100) {
                e.preventDefault();
                showAlert('error', 'Tamanho máximo de arquivo deve estar entre 1 e 100 MB');
                return;
            }
            
            // Mostrar loading no botão de salvar
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
            }
        });
    }
});

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
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
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.25rem;
}

.card-header h5 {
    margin-bottom: 0;
    color: #2c3e50;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-outline-info:hover {
    background-color: #17a2b8;
    border-color: #17a2b8;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
}

.alert {
    border: none;
    border-radius: 0.375rem;
}

.alert-success {
    background-color: #d1edff;
    color: #0c5460;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.small, .form-text {
    font-size: 0.875rem;
}

.text-muted {
    color: #6c757d !important;
}

#alert-container {
    max-width: 500px;
    right: 20px;
    left: auto;
}

.fade.show {
    opacity: 1;
}

.fade {
    transition: opacity 0.15s linear;
}

.btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}
</style>

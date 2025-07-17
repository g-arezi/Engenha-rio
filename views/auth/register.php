<?php 
$title = 'Cadastro - Engenha Rio';
$showSidebar = false;
ob_start();
?>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="/assets/images/engenhario-logo-new.png" alt="Engenha Rio" class="logo" style="width: 160px; height: auto; display: block; margin: 0 auto 20px; object-fit: contain;">
            <p>Sistema de Gestão de Documentos</p>
            <div class="info-message" style="background: #e7f3ff; color: #0066cc; padding: 10px; border-radius: 5px; margin: 15px 0; font-size: 14px;">
                <i class="fas fa-info-circle"></i> Todos os novos usuários são registrados como <strong>Cliente</strong>. Para alterar permissões, entre em contato com um administrador.
            </div>
        </div>
        
        <form method="POST" action="/register" class="login-form">
            <div class="input-group">
                <label for="name">
                    <i class="fas fa-user"></i>
                    Nome Completo
                </label>
                <input type="text" id="name" name="name" 
                       value="<?= htmlspecialchars(\App\Core\Session::get('old')['name'] ?? '') ?>" 
                       required>
                <?php if ($errors = \App\Core\Session::get('errors')): ?>
                    <?php if (isset($errors['name'])): ?>
                        <div class="error-message"><?= $errors['name'] ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="input-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Email
                </label>
                <input type="email" id="email" name="email" 
                       value="<?= htmlspecialchars(\App\Core\Session::get('old')['email'] ?? '') ?>" 
                       required>
                <?php if ($errors = \App\Core\Session::get('errors')): ?>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error-message"><?= $errors['email'] ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="input-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Senha
                </label>
                <input type="password" id="password" name="password" required>
                <?php if ($errors = \App\Core\Session::get('errors')): ?>
                    <?php if (isset($errors['password'])): ?>
                        <div class="error-message"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <!-- Campo oculto para definir o tipo como cliente automaticamente -->
            <input type="hidden" name="role" value="cliente">
            
            <button type="submit" class="btn-login">
                <i class="fas fa-user-plus"></i>
                Criar Conta
            </button>
        </form>
        
        <div class="login-footer">
            <p>Já tem uma conta?</p>
            <a href="/login" class="btn-register">
                <i class="fas fa-sign-in-alt"></i>
                Fazer Login
            </a>
        </div>
    </div>
    
    <div class="login-copyright">
        <p>&copy; 2025 ENGENHARIO. Todos os direitos reservados.</p>
    </div>
</div>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #35363a;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .login-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
    }
    
    .login-card {
        background: #35363a;
        border-radius: 20px;
        padding: 40px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .login-header {
        margin-bottom: 30px;
        padding: 10px 0;
    }
    
    .logo {
        width: 160px;
        height: auto;
        margin-bottom: 20px;
        border-radius: 0;
        object-fit: contain;
        max-width: 100%;
    }
    
    .login-header h1 {
        color: white;
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 5px 0;
        letter-spacing: 1px;
    }
    
    .login-header p {
        color: #bdc3c7;
        font-size: 14px;
        margin: 0;
    }
    
    .login-form {
        text-align: left;
    }
    
    .input-group {
        margin-bottom: 20px;
    }
    
    .input-group label {
        display: block;
        color: white;
        font-size: 14px;
        margin-bottom: 8px;
        font-weight: 500;
    }
    
    .input-group label i {
        margin-right: 8px;
        width: 16px;
    }
    
    .input-group input,
    .input-group select {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 10px !important;
        background: #34495e;
        color: white;
        font-size: 16px;
        transition: all 0.3s ease;
        box-sizing: border-box;
        -webkit-border-radius: 10px !important;
        -moz-border-radius: 10px !important;
        -webkit-appearance: none;
    }
    
    .input-group input:focus,
    .input-group select:focus {
        outline: none;
        background: #3f5468;
        box-shadow: 0 0 0 2px #6c757d;
        border-radius: 10px !important;
    }
    
    /* Garantir que todos os inputs tenham bordas arredondadas */
    input[type="email"], 
    input[type="password"], 
    input[type="text"],
    select {
        border-radius: 10px !important;
        -webkit-border-radius: 10px !important;
        -moz-border-radius: 10px !important;
        -webkit-appearance: none;
    }
    
    .input-group select {
        cursor: pointer;
    }
    
    .input-group select option {
        background: #34495e;
        color: white;
    }
    
    .input-group input::placeholder {
        color: #95a5a6;
    }
    
    .btn-login {
        width: 100%;
        padding: 15px;
        background: #6c757d;
        border: none;
        border-radius: 10px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    
    .btn-login:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
    }
    
    .btn-login i {
        margin-right: 10px;
    }
    
    .login-footer {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #34495e;
    }
    
    .login-footer p {
        color: #bdc3c7;
        margin: 0 0 15px 0;
        font-size: 14px;
    }
    
    .btn-register {
        display: inline-block;
        padding: 10px 20px;
        background: transparent;
        border: 2px solid #6c757d;
        border-radius: 10px;
        color: #6c757d;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-register:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-register i {
        margin-right: 8px;
    }
    
    .login-copyright {
        margin-top: 20px;
        text-align: center;
    }
    
    .login-copyright p {
        color: rgba(255, 255, 255, 0.6);
        font-size: 12px;
        margin: 0;
    }
    
    .error-message {
        color: #e74c3c;
        font-size: 12px;
        margin-top: 5px;
    }
    
    @media (max-width: 480px) {
        .login-card {
            padding: 30px 20px;
            margin: 20px;
        }
        
        .login-header h1 {
            font-size: 24px;
        }
        
        .logo {
            width: 50px;
            height: 50px;
        }
    }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

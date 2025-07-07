<?php 
$title = 'Engenha Rio - Sistema de Gestão de Projetos';
$showSidebar = false;
ob_start();
?>

<div class="container-fluid vh-100">
    <div class="row h-100">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="text-center">
                <div class="logo-container mb-4">
                    <img src="/assets/images/engenhario-logo-new.png" alt="Engenha Rio" class="main-logo">
                </div>
                <p class="lead text-light mb-5">Sistema Completo de Gestão de Documentos e Projetos de Arquitetura</p>
                
                <div class="row justify-content-center mb-5">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                        <h5>Sistema de Usuários</h5>
                                        <p class="text-muted">3 tipos de usuários com controle de acesso diferenciado</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                                        <h5>Dashboard Interativo</h5>
                                        <p class="text-muted">Gestão completa de projetos e documentos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-bell fa-3x text-warning mb-3"></i>
                                        <h5>Notificações</h5>
                                        <p class="text-muted">Sistema automático de emails e alertas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center gap-3">
                    <a href="/login" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-sign-in-alt me-2"></i>Fazer Login
                    </a>
                    <a href="/register" class="btn btn-outline-primary btn-lg px-4">
                        <i class="fas fa-user-plus me-2"></i>Criar Conta
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: #35363a;
        background-attachment: fixed;
    }
    
    .container-fluid {
        background: rgba(53, 54, 58, 0.95);
        backdrop-filter: blur(10px);
    }
    
    .main-logo {
        width: 300px;
        height: auto;
        max-width: 100%;
        filter: brightness(1.1) contrast(1.1);
    }
    
    .logo-container {
        margin-bottom: 2rem;
    }
    
    .card {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }
    
    .card-body {
        color: #ffffff;
    }
    
    .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    .btn-primary {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
    }
    
    .btn-primary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        color: #ffffff;
    }
    
    .btn-outline-primary {
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
    }
    
    .btn-outline-primary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        color: #ffffff;
    }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/app.php';
?>

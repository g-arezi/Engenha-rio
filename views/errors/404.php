<?php 
$title = 'Página não encontrada - Engenha Rio';
$showSidebar = false;
ob_start();
?>

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        <div class="display-1 text-primary mb-4">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h1 class="display-4 mb-4">404</h1>
        <h2 class="h4 mb-4">Página não encontrada</h2>
        <p class="text-muted mb-4">
            A página que você está procurando não existe ou foi movida.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="/" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>Início
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Voltar
            </a>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        background-attachment: fixed;
    }
    
    .container-fluid {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    
    .display-1 {
        font-size: 8rem;
        opacity: 0.8;
    }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>

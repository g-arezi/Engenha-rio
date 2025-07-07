<?php
// Verificar se o usuário está autenticado
if (!\App\Core\Auth::check()) {
    header('Location: /login');
    exit;
}

$user = \App\Core\Auth::user();
?>

<!-- Sidebar -->
<nav class="sidebar position-fixed">
    <div class="sidebar-header p-3">
        <div class="text-center">
            <img src="/assets/images/engenhario-logo-new.png" alt="Engenha Rio" 
                 style="width: 160px; height: auto; max-width: 100%;" 
                 onerror="console.log('Erro ao carregar logo:', this.src); this.style.display='none'; this.nextElementSibling.style.display='block';">
            </img>
            <h4 class="text-white" style="display: none;">
                <i class="fas fa-building me-2"></i>
                Engenha Rio
            </h4>
        </div>
        <small class="text-white-50 d-block text-center">Painel Administrativo</small>
    </div>
    
    <div class="sidebar-user p-3 border-bottom border-secondary">
        <div class="d-flex align-items-center">
            <div class="user-avatar bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                 style="width: 40px; height: 40px;">
                <i class="fas fa-user text-white"></i>
            </div>
            <div>
                <div class="text-white font-weight-bold"><?= $user['name'] ?></div>
                <small class="text-white-50"><?= ucfirst($user['role']) ?></small>
            </div>
        </div>
    </div>
    
    <ul class="nav flex-column p-3">
        <li class="nav-item">
            <a class="nav-link text-white <?= $activeMenu === 'dashboard' ? 'active' : '' ?>" 
               href="/admin">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?= $activeMenu === 'users' ? 'active' : '' ?>" 
               href="/admin/users">
                <i class="fas fa-users me-2"></i>
                Usuários
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?= $activeMenu === 'history' ? 'active' : '' ?>" 
               href="/admin/history">
                <i class="fas fa-history me-2"></i>
                Histórico
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?= $activeMenu === 'settings' ? 'active' : '' ?>" 
               href="/admin/settings">
                <i class="fas fa-cog me-2"></i>
                Configurações
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?= $activeMenu === 'logs' ? 'active' : '' ?>" 
               href="/admin/logs">
                <i class="fas fa-file-alt me-2"></i>
                Logs
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer p-3 mt-auto">
        <div class="d-grid gap-2">
            <a href="/dashboard" class="btn btn-outline-light btn-sm">
                <i class="fas fa-home me-1"></i>
                Dashboard Principal
            </a>
            <a href="/logout" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-sign-out-alt me-1"></i>
                Sair
            </a>
        </div>
    </div>
</nav>

<style>
.sidebar {
    width: 280px;
    height: 100vh;
    background: #35363a;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    border-right: 1px solid #6c757d;
}

.sidebar .nav-link {
    transition: all 0.3s ease;
    border-radius: 8px;
    margin-bottom: 5px;
}

.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.sidebar .nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    font-weight: bold;
}

.sidebar-header {
    background-color: rgba(0, 0, 0, 0.1);
}

.sidebar-user {
    background-color: rgba(0, 0, 0, 0.05);
}

.sidebar-footer {
    background-color: rgba(0, 0, 0, 0.1);
}
</style>

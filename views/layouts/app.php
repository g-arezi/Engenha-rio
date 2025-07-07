<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Engenha Rio' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <?php
    // Garantir que a sessão esteja ativa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Garantir que a classe Auth esteja disponível no layout
    if (!class_exists('App\Core\Auth')) {
        require_once __DIR__ . '/../../vendor/autoload.php';
    }
    
    // Inicializar a classe Session
    \App\Core\Session::start();
    
    // Verificar se há um usuário logado
    $currentUser = null;
    $isAdmin = false;
    $isAnalyst = false;
    
    try {
        // Método 1: Tentar usar a classe Auth
        $currentUser = \App\Core\Auth::user();
        $isAdmin = \App\Core\Auth::isAdmin();
        $isAnalyst = \App\Core\Auth::isAnalyst();
    } catch (Exception $e) {
        // Método 2: Fallback - usar sessão diretamente
        $userId = \App\Core\Session::get('user_id');
        if ($userId) {
            $userModel = new \App\Models\User();
            $currentUser = $userModel->find($userId);
            
            if ($currentUser) {
                $isAdmin = ($currentUser['role'] === 'admin');
                $isAnalyst = ($currentUser['role'] === 'analista');
            }
        }
    }
    ?>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --dark-bg: #2c3e50;
        }
        
        * {
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--light-bg);
        }

        .sidebar {
            background: #1a1a1a;
            min-height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            color: white;
        }

        .sidebar .brand {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar .brand img {
            width: 64px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .sidebar .brand-text {
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.5px;
            white-space: nowrap;
            color: white;
            line-height: 1.2;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1rem;
            border-radius: 0;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--accent-color);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        .sidebar .dropdown-menu {
            background-color: #2c2c2c;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-left: 1rem;
            margin-top: 0.5rem;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            min-width: 220px;
            position: static;
        }

        .sidebar .dropdown-item {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border-radius: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .sidebar .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar .dropdown-item i {
            width: 16px;
            margin-right: 8px;
        }

        .sidebar .dropdown-toggle::after {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .sidebar .dropdown-toggle.show::after {
            transform: rotate(180deg);
        }

        /* Estilos adicionais para melhor visualização */
        .sidebar .nav-item.dropdown {
            position: relative;
        }

        .sidebar .nav-item.dropdown > .nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar .nav-item.dropdown.show > .nav-link {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .table-responsive {
            border-radius: 0.375rem;
            margin: 1rem 0;
        }
        
        .container-fluid {
            max-width: none;
            padding-left: 15px;
            padding-right: 15px;
            width: 100%;
            overflow: visible;
        }
        
        .row {
            margin-left: 0;
            margin-right: 0;
        }
        
        .col-12 {
            padding-left: 0;
            padding-right: 0;
            width: 100%;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }

        .btn-group .btn {
            border-radius: 0.25rem;
        }

        .btn-group .btn:not(:first-child) {
            margin-left: 0.25rem;
        
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar .dropdown-item i {
            width: 16px;
            margin-right: 8px;
            font-size: 0.875rem;
        }

        .sidebar .nav-link.dropdown-toggle::after {
            margin-left: auto;
        }

        .sidebar .nav-link.dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar .nav-link.dropdown-toggle .fa-chevron-down {
            transition: transform 0.3s ease;
            font-size: 0.75rem;
        }

        .sidebar .nav-link.dropdown-toggle.show .fa-chevron-down {
            transform: rotate(180deg);
        }

        .main-content {
            margin-left: 260px;
            padding: 20px;
            transition: all 0.3s ease;
            min-width: calc(100% - 280px);
            width: calc(100% - 280px);
            max-width: calc(100% - 280px);
            overflow-x: visible;
            box-sizing: border-box;
            position: relative;
        }

        .top-bar {
            background: white;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.aguardando {
            background: linear-gradient(135deg, #17a2b8, #20c997);
        }

        .stat-card.pendente {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .stat-card.atrasado {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
        }

        .stat-card.aprovado {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .stat-card.aguardando,
        .stat-card.pendente,
        .stat-card.atrasado,
        .stat-card.aprovado {
            color: white;
        }

        .stat-card h5 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
        }

        .content-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--accent-color);
        }

        .history-item {
            background: var(--light-bg);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .history-item:hover {
            background: #e9ecef;
        }

        .btn-primary {
            background: var(--accent-color);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .avatar-initials {
            background: var(--accent-color);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-aguardando {
            background: #17a2b8;
            color: white;
        }

        .status-pendente {
            background: #ffc107;
            color: #212529;
        }

        .status-atrasado {
            background: #dc3545;
            color: white;
        }

        .status-aprovado {
            background: #28a745;
            color: white;
        }

        .chat-button {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--accent-color);
            color: white;
            border: none;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .chat-button:hover {
            background: #2980b9;
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (min-width: 769px) {
            .main-content {
                margin-left: 260px;
                width: calc(100% - 280px);
                min-width: calc(100% - 280px);
                max-width: calc(100% - 280px);
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <?php if (isset($showSidebar) && $showSidebar): ?>
        <nav class="sidebar">
            <div class="brand">
                <img src="/assets/images/engenhario-logo-new.png" alt="Engenha Rio" class="img-fluid" style="width: 160px; height: auto; display: block; margin: 0 auto 10px;">
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>" href="/dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'projects' ? 'active' : '' ?>" href="/projects">
                        <i class="fas fa-folder"></i>
                        Projetos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'documents' ? 'active' : '' ?>" href="/documents">
                        <i class="fas fa-file-alt"></i>
                        Documentos
                    </a>
                </li>
                
                <?php if ($isAdmin || $isAnalyst): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $activeMenu === 'admin' ? 'active' : '' ?>" href="#" id="adminDropdown" role="button" aria-expanded="false">
                        <i class="fas fa-cogs"></i>
                        Administração
                        <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" style="display: none;">
                        <li><a class="dropdown-item" href="/admin">
                            <i class="fas fa-chart-line"></i> Painel Geral
                        </a></li>
                        <?php if ($isAdmin): ?>
                        <li><a class="dropdown-item" href="/admin/reports">
                            <i class="fas fa-chart-bar"></i> Relatórios
                        </a></li>
                        <li><a class="dropdown-item" href="/admin/users">
                            <i class="fas fa-users"></i> Gerenciar Usuários
                        </a></li>
                        <li><a class="dropdown-item" href="/admin/settings">
                            <i class="fas fa-cog"></i> Configurações
                        </a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="/admin/history">
                            <i class="fas fa-history"></i> Histórico
                        </a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'profile' ? 'active' : '' ?>" href="/profile">
                        <i class="fas fa-user"></i>
                        Perfil
                    </a>
                </li>
                <li class="nav-item mt-auto">
                    <a class="nav-link text-warning" href="/logout">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

    <main class="<?= isset($showSidebar) && $showSidebar ? 'main-content' : '' ?>">
        <?php if (isset($showSidebar) && $showSidebar && !isset($hideTopBar)): ?>
            <div class="top-bar">
                <div class="welcome-text">
                    Dashboard
                </div>
                <div class="user-info">
                    <span class="badge bg-primary"><?= ucfirst($currentUser['role'] ?? 'Usuario') ?></span>
                    <div class="user-avatar">
                        <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile"><i class="fas fa-user me-2"></i>Perfil</a></li>
                            <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (\App\Core\Session::has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= \App\Core\Session::flash('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (\App\Core\Session::has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= \App\Core\Session::flash('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <?php if (isset($showSidebar) && $showSidebar): ?>
        <button class="chat-button" title="Chat de suporte">
            <i class="fas fa-comments"></i>
        </button>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.sidebar-toggle');
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Dropdown toggle for sidebar
            const dropdownToggles = document.querySelectorAll('.sidebar .dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdown = this.nextElementSibling;
                    const parentDropdown = this.parentElement;
                    const isVisible = dropdown.style.display === 'block';
                    
                    // Close other dropdowns
                    document.querySelectorAll('.sidebar .dropdown-menu').forEach(menu => {
                        menu.style.display = 'none';
                    });
                    document.querySelectorAll('.sidebar .dropdown-toggle').forEach(t => {
                        t.classList.remove('show');
                    });
                    document.querySelectorAll('.sidebar .nav-item.dropdown').forEach(item => {
                        item.classList.remove('show');
                    });
                    
                    // Toggle current dropdown
                    if (!isVisible) {
                        dropdown.style.display = 'block';
                        this.classList.add('show');
                        parentDropdown.classList.add('show');
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.sidebar .dropdown')) {
                    document.querySelectorAll('.sidebar .dropdown-menu').forEach(menu => {
                        menu.style.display = 'none';
                    });
                    document.querySelectorAll('.sidebar .dropdown-toggle').forEach(t => {
                        t.classList.remove('show');
                    });
                    document.querySelectorAll('.sidebar .nav-item.dropdown').forEach(item => {
                        item.classList.remove('show');
                    });
                }
            });
        });
    </script>
</body>
</html>

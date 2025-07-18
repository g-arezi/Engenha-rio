<!--
Sistema de Gestão de Projetos - Engenha Rio

© 2025 Engenha Rio - Todos os direitos reservados
Desenvolvido por: Gabriel Arezi
Portfolio: https://portifolio-beta-five-52.vercel.app/
GitHub: https://github.com/g-arezi

Este software é propriedade intelectual protegida.
Uso não autorizado será processado judicialmente.
-->
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
            background: #262626;
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
                
                <?php if ($isAdmin || $isAnalyst): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $activeMenu === 'admin' ? 'active' : '' ?>" href="#" id="adminDropdown" role="button" aria-expanded="false">
                        <i class="fas fa-cogs"></i>
                        Administração
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
                        <?php endif; ?>
                        <?php if ($isAdmin || $isAnalyst): ?>
                        <li><a class="dropdown-item" href="/admin/document-templates">
                            <i class="fas fa-file-text"></i> Templates
                        </a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="/tickets">
                            <i class="fas fa-ticket-alt"></i> Gerenciar Tickets
                        </a></li>
                        <?php if ($isAdmin): ?>
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
            </ul>
        </nav>
    <?php endif; ?>

    <main class="<?= isset($showSidebar) && $showSidebar ? 'main-content' : '' ?>">
        <?php if (isset($showSidebar) && $showSidebar && !isset($hideTopBar)): ?>
            <div class="top-bar">
                <div class="welcome-text">
                    <?= $pageTitle ?? $title ?? 'Dashboard' ?>
                </div>
                <div class="user-info">
                    <span class="badge bg-primary"><?= ucfirst($currentUser['role'] ?? 'Usuario') ?></span>
                    <div class="user-avatar">
                        <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown">
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
        <button class="chat-button" title="Chat de suporte" onclick="openTicketModal()">
            <i class="fas fa-comments"></i>
        </button>
        
        <!-- Modal de Tickets -->
        <div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ticketModalLabel">
                            <i class="fas fa-ticket-alt me-2"></i>Sistema de Suporte
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Aba para criar novo ticket -->
                        <div id="newTicketTab">
                            <h6 class="mb-3">Criar Novo Ticket</h6>
                            <form id="ticketForm">
                                <div class="mb-3">
                                    <label for="ticketProject" class="form-label">Projeto <span class="text-danger">*</span></label>
                                    <select class="form-select" id="ticketProject" required>
                                        <option value="">Selecione o projeto...</option>
                                        <!-- Projetos serão carregados via JavaScript -->
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="ticketSubject" class="form-label">Assunto</label>
                                    <input type="text" class="form-control" id="ticketSubject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ticketPriority" class="form-label">Prioridade</label>
                                    <select class="form-select" id="ticketPriority">
                                        <option value="baixa">Baixa</option>
                                        <option value="media" selected>Média</option>
                                        <option value="alta">Alta</option>
                                        <option value="urgente">Urgente</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="ticketDescription" class="form-label">Descrição do problema</label>
                                    <textarea class="form-control" id="ticketDescription" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>Enviar Ticket
                                </button>
                                <button type="button" class="btn btn-secondary ms-2" onclick="showMyTickets()">
                                    <i class="fas fa-list me-1"></i>Meus Tickets
                                </button>
                            </form>
                        </div>
                        
                        <!-- Aba para ver meus tickets -->
                        <div id="myTicketsTab" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Meus Tickets</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="showNewTicketForm()">
                                    <i class="fas fa-plus me-1"></i>Novo Ticket
                                </button>
                            </div>
                            <div id="ticketsList">
                                <!-- Lista de tickets será carregada via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Modal de Detalhes do Ticket para Usuário -->
    <div class="modal fade" id="ticketDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-ticket-alt me-2"></i>
                        Ticket #<span id="detailTicketId"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Detalhes do Ticket -->
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between">
                            <div>
                                <strong id="detailTicketSubject"></strong>
                                <br>
                                <small class="text-info">
                                    <i class="fas fa-folder"></i> <span id="detailTicketProject"></span>
                                </small>
                                <br>
                                <small class="text-muted">
                                    Criado em: <span id="detailTicketDate"></span>
                                </small>
                            </div>
                            <div>
                                <span class="badge" id="detailTicketStatus"></span>
                                <span class="badge ms-1" id="detailTicketPriority"></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Descrição do problema:</strong>
                            </div>
                            <div id="detailTicketMessage" class="text-muted" style="white-space: pre-wrap;"></div>
                        </div>
                    </div>

                    <!-- Histórico de Respostas -->
                    <div id="detailTicketResponses"></div>

                    <!-- Formulário para Cliente Responder (se aplicável) -->
                    <div id="clientResponseSection" class="card" style="display: none;">
                        <div class="card-header">
                            <strong>Adicionar Comentário</strong>
                        </div>
                        <div class="card-body">
                            <form id="clientResponseForm">
                                <div class="mb-3">
                                    <textarea class="form-control" id="clientResponseMessage" rows="3" placeholder="Digite seu comentário..."></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-reply me-1"></i>Enviar Comentário
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            
            // Sistema de Tickets
            window.openTicketModal = function() {
                new bootstrap.Modal(document.getElementById('ticketModal')).show();
                showNewTicketForm();
            }
            
            window.showNewTicketForm = function() {
                document.getElementById('newTicketTab').style.display = 'block';
                document.getElementById('myTicketsTab').style.display = 'none';
                document.getElementById('ticketModalLabel').innerHTML = '<i class="fas fa-ticket-alt me-2"></i>Sistema de Suporte';
                
                // Carregar projetos do usuário
                loadUserProjects();
            }
            
            // Função para carregar projetos do usuário
            function loadUserProjects() {
                fetch('/projects', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const projectSelect = document.getElementById('ticketProject');
                    projectSelect.innerHTML = '<option value="">Selecione o projeto...</option>';
                    
                    if (data.success && data.projects) {
                        data.projects.forEach(project => {
                            const option = document.createElement('option');
                            option.value = project.id;
                            option.textContent = project.name;
                            projectSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar projetos:', error);
                });
            }
            
            window.showMyTickets = function() {
                document.getElementById('newTicketTab').style.display = 'none';
                document.getElementById('myTicketsTab').style.display = 'block';
                document.getElementById('ticketModalLabel').innerHTML = '<i class="fas fa-list me-2"></i>Meus Tickets';
                loadMyTickets();
            }
            
            function loadMyTickets() {
                fetch('/api/tickets/my', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayTickets(data.tickets);
                    } else {
                        document.getElementById('ticketsList').innerHTML = '<div class="alert alert-danger">Erro ao carregar tickets</div>';
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    document.getElementById('ticketsList').innerHTML = '<div class="alert alert-danger">Erro ao carregar tickets</div>';
                });
            }
            
            function displayTickets(tickets) {
                const ticketsList = document.getElementById('ticketsList');
                
                if (tickets.length === 0) {
                    ticketsList.innerHTML = '<div class="alert alert-info">Você ainda não possui tickets</div>';
                    return;
                }
                
                let html = '';
                tickets.forEach(ticket => {
                    const statusClass = getStatusClass(ticket.status);
                    const priorityClass = getPriorityClass(ticket.priority);
                    
                    html += `
                        <div class="card mb-2">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1">${ticket.subject}</h6>
                                        ${ticket.project_name ? `<small class="text-info mb-1"><i class="fas fa-folder"></i> ${ticket.project_name}</small><br>` : ''}
                                        <p class="card-text text-muted small mb-2">${ticket.description.substring(0, 100)}${ticket.description.length > 100 ? '...' : ''}</p>
                                        <div class="d-flex gap-2">
                                            <span class="badge ${statusClass}">${getStatusLabel(ticket.status)}</span>
                                            <span class="badge ${priorityClass}">${getPriorityLabel(ticket.priority)}</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">${formatDate(ticket.created_at)}</small>
                                        <br>
                                        <small class="text-muted">ID: ${ticket.id}</small>
                                        ${ticket.responses && ticket.responses.length > 0 ? `<br><small class="badge bg-info">${ticket.responses.length} resposta(s)</small>` : ''}
                                        <br>
                                        <button class="btn btn-sm btn-outline-primary mt-1" onclick="viewTicketDetails('${ticket.id}')">
                                            <i class="fas fa-eye"></i> Ver Detalhes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                ticketsList.innerHTML = html;
            }
            
            function getStatusClass(status) {
                const classes = {
                    'aberto': 'bg-warning',
                    'em_andamento': 'bg-info',
                    'resolvido': 'bg-success',
                    'fechado': 'bg-secondary'
                };
                return classes[status] || 'bg-secondary';
            }
            
            function getPriorityClass(priority) {
                const classes = {
                    'baixa': 'bg-light text-dark',
                    'media': 'bg-primary',
                    'alta': 'bg-warning text-dark',
                    'urgente': 'bg-danger'
                };
                return classes[priority] || 'bg-primary';
            }
            
            function getStatusLabel(status) {
                const labels = {
                    'aberto': 'Aberto',
                    'em_andamento': 'Em Andamento',
                    'resolvido': 'Resolvido',
                    'fechado': 'Fechado'
                };
                return labels[status] || status;
            }
            
            function getPriorityLabel(priority) {
                const labels = {
                    'baixa': 'Baixa',
                    'media': 'Média',
                    'alta': 'Alta',
                    'urgente': 'Urgente'
                };
                return labels[priority] || priority;
            }
            
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleString('pt-BR');
            }
            
            // Submit do formulário de ticket
            const ticketForm = document.getElementById('ticketForm');
            if (ticketForm) {
                ticketForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const project = document.getElementById('ticketProject').value;
                    const subject = document.getElementById('ticketSubject').value;
                    const description = document.getElementById('ticketDescription').value;
                    const priority = document.getElementById('ticketPriority').value;
                    
                    if (!project || !subject || !description) {
                        alert('Por favor, preencha todos os campos obrigatórios');
                        return;
                    }
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enviando...';
                    submitBtn.disabled = true;
                    
                    fetch('/api/tickets/create', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            project_id: project,
                            subject: subject,
                            description: description,
                            priority: priority
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Ticket criado com sucesso! ID: ' + data.ticketId);
                            document.getElementById('ticketForm').reset();
                            showMyTickets(); // Mostrar a lista de tickets
                        } else {
                            alert('Erro: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao criar ticket');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }

            // Função para ver detalhes do ticket
            window.viewTicketDetails = function(ticketId) {
                fetch(`/tickets/${ticketId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const ticket = data.ticket;
                        
                        // Preencher detalhes básicos
                        document.getElementById('detailTicketId').textContent = ticket.id;
                        document.getElementById('detailTicketSubject').textContent = ticket.subject;
                        document.getElementById('detailTicketProject').textContent = ticket.project_name || 'Projeto não especificado';
                        document.getElementById('detailTicketDate').textContent = new Date(ticket.created_at).toLocaleString('pt-BR');
                        document.getElementById('detailTicketMessage').textContent = ticket.description;
                        document.getElementById('detailTicketStatus').innerHTML = getStatusBadge(ticket.status);
                        document.getElementById('detailTicketPriority').innerHTML = getPriorityBadge(ticket.priority);
                        
                        // Carregar respostas
                        displayTicketResponses(ticket.responses || []);
                        
                        // Mostrar seção de resposta se ticket não estiver fechado
                        const responseSection = document.getElementById('clientResponseSection');
                        if (ticket.status !== 'fechado') {
                            responseSection.style.display = 'block';
                            setupClientResponseForm(ticket.id);
                        } else {
                            responseSection.style.display = 'none';
                        }
                        
                        // Mostrar modal
                        new bootstrap.Modal(document.getElementById('ticketDetailsModal')).show();
                    } else {
                        alert('Erro ao carregar ticket: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao carregar ticket');
                });
            };

            // Função para exibir respostas do ticket
            function displayTicketResponses(responses) {
                const container = document.getElementById('detailTicketResponses');
                
                if (responses.length === 0) {
                    container.innerHTML = '<div class="text-center text-muted py-3"><i class="fas fa-comments"></i><br>Nenhuma resposta ainda</div>';
                    return;
                }
                
                let html = '<h6 class="mb-3">Histórico de Conversas:</h6>';
                
                responses.forEach(response => {
                    const date = new Date(response.created_at).toLocaleString('pt-BR');
                    const roleClass = response.user_role === 'admin' ? 'text-danger' : 
                                     response.user_role === 'analista' ? 'text-warning' : 'text-primary';
                    const roleText = response.user_role === 'admin' ? '(Administrador)' : 
                                    response.user_role === 'analista' ? '(Analista)' : '(Você)';
                    
                    html += `
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong class="${roleClass}">
                                        ${response.user_name} ${roleText}
                                    </strong>
                                    <small class="text-muted">${date}</small>
                                </div>
                                <div class="text-muted" style="white-space: pre-wrap;">${response.message}</div>
                            </div>
                        </div>
                    `;
                });
                
                container.innerHTML = html;
            }

            // Função para configurar formulário de resposta do cliente
            function setupClientResponseForm(ticketId) {
                const form = document.getElementById('clientResponseForm');
                
                // Remove listeners anteriores
                const newForm = form.cloneNode(true);
                form.parentNode.replaceChild(newForm, form);
                
                // Adiciona novo listener
                document.getElementById('clientResponseForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const message = document.getElementById('clientResponseMessage').value.trim();
                    
                    if (!message) {
                        alert('Por favor, digite um comentário');
                        return;
                    }
                    
                    fetch(`/api/tickets/${ticketId}/respond`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ message: message })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Comentário enviado com sucesso!');
                            document.getElementById('clientResponseMessage').value = '';
                            // Recarregar detalhes do ticket
                            viewTicketDetails(ticketId);
                        } else {
                            alert('Erro ao enviar comentário: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao enviar comentário');
                    });
                });
            }

            // Funções auxiliares para badges
            function getStatusBadge(status) {
                const badges = {
                    'aberto': '<span class="badge bg-success">Aberto</span>',
                    'em_andamento': '<span class="badge bg-warning">Em Andamento</span>',
                    'resolvido': '<span class="badge bg-info">Resolvido</span>',
                    'fechado': '<span class="badge bg-secondary">Fechado</span>'
                };
                return badges[status] || '<span class="badge bg-secondary">' + status + '</span>';
            }

            function getPriorityBadge(priority) {
                const badges = {
                    'baixa': '<span class="badge bg-light text-dark">Baixa</span>',
                    'media': '<span class="badge bg-primary">Média</span>',
                    'alta': '<span class="badge bg-warning">Alta</span>',
                    'urgente': '<span class="badge bg-danger">Urgente</span>'
                };
                return badges[priority] || '<span class="badge bg-secondary">' + priority + '</span>';
            }
        });
    </script>
</body>
</html>

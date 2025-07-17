<?php
$title = 'Editar Projeto';
$activeMenu = 'projects';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Engenha-rio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .main-content {
            margin-left: 280px;
            padding: 20px;
        }
        .project-card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .project-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .status-badge {
            font-size: 0.8em;
            padding: 0.3em 0.8em;
            border-radius: 20px;
        }
        .priority-badge {
            font-size: 0.8em;
            padding: 0.3em 0.8em;
            border-radius: 20px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            .sidebar {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body>
    
    <?php include 'views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3 mb-0">
                                <i class="fas fa-edit me-2"></i>
                                <?= $title ?>
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="/projects">Projetos</a></li>
                                    <li class="breadcrumb-item"><a href="/projects/<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></a></li>
                                    <li class="breadcrumb-item active">Editar</li>
                                </ol>
                            </nav>
                        </div>
                        <div>
                            <a href="/projects/<?= $project['id'] ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Voltar
                            </a>
                        </div>
                    </div>
                    
                    <?php if (isset($_SESSION['project_message'])): ?>
                        <div class="alert alert-<?= $_SESSION['project_success'] ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                            <?= $_SESSION['project_message'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['project_message'], $_SESSION['project_success']); ?>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="project-card card">
                                <div class="project-header card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-project-diagram me-2"></i>
                                        Informações do Projeto
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="/projects/<?= $project['id'] ?>" id="projectForm">
                                        <input type="hidden" name="_method" value="PUT">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Nome do Projeto</label>
                                                    <input type="text" class="form-control" id="name" name="name" 
                                                           value="<?= htmlspecialchars($project['name']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <?php if ($user['role'] === 'admin' || $user['role'] === 'analista'): ?>
                                                    <select class="form-select" id="status" name="status">
                                                        <option value="aguardando" <?= $project['status'] === 'aguardando' ? 'selected' : '' ?>>Aguardando</option>
                                                        <option value="pendente" <?= $project['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                                        <option value="em_andamento" <?= $project['status'] === 'em_andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                                        <option value="aprovado" <?= $project['status'] === 'aprovado' ? 'selected' : '' ?>>Aprovado</option>
                                                        <option value="concluido" <?= $project['status'] === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                                                        <option value="cancelado" <?= $project['status'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                                        <option value="atrasado" <?= $project['status'] === 'atrasado' ? 'selected' : '' ?>>Atrasado</option>
                                                    </select>
                                                    <?php else: ?>
                                                    <input type="text" class="form-control" value="<?= ucfirst(str_replace('_', ' ', $project['status'])) ?>" readonly>
                                                    <small class="form-text text-muted">Apenas administradores e analistas podem alterar o status.</small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="priority" class="form-label">Prioridade</label>
                                                    <?php if ($user['role'] === 'admin' || $user['role'] === 'analista'): ?>
                                                    <select class="form-select" id="priority" name="priority">
                                                        <option value="baixa" <?= $project['priority'] === 'baixa' ? 'selected' : '' ?>>Baixa</option>
                                                        <option value="normal" <?= $project['priority'] === 'normal' ? 'selected' : '' ?>>Normal</option>
                                                        <option value="alta" <?= $project['priority'] === 'alta' ? 'selected' : '' ?>>Alta</option>
                                                        <option value="urgente" <?= $project['priority'] === 'urgente' ? 'selected' : '' ?>>Urgente</option>
                                                    </select>
                                                    <?php else: ?>
                                                    <input type="text" class="form-control" value="<?= ucfirst($project['priority']) ?>" readonly>
                                                    <small class="form-text text-muted">Apenas administradores e analistas podem alterar a prioridade.</small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="deadline" class="form-label">Prazo</label>
                                                    <?php if ($user['role'] === 'admin' || $user['role'] === 'analista'): ?>
                                                    <input type="date" class="form-control" id="deadline" name="deadline" 
                                                           value="<?= $project['deadline'] ?>">
                                                    <?php else: ?>
                                                    <input type="text" class="form-control" value="<?= $project['deadline'] ? date('d/m/Y', strtotime($project['deadline'])) : 'Não definido' ?>" readonly>
                                                    <small class="form-text text-muted">Apenas administradores e analistas podem alterar o prazo.</small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Descrição</label>
                                            <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($project['description']) ?></textarea>
                                        </div>
                                        
                                        <?php if ($user['role'] === 'admin' || $user['role'] === 'analista'): ?>
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Observações (Interno)</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Notas internas para administradores e analistas..."><?= htmlspecialchars($project['notes'] ?? '') ?></textarea>
                                            <small class="form-text text-muted">Este campo é visível apenas para administradores e analistas.</small>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="client" class="form-label">Cliente</label>
                                                    <input type="text" class="form-control" id="client" name="client" 
                                                           value="<?= htmlspecialchars($project['client_name'] ?? '') ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="analyst" class="form-label">Analista</label>
                                                    <input type="text" class="form-control" id="analyst" name="analyst" 
                                                           value="<?= htmlspecialchars($project['analyst_name'] ?? '') ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>
                                                Salvar Alterações
                                            </button>
                                            <a href="/projects/<?= $project['id'] ?>" class="btn btn-secondary">
                                                <i class="fas fa-times me-2"></i>
                                                Cancelar
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Informações Adicionais
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Status Atual:</strong>
                                        <span class="status-badge bg-<?= $project['status'] === 'concluido' ? 'success' : 
                                                                        ($project['status'] === 'atrasado' ? 'danger' : 
                                                                        ($project['status'] === 'em_andamento' ? 'info' : 'warning')) ?> text-white ms-2">
                                            <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Prioridade:</strong>
                                        <span class="priority-badge bg-<?= $project['priority'] === 'urgente' ? 'danger' : 
                                                                          ($project['priority'] === 'alta' ? 'warning' : 
                                                                          ($project['priority'] === 'normal' ? 'info' : 'secondary')) ?> text-white ms-2">
                                            <?= ucfirst($project['priority']) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Criado em:</strong><br>
                                        <?= date('d/m/Y H:i', strtotime($project['created_at'])) ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Última atualização:</strong><br>
                                        <?= date('d/m/Y H:i', strtotime($project['updated_at'])) ?>
                                    </div>
                                    
                                    <?php if (isset($project['deadline'])): ?>
                                    <div class="mb-3">
                                        <strong>Prazo:</strong><br>
                                        <?= date('d/m/Y', strtotime($project['deadline'])) ?>
                                        <?php 
                                        $now = new DateTime();
                                        $deadline = new DateTime($project['deadline']);
                                        $diff = $now->diff($deadline);
                                        $daysLeft = $diff->invert ? -$diff->days : $diff->days;
                                        ?>
                                        <small class="text-<?= $daysLeft < 0 ? 'danger' : ($daysLeft < 7 ? 'warning' : 'success') ?>">
                                            <?= $daysLeft < 0 ? 'Atrasado ' . abs($daysLeft) . ' dias' : 
                                                ($daysLeft == 0 ? 'Vence hoje' : 'Faltam ' . $daysLeft . ' dias') ?>
                                        </small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-lightbulb me-2"></i>
                                        Dicas
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Mantenha a descrição clara e detalhada</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Atualize o status regularmente</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Configure prazos realistas</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Monitore o progresso constantemente</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('projectForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const cancelBtn = this.querySelector('a[href*="/projects/"]');
            
            // Validar campos obrigatórios
            const name = document.getElementById('name').value.trim();
            const description = document.getElementById('description').value.trim();
            
            if (!name) {
                e.preventDefault();
                alert('Por favor, preencha o nome do projeto.');
                document.getElementById('name').focus();
                return;
            }
            
            if (description.length < 10) {
                e.preventDefault();
                alert('A descrição deve ter pelo menos 10 caracteres.');
                document.getElementById('description').focus();
                return;
            }
            
            // Desabilitar botões durante o envio
            submitBtn.disabled = true;
            cancelBtn.style.pointerEvents = 'none';
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...';
        });
        
        // Confirmar ao sair sem salvar
        let formChanged = false;
        const form = document.getElementById('projectForm');
        const initialFormData = new FormData(form);
        
        form.addEventListener('input', function() {
            formChanged = true;
        });
        
        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
        
        // Remover aviso ao enviar formulário
        form.addEventListener('submit', function() {
            formChanged = false;
        });
        
        // Auto-ajustar altura do textarea
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }
        
        document.getElementById('description').addEventListener('input', function() {
            autoResize(this);
        });
        
        const notesField = document.getElementById('notes');
        if (notesField) {
            notesField.addEventListener('input', function() {
                autoResize(this);
            });
        }
        
        // Inicializar altura dos textareas
        window.addEventListener('load', function() {
            autoResize(document.getElementById('description'));
            if (notesField) {
                autoResize(notesField);
            }
        });
    </script>
</body>
</html>

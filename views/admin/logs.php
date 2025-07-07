<?php
$title = 'Logs do Sistema';
$activeMenu = 'admin';
$showSidebar = true;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1><i class="fas fa-file-alt"></i> Logs do Sistema</h1>
                <p>Visualize os logs e atividades do sistema</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-list"></i> Registros do Sistema</h5>
                        <button type="button" class="btn btn-primary btn-sm" onclick="refreshLogs()">
                            <i class="fas fa-sync-alt"></i> Atualizar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($logs)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Nenhum log encontrado. O sistema ainda não gerou registros de atividade.
                    </div>
                    <?php else: ?>
                    <div class="log-container" style="max-height: 600px; overflow-y: auto; font-family: monospace; font-size: 0.85rem;">
                        <?php foreach ($logs as $log): ?>
                        <div class="log-entry p-2 mb-1 bg-light border-left" style="border-left: 3px solid #007bff;">
                            <?= htmlspecialchars($log) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line"></i> Estatísticas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-primary"><?= count($logs) ?></h3>
                                <p class="text-muted">Registros Exibidos</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-success"><?= date('H:i:s') ?></h3>
                                <p class="text-muted">Última Atualização</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-tools"></i> Ações</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-warning" onclick="clearLogs()">
                            <i class="fas fa-trash"></i> Limpar Logs
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="downloadLogs()">
                            <i class="fas fa-download"></i> Baixar Logs
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshLogs() {
    window.location.reload();
}

function clearLogs() {
    if (confirm('Tem certeza que deseja limpar todos os logs? Esta ação não pode ser desfeita.')) {
        fetch('/admin/logs/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Logs limpos com sucesso!');
                window.location.reload();
            } else {
                alert('Erro ao limpar logs: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erro ao limpar logs.');
        });
    }
}

function downloadLogs() {
    window.location.href = '/admin/logs/download';
}
</script>

<style>
.log-entry {
    border-radius: 4px;
    transition: background-color 0.2s;
}

.log-entry:hover {
    background-color: #f8f9fa !important;
}

.log-container {
    background-color: #f8f9fa;
    border-radius: 6px;
    padding: 1rem;
}

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
</style>

<?php
// Página de visualização de logs - apenas para administradores

require_once 'config/environment.php';
require_once 'config/security.php';
require_once 'config/logger.php';
require_once 'vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;

Session::start();

// Verificar se é administrador
if (!Auth::check() || Auth::user()['role'] !== 'admin') {
    http_response_code(403);
    die('Acesso negado. Apenas administradores podem visualizar logs.');
}

$action = $_GET['action'] ?? 'list';
$file = $_GET['file'] ?? '';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de Logs - Engenha Rio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .log-content {
            background: #1e1e1e;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            height: 500px;
            overflow-y: auto;
            padding: 15px;
            border-radius: 5px;
        }
        .log-error { color: #ff6b6b; }
        .log-warning { color: #ffd93d; }
        .log-info { color: #74c0fc; }
        .log-debug { color: #51cf66; }
        .log-system { color: #ff8cc8; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">📋 Visualizador de Logs</h1>
                    <div>
                        <a href="/dashboard" class="btn btn-outline-secondary">Voltar ao Dashboard</a>
                        <button onclick="location.reload()" class="btn btn-primary">🔄 Atualizar</button>
                    </div>
                </div>
                
                <?php if ($action === 'list'): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">📁 Arquivos de Log Disponíveis</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $log_files = CustomLogger::getLogFiles();
                            
                            if (empty($log_files)) {
                                echo '<p class="text-muted">Nenhum arquivo de log encontrado.</p>';
                            } else {
                                echo '<div class="list-group">';
                                foreach ($log_files as $log_file) {
                                    $file_path = __DIR__ . '/logs/' . $log_file;
                                    $size = file_exists($file_path) ? filesize($file_path) : 0;
                                    $modified = file_exists($file_path) ? date('Y-m-d H:i:s', filemtime($file_path)) : 'N/A';
                                    
                                    echo '<a href="?action=view&file=' . urlencode($log_file) . '" class="list-group-item list-group-item-action">';
                                    echo '<div class="d-flex w-100 justify-content-between">';
                                    echo '<h6 class="mb-1">📄 ' . htmlspecialchars($log_file) . '</h6>';
                                    echo '<small>' . number_format($size) . ' bytes</small>';
                                    echo '</div>';
                                    echo '<small>Última modificação: ' . $modified . '</small>';
                                    echo '</a>';
                                }
                                echo '</div>';
                            }
                            ?>
                            
                            <hr>
                            <h6>🧪 Gerar Log de Teste</h6>
                            <div class="btn-group" role="group">
                                <button onclick="generateTestLog('info')" class="btn btn-sm btn-info">Info</button>
                                <button onclick="generateTestLog('warning')" class="btn btn-sm btn-warning">Warning</button>
                                <button onclick="generateTestLog('error')" class="btn btn-sm btn-danger">Error</button>
                                <button onclick="generateTestLog('debug')" class="btn btn-sm btn-success">Debug</button>
                            </div>
                        </div>
                    </div>
                    
                <?php elseif ($action === 'view' && $file): ?>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">📄 <?= htmlspecialchars($file) ?></h5>
                            <div>
                                <a href="?action=list" class="btn btn-sm btn-secondary">← Voltar</a>
                                <button onclick="downloadLog('<?= htmlspecialchars($file) ?>')" class="btn btn-sm btn-primary">💾 Download</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="log-content">
                                <?php
                                $content = CustomLogger::getLogContent($file, 200);
                                
                                // Colorir logs por tipo
                                $content = htmlspecialchars($content);
                                $content = preg_replace('/\[ERROR\]/', '<span class="log-error">[ERROR]</span>', $content);
                                $content = preg_replace('/\[WARNING\]/', '<span class="log-warning">[WARNING]</span>', $content);
                                $content = preg_replace('/\[INFO\]/', '<span class="log-info">[INFO]</span>', $content);
                                $content = preg_replace('/\[DEBUG\]/', '<span class="log-debug">[DEBUG]</span>', $content);
                                $content = preg_replace('/\[SYSTEM\]/', '<span class="log-system">[SYSTEM]</span>', $content);
                                
                                echo nl2br($content);
                                ?>
                            </div>
                        </div>
                    </div>
                    
                <?php elseif ($action === 'test'): ?>
                    <?php
                    $type = $_POST['type'] ?? 'info';
                    $message = "Log de teste gerado pelo administrador em " . date('Y-m-d H:i:s');
                    
                    switch ($type) {
                        case 'error':
                            CustomLogger::error($message);
                            break;
                        case 'warning':
                            CustomLogger::warning($message);
                            break;
                        case 'debug':
                            CustomLogger::debug($message);
                            break;
                        default:
                            CustomLogger::info($message);
                    }
                    
                    echo '<div class="alert alert-success">Log de teste gerado com sucesso!</div>';
                    echo '<script>setTimeout(() => window.location.href = "?action=list", 1000);</script>';
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function generateTestLog(type) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '?action=test';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'type';
            input.value = type;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
        
        function downloadLog(filename) {
            window.open('?action=download&file=' + encodeURIComponent(filename), '_blank');
        }
        
        // Auto-scroll para o final dos logs
        const logContent = document.querySelector('.log-content');
        if (logContent) {
            logContent.scrollTop = logContent.scrollHeight;
        }
    </script>
</body>
</html>

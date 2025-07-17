<?php
/**
 * Teste do Sistema de Log Personalizado
 * Este arquivo testa todas as funcionalidades do nosso CustomLogger
 */

// Verificar se estamos em desenvolvimento ou produção
$isDev = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false || 
          strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') !== false);

if (!$isDev) {
    // Em produção, verificar se o usuário é admin
    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'administrador') {
        header('HTTP/1.1 403 Forbidden');
        exit('Acesso negado');
    }
}

// Carregar sistema de log
require_once 'config/logger.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste do Sistema de Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .log-test-result { margin: 10px 0; padding: 10px; border-radius: 5px; }
        .success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>🔧 Teste do Sistema de Log Personalizado</h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Testes de Funcionalidade</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        echo "<h4>1. Verificação da Classe CustomLogger</h4>";
                        if (class_exists('CustomLogger')) {
                            echo "<div class='log-test-result success'>✅ CustomLogger carregado com sucesso</div>";
                        } else {
                            echo "<div class='log-test-result error'>❌ CustomLogger não encontrado</div>";
                            exit;
                        }
                        
                        echo "<h4>2. Teste de Logs por Tipo</h4>";
                        
                        // Teste de cada tipo de log
                        $tests = [
                            'error' => 'Teste de log de erro - ' . date('Y-m-d H:i:s'),
                            'warning' => 'Teste de log de aviso - ' . date('Y-m-d H:i:s'),
                            'info' => 'Teste de log de informação - ' . date('Y-m-d H:i:s'),
                            'debug' => 'Teste de log de debug - ' . date('Y-m-d H:i:s'),
                            'system' => 'Teste de log de sistema - ' . date('Y-m-d H:i:s')
                        ];
                        
                        foreach ($tests as $type => $message) {
                            $result = CustomLogger::$type($message);
                            $status = $result ? '✅ Sucesso' : '❌ Falhou';
                            $class = $result ? 'success' : 'error';
                            echo "<div class='log-test-result $class'>Log $type: $status</div>";
                        }
                        
                        echo "<h4>3. Teste de Error Handler</h4>";
                        // Testar captura de erro
                        $old_level = error_reporting(E_ALL);
                        $result = CustomLogger::error("Teste do error handler automático");
                        if ($result) {
                            echo "<div class='log-test-result success'>✅ Error handler funcionando</div>";
                        } else {
                            echo "<div class='log-test-result error'>❌ Error handler com problemas</div>";
                        }
                        error_reporting($old_level);
                        
                        echo "<h4>4. Arquivos de Log Gerados</h4>";
                        $logFiles = CustomLogger::getLogFiles();
                        if (!empty($logFiles)) {
                            echo "<div class='log-test-result success'>";
                            echo "<strong>Arquivos encontrados:</strong><br>";
                            foreach ($logFiles as $file) {
                                $size = file_exists("logs/$file") ? filesize("logs/$file") : 0;
                                echo "📄 $file (" . number_format($size) . " bytes)<br>";
                            }
                            echo "</div>";
                        } else {
                            echo "<div class='log-test-result error'>❌ Nenhum arquivo de log encontrado</div>";
                        }
                        
                        echo "<h4>5. Teste de Performance</h4>";
                        $start = microtime(true);
                        for ($i = 0; $i < 10; $i++) {
                            CustomLogger::info("Teste de performance #$i");
                        }
                        $end = microtime(true);
                        $duration = ($end - $start) * 1000;
                        echo "<div class='log-test-result info'>⏱️ 10 logs gravados em " . number_format($duration, 2) . "ms</div>";
                        
                        echo "<h4>6. Informações do Sistema</h4>";
                        echo "<div class='log-test-result info'>";
                        echo "<strong>PHP Version:</strong> " . PHP_VERSION . "<br>";
                        echo "<strong>Log Directory:</strong> " . realpath('logs') . "<br>";
                        echo "<strong>Directory Writable:</strong> " . (is_writable('logs') ? 'Sim' : 'Não') . "<br>";
                        echo "<strong>Error Reporting:</strong> " . error_reporting() . "<br>";
                        echo "<strong>Log Errors (ini):</strong> " . (ini_get('log_errors') ? 'Habilitado' : 'Desabilitado') . "<br>";
                        echo "<strong>Error Log (ini):</strong> " . ini_get('error_log') . "<br>";
                        echo "</div>";
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Ações Rápidas</h3>
                    </div>
                    <div class="card-body">
                        <a href="visualizar-logs.php" class="btn btn-primary mb-2 w-100">📋 Ver Logs Detalhados</a>
                        <a href="diagnostico.php" class="btn btn-info mb-2 w-100">🔍 Diagnóstico Completo</a>
                        <a href="index.php" class="btn btn-success mb-2 w-100">🏠 Voltar ao Sistema</a>
                        
                        <hr>
                        
                        <h5>Teste Manual</h5>
                        <form method="post" action="">
                            <div class="mb-2">
                                <select name="log_type" class="form-select">
                                    <option value="info">Info</option>
                                    <option value="error">Error</option>
                                    <option value="warning">Warning</option>
                                    <option value="debug">Debug</option>
                                    <option value="system">System</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <textarea name="log_message" class="form-control" placeholder="Mensagem do log..." rows="3"></textarea>
                            </div>
                            <button type="submit" name="manual_test" class="btn btn-outline-primary w-100">Enviar Log</button>
                        </form>
                        
                        <?php
                        if (isset($_POST['manual_test']) && !empty($_POST['log_message'])) {
                            $type = $_POST['log_type'] ?? 'info';
                            $message = $_POST['log_message'];
                            $result = CustomLogger::$type($message);
                            $status = $result ? 'sucesso' : 'erro';
                            echo "<div class='alert alert-" . ($result ? 'success' : 'danger') . " mt-2'>";
                            echo "Log enviado com $status!";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

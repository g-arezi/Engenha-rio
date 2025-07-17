<?php
/**
 * Simulador de Erros para Teste do Sistema de Log
 * Este arquivo simula diferentes tipos de erros para testar a captura
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
    <title>Simulador de Erros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>⚠️ Simulador de Erros para Teste do Log</h1>
        
        <div class="alert alert-warning">
            <strong>Atenção:</strong> Este arquivo é apenas para teste do sistema de log. 
            Os erros são simulados e capturados pelo nosso CustomLogger.
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Testes de Erro</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['test'])) {
                            echo "<h5>Resultado do Teste:</h5>";
                            
                            switch ($_GET['test']) {
                                case 'notice':
                                    echo "<div class='alert alert-info'>Gerando Notice...</div>";
                                    // Gerar um notice
                                    echo $variavel_inexistente; // Isso gera um notice
                                    CustomLogger::warning("Notice capturado: Undefined variable");
                                    echo "<div class='alert alert-success'>Notice gerado e capturado!</div>";
                                    break;
                                    
                                case 'warning':
                                    echo "<div class='alert alert-info'>Gerando Warning...</div>";
                                    // Gerar um warning
                                    include 'arquivo_inexistente.php'; // Isso gera um warning
                                    CustomLogger::warning("Warning capturado: Failed to include file");
                                    echo "<div class='alert alert-success'>Warning gerado e capturado!</div>";
                                    break;
                                    
                                case 'error':
                                    echo "<div class='alert alert-info'>Gerando Error...</div>";
                                    try {
                                        // Simular um erro
                                        throw new Exception("Erro simulado para teste do sistema de log");
                                    } catch (Exception $e) {
                                        CustomLogger::error("Exception capturada: " . $e->getMessage());
                                        echo "<div class='alert alert-success'>Exception gerada e capturada!</div>";
                                    }
                                    break;
                                    
                                case 'database':
                                    echo "<div class='alert alert-info'>Simulando erro de banco...</div>";
                                    try {
                                        // Simular erro de conexão com banco
                                        new PDO('mysql:host=servidor_inexistente;dbname=teste', 'user', 'pass');
                                    } catch (PDOException $e) {
                                        CustomLogger::error("Erro de banco simulado: " . $e->getMessage());
                                        echo "<div class='alert alert-success'>Erro de banco capturado!</div>";
                                    }
                                    break;
                                    
                                case 'memory':
                                    echo "<div class='alert alert-info'>Simulando problema de memória...</div>";
                                    // Log de uso de memória
                                    $memory_usage = memory_get_usage(true);
                                    $memory_peak = memory_get_peak_usage(true);
                                    CustomLogger::system("Uso de memória: " . number_format($memory_usage / 1024 / 1024, 2) . "MB | Pico: " . number_format($memory_peak / 1024 / 1024, 2) . "MB");
                                    echo "<div class='alert alert-success'>Informações de memória registradas!</div>";
                                    break;
                                    
                                case 'security':
                                    echo "<div class='alert alert-info'>Simulando tentativa de acesso suspeito...</div>";
                                    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                                    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
                                    CustomLogger::warning("Tentativa de acesso suspeito - IP: $ip | User-Agent: $user_agent");
                                    echo "<div class='alert alert-success'>Log de segurança registrado!</div>";
                                    break;
                                    
                                case 'performance':
                                    echo "<div class='alert alert-info'>Testando log de performance...</div>";
                                    $start = microtime(true);
                                    
                                    // Simular processo lento
                                    usleep(100000); // 100ms
                                    
                                    $end = microtime(true);
                                    $duration = ($end - $start) * 1000;
                                    CustomLogger::debug("Performance: Operação executada em " . number_format($duration, 2) . "ms");
                                    echo "<div class='alert alert-success'>Log de performance registrado!</div>";
                                    break;
                                    
                                case 'all':
                                    echo "<div class='alert alert-info'>Executando todos os testes...</div>";
                                    
                                    // Teste 1: Info
                                    CustomLogger::info("Teste completo iniciado em " . date('Y-m-d H:i:s'));
                                    
                                    // Teste 2: Debug
                                    CustomLogger::debug("Debug: Variáveis de sessão = " . json_encode($_SESSION ?? []));
                                    
                                    // Teste 3: Warning
                                    CustomLogger::warning("Warning simulado: Tentativa de acesso a recurso restrito");
                                    
                                    // Teste 4: Error
                                    CustomLogger::error("Error simulado: Falha na validação de dados");
                                    
                                    // Teste 5: System
                                    CustomLogger::system("System: Teste completo executado com sucesso");
                                    
                                    echo "<div class='alert alert-success'>Todos os tipos de log foram testados!</div>";
                                    break;
                            }
                        }
                        ?>
                        
                        <h5>Tipos de Teste Disponíveis:</h5>
                        <div class="list-group">
                            <a href="?test=notice" class="list-group-item list-group-item-action">
                                <strong>Notice</strong> - Variável indefinida
                            </a>
                            <a href="?test=warning" class="list-group-item list-group-item-action">
                                <strong>Warning</strong> - Arquivo não encontrado
                            </a>
                            <a href="?test=error" class="list-group-item list-group-item-action">
                                <strong>Error</strong> - Exception simulada
                            </a>
                            <a href="?test=database" class="list-group-item list-group-item-action">
                                <strong>Database</strong> - Erro de conexão
                            </a>
                            <a href="?test=memory" class="list-group-item list-group-item-action">
                                <strong>Memory</strong> - Informações de memória
                            </a>
                            <a href="?test=security" class="list-group-item list-group-item-action">
                                <strong>Security</strong> - Log de segurança
                            </a>
                            <a href="?test=performance" class="list-group-item list-group-item-action">
                                <strong>Performance</strong> - Tempo de execução
                            </a>
                            <a href="?test=all" class="list-group-item list-group-item-action list-group-item-primary">
                                <strong>Todos</strong> - Executar todos os testes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Informações do Sistema</h3>
                    </div>
                    <div class="card-body">
                        <h5>Configuração de Erro:</h5>
                        <ul class="list-unstyled">
                            <li><strong>Error Reporting:</strong> <?= error_reporting() ?></li>
                            <li><strong>Display Errors:</strong> <?= ini_get('display_errors') ? 'On' : 'Off' ?></li>
                            <li><strong>Log Errors:</strong> <?= ini_get('log_errors') ? 'On' : 'Off' ?></li>
                            <li><strong>Error Log:</strong> <?= ini_get('error_log') ?: 'Não definido' ?></li>
                        </ul>
                        
                        <h5>CustomLogger Status:</h5>
                        <ul class="list-unstyled">
                            <li><strong>Classe:</strong> <?= class_exists('CustomLogger') ? '✅ Carregada' : '❌ Não encontrada' ?></li>
                            <li><strong>Diretório logs:</strong> <?= is_dir('logs') ? '✅ Existe' : '❌ Não existe' ?></li>
                            <li><strong>Permissão escrita:</strong> <?= is_writable('logs') ? '✅ Ok' : '❌ Sem permissão' ?></li>
                        </ul>
                        
                        <h5>Arquivos de Log:</h5>
                        <?php
                        if (class_exists('CustomLogger')) {
                            $logFiles = CustomLogger::getLogFiles();
                            if (!empty($logFiles)) {
                                echo "<ul class='list-unstyled'>";
                                foreach ($logFiles as $file) {
                                    $size = file_exists("logs/$file") ? filesize("logs/$file") : 0;
                                    echo "<li>📄 $file (" . number_format($size) . " bytes)</li>";
                                }
                                echo "</ul>";
                            } else {
                                echo "<p>Nenhum arquivo de log encontrado.</p>";
                            }
                        }
                        ?>
                        
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="visualizar-logs.php" class="btn btn-primary">📋 Ver Logs Detalhados</a>
                            <a href="teste-log-sistema.php" class="btn btn-info">🔧 Teste do Sistema</a>
                            <a href="diagnostico.php" class="btn btn-secondary">🔍 Diagnóstico</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

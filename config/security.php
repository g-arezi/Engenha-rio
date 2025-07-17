<?php
// Configurações de segurança e inicialização para produção
// Configuração de erros mais robusta para hospedagem compartilhada (Hostinger)

// Forçar configurações de erro - método mais agressivo
if (function_exists('ini_set')) {
    // Configurar error reporting - reduzir para apenas erros críticos
    @ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);
    
    // FORÇAR log_errors = 1 - essencial para debugging
    @ini_set('log_errors', '1');
    @ini_set('display_errors', '0');
    
    // Tentar definir arquivo de log personalizado
    $custom_log_path = __DIR__ . '/../logs/engenha_rio_errors.log';
    @ini_set('error_log', $custom_log_path);
    
    // Configurações de upload e performance
    @ini_set('upload_max_filesize', '10M');
    @ini_set('post_max_size', '10M');
    @ini_set('max_execution_time', 300);
    @ini_set('memory_limit', '256M');
    
    // Configurações de sessão
    @ini_set('session.cookie_httponly', 1);
    @ini_set('session.use_strict_mode', 1);
    @ini_set('session.cookie_samesite', 'Strict');
}

// Verificar se o diretório de logs existe
$log_dir = __DIR__ . '/../logs';
if (!is_dir($log_dir)) {
    @mkdir($log_dir, 0755, true);
}

// Verificar configurações aplicadas e forçar novamente se necessário
$current_log_errors = ini_get('log_errors');
if (!$current_log_errors) {
    // Última tentativa de forçar log_errors
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    if (function_exists('ini_alter')) {
        @ini_alter('log_errors', '1');
    }
}

// Cabeçalhos de segurança (apenas se não foram definidos antes)
if (!headers_sent()) {
    @header('X-Frame-Options: DENY');
    @header('X-XSS-Protection: 1; mode=block');
    @header('X-Content-Type-Options: nosniff');
    @header('Referrer-Policy: strict-origin-when-cross-origin');
    @header('X-Powered-By: '); // Remover header que expõe PHP
}

// Configuração de timezone
@date_default_timezone_set('America/Sao_Paulo');

// Definir constantes
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}
if (!defined('APP_VERSION')) {
    define('APP_VERSION', '1.0.0');
}
if (!defined('APP_NAME')) {
    define('APP_NAME', 'Engenha Rio');
}

// Log de inicialização - usar error_log mesmo se ini_set falhar
$init_message = 'Engenha Rio - Sistema inicializado em ' . date('Y-m-d H:i:s');
@error_log($init_message);

// Se error_log não funcionar, tentar file_put_contents
if (is_writable($log_dir)) {
    $manual_log = $log_dir . '/system_init.log';
    @file_put_contents($manual_log, $init_message . PHP_EOL, FILE_APPEND | LOCK_EX);
}
?>

<?php
/**
 * Sistema de Log Personalizado para Engenha Rio
 * Funciona independente das configurações de error_log do servidor
 */

class CustomLogger {
    private static $log_dir;
    private static $initialized = false;
    
    public static function init() {
        if (self::$initialized) return;
        
        self::$log_dir = __DIR__ . '/../logs';
        
        // Criar diretório se não existir
        if (!is_dir(self::$log_dir)) {
            @mkdir(self::$log_dir, 0755, true);
        }
        
        self::$initialized = true;
    }
    
    public static function log($message, $level = 'INFO', $file = 'application.log') {
        self::init();
        
        $timestamp = date('Y-m-d H:i:s');
        $formatted_message = "[$timestamp] [$level] $message" . PHP_EOL;
        $log_file = self::$log_dir . '/' . $file;
        
        // Tentar escrever no arquivo
        $written = @file_put_contents($log_file, $formatted_message, FILE_APPEND | LOCK_EX);
        
        // Se falhar, tentar error_log padrão
        if (!$written) {
            @error_log("Engenha Rio - $level: $message");
        }
        
        return $written !== false;
    }
    
    public static function error($message) {
        return self::log($message, 'ERROR', 'errors.log');
    }
    
    public static function warning($message) {
        return self::log($message, 'WARNING', 'warnings.log');
    }
    
    public static function info($message) {
        return self::log($message, 'INFO', 'info.log');
    }
    
    public static function debug($message) {
        return self::log($message, 'DEBUG', 'debug.log');
    }
    
    public static function system($message) {
        return self::log($message, 'SYSTEM', 'system.log');
    }
    
    public static function getLogFiles() {
        self::init();
        $files = [];
        
        if (is_dir(self::$log_dir)) {
            $scan = scandir(self::$log_dir);
            foreach ($scan as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'log') {
                    $files[] = $file;
                }
            }
        }
        
        return $files;
    }
    
    public static function getLogContent($file, $lines = 50) {
        self::init();
        $log_file = self::$log_dir . '/' . $file;
        
        if (!file_exists($log_file)) {
            return "Arquivo de log não encontrado: $file";
        }
        
        $content = file($log_file);
        $content = array_slice($content, -$lines); // Pegar últimas linhas
        return implode('', $content);
    }
}

// Registrar handler de erro personalizado
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    $error_types = [
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING', 
        E_PARSE => 'PARSE',
        E_NOTICE => 'NOTICE',
        E_USER_ERROR => 'USER_ERROR',
        E_USER_WARNING => 'USER_WARNING',
        E_USER_NOTICE => 'USER_NOTICE'
    ];
    
    $type = $error_types[$errno] ?? 'UNKNOWN';
    $message = "[$type] $errstr in $errfile:$errline";
    
    CustomLogger::error($message);
    
    // Não impedir o handler padrão
    return false;
}

// Registrar handler de exceção personalizado
function custom_exception_handler($exception) {
    $message = "EXCEPTION: " . $exception->getMessage() . 
               " in " . $exception->getFile() . ":" . $exception->getLine() .
               "\nStack trace:\n" . $exception->getTraceAsString();
    
    CustomLogger::error($message);
}

// Registrar handlers
set_error_handler('custom_error_handler');
set_exception_handler('custom_exception_handler');

// Log de inicialização
CustomLogger::system('Sistema de log personalizado inicializado');
?>

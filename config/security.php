<?php
// Configurações de segurança e inicialização
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Configurações de upload
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
ini_set('max_execution_time', 300);
ini_set('memory_limit', '256M');

// Cabeçalhos de segurança
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Configuração de timezone
date_default_timezone_set('America/Sao_Paulo');

// Definir constantes
define('BASE_PATH', __DIR__);
define('APP_VERSION', '1.0.0');
define('APP_NAME', 'Engenha Rio');
?>

<?php
/**
 * Configurações de Ambiente - EXEMPLO
 * 
 * Copie este arquivo para environment.php e configure conforme necessário.
 * NUNCA commite o arquivo environment.php com dados reais!
 */

return [
    // Configurações da Aplicação
    'app_name' => 'Engenha Rio',
    'app_env' => 'development', // development, testing, production
    'app_debug' => true,
    'app_url' => 'http://localhost:8080',
    
    // Configurações de Banco de Dados (JSON)
    'data_path' => __DIR__ . '/../data/',
    'backup_enabled' => true,
    'backup_frequency' => 'daily', // hourly, daily, weekly
    
    // Configurações de Upload
    'max_upload_size' => 52428800, // 50MB em bytes
    'upload_path' => __DIR__ . '/../public/documents/',
    'allowed_extensions' => [
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'dwg', 'dxf', 'txt', 'zip', 'rar'
    ],
    'allowed_mime_types' => [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
        'text/plain',
        'application/zip',
        'application/x-rar-compressed',
        'application/octet-stream'
    ],
    
    // Configurações de Segurança
    'session_timeout' => 7200, // 2 horas em segundos
    'session_name' => 'ENGENHA_RIO_SESSION',
    'password_min_length' => 8,
    'password_require_special' => true,
    'login_attempts_limit' => 5,
    'login_lockout_time' => 900, // 15 minutos
    
    // Configurações de Email (para futuras implementações)
    'mail_driver' => 'smtp',
    'mail_host' => 'smtp.example.com',
    'mail_port' => 587,
    'mail_username' => 'your-email@example.com',
    'mail_password' => 'YOUR_EMAIL_PASSWORD_HERE',
    'mail_encryption' => 'tls',
    'mail_from_address' => 'noreply@engenha-rio.com',
    'mail_from_name' => 'Engenha Rio System',
    
    // Configurações de Logs
    'log_level' => 'info', // debug, info, warning, error
    'log_path' => __DIR__ . '/../logs/',
    'log_max_files' => 30,
    'log_channels' => [
        'app' => 'logs/app.log',
        'errors' => 'logs/errors.log',
        'access' => 'logs/access.log',
        'documents' => 'logs/documents.log',
        'security' => 'logs/security.log'
    ],
    
    // Configurações de Cache
    'cache_enabled' => true,
    'cache_driver' => 'file', // file, memory
    'cache_path' => __DIR__ . '/../cache/',
    'cache_ttl' => 3600, // 1 hora
    
    // Configurações de API (para integrações futuras)
    'api_enabled' => false,
    'api_key' => 'YOUR_API_KEY_HERE',
    'api_secret' => 'YOUR_API_SECRET_HERE',
    'api_rate_limit' => 1000, // requests per hour
    
    // Configurações de Integração Externa
    'external_apis' => [
        'google_maps' => [
            'enabled' => false,
            'api_key' => 'YOUR_GOOGLE_MAPS_API_KEY_HERE'
        ],
        'aws_s3' => [
            'enabled' => false,
            'access_key' => 'YOUR_AWS_ACCESS_KEY_HERE',
            'secret_key' => 'YOUR_AWS_SECRET_KEY_HERE',
            'bucket' => 'your-bucket-name',
            'region' => 'us-east-1'
        ]
    ],
    
    // Configurações de Notificações
    'notifications' => [
        'email_enabled' => false,
        'sms_enabled' => false,
        'push_enabled' => false,
        'slack_webhook' => 'YOUR_SLACK_WEBHOOK_URL_HERE'
    ],
    
    // Configurações de Timezone
    'timezone' => 'America/Sao_Paulo',
    'date_format' => 'd/m/Y',
    'datetime_format' => 'd/m/Y H:i:s',
    
    // Configurações de Performance
    'enable_compression' => true,
    'enable_minification' => false, // true em produção
    'enable_cache_headers' => true,
    
    // Configurações de Desenvolvimento
    'development' => [
        'show_errors' => true,
        'debug_toolbar' => true,
        'profiler_enabled' => true,
        'fake_slow_queries' => false
    ],
    
    // Configurações de Produção
    'production' => [
        'show_errors' => false,
        'debug_toolbar' => false,
        'profiler_enabled' => false,
        'force_https' => true,
        'security_headers' => true
    ]
];
?>

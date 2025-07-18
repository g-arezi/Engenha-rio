<?php
/**
 * Sistema de Gestão de Projetos - Engenha Rio
 * 
 * © 2025 Engenha Rio - Todos os direitos reservados
 * Desenvolvido por: Gabriel Arezi
 * Portfolio: https://portifolio-beta-five-52.vercel.app/
 * GitHub: https://github.com/g-arezi
 * 
 * Este software é propriedade intelectual protegida.
 * Uso não autorizado será processado judicialmente.
 */

// Configurações globais do sistema Engenha-rio

return [
    // Configurações do sistema
    'app' => [
        'name' => 'Engenha-rio',
        'version' => '1.0.0',
        'description' => 'Sistema de Gerenciamento de Projetos de Engenharia',
        'url' => 'http://localhost:8000',
        'timezone' => 'America/Sao_Paulo',
        'language' => 'pt-BR',
        'debug' => true
    ],
    
    // Configurações de banco/dados
    'database' => [
        'type' => 'json',
        'path' => 'data/',
        'backup_enabled' => true,
        'backup_frequency' => 'daily'
    ],
    
    // Configurações de autenticação
    'auth' => [
        'session_timeout' => 3600, // 1 hora
        'remember_token_lifetime' => 2592000, // 30 dias
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15 minutos
        'password_min_length' => 8,
        'require_email_verification' => false
    ],
    
    // Configurações de upload
    'upload' => [
        'max_file_size' => 10485760, // 10MB
        'allowed_extensions' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'dwg', 'dxf'],
        'upload_path' => 'public/uploads/',
        'thumbnails_enabled' => true,
        'virus_scan' => false
    ],
    
    // Configurações de email
    'mail' => [
        'driver' => 'smtp',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => '',
        'password' => '',
        'encryption' => 'tls',
        'from_address' => 'noreply@engenhario.com',
        'from_name' => 'Engenha-rio'
    ],
    
    // Configurações de notificações
    'notifications' => [
        'email_enabled' => true,
        'sms_enabled' => false,
        'push_enabled' => false,
        'admin_notifications' => true,
        'user_notifications' => true
    ],
    
    // Configurações de cache
    'cache' => [
        'enabled' => true,
        'driver' => 'file',
        'path' => 'cache/',
        'lifetime' => 3600,
        'auto_cleanup' => true
    ],
    
    // Configurações de logs
    'logs' => [
        'enabled' => true,
        'level' => 'info',
        'path' => 'logs/',
        'max_file_size' => 10485760, // 10MB
        'max_files' => 30,
        'rotate_daily' => true
    ],
    
    // Configurações de segurança
    'security' => [
        'csrf_protection' => true,
        'sql_injection_protection' => true,
        'xss_protection' => true,
        'force_https' => false,
        'ip_whitelist' => [],
        'rate_limiting' => [
            'enabled' => true,
            'max_requests' => 100,
            'window' => 3600 // 1 hora
        ]
    ],
    
    // Configurações da interface
    'ui' => [
        'theme' => 'default',
        'items_per_page' => 20,
        'date_format' => 'd/m/Y',
        'datetime_format' => 'd/m/Y H:i',
        'currency' => 'BRL',
        'currency_symbol' => 'R$'
    ],
    
    // Configurações de relatórios
    'reports' => [
        'enabled' => true,
        'export_formats' => ['pdf', 'excel', 'csv'],
        'cache_reports' => true,
        'max_export_records' => 10000
    ]
];

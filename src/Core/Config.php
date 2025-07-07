<?php

namespace App\Core;

use Dotenv\Dotenv;

class Config
{
    private static $config = [];
    
    public static function load()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        
        self::$config = [
            'app' => [
                'name' => $_ENV['APP_NAME'] ?? 'Engenha Rio',
                'env' => $_ENV['APP_ENV'] ?? 'local',
                'debug' => $_ENV['APP_DEBUG'] ?? true,
                'url' => $_ENV['APP_URL'] ?? 'http://localhost'
            ],
            'mail' => [
                'driver' => $_ENV['MAIL_DRIVER'] ?? 'smtp',
                'host' => $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com',
                'port' => $_ENV['MAIL_PORT'] ?? 587,
                'username' => $_ENV['MAIL_USERNAME'] ?? '',
                'password' => $_ENV['MAIL_PASSWORD'] ?? '',
                'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
                'from' => [
                    'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@engenhario.com',
                    'name' => $_ENV['MAIL_FROM_NAME'] ?? 'Engenha Rio'
                ]
            ],
            'upload' => [
                'max_size' => $_ENV['UPLOAD_MAX_SIZE'] ?? 10485760, // 10MB
                'path' => $_ENV['UPLOAD_PATH'] ?? 'uploads/'
            ],
            'session' => [
                'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 120,
                'name' => 'engenhario_session'
            ]
        ];
    }
    
    public static function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $value = self::$config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    public static function app($key = null, $default = null)
    {
        if ($key === null) {
            return self::$config['app'];
        }
        
        return self::$config['app'][$key] ?? $default;
    }
    
    public static function mail($key = null, $default = null)
    {
        if ($key === null) {
            return self::$config['mail'];
        }
        
        return self::$config['mail'][$key] ?? $default;
    }
}

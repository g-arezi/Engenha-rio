<?php

namespace App\Middleware;

class ValidationMiddleware
{
    /**
     * Sanitizar entrada de dados
     */
    public static function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        
        if (is_string($input)) {
            // Remover tags HTML e PHP
            $input = strip_tags($input);
            
            // Converter caracteres especiais para entidades HTML
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            
            // Remover caracteres de controle
            $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
            
            // Trim espaços em branco
            $input = trim($input);
        }
        
        return $input;
    }
    
    /**
     * Validar CSRF token
     */
    public static function validateCsrfToken($token)
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Gerar CSRF token
     */
    public static function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validar email
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validar senha forte
     */
    public static function validateStrongPassword($password)
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'A senha deve ter pelo menos 8 caracteres';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos uma letra maiúscula';
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos uma letra minúscula';
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos um número';
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos um caractere especial';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Validar dados de entrada HTTP
     */
    public static function validateHttpInput()
    {
        // Sanitizar $_POST
        if (!empty($_POST)) {
            $_POST = self::sanitizeInput($_POST);
        }
        
        // Sanitizar $_GET
        if (!empty($_GET)) {
            $_GET = self::sanitizeInput($_GET);
        }
        
        // Verificar tamanho máximo de dados POST
        $maxPostSize = ini_get('post_max_size');
        $maxPostBytes = self::convertToBytes($maxPostSize);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
            $_SERVER['CONTENT_LENGTH'] > $maxPostBytes) {
            throw new \Exception('Dados enviados excedem o limite permitido');
        }
    }
    
    /**
     * Converter string de tamanho para bytes
     */
    private static function convertToBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;
        
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        
        return $val;
    }
    
    /**
     * Validar rate limiting
     */
    public static function checkRateLimit($action, $maxAttempts = 5, $timeWindow = 300)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = "rate_limit_{$action}_{$ip}";
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'first_attempt' => time()
            ];
        }
        
        $data = $_SESSION[$key];
        
        // Reset se passou do tempo limite
        if (time() - $data['first_attempt'] > $timeWindow) {
            $_SESSION[$key] = [
                'attempts' => 1,
                'first_attempt' => time()
            ];
            return true;
        }
        
        // Verificar se excedeu tentativas
        if ($data['attempts'] >= $maxAttempts) {
            return false;
        }
        
        // Incrementar tentativas
        $_SESSION[$key]['attempts']++;
        
        return true;
    }
}

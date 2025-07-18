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

/**
 * Configurações específicas para ambiente de hospedagem
 * Use este arquivo para sobrescrever configurações locais
 */

// Detectar se estamos em ambiente de produção
$is_production = !in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1']);

if ($is_production) {
    // Configurações para produção/hospedagem
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    
    // Definir arquivo de log personalizado se possível
    $custom_log = __DIR__ . '/../logs/app_errors.log';
    if (is_writable(dirname($custom_log))) {
        ini_set('error_log', $custom_log);
    }
    
    // Cabeçalhos de segurança adicionais para produção
    if (!headers_sent()) {
        header('X-Powered-By: ');  // Remover header que revela tecnologia
        header('Server: ');        // Remover header de servidor
    }
    
    // Desabilitar funções perigosas se possível
    if (function_exists('ini_set')) {
        @ini_set('expose_php', '0');
        @ini_set('allow_url_fopen', '0');
        @ini_set('allow_url_include', '0');
    }
    
} else {
    // Configurações para desenvolvimento local
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('log_errors', '1');
}

// Log da configuração aplicada
error_log('Engenha Rio - Configuração de ambiente: ' . ($is_production ? 'PRODUÇÃO' : 'DESENVOLVIMENTO'));
?>

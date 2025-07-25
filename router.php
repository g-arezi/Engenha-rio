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

// Router para servidor PHP embutido
// Este arquivo substitui .htaccess quando usando php -S

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Log da requisição
error_log("ROUTER.PHP - URI: " . $uri);

// Servir arquivos estáticos se existirem
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|webp|ico|woff|woff2|ttf|eot|pdf)$/i', $uri)) {
    // Primeiro tentar em /public
    $file = __DIR__ . '/public' . $uri;
    
    if (file_exists($file)) {
        error_log("ROUTER.PHP - Servindo arquivo estático de /public: " . $file);
        
        // Definir content-type correto
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'pdf' => 'application/pdf'
        ];
        
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($file));
        header('Cache-Control: public, max-age=31536000');
        readfile($file);
        return true;
    }
    
    // Se não encontrou em /public, tentar na raiz
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        error_log("ROUTER.PHP - Servindo arquivo da raiz: " . $file);
        return false;
    }
    
    error_log("ROUTER.PHP - Arquivo não encontrado: " . $uri);
    http_response_code(404);
    echo "Arquivo não encontrado: " . $uri;
    return true;
}

// Rotas especiais para documentos
if (preg_match('/^\/documents\/([^\/]+)\/info$/', $uri, $matches)) {
    error_log("ROUTER.PHP - Rota de info de documento: " . $uri);
    $_GET['action'] = 'downloadInfo';
    $_GET['id'] = $matches[1];
    require_once 'index.php';
    return true;
}

// Para todas as outras requisições, redirecionar para index.php
error_log("ROUTER.PHP - Redirecionando para index.php: " . $uri);
require_once 'index.php';
return true;
?>

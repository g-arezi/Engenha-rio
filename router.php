<?php
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

// Para todas as outras requisições, redirecionar para index.php
error_log("ROUTER.PHP - Redirecionando para index.php: " . $uri);
require_once 'index.php';
return true;
?>

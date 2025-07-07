<?php
// Arquivo de entrada para servidor PHP interno
// Redireciona tudo para o index.php principal

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Se for um arquivo estático que existe, serve diretamente
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|webp|ico|woff|woff2|ttf|eot)$/i', $uri)) {
    $staticFile = __DIR__ . $uri;
    if (file_exists($staticFile)) {
        return false; // Deixa o servidor PHP servir o arquivo
    }
}

// Para todas as outras requisições, redireciona para o index principal
require_once __DIR__ . '/../index.php';
?>

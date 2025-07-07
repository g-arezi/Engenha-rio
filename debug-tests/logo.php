<?php
// Servir logo diretamente
$paths = [
    __DIR__ . '/public/assets/images/logo.webp',
    __DIR__ . '/logo.webp',
    __DIR__ . '/public/assets/images/engenha-rio-b4616.webp'
];

foreach ($paths as $logoFile) {
    if (file_exists($logoFile)) {
        header('Content-Type: image/webp');
        header('Content-Length: ' . filesize($logoFile));
        header('Cache-Control: public, max-age=31536000');
        readfile($logoFile);
        exit;
    }
}

http_response_code(404);
echo "Logo não encontrada";

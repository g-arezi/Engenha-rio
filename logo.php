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

// Servir nova logo diretamente
$paths = [
    __DIR__ . '/public/assets/images/engenhario-logo-new.png',
    __DIR__ . '/public/assets/images/logo-new.png'
];

foreach ($paths as $logoFile) {
    if (file_exists($logoFile)) {
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($logoFile));
        header('Cache-Control: public, max-age=31536000');
        readfile($logoFile);
        exit;
    }
}

// Se não encontrar, tentar fallback para a logo antiga
$fallbackPaths = [
    __DIR__ . '/public/assets/images/logo.webp',
    __DIR__ . '/public/assets/images/logo.png',
    __DIR__ . '/public/assets/images/logo.jpg',
    __DIR__ . '/public/assets/images/logo.svg'
];

foreach ($fallbackPaths as $logoFile) {
    if (file_exists($logoFile)) {
        $extension = pathinfo($logoFile, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'svg':
                header('Content-Type: image/svg+xml');
                break;
        }
        header('Content-Length: ' . filesize($logoFile));
        header('Cache-Control: public, max-age=31536000');
        readfile($logoFile);
        exit;
    }
}

http_response_code(404);
echo "Logo não encontrada";
?>

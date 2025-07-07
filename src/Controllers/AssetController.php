<?php

namespace App\Controllers;

use App\Core\Controller;

class AssetController extends Controller
{
    public function logo()
    {
        $logoPath = __DIR__ . '/../../public/assets/images/engenhario-logo-new.png';
        
        // Fallback para logo-new.png se a principal não existir
        if (!file_exists($logoPath)) {
            $logoPath = __DIR__ . '/../../public/assets/images/logo-new.png';
        }
        
        if (!file_exists($logoPath)) {
            http_response_code(404);
            exit('Logo não encontrada');
        }
        
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($logoPath));
        header('Cache-Control: public, max-age=31536000');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        
        readfile($logoPath);
        exit;
    }
}

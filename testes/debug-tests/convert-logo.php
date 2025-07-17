<?php
// Converter logo para base64 e gerar CSS
$logoPath = __DIR__ . '/public/assets/images/logo.webp';

if (file_exists($logoPath)) {
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoBase64 = 'data:image/webp;base64,' . $logoData;
    
    echo "Logo em Base64 (para usar em CSS):\n";
    echo "background-image: url('$logoBase64');\n\n";
    
    echo "Para usar em HTML:\n";
    echo "<img src=\"$logoBase64\" alt=\"Logo\" style=\"width: 32px;\">\n\n";
    
    echo "Tamanho do arquivo: " . filesize($logoPath) . " bytes\n";
    echo "Tamanho em base64: " . strlen($logoData) . " caracteres\n";
} else {
    echo "Arquivo não encontrado: $logoPath\n";
}
?>

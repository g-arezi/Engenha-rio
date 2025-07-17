<?php
// Debug da logo
echo "<h1>Debug da Logo</h1>";

$logoPaths = [
    '/assets/images/logo.webp',
    'assets/images/logo.webp', 
    './assets/images/logo.webp',
    '/public/assets/images/logo.webp',
    'public/assets/images/logo.webp',
    '/logo-test.webp',
    'logo-test.webp'
];

foreach ($logoPaths as $path) {
    echo "<div style='margin: 20px; padding: 10px; border: 1px solid #ccc;'>";
    echo "<p><strong>Caminho:</strong> $path</p>";
    echo "<img src='$path' alt='Logo' style='width: 60px; height: auto; border: 1px solid red;' onload='this.style.border=\"1px solid green\";' onerror='this.style.border=\"1px solid red\"; this.alt=\"ERRO: Imagem não encontrada\";'>";
    echo "</div>";
}

// Verificar se o arquivo existe fisicamente
$physicalPath = __DIR__ . '/public/assets/images/logo.webp';
echo "<p><strong>Arquivo físico existe:</strong> " . (file_exists($physicalPath) ? 'SIM' : 'NÃO') . "</p>";
echo "<p><strong>Caminho físico:</strong> $physicalPath</p>";

if (file_exists($physicalPath)) {
    echo "<p><strong>Tamanho do arquivo:</strong> " . filesize($physicalPath) . " bytes</p>";
}

// Verificar arquivo de teste na raiz
$testPath = __DIR__ . '/logo-test.webp';
echo "<p><strong>Arquivo de teste existe:</strong> " . (file_exists($testPath) ? 'SIM' : 'NÃO') . "</p>";

// Debug do servidor
echo "<h2>Debug do Servidor</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'não definido') . "</p>";
echo "<p><strong>DOCUMENT_ROOT:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'não definido') . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'não definido') . "</p>";
echo "<p><strong>__DIR__:</strong> " . __DIR__ . "</p>";
?>

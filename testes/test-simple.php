<?php
echo "Sistema funcionando!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Diretório atual: " . __DIR__ . "<br>";

// Verificar se o init.php existe
if (file_exists(__DIR__ . '/init.php')) {
    echo "init.php existe<br>";
} else {
    echo "init.php NÃO existe<br>";
}

// Verificar o arquivo de dados
if (file_exists(__DIR__ . '/data/document_templates.json')) {
    echo "document_templates.json existe<br>";
} else {
    echo "document_templates.json NÃO existe<br>";
}
?>

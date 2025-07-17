<?php
// Teste de debug simples para upload
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Upload</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>POST Data:</h2>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    echo "<h2>FILES Data:</h2>";
    echo "<pre>" . print_r($_FILES, true) . "</pre>";
    
    echo "<h2>Headers:</h2>";
    echo "<pre>" . print_r(getallheaders(), true) . "</pre>";
} else {
    echo "<p>Nenhum POST recebido ainda.</p>";
}
?>

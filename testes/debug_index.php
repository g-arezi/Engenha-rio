<?php
// Debug para capturar dados do formulário de templates

// Verificar se é um POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("=== DEBUG FORM SUBMISSION ===");
    error_log("POST Data: " . print_r($_POST, true));
    error_log("Raw input: " . file_get_contents('php://input'));
    error_log("Headers: " . print_r(getallheaders(), true));
    
    // Salvar em arquivo para análise
    file_put_contents(__DIR__ . '/form_debug.log', 
        "=== " . date('Y-m-d H:i:s') . " ===\n" .
        "POST: " . print_r($_POST, true) . "\n" .
        "Raw: " . file_get_contents('php://input') . "\n" .
        "================\n\n", FILE_APPEND);
}

// Redirecionar para o index.php original
require_once '../index.php';
?>

<?php

require_once __DIR__ . '/init.php';

use App\Core\Auth;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = Auth::user();
        
        $response = [
            'success' => true,
            'message' => 'Teste OK',
            'user' => $user ? $user['name'] : 'Não autenticado',
            'post' => $_POST,
            'files' => $_FILES,
            'method' => $_SERVER['REQUEST_METHOD']
        ];
        
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Método não permitido'
    ]);
}
?>

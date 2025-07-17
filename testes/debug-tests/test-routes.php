<?php
// Teste de rotas
session_start();
$_SESSION['user_id'] = 'admin_001';
$_SESSION['role'] = 'admin';

echo "=== TESTE DE ROTAS ===\n\n";

$baseUrl = "http://localhost:8000";
$testRoutes = [
    '/admin/users/admin_001/edit' => 'Rota padrão de edição',
    '/admin/edit-user/admin_001' => 'Rota alternativa de edição',
    '/admin/history' => 'Rota de histórico',
    '/admin/users' => 'Rota de listagem de usuários'
];

foreach ($testRoutes as $route => $description) {
    echo "Testando: $description\n";
    echo "URL: $baseUrl$route\n";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "Content-Type: application/json\r\n",
            'timeout' => 5
        ]
    ]);
    
    $response = @file_get_contents($baseUrl . $route, false, $context);
    
    if ($response) {
        if (strlen($response) > 200) {
            echo "✅ Sucesso - Resposta recebida (" . strlen($response) . " bytes)\n";
        } else {
            echo "⚠️  Resposta curta: " . substr($response, 0, 100) . "...\n";
        }
    } else {
        echo "❌ Erro - Sem resposta\n";
        if (isset($http_response_header)) {
            echo "Headers: " . implode(' | ', $http_response_header) . "\n";
        }
    }
    
    echo "\n";
}

echo "=== FIM DO TESTE ===\n";
?>

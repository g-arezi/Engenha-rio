<?php
// Teste direto do AdminController - sem init.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir apenas os arquivos necessários
require_once 'src/Core/Controller.php';
require_once 'src/Core/Model.php';
require_once 'src/Core/Auth.php';
require_once 'src/Models/User.php';
require_once 'src/Controllers/AdminController.php';

echo "=== TESTE DIRETO DO ADMINCONTROLLER ===\n";

// Simular sessão
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Administrador';
$_SESSION['user_email'] = 'admin@engenhario.com';
$_SESSION['user_role'] = 'admin';

try {
    $adminController = new \App\Controllers\AdminController();
    
    // Testar método getRecentActivities
    $reflection = new ReflectionClass($adminController);
    $method = $reflection->getMethod('getRecentActivities');
    $method->setAccessible(true);
    
    echo "1. Testando getRecentActivities:\n";
    $result = $method->invoke($adminController, '', '', '', 1);
    
    echo "   Tipo: " . gettype($result) . "\n";
    if (is_array($result)) {
        echo "   Chaves: " . implode(', ', array_keys($result)) . "\n";
        echo "   Estrutura válida: " . (isset($result['data']) && isset($result['totalPages']) ? 'SIM' : 'NÃO') . "\n";
    }
    
    echo "\n2. Testando com diferentes parâmetros:\n";
    
    // Teste com filtros
    $result2 = $method->invoke($adminController, 'login', '', '', 1);
    echo "   Com filtro 'login': " . (is_array($result2) && isset($result2['data']) ? 'OK' : 'ERRO') . "\n";
    
    // Teste com página diferente
    $result3 = $method->invoke($adminController, '', '', '', 2);
    echo "   Com página 2: " . (is_array($result3) && isset($result3['data']) ? 'OK' : 'ERRO') . "\n";
    
    echo "\n3. Dados de exemplo:\n";
    if (isset($result['data']) && is_array($result['data'])) {
        echo "   Número de atividades: " . count($result['data']) . "\n";
        if (count($result['data']) > 0) {
            echo "   Primeira atividade: " . $result['data'][0]['action'] . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

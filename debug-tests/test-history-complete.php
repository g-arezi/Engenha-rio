<?php
// Teste completo da funcionalidade de histórico

// Incluir inicializações necessárias
require_once 'init.php';

// Simular sessão de admin
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Administrador';
$_SESSION['user_email'] = 'admin@engenhario.com';
$_SESSION['user_role'] = 'admin';

// Simular parâmetros GET
$_GET['type'] = '';
$_GET['user'] = '';
$_GET['date'] = '';
$_GET['page'] = '1';

echo "=== TESTE DO HISTÓRICO ===\n";

try {
    // Criar instância do controller
    $adminController = new \App\Controllers\AdminController();
    
    // Testar método getRecentActivities via reflexão
    $reflection = new ReflectionClass($adminController);
    $method = $reflection->getMethod('getRecentActivities');
    $method->setAccessible(true);
    
    echo "1. Testando método getRecentActivities diretamente:\n";
    $result = $method->invoke($adminController, '', '', '', 1);
    
    echo "   Tipo do resultado: " . gettype($result) . "\n";
    if (is_array($result)) {
        echo "   Chaves: " . implode(', ', array_keys($result)) . "\n";
        echo "   Tem 'data': " . (isset($result['data']) ? 'SIM' : 'NÃO') . "\n";
        echo "   Tem 'totalPages': " . (isset($result['totalPages']) ? 'SIM' : 'NÃO') . "\n";
        echo "   Tem 'currentPage': " . (isset($result['currentPage']) ? 'SIM' : 'NÃO') . "\n";
        echo "   Quantidade de atividades: " . (isset($result['data']) ? count($result['data']) : 0) . "\n";
    }
    
    echo "\n2. Testando método history completo:\n";
    
    // Capturar a saída do método history
    ob_start();
    $adminController->history();
    $output = ob_get_clean();
    
    echo "   Método history executado com sucesso!\n";
    echo "   Tamanho da saída: " . strlen($output) . " caracteres\n";
    
    // Verificar se há warnings na saída
    if (strpos($output, 'Undefined array key') !== false) {
        echo "   ERRO: Encontrados warnings na saída!\n";
        // Extrair as linhas com warnings
        $lines = explode("\n", $output);
        foreach ($lines as $line) {
            if (strpos($line, 'Undefined array key') !== false) {
                echo "   Warning: " . trim($line) . "\n";
            }
        }
    } else {
        echo "   OK: Nenhum warning encontrado!\n";
    }
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

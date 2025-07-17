<?php
require_once 'init.php';
require_once 'src/Core/Auth.php';

// Simulando login de admin
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Administrador';
$_SESSION['user_email'] = 'admin@engenha-rio.com';
$_SESSION['user_role'] = 'admin';

// Teste direto do método getRecentActivities
$adminController = new \App\Controllers\AdminController();

// Usar reflexão para acessar o método privado
$reflection = new ReflectionClass($adminController);
$method = $reflection->getMethod('getRecentActivities');
$method->setAccessible(true);

echo "=== Teste do método getRecentActivities ===\n";

// Teste 1: Chamada padrão (dashboard)
$result = $method->invoke($adminController, '', '', '', 1);
echo "Teste 1 (dashboard): ";
var_dump($result);
echo "\n";

// Teste 2: Chamada com filtros (histórico)
$result = $method->invoke($adminController, 'login', '', '', 1);
echo "Teste 2 (histórico com filtro): ";
var_dump($result);
echo "\n";

// Teste 3: Verificar se as chaves existem
$result = $method->invoke($adminController, '', '', '', 1);
echo "Teste 3 - Verificação das chaves:\n";
echo "Existe 'data': " . (isset($result['data']) ? 'SIM' : 'NÃO') . "\n";
echo "Existe 'totalPages': " . (isset($result['totalPages']) ? 'SIM' : 'NÃO') . "\n";
echo "Existe 'currentPage': " . (isset($result['currentPage']) ? 'SIM' : 'NÃO') . "\n";
echo "Existe 'total': " . (isset($result['total']) ? 'SIM' : 'NÃO') . "\n";

// Teste 4: Simular a chamada do método history()
echo "\n=== Teste do método history() ===\n";
try {
    // Simular parâmetros GET
    $_GET['type'] = '';
    $_GET['user'] = '';
    $_GET['date'] = '';
    $_GET['page'] = '1';
    
    ob_start();
    $adminController->history();
    $output = ob_get_clean();
    
    echo "Método history() executado com sucesso!\n";
    echo "Tamanho do output: " . strlen($output) . " caracteres\n";
    
} catch (Exception $e) {
    echo "Erro no método history(): " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

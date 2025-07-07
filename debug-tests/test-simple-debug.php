<?php
// Teste simples sem sessão
require_once 'src/Controllers/AdminController.php';
require_once 'src/Models/User.php';

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
print_r($result);
echo "\n";

// Teste 3: Verificar se as chaves existem
$result = $method->invoke($adminController, '', '', '', 1);
echo "Teste 3 - Verificação das chaves:\n";
echo "Existe 'data': " . (isset($result['data']) ? 'SIM' : 'NÃO') . "\n";
echo "Existe 'totalPages': " . (isset($result['totalPages']) ? 'SIM' : 'NÃO') . "\n";
echo "Existe 'currentPage': " . (isset($result['currentPage']) ? 'SIM' : 'NÃO') . "\n";
echo "Existe 'total': " . (isset($result['total']) ? 'SIM' : 'NÃO') . "\n";

echo "\nTipo de retorno: " . gettype($result) . "\n";
echo "Chaves do array: " . implode(', ', array_keys($result)) . "\n";

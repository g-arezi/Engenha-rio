<?php
require_once 'vendor/autoload.php';

// Simular ambiente web
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

// Simular dados do usuário logado
session_start();
$_SESSION['user'] = [
    'id' => '686c14c547d77',
    'name' => 'Teste user',
    'email' => 'gabriel.arezi.gsa@gmail.com',
    'role' => 'client'
];

// Simular dados JSON
$jsonData = json_encode([
    'project_id' => 'project_001',
    'subject' => 'Teste com projeto via script',
    'description' => 'Testando se o projeto está sendo salvo corretamente via simulação',
    'priority' => 'media'
]);

// Simular php://input
file_put_contents('php://memory', $jsonData);

// Incluir o autoloader e as classes necessárias
use App\Controllers\TicketController;
use App\Core\Auth;

try {
    echo "Criando ticket...\n";
    
    $controller = new TicketController();
    
    // Capturar a saída
    ob_start();
    $controller->create();
    $output = ob_get_clean();
    
    echo "Resposta do controller:\n";
    echo $output . "\n";
    
    // Verificar se o ticket foi criado
    $ticketsDir = __DIR__ . '/data/tickets';
    $files = glob($ticketsDir . '/TK*.json');
    if ($files) {
        $latestFile = array_pop($files);
        echo "\nÚltimo ticket criado: " . basename($latestFile) . "\n";
        
        $ticketData = json_decode(file_get_contents($latestFile), true);
        echo "Dados do ticket:\n";
        print_r($ticketData);
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>

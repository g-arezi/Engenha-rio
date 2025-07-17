<?php
/**
 * Teste da funcionalidade de alteração de senha
 * Este arquivo testa se a validação e alteração de senha está funcionando
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Core/Session.php';
require_once __DIR__ . '/src/Core/Auth.php';
require_once __DIR__ . '/src/Models/User.php';

use App\Core\Session;
use App\Core\Auth;
use App\Models\User;

Session::start();

echo "<h1>Teste de Alteração de Senha</h1>";

// Verificar se usuário está logado
if (!Auth::check()) {
    echo "<p>❌ Usuário não está logado</p>";
    exit;
}

$user = Auth::user();
echo "<p>✅ Usuário logado: " . htmlspecialchars($user['name']) . " (" . $user['email'] . ")</p>";

// Simular dados do formulário
$testCases = [
    [
        'name' => 'Senha atual inválida',
        'current_password' => 'senha_errada',
        'new_password' => 'nova_senha_123',
        'confirm_password' => 'nova_senha_123'
    ],
    [
        'name' => 'Senhas não conferem',
        'current_password' => 'password', // Senha padrão do admin
        'new_password' => 'nova_senha_123',
        'confirm_password' => 'nova_senha_456'
    ],
    [
        'name' => 'Senha muito curta',
        'current_password' => 'password',
        'new_password' => '123',
        'confirm_password' => '123'
    ]
];

echo "<h2>Casos de Teste:</h2>";

foreach ($testCases as $i => $test) {
    echo "<h3>Teste " . ($i + 1) . ": " . $test['name'] . "</h3>";
    
    // Verificar senha atual
    if (!password_verify($test['current_password'], $user['password'])) {
        echo "<p>❌ Senha atual incorreta</p>";
        continue;
    }
    
    // Verificar se senhas conferem
    if ($test['new_password'] !== $test['confirm_password']) {
        echo "<p>❌ Senhas não conferem</p>";
        continue;
    }
    
    // Verificar tamanho da senha
    if (strlen($test['new_password']) < 6) {
        echo "<p>❌ Senha muito curta</p>";
        continue;
    }
    
    echo "<p>✅ Validação passou</p>";
}

echo "<h2>Informações do Sistema:</h2>";
echo "<p><strong>Arquivo de dados:</strong> " . realpath(__DIR__ . '/data/users.json') . "</p>";
echo "<p><strong>Hash da senha atual:</strong> " . substr($user['password'], 0, 20) . "...</p>";
echo "<p><strong>Verificação de senha 'password':</strong> " . (password_verify('password', $user['password']) ? '✅ Correta' : '❌ Incorreta') . "</p>";

?>

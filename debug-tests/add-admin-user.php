<?php
require_once 'vendor/autoload.php';

use App\Core\Config;
use App\Models\User;

echo "=== ADICIONANDO USUÁRIO ADMINISTRADOR ===\n\n";

// Carregar configurações
Config::load();

// Criar hash da senha 'password'
$hashedPassword = password_hash('password', PASSWORD_DEFAULT);

echo "Email: admin@sistema.com\n";
echo "Senha: password\n";
echo "Hash gerado: " . $hashedPassword . "\n\n";

// Carregar usuários existentes
$usersFile = 'data/users.json';
$users = [];

if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
}

// Verificar se o usuário já existe
$userExists = false;
foreach ($users as $user) {
    if ($user['email'] === 'admin@sistema.com') {
        $userExists = true;
        break;
    }
}

if ($userExists) {
    echo "❌ Usuário admin@sistema.com já existe!\n";
    echo "Atualizando dados do usuário...\n";
    
    // Atualizar usuário existente
    foreach ($users as $key => $user) {
        if ($user['email'] === 'admin@sistema.com') {
            $users[$key]['password'] = $hashedPassword;
            $users[$key]['role'] = 'admin';
            $users[$key]['active'] = true;
            $users[$key]['approved'] = true;
            $users[$key]['updated_at'] = date('Y-m-d H:i:s');
            break;
        }
    }
} else {
    echo "✅ Adicionando novo usuário admin@sistema.com\n";
    
    // Adicionar novo usuário
    $users['admin_sistema'] = [
        'id' => 'admin_002',
        'name' => 'Administrador do Sistema',
        'email' => 'admin@sistema.com',
        'password' => $hashedPassword,
        'role' => 'admin',
        'active' => true,
        'approved' => true,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
}

// Salvar arquivo
if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT))) {
    echo "✅ Arquivo users.json atualizado com sucesso!\n\n";
} else {
    echo "❌ Erro ao salvar arquivo users.json\n\n";
}

// Verificar se o hash está correto
echo "=== VERIFICAÇÃO DA SENHA ===\n";
$testPassword = 'password';
$isValid = password_verify($testPassword, $hashedPassword);
echo "Senha 'password' válida: " . ($isValid ? "✅ SIM" : "❌ NÃO") . "\n\n";

// Listar todos os usuários admin
echo "=== USUÁRIOS ADMINISTRADORES ===\n";
foreach ($users as $user) {
    if ($user['role'] === 'admin') {
        echo "- " . $user['name'] . " (" . $user['email'] . ")\n";
    }
}

echo "\n=== CONCLUÍDO ===\n";
echo "Agora você pode fazer login com:\n";
echo "Email: admin@sistema.com\n";
echo "Senha: password\n";
?>

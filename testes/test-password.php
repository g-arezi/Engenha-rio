<?php
// Verificar senha do admin
require_once __DIR__ . '/init.php';

echo "<h1>Verificação de Senha do Admin</h1>";

$userModel = new App\Models\User();
$admin = $userModel->findByEmail('admin@sistema.com');

if ($admin) {
    echo "<h2>Dados do Admin:</h2>";
    echo "ID: " . $admin['id'] . "<br>";
    echo "Nome: " . $admin['name'] . "<br>";
    echo "Email: " . $admin['email'] . "<br>";
    echo "Role: " . $admin['role'] . "<br>";
    echo "Hash da senha: " . $admin['password'] . "<br>";
    
    echo "<h2>Testando senhas:</h2>";
    $passwords = ['admin123', 'admin', '123456', 'password', 'sistema', 'admin2024', 'admin2025'];
    
    foreach ($passwords as $pass) {
        $isValid = password_verify($pass, $admin['password']);
        echo "Senha '$pass': " . ($isValid ? "✅ CORRETA" : "❌ Incorreta") . "<br>";
        
        if ($isValid) {
            echo "<strong>✅ SENHA ENCONTRADA: $pass</strong><br>";
            break;
        }
    }
    
    echo "<h2>Criar nova senha para teste:</h2>";
    $newPassword = 'admin123';
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    echo "Nova senha: $newPassword<br>";
    echo "Novo hash: $newHash<br>";
    
    // Atualizar a senha do admin
    $admin['password'] = $newHash;
    $userModel->update($admin['id'], ['password' => $newHash]);
    echo "✅ Senha do admin atualizada para: $newPassword<br>";
    
} else {
    echo "❌ Admin não encontrado<br>";
}
?>

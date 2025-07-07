<?php
echo "<h1>Gerador de Hash de Senha</h1>";

$password = 'password';
$newHash = password_hash($password, PASSWORD_DEFAULT);

echo "Senha: '$password'<br>";
echo "Novo Hash: $newHash<br>";

// Verificar se o hash funciona
$verification = password_verify($password, $newHash);
echo "Verificação: " . ($verification ? 'VÁLIDA' : 'INVÁLIDA') . "<br>";

echo "<h2>Verificar Hash Atual do Usuário:</h2>";
$usersFile = 'data/users.json';
if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
    
    foreach ($users as $key => $user) {
        if ($user['email'] === 'admin@sistema.com') {
            echo "Usuário encontrado: " . $user['name'] . "<br>";
            echo "Hash atual: " . $user['password'] . "<br>";
            
            $currentVerification = password_verify($password, $user['password']);
            echo "Hash atual é válido: " . ($currentVerification ? 'SIM' : 'NÃO') . "<br>";
            
            if (!$currentVerification) {
                echo "<br><strong>❌ PROBLEMA: Hash atual não é válido!</strong><br>";
                echo "Novo hash que deve ser usado: $newHash<br>";
                
                // Atualizar o hash
                $users[$key]['password'] = $newHash;
                $users[$key]['updated_at'] = date('Y-m-d H:i:s');
                
                file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
                echo "✅ Hash atualizado com sucesso!<br>";
            }
            break;
        }
    }
}
?>

<hr>
<p><a href="/login-test.php">Testar Login</a></p>

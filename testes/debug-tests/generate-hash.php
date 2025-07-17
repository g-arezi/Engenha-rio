<?php
$password = 'password';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Senha: {$password}<br>";
echo "Hash: {$hash}<br>";
echo "<br>Teste de verificação: " . (password_verify($password, $hash) ? 'OK' : 'ERRO');
?>

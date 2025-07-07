<?php
require_once 'vendor/autoload.php';

// Inicia sessão limpa
session_start();

echo "<h1>Debug da Sessão</h1>";

// Limpar sessão
session_unset();
session_destroy();
session_start();

echo "<h2>1. Estado inicial da sessão:</h2>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

echo "<h2>2. Testando Session::start():</h2>";
\App\Core\Session::start();
echo "Session::start() executado<br>";

echo "<h2>3. Fazendo login:</h2>";
$result = \App\Core\Auth::login('admin@sistema.com', 'password');
echo "Login result: " . ($result ? 'TRUE' : 'FALSE') . "<br>";

echo "<h2>4. Estado da sessão após login:</h2>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

echo "<h2>5. Testando Session::get('user_id'):</h2>";
$userId = \App\Core\Session::get('user_id');
echo "user_id: " . ($userId ?? 'NULL') . "<br>";

echo "<h2>6. Testando Auth::user():</h2>";
$user = \App\Core\Auth::user();
echo "Auth::user(): " . ($user ? 'ENCONTRADO' : 'NULL') . "<br>";

if ($user) {
    echo "Nome: " . $user['name'] . "<br>";
    echo "Role: " . $user['role'] . "<br>";
} else {
    echo "Usuário não encontrado. Debug:<br>";
    echo "Session::has('user_id'): " . (\App\Core\Session::has('user_id') ? 'TRUE' : 'FALSE') . "<br>";
    echo "Auth::check(): " . (\App\Core\Auth::check() ? 'TRUE' : 'FALSE') . "<br>";
    
    // Testar busca direta
    if ($userId) {
        echo "Testando busca direta por ID: $userId<br>";
        $userModel = new \App\Models\User();
        $directUser = $userModel->find($userId);
        echo "Busca direta: " . ($directUser ? 'ENCONTRADO' : 'NULL') . "<br>";
        
        if ($directUser) {
            echo "Nome direto: " . $directUser['name'] . "<br>";
        }
    }
}
?>

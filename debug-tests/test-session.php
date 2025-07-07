<?php
require_once 'vendor/autoload.php';

echo "<h1>Teste da Session</h1>";

// Teste 1: Verificar se a sessão PHP está ativa
echo "<h2>1. Teste da Sessão PHP:</h2>";
echo "Status da sessão: " . session_status() . "<br>";
echo "Nome da sessão: " . session_name() . "<br>";

// Inicia a sessão se não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "Sessão iniciada manualmente<br>";
}

// Teste 2: Verificar a classe Session
echo "<h2>2. Teste da Classe Session:</h2>";
try {
    \App\Core\Session::start();
    echo "Session::start() executado com sucesso<br>";
    
    // Definir um valor de teste
    \App\Core\Session::set('teste', 'valor_teste');
    echo "Valor definido: teste = valor_teste<br>";
    
    // Recuperar o valor
    $valor = \App\Core\Session::get('teste');
    echo "Valor recuperado: " . $valor . "<br>";
    
    // Verificar se existe
    $existe = \App\Core\Session::has('teste');
    echo "Chave 'teste' existe: " . ($existe ? 'SIM' : 'NÃO') . "<br>";
    
} catch (Exception $e) {
    echo "❌ Erro na classe Session: " . $e->getMessage() . "<br>";
}

// Teste 3: Simular login manual
echo "<h2>3. Simulando Login Manual:</h2>";
try {
    // Definir user_id na sessão
    \App\Core\Session::set('user_id', 'admin_002');
    echo "user_id definido na sessão<br>";
    
    // Verificar se Auth::check() funciona
    $isLoggedIn = \App\Core\Auth::check();
    echo "Auth::check(): " . ($isLoggedIn ? 'TRUE' : 'FALSE') . "<br>";
    
    // Tentar recuperar o usuário
    $user = \App\Core\Auth::user();
    echo "Auth::user(): " . ($user ? 'DADOS ENCONTRADOS' : 'NULL') . "<br>";
    
    if ($user) {
        echo "Nome: " . ($user['name'] ?? 'N/A') . "<br>";
        echo "Email: " . ($user['email'] ?? 'N/A') . "<br>";
        echo "Role: " . ($user['role'] ?? 'N/A') . "<br>";
        
        $isAdmin = \App\Core\Auth::isAdmin();
        $isAnalyst = \App\Core\Auth::isAnalyst();
        
        echo "isAdmin(): " . ($isAdmin ? 'TRUE' : 'FALSE') . "<br>";
        echo "isAnalyst(): " . ($isAnalyst ? 'TRUE' : 'FALSE') . "<br>";
        
        if ($isAdmin || $isAnalyst) {
            echo "✅ <strong>DROPDOWN DEVE APARECER</strong><br>";
        } else {
            echo "❌ <strong>DROPDOWN NÃO DEVE APARECER</strong><br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erro no teste de login: " . $e->getMessage() . "<br>";
}

// Teste 4: Verificar conteúdo da sessão
echo "<h2>4. Conteúdo da Sessão:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<hr>";
echo "<p><a href='/dashboard'>Ir para Dashboard</a></p>";
?>

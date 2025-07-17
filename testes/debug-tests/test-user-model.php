<?php
require_once 'vendor/autoload.php';

echo "<h1>Teste da Model User</h1>";

try {
    $userModel = new \App\Models\User();
    
    echo "<h2>1. Testando findByEmail:</h2>";
    $user = $userModel->findByEmail('admin@sistema.com');
    
    if ($user) {
        echo "✅ Usuário encontrado!<br>";
        echo "ID: " . $user['id'] . "<br>";
        echo "Nome: " . $user['name'] . "<br>";
        echo "Email: " . $user['email'] . "<br>";
        echo "Role: " . $user['role'] . "<br>";
        echo "Ativo: " . ($user['active'] ? 'SIM' : 'NÃO') . "<br>";
        echo "Aprovado: " . ($user['approved'] ? 'SIM' : 'NÃO') . "<br>";
        
        echo "<h2>2. Testando find por ID:</h2>";
        $userById = $userModel->find($user['id']);
        
        if ($userById) {
            echo "✅ Usuário encontrado por ID!<br>";
            echo "Nome: " . $userById['name'] . "<br>";
            echo "Role: " . $userById['role'] . "<br>";
        } else {
            echo "❌ Usuário não encontrado por ID<br>";
        }
        
        echo "<h2>3. Testando Auth com usuário encontrado:</h2>";
        
        // Iniciar sessão
        session_start();
        
        // Definir user_id na sessão
        \App\Core\Session::start();
        \App\Core\Session::set('user_id', $user['id']);
        
        echo "user_id definido na sessão: " . $user['id'] . "<br>";
        
        // Testar Auth
        $authUser = \App\Core\Auth::user();
        
        if ($authUser) {
            echo "✅ Auth::user() funcionou!<br>";
            echo "Nome: " . $authUser['name'] . "<br>";
            echo "Role: " . $authUser['role'] . "<br>";
            
            $isAdmin = \App\Core\Auth::isAdmin();
            $isAnalyst = \App\Core\Auth::isAnalyst();
            
            echo "isAdmin(): " . ($isAdmin ? 'TRUE' : 'FALSE') . "<br>";
            echo "isAnalyst(): " . ($isAnalyst ? 'TRUE' : 'FALSE') . "<br>";
            
            if ($isAdmin || $isAnalyst) {
                echo "✅ <strong>DROPDOWN DEVE APARECER NO DASHBOARD</strong><br>";
            } else {
                echo "❌ <strong>DROPDOWN NÃO DEVE APARECER</strong><br>";
            }
        } else {
            echo "❌ Auth::user() retornou null<br>";
        }
        
    } else {
        echo "❌ Usuário admin@sistema.com não encontrado<br>";
        
        echo "<h2>Todos os usuários:</h2>";
        $allUsers = $userModel->all();
        foreach ($allUsers as $u) {
            echo "- " . $u['email'] . " (Role: " . $u['role'] . ")<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
}

echo "<hr>";
echo "<p><a href='/dashboard'>Ir para Dashboard</a></p>";
?>

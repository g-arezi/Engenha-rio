<?php
require_once 'vendor/autoload.php';

echo "<h1>Teste da Classe Auth</h1>";

// Inicia a sessão
session_start();

// Simula um usuário logado
$_SESSION['user'] = [
    'id' => 'admin_002',
    'name' => 'Administrador do Sistema',
    'email' => 'admin@sistema.com',
    'role' => 'admin',
    'active' => true,
    'approved' => true
];

echo "<h2>1. Estado da Sessão:</h2>";
echo "Variável \$_SESSION['user']: " . (isset($_SESSION['user']) ? 'DEFINIDA' : 'NÃO DEFINIDA') . "<br>";
if (isset($_SESSION['user'])) {
    echo "Role na sessão: " . $_SESSION['user']['role'] . "<br>";
}

echo "<h2>2. Testando Classe Auth:</h2>";
try {
    $user = \App\Core\Auth::user();
    echo "Auth::user() retornou: " . ($user ? 'DADOS' : 'NULL') . "<br>";
    if ($user) {
        echo "Nome: " . ($user['name'] ?? 'N/A') . "<br>";
        echo "Email: " . ($user['email'] ?? 'N/A') . "<br>";
        echo "Role: " . ($user['role'] ?? 'N/A') . "<br>";
    }
    
    $isAdmin = \App\Core\Auth::isAdmin();
    $isAnalyst = \App\Core\Auth::isAnalyst();
    
    echo "<h3>Permissões:</h3>";
    echo "Auth::isAdmin(): " . ($isAdmin ? 'TRUE' : 'FALSE') . "<br>";
    echo "Auth::isAnalyst(): " . ($isAnalyst ? 'TRUE' : 'FALSE') . "<br>";
    
    echo "<h3>Condição do Dropdown:</h3>";
    echo "(\$isAdmin || \$isAnalyst): " . (($isAdmin || $isAnalyst) ? 'TRUE' : 'FALSE') . "<br>";
    
    if ($isAdmin || $isAnalyst) {
        echo "✅ <strong>DROPDOWN DEVE APARECER</strong><br>";
    } else {
        echo "❌ <strong>DROPDOWN NÃO DEVE APARECER</strong><br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro na classe Auth: " . $e->getMessage() . "<br>";
}

echo "<h2>3. Verificando Métodos da Classe Auth:</h2>";
try {
    $reflection = new ReflectionClass('App\Core\Auth');
    echo "Classe Auth existe: SIM<br>";
    
    $methods = $reflection->getMethods();
    echo "Métodos disponíveis:<br>";
    foreach ($methods as $method) {
        if ($method->isPublic() && $method->isStatic()) {
            echo "- " . $method->getName() . "<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erro ao verificar classe Auth: " . $e->getMessage() . "<br>";
}
?>

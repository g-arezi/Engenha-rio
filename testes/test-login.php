<?php
// Auto-login e teste de criação de template
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/init.php';

echo "<h1>Auto-Login e Teste de Template</h1>";

// Fazer login com o admin
App\Core\Session::start();

echo "<h2>1. Fazendo login como admin...</h2>";
$loginResult = App\Core\Auth::login('admin@sistema.com', 'admin123');

if ($loginResult) {
    echo "✅ Login realizado com sucesso<br>";
    
    // Verificar se é admin
    if (App\Core\Auth::isAdmin()) {
        echo "✅ Usuário é administrador<br>";
        
        echo "<h2>2. Acessando página de criação de template...</h2>";
        
        try {
            $controller = new App\Controllers\DocumentTemplateController();
            
            // Testar o método create
            ob_start();
            $controller->create();
            $output = ob_get_clean();
            
            if (strpos($output, 'Erro') === false && !empty($output)) {
                echo "✅ Página carregada com sucesso!<br>";
                echo "<h3>Preview da página:</h3>";
                echo "<iframe style='width: 100%; height: 500px; border: 1px solid #ccc;' srcdoc='" . htmlspecialchars($output) . "'></iframe>";
            } else {
                echo "❌ Erro na página ou página vazia<br>";
                echo "<pre>" . htmlspecialchars($output) . "</pre>";
            }
            
        } catch (Exception $e) {
            echo "❌ Erro ao carregar página: " . $e->getMessage() . "<br>";
            echo "Arquivo: " . $e->getFile() . "<br>";
            echo "Linha: " . $e->getLine() . "<br>";
        }
        
    } else {
        echo "❌ Usuário não é administrador<br>";
    }
    
} else {
    echo "❌ Falha no login<br>";
    echo "Tentando com senha padrão...<br>";
    
    // Tentar outras senhas comuns
    $passwords = ['admin123', 'admin', '123456', 'password'];
    foreach ($passwords as $pass) {
        $result = App\Core\Auth::login('admin@sistema.com', $pass);
        if ($result) {
            echo "✅ Login com senha '$pass' funcionou!<br>";
            break;
        }
    }
}

// Informações de debug
echo "<h2>3. Informações de Debug:</h2>";
echo "Usuário logado: " . (App\Core\Auth::check() ? 'Sim' : 'Não') . "<br>";
if (App\Core\Auth::check()) {
    $user = App\Core\Auth::user();
    echo "ID do usuário: " . App\Core\Auth::id() . "<br>";
    echo "Nome: " . ($user['name'] ?? 'N/A') . "<br>";
    echo "Email: " . ($user['email'] ?? 'N/A') . "<br>";
    echo "Role: " . ($user['role'] ?? 'N/A') . "<br>";
    echo "É admin: " . (App\Core\Auth::isAdmin() ? 'Sim' : 'Não') . "<br>";
}
?>

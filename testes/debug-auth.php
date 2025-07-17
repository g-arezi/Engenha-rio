<?php
// Debug de autenticação
require_once __DIR__ . '/init.php';

echo "<h1>🔍 Debug de Autenticação</h1>";

// Iniciar sessão
App\Core\Session::start();

echo "<h2>1. Status da Sessão:</h2>";
echo "Session ID: " . session_id() . "<br>";
echo "Session Data: <pre>" . print_r($_SESSION, true) . "</pre>";

echo "<h2>2. Testando Auth::check():</h2>";
$isLogged = App\Core\Auth::check();
echo "Auth::check(): " . ($isLogged ? "✅ TRUE" : "❌ FALSE") . "<br>";

if ($isLogged) {
    echo "<h2>3. Dados do Usuário:</h2>";
    $user = App\Core\Auth::user();
    if ($user) {
        echo "ID: " . $user['id'] . "<br>";
        echo "Nome: " . $user['name'] . "<br>";
        echo "Email: " . $user['email'] . "<br>";
        echo "Role: " . $user['role'] . "<br>";
        
        echo "<h2>4. Testando Auth::isAdmin():</h2>";
        $isAdmin = App\Core\Auth::isAdmin();
        echo "Auth::isAdmin(): " . ($isAdmin ? "✅ TRUE" : "❌ FALSE") . "<br>";
        
        if ($isAdmin) {
            echo "<h2>5. Testando acesso ao controller:</h2>";
            try {
                $controller = new App\Controllers\DocumentTemplateController();
                echo "✅ Controller criado com sucesso<br>";
                
                // Simular requisição GET
                $_SERVER['REQUEST_METHOD'] = 'GET';
                $_SERVER['REQUEST_URI'] = '/admin/document-templates';
                
                ob_start();
                $controller->index();
                $output = ob_get_clean();
                
                if (!empty($output)) {
                    echo "✅ Método index executado!<br>";
                    echo "<h3>Output gerado:</h3>";
                    echo "<div style='border: 1px solid #ccc; padding: 10px; max-height: 300px; overflow-y: auto;'>";
                    echo $output;
                    echo "</div>";
                } else {
                    echo "⚠️ Método executado mas sem output<br>";
                }
                
            } catch (Exception $e) {
                echo "❌ Erro: " . $e->getMessage() . "<br>";
                echo "Arquivo: " . $e->getFile() . "<br>";
                echo "Linha: " . $e->getLine() . "<br>";
            }
        }
    } else {
        echo "❌ Não foi possível carregar dados do usuário<br>";
    }
} else {
    echo "<h2>3. Fazendo login manual:</h2>";
    $_SESSION['user_id'] = 'admin_002';
    echo "Session definida manualmente<br>";
    
    $isLogged2 = App\Core\Auth::check();
    echo "Auth::check() após definir sessão: " . ($isLogged2 ? "✅ TRUE" : "❌ FALSE") . "<br>";
}
?>

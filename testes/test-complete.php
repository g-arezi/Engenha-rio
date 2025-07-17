<?php
// Teste final com autenticação completa
require_once __DIR__ . '/init.php';

// Iniciar sessão e fazer login como admin
App\Core\Session::start();

// Simular login direto
$_SESSION['user_id'] = 'admin_002';

echo "<h1>🔧 Teste Final do Sistema de Templates</h1>";

if (App\Core\Auth::check()) {
    echo "✅ Usuário logado: " . App\Core\Auth::user()['name'] . "<br>";
    
    if (App\Core\Auth::isAdmin()) {
        echo "✅ Permissões de admin confirmadas<br>";
        
        echo "<h2>📝 Testando página de criação...</h2>";
        
        try {
            $controller = new App\Controllers\DocumentTemplateController();
            
            ob_start();
            $controller->create();
            $output = ob_get_clean();
            
            if (!empty($output) && strpos($output, 'Erro') === false) {
                echo "✅ Página carregada com sucesso!<br>";
                echo "<hr>";
                echo $output;
            } else {
                echo "❌ Problema na página<br>";
                echo "<pre>" . htmlspecialchars($output) . "</pre>";
            }
            
        } catch (Exception $e) {
            echo "❌ Erro: " . $e->getMessage() . "<br>";
        }
        
    } else {
        echo "❌ Usuário não tem permissões de admin<br>";
    }
} else {
    echo "❌ Usuário não está logado<br>";
}
?>

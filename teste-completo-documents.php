<?php
// Script para login automático e teste de /documents
session_start();

require_once 'vendor/autoload.php';

use App\Core\Auth;
use App\Core\Config;
use App\Core\Session;

Config::load();
Session::start();

echo "<h1>🔐 Login Automático e Teste de Documents</h1>";
echo "<hr>";

// Verificar se já está logado
if (Auth::check()) {
    $user = Auth::user();
    echo "<h2>✅ Usuário já está logado!</h2>";
    echo "<p><strong>Nome:</strong> " . $user['name'] . "</p>";
    echo "<p><strong>Email:</strong> " . $user['email'] . "</p>";
    echo "<p><strong>É Admin:</strong> " . (Auth::isAdmin() ? 'Sim' : 'Não') . "</p>";
} else {
    echo "<h2>⚠️ Usuário não está logado</h2>";
    
    // Tentar login com credenciais de admin
    echo "<p>Tentando fazer login automático...</p>";
    
    // Carregar usuários para encontrar um admin
    $usersFile = __DIR__ . '/data/users.json';
    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true);
        
        foreach ($users as $user) {
            if ($user['role'] === 'admin' && $user['active'] && $user['approved']) {
                echo "<p>Encontrado usuário admin: " . $user['email'] . "</p>";
                
                // Simular login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['authenticated'] = true;
                
                echo "<p>✅ Login simulado com sucesso!</p>";
                break;
            }
        }
    }
}

echo "<hr>";

// Agora testar o acesso a /documents
echo "<h2>📄 Testando Acesso a /documents</h2>";

if (Auth::check()) {
    try {
        echo "<p>⚙️ Instanciando DocumentController...</p>";
        $controller = new \App\Controllers\DocumentController();
        
        echo "<p>⚙️ Executando método index()...</p>";
        
        // Capturar output do controller
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        
        if (!empty($output)) {
            echo "<p>✅ Controller executou com sucesso!</p>";
            
            // Mostrar início do output
            echo "<h3>🖥️ Preview do Output:</h3>";
            echo "<div style='border: 1px solid #ddd; padding: 10px; max-height: 300px; overflow-y: auto; background: #f9f9f9;'>";
            
            // Tentar extrair o título
            if (preg_match('/<title[^>]*>(.*?)<\/title>/i', $output, $matches)) {
                echo "<p><strong>Título da página:</strong> " . $matches[1] . "</p>";
            }
            
            // Mostrar parte do HTML
            echo "<pre>" . htmlspecialchars(substr($output, 0, 1000)) . "...</pre>";
            echo "</div>";
            
            echo "<h3>✅ SUCCESS - A página /documents está funcionando!</h3>";
            
        } else {
            echo "<p>⚠️ Controller executou mas não produziu output</p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Erro ao executar DocumentController: " . $e->getMessage() . "</p>";
        echo "<details>";
        echo "<summary>Stack trace</summary>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        echo "</details>";
    }
} else {
    echo "<p>❌ Não é possível testar - usuário não autenticado</p>";
}

echo "<hr>";
echo "<h2>🔗 Links para Teste Manual</h2>";
echo "<p><a href='/documents' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>📄 Testar /documents</a></p>";
echo "<p><a href='/dashboard' target='_blank' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>📊 Ir para Dashboard</a></p>";
echo "<p><a href='/admin/users' target='_blank' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>👥 Gerenciar Usuários</a></p>";

?>

<script>
// Auto-redirect para testar /documents se deu tudo certo
setTimeout(function() {
    if (document.querySelector('h3') && document.querySelector('h3').textContent.includes('SUCCESS')) {
        console.log('Redirecionando para /documents em 3 segundos...');
        setTimeout(function() {
            window.open('/documents', '_blank');
        }, 3000);
    }
}, 1000);
</script>

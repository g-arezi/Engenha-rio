<?php
// Script para fazer login automático e testar documentos
require_once 'vendor/autoload.php';

use App\Core\Session;
use App\Core\Auth;
use App\Models\User;

// Inicializar sessão
Session::start();

echo "<!DOCTYPE html>";
echo "<html><head><title>Teste Automatizado</title></head><body>";
echo "<h1>🚀 Teste Automatizado - Sistema Engenha Rio</h1>";

// Fazer login automático se necessário
if (!Auth::check()) {
    echo "<h2>🔐 Fazendo login automático...</h2>";
    
    try {
        $userModel = new User();
        
        // Tentar encontrar usuário admin
        $adminUser = $userModel->findByEmail('admin@sistema.com');
        
        if (!$adminUser) {
            echo "📝 Criando usuário admin...<br>";
            
            $userData = [
                'name' => 'Administrador do Sistema',
                'email' => 'admin@sistema.com', 
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role' => 'admin',
                'active' => true,
                'approved' => true,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $userId = $userModel->create($userData);
            if ($userId) {
                $adminUser = $userModel->find($userId);
                echo "✅ Usuário admin criado com ID: $userId<br>";
            } else {
                echo "❌ Erro ao criar usuário admin<br>";
            }
        }
        
        if ($adminUser) {
            // Fazer login direto
            Session::set('user_id', $adminUser['id']);
            Session::regenerate();
            echo "✅ Login realizado como: " . $adminUser['name'] . "<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Erro no login: " . $e->getMessage() . "<br>";
    }
}

// Verificar status do login
if (Auth::check()) {
    $user = Auth::user();
    echo "<h2>✅ Status: Logado</h2>";
    echo "👤 Usuário: " . $user['name'] . "<br>";
    echo "📧 Email: " . $user['email'] . "<br>";
    echo "🎯 Role: " . $user['role'] . "<br>";
    
    echo "<h2>🧪 Testando DocumentController...</h2>";
    
    try {
        $controller = new \App\Controllers\DocumentController();
        
        // Capturar a saída do controller
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        
        if (strlen($output) > 100) {
            echo "✅ DocumentController funcionando! (Saída: " . strlen($output) . " bytes)<br>";
            
            // Mostrar uma prévia da saída
            echo "<h3>📋 Prévia da página de documentos:</h3>";
            echo "<div style='border:1px solid #ccc; padding:10px; max-height:300px; overflow:auto; background:#f5f5f5;'>";
            
            // Extrair o título da página
            if (preg_match('/<title>(.*?)<\/title>/i', $output, $matches)) {
                echo "<strong>Título:</strong> " . $matches[1] . "<br><br>";
            }
            
            // Extrair alguns elementos importantes
            if (strpos($output, 'Documentos') !== false) {
                echo "✅ Palavra 'Documentos' encontrada<br>";
            }
            
            if (strpos($output, 'fas fa-') !== false) {
                echo "✅ Ícones FontAwesome encontrados<br>";
            }
            
            if (strpos($output, 'text-dark') !== false) {
                echo "✅ Classes de cor escura aplicadas<br>";
            }
            
            echo "</div>";
            
            echo "<h3>🔗 Links de Teste:</h3>";
            echo '<a href="/documents" target="_blank" style="background:#007bff;color:white;padding:10px;text-decoration:none;border-radius:5px;">📄 Abrir Documentos</a><br><br>';
            echo '<a href="/dashboard" target="_blank" style="background:#28a745;color:white;padding:10px;text-decoration:none;border-radius:5px;">🏠 Dashboard</a><br><br>';
            echo '<a href="/admin/users" target="_blank" style="background:#dc3545;color:white;padding:10px;text-decoration:none;border-radius:5px;">👥 Usuários Admin</a><br><br>';
            
        } else {
            echo "⚠️ Controller executou mas pouca saída gerada (" . strlen($output) . " bytes)<br>";
            echo "Saída: <pre>" . htmlspecialchars($output) . "</pre>";
        }
        
    } catch (Exception $e) {
        echo "❌ Erro no DocumentController: " . $e->getMessage() . "<br>";
        echo "📂 Arquivo: " . $e->getFile() . "<br>";
        echo "📍 Linha: " . $e->getLine() . "<br>";
    }
    
} else {
    echo "<h2>❌ Status: Não logado</h2>";
    echo '<a href="/login">🔐 Fazer Login Manual</a><br>';
}

echo "<hr>";
echo "<h2>🔧 Informações do Sistema:</h2>";
echo "📁 Diretório atual: " . getcwd() . "<br>";
echo "🌐 URI atual: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "<br>";
echo "⚙️ Versão PHP: " . PHP_VERSION . "<br>";

echo "</body></html>";
?>

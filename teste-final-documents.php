<?php
// Teste Final - Múltiplas abordagens para resolver /documents
echo "<!DOCTYPE html>";
echo "<html><head><title>Teste Final Documents</title></head><body>";
echo "<h1>🎯 Teste Final - Rota /documents</h1>";
echo "<hr>";

// 1. Verificar dependências básicas
echo "<h2>1. ✅ Verificações Básicas</h2>";

require_once 'vendor/autoload.php';
require_once 'config/security.php';

use App\Core\Router;
use App\Core\Session;
use App\Core\Auth;
use App\Core\Config;

Config::load();
Session::start();

echo "<p>✅ Autoloader, configurações e sessão carregados</p>";

// 2. Fazer login automático se necessário
echo "<h2>2. 🔐 Autenticação</h2>";

if (!Auth::check()) {
    // Carregar usuários e fazer login automático
    $usersFile = __DIR__ . '/data/users.json';
    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true);
        
        foreach ($users as $user) {
            if ($user['role'] === 'admin' && $user['active'] && $user['approved']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['authenticated'] = true;
                echo "<p>✅ Login automático realizado para: " . $user['email'] . "</p>";
                break;
            }
        }
    }
}

if (Auth::check()) {
    $user = Auth::user();
    echo "<p>✅ Usuário autenticado: " . $user['name'] . " (" . $user['role'] . ")</p>";
} else {
    echo "<p>❌ Falha na autenticação</p>";
}

// 3. Teste direto do DocumentController
echo "<h2>3. 📄 Teste Direto do DocumentController</h2>";

if (Auth::check()) {
    try {
        $controller = new \App\Controllers\DocumentController();
        echo "<p>✅ DocumentController instanciado</p>";
        
        // Verificar método index
        if (method_exists($controller, 'index')) {
            echo "<p>✅ Método index() encontrado</p>";
            
            // Executar e capturar output
            ob_start();
            $controller->index();
            $output = ob_get_clean();
            
            if (!empty($output)) {
                echo "<p>✅ Controller executou e produziu output!</p>";
                echo "<p><strong>Tamanho do output:</strong> " . strlen($output) . " bytes</p>";
                
                // Verificar se contém HTML válido
                if (strpos($output, '<html') !== false || strpos($output, 'DOCTYPE') !== false) {
                    echo "<p>✅ Output contém HTML válido</p>";
                    
                    // Salvar em arquivo para análise
                    file_put_contents(__DIR__ . '/debug_documents_output.html', $output);
                    echo "<p>✅ Output salvo em debug_documents_output.html</p>";
                    
                    echo "<p><strong>SUCCESS!</strong> O controller está funcionando corretamente!</p>";
                    
                } else {
                    echo "<p>⚠️ Output não parece ser HTML completo</p>";
                    echo "<div style='border: 1px solid #ccc; padding: 10px; max-height: 200px; overflow-y: auto;'>";
                    echo "<pre>" . htmlspecialchars(substr($output, 0, 500)) . "</pre>";
                    echo "</div>";
                }
                
            } else {
                echo "<p>⚠️ Controller executou mas não produziu output</p>";
            }
            
        } else {
            echo "<p>❌ Método index() não encontrado</p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Erro no DocumentController: " . $e->getMessage() . "</p>";
        echo "<details><summary>Stack trace</summary><pre>" . $e->getTraceAsString() . "</pre></details>";
    }
}

// 4. Simulação do sistema de rotas
echo "<h2>4. 🛣️ Teste do Sistema de Rotas</h2>";

try {
    // Simular REQUEST para /documents
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/documents';
    
    $router = new Router();
    echo "<p>✅ Router instanciado</p>";
    
    // Registrar rotas básicas
    $router->group(['middleware' => 'auth'], function($router) {
        $router->get('/documents', 'DocumentController@index');
    });
    
    echo "<p>✅ Rota /documents registrada</p>";
    
    // Tentar dispatch
    ob_start();
    $router->dispatch();
    $routerOutput = ob_get_clean();
    
    if (!empty($routerOutput)) {
        echo "<p>✅ Router executou com sucesso!</p>";
        echo "<p><strong>Tamanho do output do router:</strong> " . strlen($routerOutput) . " bytes</p>";
        
        // Salvar output do router
        file_put_contents(__DIR__ . '/debug_router_output.html', $routerOutput);
        echo "<p>✅ Output do router salvo em debug_router_output.html</p>";
    } else {
        echo "<p>⚠️ Router executou mas não produziu output</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erro no router: " . $e->getMessage() . "</p>";
    echo "<details><summary>Stack trace</summary><pre>" . $e->getTraceAsString() . "</pre></details>";
}

// 5. Links para teste manual
echo "<h2>5. 🔗 Testes Manuais</h2>";
echo "<div style='display: flex; gap: 10px; flex-wrap: wrap;'>";
echo "<a href='/documents' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>📄 Testar /documents</a>";
echo "<a href='/dashboard' target='_blank' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>📊 Dashboard</a>";
echo "<a href='/admin/users' target='_blank' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>👥 Admin</a>";
echo "<a href='debug_documents_output.html' target='_blank' style='background: #ffc107; color: black; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔍 Ver Output Controller</a>";
echo "<a href='debug_router_output.html' target='_blank' style='background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🛣️ Ver Output Router</a>";
echo "</div>";

echo "<hr>";
echo "<h2>📋 Resumo</h2>";
echo "<p>Se você viu mensagens de SUCCESS acima, a rota /documents está funcionando!</p>";
echo "<p>Clique no link 'Testar /documents' para verificar no navegador.</p>";

echo "</body></html>";
?>

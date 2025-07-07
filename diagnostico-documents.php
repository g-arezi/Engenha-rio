<?php
// Teste específico para diagnóstico da rota /documents
echo "<h1>🔍 Diagnóstico da Rota /documents</h1>";
echo "<hr>";

// Verificar se o autoloader está carregando
echo "<h2>1. Verificação do Autoloader</h2>";
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    echo "✅ Autoloader carregado com sucesso<br>";
} else {
    echo "❌ Autoloader não encontrado<br>";
    exit;
}

// Verificar configurações
echo "<h2>2. Verificação das Configurações</h2>";
if (file_exists('config/security.php')) {
    require_once 'config/security.php';
    echo "✅ Configurações de segurança carregadas<br>";
}

use App\Core\Session;
use App\Core\Auth;
use App\Core\Config;

try {
    Config::load();
    echo "✅ Configurações gerais carregadas<br>";
} catch (Exception $e) {
    echo "❌ Erro ao carregar configurações: " . $e->getMessage() . "<br>";
}

// Inicializar sessão
echo "<h2>3. Verificação da Sessão</h2>";
try {
    Session::start();
    echo "✅ Sessão iniciada com sucesso<br>";
    
    if (Auth::check()) {
        $user = Auth::user();
        echo "✅ Usuário autenticado: " . ($user['name'] ?? 'Nome não disponível') . "<br>";
        echo "✅ É admin: " . (Auth::isAdmin() ? 'Sim' : 'Não') . "<br>";
    } else {
        echo "⚠️ Usuário não autenticado<br>";
    }
} catch (Exception $e) {
    echo "❌ Erro na sessão: " . $e->getMessage() . "<br>";
}

// Verificar DocumentController
echo "<h2>4. Verificação do DocumentController</h2>";
try {
    $controller = new \App\Controllers\DocumentController();
    echo "✅ DocumentController instanciado com sucesso<br>";
    
    // Verificar se o método index existe
    if (method_exists($controller, 'index')) {
        echo "✅ Método index() existe no controller<br>";
    } else {
        echo "❌ Método index() não encontrado no controller<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro ao instanciar DocumentController: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

// Verificar Model Document
echo "<h2>5. Verificação do Model Document</h2>";
try {
    $documentModel = new \App\Models\Document();
    echo "✅ Model Document instanciado com sucesso<br>";
    
    // Testar método all
    if (method_exists($documentModel, 'all')) {
        echo "✅ Método all() existe no model<br>";
        
        $documents = $documentModel->all();
        echo "✅ Documentos carregados: " . count($documents) . " encontrados<br>";
    } else {
        echo "❌ Método all() não encontrado no model<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro ao instanciar Model Document: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

// Verificar views
echo "<h2>6. Verificação das Views</h2>";
$viewPaths = [
    'views/documents/index.php',
    'views/layouts/app.php'
];

foreach ($viewPaths as $view) {
    if (file_exists($view)) {
        echo "✅ View encontrada: $view<br>";
    } else {
        echo "❌ View não encontrada: $view<br>";
    }
}

// Teste do sistema de rotas
echo "<h2>7. Teste do Sistema de Rotas</h2>";
try {
    $router = new \App\Core\Router();
    echo "✅ Router instanciado com sucesso<br>";
    
    // Simular uma requisição para /documents
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/documents';
    
    echo "⚙️ Simulando requisição GET /documents...<br>";
    
} catch (Exception $e) {
    echo "❌ Erro no router: " . $e->getMessage() . "<br>";
}

// Tentar executar o controller diretamente
echo "<h2>8. Teste Direto do Controller</h2>";
if (Auth::check()) {
    try {
        echo "⚙️ Tentando executar DocumentController->index() diretamente...<br>";
        
        $controller = new \App\Controllers\DocumentController();
        
        // Capturar output
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        
        if (!empty($output)) {
            echo "✅ Controller executou com sucesso!<br>";
            echo "<strong>Output do controller:</strong><br>";
            echo "<div style='border: 1px solid #ccc; padding: 10px; max-height: 200px; overflow-y: auto;'>";
            echo htmlspecialchars(substr($output, 0, 500)) . "...";
            echo "</div>";
        } else {
            echo "⚠️ Controller executou mas não produziu output<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Erro ao executar controller: " . $e->getMessage() . "<br>";
        echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "⚠️ Não é possível testar controller - usuário não autenticado<br>";
}

echo "<hr>";
echo "<h2>🔗 Links de Teste</h2>";
echo "<a href='/documents' target='_blank'>🎯 Testar /documents</a><br>";
echo "<a href='/login' target='_blank'>🔐 Ir para Login</a><br>";
echo "<a href='/dashboard' target='_blank'>📊 Ir para Dashboard</a><br>";

echo "<hr>";
echo "<p><strong>Diagnóstico concluído!</strong></p>";
?>

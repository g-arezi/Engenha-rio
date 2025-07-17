<?php
// Teste direto da rota de template
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Teste da Rota de Template</h1>";

// Simular requisição GET para /admin/document-templates/create
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/admin/document-templates/create';

echo "<h2>1. Incluindo sistema...</h2>";
require_once __DIR__ . '/init.php';
echo "✅ Sistema carregado<br>";

// Verificar se o usuário está logado
echo "<h2>2. Verificando autenticação...</h2>";
App\Core\Session::start();

// Simular admin logado
App\Core\Session::set('user_id', 'admin_test');

// Criar usuário admin no sistema
$userModel = new App\Models\User();
$adminUser = [
    'id' => 'admin_test',
    'name' => 'Admin Teste',
    'email' => 'admin@test.com',
    'role' => 'admin',
    'approved' => true
];

// Salvar usuário temporariamente
try {
    $userModel->create($adminUser);
    echo "✅ Usuário admin criado para teste<br>";
} catch (Exception $e) {
    echo "Usuário admin já existe ou erro: " . $e->getMessage() . "<br>";
}

if (App\Core\Auth::check()) {
    echo "✅ Usuário está logado<br>";
    $user = App\Core\Auth::user();
    if ($user) {
        echo "✅ Dados do usuário carregados: " . $user['name'] . "<br>";
        if (App\Core\Auth::isAdmin()) {
            echo "✅ Usuário é admin<br>";
        } else {
            echo "❌ Usuário NÃO é admin<br>";
        }
    } else {
        echo "❌ Erro ao carregar dados do usuário<br>";
    }
} else {
    echo "❌ Usuário NÃO está logado<br>";
}

echo "<h2>3. Testando Controller...</h2>";
try {
    $controller = new App\Controllers\DocumentTemplateController();
    echo "✅ Controller criado<br>";
    
    echo "<h3>Testando método create()...</h3>";
    ob_start();
    $controller->create();
    $output = ob_get_clean();
    
    if (!empty($output)) {
        echo "✅ Método create() executado com sucesso<br>";
        echo "<h4>Output gerado:</h4>";
        echo "<div style='border: 1px solid #ccc; padding: 10px; max-height: 400px; overflow-y: auto;'>";
        echo $output;
        echo "</div>";
    } else {
        echo "⚠️ Método create() executado mas sem output<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro no controller: " . $e->getMessage() . "<br>";
    echo "Arquivo: " . $e->getFile() . "<br>";
    echo "Linha: " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

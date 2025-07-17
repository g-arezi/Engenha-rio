<?php
/**
 * Debug específico para o sistema de documentos
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug do Sistema de Documentos</h1>";

// Verificar autoloader
require_once 'vendor/autoload.php';

// Verificar se as classes necessárias existem
echo "<h2>1. Verificação de Classes</h2>";

$classes = [
    'App\Core\Auth',
    'App\Core\Session', 
    'App\Core\Router',
    'App\Controllers\DocumentController',
    'App\Models\Document'
];

foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "✅ $class - OK<br>";
    } else {
        echo "❌ $class - NÃO ENCONTRADA<br>";
    }
}

echo "<h2>2. Verificação de Arquivos</h2>";

$files = [
    'src/Controllers/DocumentController.php',
    'src/Models/Document.php',
    'data/documents.json',
    'views/documents/index.php',
    'public/documents/',
    'uploads/documents/'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file - Existe<br>";
        if (is_dir($file)) {
            $count = count(scandir($file)) - 2; // Remove . e ..
            echo "&nbsp;&nbsp;&nbsp;📁 $count itens<br>";
        }
    } else {
        echo "❌ $file - NÃO EXISTE<br>";
    }
}

echo "<h2>3. Dados dos Documentos</h2>";

try {
    $documentsData = file_get_contents('data/documents.json');
    $documents = json_decode($documentsData, true);
    
    if ($documents) {
        echo "✅ Arquivo JSON válido<br>";
        echo "📄 " . count($documents) . " documentos encontrados<br>";
        
        echo "<table border='1' style='border-collapse: collapse; margin-top: 10px;'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Caminho</th><th>Arquivo Existe?</th></tr>";
        
        foreach ($documents as $doc) {
            $filePath = "public/" . $doc['file_path'];
            $exists = file_exists($filePath);
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($doc['id']) . "</td>";
            echo "<td>" . htmlspecialchars($doc['name']) . "</td>";
            echo "<td>" . htmlspecialchars($doc['file_path']) . "</td>";
            echo "<td>" . ($exists ? '✅' : '❌') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ Erro ao decodificar JSON<br>";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
}

echo "<h2>4. Teste do DocumentController</h2>";

try {
    // Simular autenticação para teste
    if (!session_id()) {
        session_start();
    }
    
    // Simular usuário logado
    $_SESSION['user'] = [
        'id' => 'admin_001',
        'name' => 'Admin Teste',
        'type' => 'administrador'
    ];
    
    $controller = new \App\Controllers\DocumentController();
    echo "✅ DocumentController instanciado com sucesso<br>";
    
    // Testar método index
    ob_start();
    $controller->index();
    $output = ob_get_clean();
    
    if (strlen($output) > 100) {
        echo "✅ Método index() executou com sucesso (" . strlen($output) . " bytes de output)<br>";
    } else {
        echo "⚠️ Método index() executou mas com pouco output<br>";
        echo "Output: " . htmlspecialchars(substr($output, 0, 200)) . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro no DocumentController: " . $e->getMessage() . "<br>";
    echo "Stack trace:<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>5. Teste de Rotas</h2>";

try {
    $router = new \App\Core\Router();
    echo "✅ Router instanciado<br>";
    
    // Simular algumas rotas
    $router->get('/documents', 'DocumentController@index');
    $router->get('/documents/{id}', 'DocumentController@show');
    $router->get('/documents/{id}/download', 'DocumentController@download');
    
    echo "✅ Rotas de documentos adicionadas<br>";
    
} catch (Exception $e) {
    echo "❌ Erro no Router: " . $e->getMessage() . "<br>";
}

echo "<h2>6. Simulação de Requisição</h2>";

// Simular REQUEST_METHOD e REQUEST_URI para teste
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/documents';

echo "Simulando GET /documents<br>";

try {
    // Reinicializar router com rotas completas
    $router = new \App\Core\Router();
    
    // Rotas protegidas (simulando middleware auth)
    $router->group(['middleware' => 'auth'], function($router) {
        $router->get('/documents', 'DocumentController@index');
        $router->post('/documents/upload', 'DocumentController@upload');
        $router->get('/documents/{id}', 'DocumentController@show');
        $router->get('/documents/{id}/download', 'DocumentController@download');
        $router->delete('/documents/{id}', 'DocumentController@destroy');
    });
    
    echo "Tentando dispatch...<br>";
    
    // Capturar output do dispatch
    ob_start();
    $router->dispatch();
    $output = ob_get_clean();
    
    echo "Dispatch executado. Output length: " . strlen($output) . "<br>";
    
} catch (Exception $e) {
    echo "❌ Erro no dispatch: " . $e->getMessage() . "<br>";
}

echo "<h2>7. Links de Teste</h2>";
echo "<a href='/documents' target='_blank'>Testar /documents</a><br>";
echo "<a href='/documents/doc_002/download' target='_blank'>Testar download</a><br>";
echo "<a href='views/documents/index.php' target='_blank'>Testar view direta</a><br>";

?>

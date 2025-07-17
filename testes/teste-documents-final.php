<?php
/**
 * Teste simples do sistema de documentos
 */

require_once 'vendor/autoload.php';

// Inicializar sessão se necessário
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simular usuário logado para teste
$_SESSION['user'] = [
    'id' => 'admin_001',
    'name' => 'Admin Teste',
    'type' => 'administrador'
];

echo "<h1>Teste do Sistema de Documentos</h1>";

try {
    // Teste 1: Instanciar DocumentController
    echo "<h2>1. Testando DocumentController</h2>";
    $controller = new \App\Controllers\DocumentController();
    echo "✅ DocumentController criado com sucesso<br>";
    
    // Teste 2: Testar método index
    echo "<h2>2. Testando método index()</h2>";
    ob_start();
    $controller->index();
    $output = ob_get_clean();
    
    if (strlen($output) > 0) {
        echo "✅ Método index() executado (" . strlen($output) . " bytes)<br>";
    } else {
        echo "❌ Método index() não retornou conteúdo<br>";
    }
    
    // Teste 3: Verificar dados de documentos
    echo "<h2>3. Verificando dados de documentos</h2>";
    $documentModel = new \App\Models\Document();
    $documents = $documentModel->all();
    
    if ($documents && count($documents) > 0) {
        echo "✅ " . count($documents) . " documentos encontrados<br>";
        
        // Mostrar primeiro documento
        $firstDoc = array_values($documents)[0];
        echo "📄 Primeiro documento: " . htmlspecialchars($firstDoc['name'] ?? 'N/A') . "<br>";
        
        // Testar download do primeiro documento
        echo "<h2>4. Testando download</h2>";
        $docId = $firstDoc['id'];
        
        // Verificar se arquivo existe
        $possiblePaths = [
            "public/" . $firstDoc['file_path'],
            $firstDoc['file_path'],
            "uploads/" . $firstDoc['file_path'],
            "uploads/documents/" . basename($firstDoc['file_path'])
        ];
        
        $foundPath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $foundPath = $path;
                break;
            }
        }
        
        if ($foundPath) {
            echo "✅ Arquivo encontrado: $foundPath<br>";
            echo "📁 Tamanho: " . number_format(filesize($foundPath)) . " bytes<br>";
        } else {
            echo "❌ Arquivo não encontrado nos caminhos:<br>";
            foreach ($possiblePaths as $path) {
                echo "&nbsp;&nbsp;- $path<br>";
            }
        }
    } else {
        echo "❌ Nenhum documento encontrado<br>";
    }
    
    echo "<h2>5. Links de Teste</h2>";
    echo "<a href='/documents' target='_blank'>🔗 Abrir página de documentos</a><br>";
    
    if (isset($docId)) {
        echo "<a href='/documents/$docId' target='_blank'>🔗 Visualizar documento</a><br>";
        echo "<a href='/documents/$docId/download' target='_blank'>🔗 Download documento</a><br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>6. Informações do Sistema</h2>";
echo "📍 Diretório atual: " . __DIR__ . "<br>";
echo "🌐 REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "<br>";
echo "🔐 Sessão ativa: " . (session_id() ? 'Sim' : 'Não') . "<br>";
echo "👤 Usuário logado: " . ($_SESSION['user']['name'] ?? 'Nenhum') . "<br>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1 { color: #2c3e50; }
h2 { color: #34495e; margin-top: 30px; }
</style>

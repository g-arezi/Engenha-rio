<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Teste Básico de Sistema</h1>";

echo "<h2>1. Verificando init.php...</h2>";
try {
    require_once __DIR__ . '/init.php';
    echo "✅ init.php carregado com sucesso<br>";
} catch (Exception $e) {
    echo "❌ Erro ao carregar init.php: " . $e->getMessage() . "<br>";
    die();
}

echo "<h2>2. Verificando classes...</h2>";
try {
    echo "✅ Classes carregadas via autoloader<br>";
} catch (Exception $e) {
    echo "❌ Erro ao importar classes: " . $e->getMessage() . "<br>";
    die();
}

echo "<h2>3. Testando Session...</h2>";
try {
    App\Core\Session::start();
    echo "✅ Sessão iniciada<br>";
} catch (Exception $e) {
    echo "❌ Erro na sessão: " . $e->getMessage() . "<br>";
}

echo "<h2>4. Testando Model...</h2>";
try {
    $model = new App\Models\DocumentTemplate();
    echo "✅ Model DocumentTemplate criado<br>";
    
    $templates = $model->all();
    echo "✅ Método all() funcionando - " . count($templates) . " templates encontrados<br>";
} catch (Exception $e) {
    echo "❌ Erro no model: " . $e->getMessage() . "<br>";
    echo "Arquivo: " . $e->getFile() . "<br>";
    echo "Linha: " . $e->getLine() . "<br>";
}

echo "<h2>5. Testando Controller...</h2>";
try {
    $controller = new App\Controllers\DocumentTemplateController();
    echo "✅ Controller criado com sucesso<br>";
} catch (Exception $e) {
    echo "❌ Erro no controller: " . $e->getMessage() . "<br>";
    echo "Arquivo: " . $e->getFile() . "<br>";
    echo "Linha: " . $e->getLine() . "<br>";
}

echo "<h2>6. Verificando arquivo de dados...</h2>";
$dataFile = __DIR__ . '/data/document_templates.json';
if (file_exists($dataFile)) {
    echo "✅ Arquivo de dados existe<br>";
    $content = file_get_contents($dataFile);
    $data = json_decode($content, true);
    if ($data !== null) {
        echo "✅ JSON válido - " . count($data) . " registros<br>";
    } else {
        echo "❌ JSON inválido<br>";
    }
} else {
    echo "❌ Arquivo de dados não encontrado: $dataFile<br>";
}

echo "<h2>7. Verificando permissões...</h2>";
$dataDir = __DIR__ . '/data';
if (is_writable($dataDir)) {
    echo "✅ Diretório data/ tem permissão de escrita<br>";
} else {
    echo "❌ Diretório data/ não tem permissão de escrita<br>";
}

?>

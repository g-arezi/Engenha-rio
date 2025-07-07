<?php
// Arquivo de inicialização alternativa para contornar problemas de roteamento
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Sistema de Diagnóstico</h1>";

// Verificar a URI atual
$uri = $_SERVER['REQUEST_URI'] ?? 'N/A';
$method = $_SERVER['REQUEST_METHOD'] ?? 'N/A';

echo "<h2>Informações da Requisição:</h2>";
echo "URI: $uri<br>";
echo "Método: $method<br>";
echo "Servidor: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "<br>";

// Se for /documents, redirecionar diretamente
if ($uri === '/documents' || strpos($uri, '/documents') === 0) {
    echo "<h2>Redirecionando para DocumentController...</h2>";
    
    // Carregar o autoloader
    require_once __DIR__ . '/vendor/autoload.php';
    
    // Usar as classes
    use App\Core\Auth;
    use App\Core\Session;
    
    // Inicializar sessão
    Session::start();
    
    if (!Auth::check()) {
        echo "Usuário não autenticado. <a href='/login'>Fazer Login</a>";
        exit;
    }
    
    try {
        // Instanciar e executar o controller
        $controller = new \App\Controllers\DocumentController();
        $controller->index();
        exit;
    } catch (Exception $e) {
        echo "Erro no DocumentController: " . $e->getMessage();
        echo "<br>Arquivo: " . $e->getFile();
        echo "<br>Linha: " . $e->getLine();
        exit;
    }
}

// Menu de navegação
echo "<h2>Menu de Navegação:</h2>";
echo '<a href="/login">Login</a> | ';
echo '<a href="/dashboard">Dashboard</a> | ';
echo '<a href="/documents">Documentos</a> | ';
echo '<a href="/admin/users">Usuários</a><br><br>';

// Verificar se os arquivos principais existem
echo "<h2>Verificação de Arquivos:</h2>";

$files = [
    'vendor/autoload.php',
    'src/Controllers/DocumentController.php',
    'src/Core/Router.php',
    'src/Core/Auth.php',
    'views/documents/index.php'
];

foreach ($files as $file) {
    $exists = file_exists(__DIR__ . '/' . $file);
    echo "$file: " . ($exists ? '✅ Existe' : '❌ Não existe') . "<br>";
}

// Verificar se as classes podem ser carregadas
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    
    echo "<h2>Verificação de Classes:</h2>";
    
    $classes = [
        'App\\Controllers\\DocumentController',
        'App\\Core\\Router',
        'App\\Core\\Auth',
        'App\\Core\\Session'
    ];
    
    foreach ($classes as $class) {
        $exists = class_exists($class);
        echo "$class: " . ($exists ? '✅ Carregada' : '❌ Não carregada') . "<br>";
    }
}

// Informações do servidor
echo "<h2>Informações do Servidor:</h2>";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "<br>";
echo "Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "<br>";
echo "Working Directory: " . getcwd() . "<br>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1, h2 { color: #333; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>

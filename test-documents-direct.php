<?php
// Teste simples de roteamento
echo "<h1>Teste de Roteamento</h1>";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "URI solicitada: " . $uri . "<br>";

// Se for /documents, tentar carregar diretamente
if ($uri === '/documents') {
    echo "Tentando carregar a página de documentos...<br>";
    
    // Verificar se o usuário está logado
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        echo "Usuário não está logado. Redirecionando para login...<br>";
        echo '<a href="/login">Fazer Login</a>';
        exit;
    }
    
    echo "Usuário está logado. ID: " . $_SESSION['user_id'] . "<br>";
    
    // Tentar carregar a view diretamente
    try {
        require_once 'vendor/autoload.php';
        
        $documentsView = __DIR__ . '/views/documents/index.php';
        
        if (file_exists($documentsView)) {
            echo "Arquivo da view encontrado: " . $documentsView . "<br>";
            
            // Preparar variáveis para a view
            $documents = [];
            $currentProject = null;
            $currentType = '';
            $user = ['id' => $_SESSION['user_id'], 'name' => 'Usuário Teste'];
            $isAdmin = true;
            
            // Função auxiliar para formatação
            $formatBytes = function($size) {
                $units = ['B', 'KB', 'MB', 'GB'];
                $unitIndex = 0;
                while ($size >= 1024 && $unitIndex < count($units) - 1) {
                    $size /= 1024;
                    $unitIndex++;
                }
                return round($size, 2) . ' ' . $units[$unitIndex];
            };
            
            $title = 'Documentos - Engenha Rio';
            $showSidebar = true;
            $activeMenu = 'documents';
            
            ob_start();
            include $documentsView;
            $content = ob_get_clean();
            
            // Incluir o layout
            include __DIR__ . '/views/layouts/app.php';
            
        } else {
            echo "Arquivo da view NÃO encontrado: " . $documentsView . "<br>";
        }
        
    } catch (Exception $e) {
        echo "Erro ao carregar a view: " . $e->getMessage() . "<br>";
        echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
    
} else {
    echo "URI não é /documents. Redirecionando para o index normal...<br>";
    echo '<a href="/dashboard">Dashboard</a><br>';
    echo '<a href="/documents">Documentos</a><br>';
}
?>

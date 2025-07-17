<?php
/**
 * Acesso direto ao sistema de documentos
 * Este arquivo contorna problemas de roteamento
 */

// Incluir autoloader
require_once 'vendor/autoload.php';

// Inicializar sistema básico
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se usuário está logado
if (!isset($_SESSION['user'])) {
    // Simular login para teste
    $_SESSION['user'] = [
        'id' => 'admin_001',
        'name' => 'Admin Teste',
        'type' => 'administrador'
    ];
}

// Obter parâmetros da URL
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

try {
    $controller = new \App\Controllers\DocumentController();
    
    switch ($action) {
        case 'index':
        default:
            echo "<h1>📄 Sistema de Documentos - Acesso Direto</h1>";
            echo "<p><strong>Usuário:</strong> " . ($_SESSION['user']['name'] ?? 'N/A') . "</p>";
            echo "<hr>";
            
            // Executar método index
            $controller->index();
            break;
            
        case 'show':
            if ($id) {
                echo "<h1>👁️ Visualizar Documento: $id</h1>";
                echo "<p><a href='?action=index'>&larr; Voltar</a></p>";
                echo "<hr>";
                $controller->show($id);
            } else {
                echo "ID do documento não fornecido";
            }
            break;
            
        case 'download':
            if ($id) {
                echo "<h1>⬇️ Download Documento: $id</h1>";
                echo "<p>Iniciando download...</p>";
                $controller->download($id);
            } else {
                echo "ID do documento não fornecido";
            }
            break;
            
        case 'upload':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo "<h1>📤 Processando Upload</h1>";
                $controller->upload();
            } else {
                echo "<h1>📤 Upload de Documento</h1>";
                echo "<p><a href='?action=index'>&larr; Voltar</a></p>";
                echo "<hr>";
                ?>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="upload">
                    
                    <div style="margin-bottom: 15px;">
                        <label>Arquivo:</label><br>
                        <input type="file" name="file" required>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label>Nome:</label><br>
                        <input type="text" name="name" placeholder="Nome do documento" required>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label>Descrição:</label><br>
                        <textarea name="description" placeholder="Descrição opcional"></textarea>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label>Tipo:</label><br>
                        <select name="type">
                            <option value="projeto">Projeto</option>
                            <option value="contrato">Contrato</option>
                            <option value="licenca">Licença</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>
                    
                    <button type="submit">Fazer Upload</button>
                </form>
                <?php
            }
            break;
            
        case 'delete':
            if ($id) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo "<h1>🗑️ Excluindo Documento: $id</h1>";
                    $controller->destroy($id);
                } else {
                    echo "<h1>🗑️ Confirmar Exclusão</h1>";
                    echo "<p>Tem certeza que deseja excluir o documento $id?</p>";
                    echo "<form method='post'>";
                    echo "<button type='submit'>Confirmar Exclusão</button> ";
                    echo "<a href='?action=index'>Cancelar</a>";
                    echo "</form>";
                }
            } else {
                echo "ID do documento não fornecido";
            }
            break;
    }
    
} catch (Exception $e) {
    echo "<h1>❌ Erro</h1>";
    echo "<p><strong>Mensagem:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
    echo "<hr>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    background-color: #f8f9fa;
}
h1 { 
    color: #2c3e50; 
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
}
form {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    max-width: 500px;
}
input, textarea, select, button {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
button {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
}
button:hover {
    background: #2980b9;
}
a {
    color: #3498db;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
</style>

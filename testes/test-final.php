<?php
// Teste final da criação de template
require_once __DIR__ . '/init.php';

// Simular ambiente de teste
App\Core\Session::start();

// Simular dados de um admin logado
$_SESSION['user_id'] = 'admin_002';

// Verificar se está funcionando
if (App\Core\Auth::check() && App\Core\Auth::isAdmin()) {
    echo "✅ Admin logado com sucesso<br>";
} else {
    echo "❌ Problema de autenticação<br>";
}

// Testar submissão de formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h1>🔄 Processando Template...</h1>";
    
    echo "<h2>Dados recebidos:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    try {
        $controller = new App\Controllers\DocumentTemplateController();
        
        // Capturar qualquer redirecionamento
        ob_start();
        $controller->store();
        $output = ob_get_clean();
        
        echo "<h2>Resultado do processamento:</h2>";
        if (!empty($output)) {
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
        }
        
        // Verificar mensagens de sessão
        if (App\Core\Session::has('success')) {
            echo "<div style='background: #d4edda; padding: 10px; border: 1px solid #c3e6cb;'>";
            echo "✅ SUCESSO: " . App\Core\Session::get('success');
            echo "</div>";
        }
        
        if (App\Core\Session::has('error')) {
            echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb;'>";
            echo "❌ ERRO: " . App\Core\Session::get('error');
            echo "</div>";
        }
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb;'>";
        echo "❌ EXCEÇÃO: " . $e->getMessage();
        echo "<br>Arquivo: " . $e->getFile();
        echo "<br>Linha: " . $e->getLine();
        echo "</div>";
    }
    
} else {
    echo "<h1>📝 Formulário de Teste de Template</h1>";
    
    ?>
    <form method="POST" style="max-width: 600px;">
        <div style="margin: 10px 0;">
            <label>Nome do Template:</label><br>
            <input type="text" name="name" value="Template Teste Final" style="width: 100%; padding: 5px;">
        </div>
        
        <div style="margin: 10px 0;">
            <label>Tipo de Projeto:</label><br>
            <select name="project_type" style="width: 100%; padding: 5px;">
                <option value="residencial">Residencial</option>
                <option value="comercial">Comercial</option>
                <option value="reforma">Reforma</option>
            </select>
        </div>
        
        <div style="margin: 10px 0;">
            <label>Descrição:</label><br>
            <textarea name="description" style="width: 100%; padding: 5px; height: 80px;">Descrição do template de teste</textarea>
        </div>
        
        <div style="margin: 10px 0;">
            <label>Documentos Obrigatórios:</label><br>
            <input type="hidden" name="required_documents[]" value="rg">
            <input type="hidden" name="required_documents[]" value="cpf">
            <input type="hidden" name="required_documents[]" value="escritura">
            ✅ RG, CPF, Escritura (pré-selecionados)
        </div>
        
        <div style="margin: 10px 0;">
            <label>Documentos Opcionais:</label><br>
            <input type="hidden" name="optional_documents[]" value="comprovante_endereco">
            ✅ Comprovante de Endereço (pré-selecionado)
        </div>
        
        <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer;">
            🚀 Criar Template
        </button>
    </form>
    <?php
}
?>
